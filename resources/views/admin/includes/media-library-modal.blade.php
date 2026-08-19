<div class="modal fade" id="mediaPickerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Choose an existing image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small">Select an image to add it to the upload field. Files over 700 KB are resized first.</p>
                <div id="mediaPickerGrid" class="media-picker-grid"></div>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="mediaPickerPrev" disabled>Previous</button>
                    <span class="small text-muted" id="mediaPickerPageLabel"></span>
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="mediaPickerNext" disabled>Next</button>
                </div>
            </div>
        </div>
    </div>
</div>
