<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Chunked PDF uploads (impact reports)
    |--------------------------------------------------------------------------
    |
    | Each HTTP request only carries one small chunk, so PHP post limits stay low.
    | The app merges chunks and enforces the total size cap below.
    |
    */

    'chunked_pdf' => [
        'chunk_bytes' => (int) env('CHUNKED_PDF_CHUNK_BYTES', 1024 * 1024),
        'max_chunk_bytes' => (int) env('CHUNKED_PDF_MAX_CHUNK_BYTES', 2 * 1024 * 1024),
        'max_file_bytes' => (int) env('CHUNKED_PDF_MAX_FILE_BYTES', 200 * 1024 * 1024),
        'session_ttl_minutes' => (int) env('CHUNKED_PDF_SESSION_TTL', 120),
        'temp_disk' => 'local',
        'temp_path' => 'chunk-uploads/pdf',
        'final_path' => 'public/documents/impact-reports',
    ],

];
