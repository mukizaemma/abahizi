@extends('layouts.adminbase')

@section('title', 'Order requests')

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
                    <h1>Product order requests</h1>
                    <p class="text-muted mb-0">Submissions from the public “Request an order” form.</p>
                </div>

                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 small">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Name</th>
                                        <th>Contact</th>
                                        <th>Product ref.</th>
                                        <th>Request</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($rows as $row)
                                        <tr>
                                            <td class="text-nowrap">{{ $row->created_at->format('M j, Y H:i') }}</td>
                                            <td class="fw-semibold">{{ $row->full_name }}</td>
                                            <td>
                                                <div>{{ $row->email }}</div>
                                                <div class="text-muted">{{ $row->phone }}</div>
                                            </td>
                                            <td>
                                                @if($row->product_reference)
                                                    {{ $row->product_reference }}
                                                @else
                                                    —
                                                @endif
                                            </td>
                                            <td style="max-width: 28rem;">{{ Str::limit($row->product_description, 220) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-5">No requests yet.</td>
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
