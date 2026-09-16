<div class="modal fade" id="mediaChangeChoiceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content media-choice">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h5 class="modal-title" data-choice-title>How do you want to change this photo?</h5>
                    <p class="text-muted small mb-0 mt-1" data-choice-lead>Choose one option. The current photo stays on the website until you save this page.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-3">
                <div class="media-choice__grid">
                    <button type="button" class="media-choice__card" data-choice="upload">
                        <span class="media-choice__icon" aria-hidden="true"><i class="fas fa-upload"></i></span>
                        <strong data-choice-upload-label>Upload a new photo</strong>
                        <span data-choice-upload-help>Take or choose a picture from your computer or phone.</span>
                    </button>
                    <button type="button" class="media-choice__card" data-choice="library">
                        <span class="media-choice__icon" aria-hidden="true"><i class="fas fa-images"></i></span>
                        <strong>Choose from the photo library</strong>
                        <span>Use a picture that is already on this website.</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mediaPickerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Choose a photo already on the website</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small">Tap a photo to use it here. You still need to save the page afterwards.</p>
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
