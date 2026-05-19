@php
    $required = $required ?? false;
    $inputId = $inputId ?? 'chunked-pdf-'.uniqid();
    $chunkBytes = (int) config('uploads.chunked_pdf.chunk_bytes', 524288);
    $chunkLabel = $chunkBytes >= 1048576
        ? number_format($chunkBytes / 1048576, 1).' MB'
        : number_format($chunkBytes / 1024, 0).' KB';
@endphp

<div
    class="chunked-pdf-upload"
    data-chunked-pdf-upload
    data-required="{{ $required ? '1' : '0' }}"
    data-chunk-url="{{ route('impactReports.admin.pdf.chunk') }}"
    data-finalize-url="{{ route('impactReports.admin.pdf.finalize') }}"
    data-cancel-url="{{ route('impactReports.admin.pdf.cancel') }}"
    data-chunk-size="{{ $chunkBytes }}"
>
    <input type="hidden" name="pdf_upload_id" value="" data-chunked-pdf-upload-id>
    <label class="form-label" for="{{ $inputId }}">{{ $label ?? 'PDF' }}</label>
    <input
        type="file"
        id="{{ $inputId }}"
        class="form-control"
        accept="application/pdf,.pdf"
        data-chunked-pdf-file
    >
    <div class="progress mt-2 d-none" data-chunked-pdf-progress>
        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%" data-chunked-pdf-progress-bar aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
    <p class="small text-muted mt-1 mb-0">
        Large files upload in {{ $chunkLabel }} chunks (max {{ number_format(config('uploads.chunked_pdf.max_file_bytes', 209715200) / 1048576, 0) }} MB total). Wait for “PDF ready” before saving.
    </p>
    <p class="small mt-1 mb-0" data-chunked-pdf-status></p>
    <button type="button" class="btn btn-sm btn-outline-secondary mt-2 d-none" data-chunked-pdf-clear>Clear upload</button>
    @isset($hint)
        <div class="small d-block mt-1">{!! $hint !!}</div>
    @endisset
</div>

@once('chunked-pdf-upload-js')
    <script src="{{ asset('assets/admin/js/chunked-pdf-upload.js') }}?v=2"></script>
@endonce
