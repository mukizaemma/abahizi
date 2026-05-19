<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class ChunkedPdfUploadService
{
    public function chunkBytes(): int
    {
        return max(256 * 1024, (int) config('uploads.chunked_pdf.chunk_bytes', 1024 * 1024));
    }

    public function maxFileBytes(): int
    {
        return (int) config('uploads.chunked_pdf.max_file_bytes', 200 * 1024 * 1024);
    }

    public function maxChunkBytes(): int
    {
        return (int) config('uploads.chunked_pdf.max_chunk_bytes', 2 * 1024 * 1024);
    }

    public function storeChunk(string $uploadId, int $chunkIndex, int $totalChunks, string $originalName, UploadedFile $chunk): array
    {
        $this->assertUploadId($uploadId);
        $this->assertOriginalName($originalName);

        if ($totalChunks < 1 || $totalChunks > $this->maxChunks()) {
            throw new RuntimeException('Invalid chunk count.');
        }

        if ($chunkIndex < 0 || $chunkIndex >= $totalChunks) {
            throw new RuntimeException('Invalid chunk index.');
        }

        if ($chunk->getSize() > $this->maxChunkBytes()) {
            throw new RuntimeException('Chunk exceeds allowed size.');
        }

        $meta = $this->readMeta($uploadId);

        if ($meta === null) {
            $meta = [
                'original_name' => $originalName,
                'total_chunks' => $totalChunks,
                'received' => [],
                'bytes' => 0,
            ];
        } else {
            if ($meta['original_name'] !== $originalName || (int) $meta['total_chunks'] !== $totalChunks) {
                throw new RuntimeException('Upload metadata mismatch. Start a new upload.');
            }
        }

        if (in_array($chunkIndex, $meta['received'], true)) {
            return [
                'upload_id' => $uploadId,
                'chunk_index' => $chunkIndex,
                'received' => count($meta['received']),
                'total_chunks' => $totalChunks,
                'complete' => count($meta['received']) === $totalChunks,
            ];
        }

        $chunkPath = $this->chunkFilePath($uploadId, $chunkIndex);
        Storage::disk($this->disk())->put($chunkPath, file_get_contents($chunk->getRealPath()));

        $meta['received'][] = $chunkIndex;
        sort($meta['received']);
        $meta['bytes'] = (int) ($meta['bytes'] ?? 0) + $chunk->getSize();

        if ($meta['bytes'] > $this->maxFileBytes()) {
            $this->discardUpload($uploadId);
            throw new RuntimeException('File exceeds maximum allowed size.');
        }

        $this->writeMeta($uploadId, $meta);

        $received = count($meta['received']);

        return [
            'upload_id' => $uploadId,
            'chunk_index' => $chunkIndex,
            'received' => $received,
            'total_chunks' => $totalChunks,
            'complete' => $received === $totalChunks,
        ];
    }

    public function finalize(string $uploadId): string
    {
        $this->assertUploadId($uploadId);

        $meta = $this->readMeta($uploadId);
        if ($meta === null) {
            throw new RuntimeException('Upload not found.');
        }

        $totalChunks = (int) $meta['total_chunks'];
        $received = $meta['received'] ?? [];

        if (count($received) !== $totalChunks) {
            throw new RuntimeException('Upload is incomplete.');
        }

        $assemblyPath = $this->assemblyPath($uploadId);
        $fullAssembly = Storage::disk($this->disk())->path($assemblyPath);
        File::ensureDirectoryExists(dirname($fullAssembly));

        $handle = fopen($fullAssembly, 'wb');
        if ($handle === false) {
            throw new RuntimeException('Could not assemble file.');
        }

        try {
            for ($i = 0; $i < $totalChunks; $i++) {
                $chunkPath = Storage::disk($this->disk())->path($this->chunkFilePath($uploadId, $i));
                if (! is_readable($chunkPath)) {
                    throw new RuntimeException('Missing chunk '.$i.'.');
                }

                $chunkHandle = fopen($chunkPath, 'rb');
                if ($chunkHandle === false) {
                    throw new RuntimeException('Could not read chunk '.$i.'.');
                }

                stream_copy_to_stream($chunkHandle, $handle);
                fclose($chunkHandle);
            }
        } finally {
            fclose($handle);
        }

        if (! $this->isPdfFile($fullAssembly)) {
            File::delete($fullAssembly);
            $this->discardUpload($uploadId);
            throw new RuntimeException('File must be a valid PDF.');
        }

        $size = filesize($fullAssembly);
        if ($size === false || $size > $this->maxFileBytes()) {
            File::delete($fullAssembly);
            $this->discardUpload($uploadId);
            throw new RuntimeException('File exceeds maximum allowed size.');
        }

        $filename = Str::uuid()->toString().'.pdf';
        $finalRelative = trim(config('uploads.chunked_pdf.final_path'), '/').'/'.$filename;
        $finalFull = storage_path('app/'.$finalRelative);

        File::ensureDirectoryExists(dirname($finalFull));
        File::move($fullAssembly, $finalFull);

        $this->discardUpload($uploadId);

        return $filename;
    }

    public function discardUpload(string $uploadId): void
    {
        $dir = Storage::disk($this->disk())->path($this->uploadDir($uploadId));
        if (File::isDirectory($dir)) {
            File::deleteDirectory($dir);
        }
    }

    public function deleteStoredPdf(?string $filename): void
    {
        if (empty($filename)) {
            return;
        }

        $path = storage_path('app/'.trim(config('uploads.chunked_pdf.final_path'), '/').'/'.$filename);
        if (File::exists($path)) {
            File::delete($path);
        }
    }

    private function isPdfFile(string $path): bool
    {
        $handle = fopen($path, 'rb');
        if ($handle === false) {
            return false;
        }

        $header = fread($handle, 5);
        fclose($handle);

        if ($header !== '%PDF-') {
            return false;
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        if ($finfo === false) {
            return true;
        }

        $mime = finfo_file($finfo, $path);
        finfo_close($finfo);

        return in_array($mime, ['application/pdf', 'application/x-pdf', 'application/octet-stream'], true);
    }

    private function maxChunks(): int
    {
        return (int) ceil($this->maxFileBytes() / max(1, $this->chunkBytes())) + 1;
    }

    private function assertUploadId(string $uploadId): void
    {
        if (! preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', $uploadId)) {
            throw new RuntimeException('Invalid upload id.');
        }
    }

    private function assertOriginalName(string $name): void
    {
        if ($name === '' || ! str_ends_with(strtolower($name), '.pdf')) {
            throw new RuntimeException('Only PDF files are allowed.');
        }
    }

    private function disk(): string
    {
        return (string) config('uploads.chunked_pdf.temp_disk', 'local');
    }

    private function uploadDir(string $uploadId): string
    {
        return trim(config('uploads.chunked_pdf.temp_path'), '/').'/'.$uploadId;
    }

    private function metaPath(string $uploadId): string
    {
        return $this->uploadDir($uploadId).'/meta.json';
    }

    private function chunkFilePath(string $uploadId, int $index): string
    {
        return $this->uploadDir($uploadId).'/chunk_'.$index;
    }

    private function assemblyPath(string $uploadId): string
    {
        return $this->uploadDir($uploadId).'/assembly.pdf';
    }

    private function readMeta(string $uploadId): ?array
    {
        $path = $this->metaPath($uploadId);
        if (! Storage::disk($this->disk())->exists($path)) {
            return null;
        }

        $decoded = json_decode(Storage::disk($this->disk())->get($path), true);

        return is_array($decoded) ? $decoded : null;
    }

    private function writeMeta(string $uploadId, array $meta): void
    {
        Storage::disk($this->disk())->put(
            $this->metaPath($uploadId),
            json_encode($meta, JSON_THROW_ON_ERROR)
        );
    }
}
