@php
    $groupKey = $group ?? '';
    $groupDef = \App\Support\SiteCopy::groups()[$groupKey] ?? null;
    $stored = \App\Support\SiteCopy::stored($setting ?? $data ?? null);
@endphp
@if($groupDef)
    @if(! empty($groupDef['help']))
        <p class="text-muted small mb-3">{{ $groupDef['help'] }}</p>
    @endif
    <div class="row g-3">
        @foreach($groupDef['fields'] as $fieldKey => $field)
            <div class="{{ ($field['type'] ?? 'text') === 'textarea' ? 'col-12' : 'col-lg-6' }}">
                <label class="form-label" for="landing_copy_{{ $groupKey }}_{{ $fieldKey }}">{{ $field['label'] }}</label>
                @if(($field['type'] ?? 'text') === 'textarea')
                    <textarea class="form-control" rows="{{ $field['rows'] ?? 3 }}" id="landing_copy_{{ $groupKey }}_{{ $fieldKey }}" name="landing_copy[{{ $fieldKey }}]" placeholder="{{ \App\Support\SiteCopy::placeholder($fieldKey) }}">{{ old('landing_copy.'.$fieldKey, $stored[$fieldKey] ?? '') }}</textarea>
                @else
                    <input type="text" class="form-control" id="landing_copy_{{ $groupKey }}_{{ $fieldKey }}" name="landing_copy[{{ $fieldKey }}]" value="{{ old('landing_copy.'.$fieldKey, $stored[$fieldKey] ?? '') }}" placeholder="{{ \App\Support\SiteCopy::placeholder($fieldKey) }}">
                @endif
            </div>
        @endforeach
    </div>
@endif
