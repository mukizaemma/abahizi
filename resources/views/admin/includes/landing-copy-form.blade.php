@php
    $groupKey = $group ?? '';
    $cardTitle = $title ?? (\App\Support\SiteCopy::groups()[$groupKey]['label'] ?? 'Section titles');
@endphp
<form action="{{ route('landingCopy.update') }}" method="POST" class="card admin-cms-card mb-4">
    @csrf
    <div class="card-header">
        <span class="admin-cms-card__kicker">Section titles</span>
        <strong>{{ $cardTitle }}</strong>
    </div>
    <div class="card-body">
        @include('admin.includes.landing-copy-fields', ['group' => $groupKey])
        <button type="submit" class="btn btn-primary mt-3"><i class="fa fa-save me-1"></i> Save titles</button>
    </div>
</form>
