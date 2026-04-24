@extends('layouts.adminbase')

@section('title', 'Partnership inquiries')

@section('sidebar')
    @parent
@endsection

@section('content')
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        @include('admin.includes.sidenav')
    </div>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4 py-4">
                <div class="admin-page-header mb-4">
                    <h1>Partnership inquiries</h1>
                    <p class="text-muted mb-0">“Get involved” form — training, equipment, fundraising, volunteering, and more.</p>
                </div>

                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 small">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Name / org.</th>
                                        <th>Contact</th>
                                        <th>Interests</th>
                                        <th>Message</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($rows as $row)
                                        <tr>
                                            <td class="text-nowrap">{{ $row->created_at->format('M j, Y H:i') }}</td>
                                            <td>
                                                <div class="fw-semibold">{{ $row->full_name }}</div>
                                                @if($row->organization)
                                                    <div class="text-muted">{{ $row->organization }}</div>
                                                @endif
                                            </td>
                                            <td>
                                                <div>{{ $row->email }}</div>
                                                <div class="text-muted">{{ $row->phone }}</div>
                                            </td>
                                            <td style="max-width: 14rem;">{{ $row->interests ?: '—' }}</td>
                                            <td style="max-width: 22rem;">{{ $row->message ? Str::limit($row->message, 200) : '—' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-5">No inquiries yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
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
