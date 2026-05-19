<?php

namespace App\Http\Controllers;

use App\Services\ChunkedPdfUploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use RuntimeException;

class ImpactReportPdfUploadController extends Controller
{
    public function __construct(
        private ChunkedPdfUploadService $uploads
    ) {}

    public function chunk(Request $request): JsonResponse
    {
        $data = $request->validate([
            'upload_id' => ['required', 'uuid'],
            'chunk_index' => ['required', 'integer', 'min:0'],
            'total_chunks' => ['required', 'integer', 'min:1'],
            'original_name' => ['required', 'string', 'max:255'],
            'chunk' => ['required', 'file', 'max:'.(int) (config('uploads.chunked_pdf.max_chunk_bytes', 2097152) / 1024)],
        ]);

        try {
            $result = $this->uploads->storeChunk(
                $data['upload_id'],
                (int) $data['chunk_index'],
                (int) $data['total_chunks'],
                $data['original_name'],
                $request->file('chunk')
            );
        } catch (RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json($result);
    }

    public function finalize(Request $request): JsonResponse
    {
        $data = $request->validate([
            'upload_id' => ['required', 'uuid'],
        ]);

        try {
            $filename = $this->uploads->finalize($data['upload_id']);
        } catch (RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        $ttl = (int) config('uploads.chunked_pdf.session_ttl_minutes', 120);
        Session::put('pdf_upload.'.$data['upload_id'], [
            'filename' => $filename,
            'user_id' => $request->user()->id,
            'expires_at' => now()->addMinutes($ttl)->toIso8601String(),
        ]);

        return response()->json([
            'upload_id' => $data['upload_id'],
            'filename' => $filename,
            'ready' => true,
        ]);
    }

    public function cancel(Request $request): JsonResponse
    {
        $data = $request->validate([
            'upload_id' => ['required', 'uuid'],
        ]);

        $session = Session::get('pdf_upload.'.$data['upload_id']);
        if (is_array($session) && ! empty($session['filename'])) {
            $this->uploads->deleteStoredPdf($session['filename']);
        }

        Session::forget('pdf_upload.'.$data['upload_id']);
        $this->uploads->discardUpload($data['upload_id']);

        return response()->json(['cancelled' => true]);
    }
}
