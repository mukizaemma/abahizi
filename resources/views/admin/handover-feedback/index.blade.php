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
                    <p class="text-muted mb-0">Notes from the public handover page before the site goes live.</p>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-6 col-md-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="text-muted small text-uppercase fw-semibold">Total</div>
                                <div class="fs-3 fw-bold">{{ $rows->total() }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="text-muted small text-uppercase fw-semibold">Unread</div>
                                <div class="fs-3 fw-bold">{{ $unreadCount }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body p-0">
                        <div class="d-none d-md-block table-responsive admin-table-wrap">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Received</th>
                                        <th>From</th>
                                        <th>View</th>
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
                                            <td>{{ $row->intentLabel() }}</td>
                                            <td>{{ $row->isUnread() ? 'Unread' : 'Read' }}</td>
                                            <td class="text-end">
                                                <a class="btn btn-sm btn-outline-primary" href="{{ route('handoverFeedback.show', $row) }}">Open</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-5">No handover feedback yet.</td>
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
                                    <div class="mt-2">{{ $row->intentLabel() }}</div>
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
