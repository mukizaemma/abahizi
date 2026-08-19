@extends('layouts.adminbase')

@section('title', 'Handover feedback')

@section('content')
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        @include('admin.includes.sidenav')
    </div>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4 py-4">
                <div class="admin-page-header">
                    <h1>Handover feedback</h1>
                    <p class="text-muted mb-0">Decisions and ratings from the public handover page before the site goes live.</p>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-6 col-md-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="text-muted small text-uppercase fw-semibold">Total</div>
                                <div class="fs-3 fw-bold">{{ $rows->total() }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="text-muted small text-uppercase fw-semibold">Unread</div>
                                <div class="fs-3 fw-bold">{{ $unreadCount }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="text-muted small text-uppercase fw-semibold">Overall avg</div>
                                <div class="fs-3 fw-bold">{{ $averages->rating ? number_format((float) $averages->rating, 1) . ' / 5' : '—' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="text-muted small text-uppercase fw-semibold">Website / admin avg</div>
                                <div class="fs-5 fw-bold">{{ $averages->rating_site ? number_format((float) $averages->rating_site, 1) : '—' }} · {{ $averages->rating_admin ? number_format((float) $averages->rating_admin, 1) : '—' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($decisionCounts->isNotEmpty())
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        @foreach(\App\Models\HandoverFeedback::decisionOptions() as $key => $label)
                            @if(($decisionCounts[$key] ?? 0) > 0)
                                <span class="badge bg-dark">{{ \App\Models\HandoverFeedback::decisionShortLabels()[$key] ?? $label }}: {{ $decisionCounts[$key] }}</span>
                            @endif
                        @endforeach
                    </div>
                @endif

                <div class="card">
                    <div class="card-body p-0">
                        <div class="d-none d-md-block table-responsive admin-table-wrap">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Received</th>
                                        <th>From</th>
                                        <th>Next step</th>
                                        <th>Ratings</th>
                                        <th>Status</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($rows as $row)
                                        <tr class="{{ $row->isUnread() ? 'table-warning' : '' }}">
                                            <td class="text-nowrap">{{ $row->created_at->format('M j, Y H:i') }}</td>
                                            <td>
                                                <div class="fw-semibold">{{ $row->names }}</div>
                                                <div class="text-muted small">{{ $row->email }}</div>
                                            </td>
                                            <td>{{ $row->intentShortLabel() }}</td>
                                            <td class="text-nowrap">
                                                @if($row->rating)
                                                    {{ $row->rating }} / {{ $row->rating_site ?? '—' }} / {{ $row->rating_admin ?? '—' }}
                                                    <div class="text-muted small">overall · site · admin</div>
                                                @else
                                                    —
                                                @endif
                                            </td>
                                            <td>{{ $row->isUnread() ? 'Unread' : 'Read' }}</td>
                                            <td class="text-end">
                                                <a class="btn btn-sm btn-outline-primary" href="{{ route('handoverFeedback.show', $row) }}">Open</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-5">No handover feedback yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-md-none admin-mobile-list">
                            @forelse($rows as $row)
                                <a class="admin-mobile-card{{ $row->isUnread() ? ' is-unread' : '' }}" href="{{ route('handoverFeedback.show', $row) }}">
                                    <div class="d-flex justify-content-between gap-2">
                                        <strong>{{ $row->names }}</strong>
                                        @if($row->isUnread())
                                            <span class="badge bg-dark">New</span>
                                        @endif
                                    </div>
                                    <div class="small text-muted">{{ $row->email }}</div>
                                    <div class="mt-2">{{ $row->intentShortLabel() }}</div>
                                    @if($row->rating)
                                        <div class="small mt-1">{{ $row->rating }} / {{ $row->rating_site ?? '—' }} / {{ $row->rating_admin ?? '—' }} · overall · site · admin</div>
                                    @endif
                                    <div class="small text-muted mt-1">{{ $row->created_at->format('M j, Y H:i') }}</div>
                                </a>
                            @empty
                                <p class="text-center text-muted py-5 mb-0">No handover feedback yet.</p>
                            @endforelse
                        </div>
                    </div>
                    @if($rows->hasPages())
                        <div class="card-footer">{{ $rows->links() }}</div>
                    @endif
                </div>
            </div>
        </main>
        @include('admin.includes.footer')
    </div>
</div>
@endsection
