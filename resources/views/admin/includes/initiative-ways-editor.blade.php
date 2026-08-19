@php
    $wayRows = $wayRows ?? \App\Models\Activity::sampleInvolvementWays();
    $fieldId = $fieldId ?? 'ways';
@endphp

<div class="initiative-ways" data-initiative-ways>
    <div class="d-flex align-items-start justify-content-between flex-wrap gap-2 mb-2">
        <div>
            <label class="form-label mb-0">Ways to get involved</label>
            <p class="text-muted small mb-0">These appear as choices on the public initiative page. Mark a row as a donation option to ask for amount and one-time vs recurring.</p>
        </div>
        <button type="button" class="btn btn-outline-secondary btn-sm" data-ways-samples>
            Insert samples
        </button>
    </div>
    <div class="initiative-ways__rows" data-ways-rows>
        @foreach($wayRows as $i => $way)
            <div class="initiative-ways__row row g-2 align-items-end mb-2" data-ways-row>
                <div class="col-md-7">
                    @if($loop->first)
                        <label class="form-label">Label</label>
                    @endif
                    <input type="text" class="form-control" name="way_label[]" value="{{ $way['label'] }}" placeholder="e.g. Volunteer" maxlength="120">
                </div>
                <div class="col-md-3">
                    @if($loop->first)
                        <label class="form-label">Type</label>
                    @endif
                    <select class="form-select" name="way_kind[]">
                        <option value="standard" {{ ($way['kind'] ?? 'standard') !== 'donate' ? 'selected' : '' }}>Get involved</option>
                        <option value="donate" {{ ($way['kind'] ?? '') === 'donate' ? 'selected' : '' }}>Donation</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-danger w-100" data-ways-remove aria-label="Remove way">Remove</button>
                </div>
            </div>
        @endforeach
    </div>
    <button type="button" class="btn btn-outline-primary btn-sm mt-1" data-ways-add>
        <i class="fa fa-plus me-1"></i> Add another way
    </button>
    <template data-ways-template>
        <div class="initiative-ways__row row g-2 align-items-end mb-2" data-ways-row>
            <div class="col-md-7">
                <input type="text" class="form-control" name="way_label[]" value="" placeholder="e.g. Volunteer" maxlength="120">
            </div>
            <div class="col-md-3">
                <select class="form-select" name="way_kind[]">
                    <option value="standard" selected>Get involved</option>
                    <option value="donate">Donation</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-outline-danger w-100" data-ways-remove aria-label="Remove way">Remove</button>
            </div>
        </div>
    </template>
    <script type="application/json" data-ways-sample-json>@json(\App\Models\Activity::sampleInvolvementWays())</script>
</div>
