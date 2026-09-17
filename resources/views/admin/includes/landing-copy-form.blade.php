@php
    $groupKey = $group ?? '';
    $cardTitle = $title ?? (\App\Support\SiteCopy::groups()[$groupKey]['label'] ?? 'Section titles');
@endphp
<form action="{{ route('landingCopy.update') }}" method="POST" class="card mb-4 border">
    @csrf
    <div class="card-header bg-light fw-semibold">{{ $cardTitle }}</div>
    <div class="card-body">
        @include('admin.includes.landing-copy-fields', ['group' => $groupKey])
        <button type="submit" class="btn btn-primary mt-3"><i class="fa fa-save me-1"></i> Save titles</button>
    </div>
</form>
