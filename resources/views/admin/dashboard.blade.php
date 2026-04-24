@extends('layouts.adminbase')

@section('title', 'Messages')

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
                <div class="admin-page-header">
                    <h1>Recent messages</h1>
                    <p class="text-muted mb-0">Contact form submissions from the website.</p>
                </div>

                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <span><i class="fas fa-table me-2 text-muted"></i>Visitor messages</span>
                        <span class="badge bg-light text-dark border">{{ $messages->count() }} total</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive admin-table-wrap">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Message</th>
                                        <th scope="col" class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($messages as $rs)
                                        <tr>
                                            <td class="text-muted">{{ $rs->id }}</td>
                                            <td><span class="text-nowrap">{{ $rs->created_at }}</span></td>
                                            <td>{{ $rs->names }}</td>
                                            <td><a href="mailto:{{ $rs->email }}">{{ $rs->email }}</a></td>
                                            <td><span class="cell-clamp d-inline-block" title="{{ $rs->message }}">{{ $rs->message }}</span></td>
                                            <td class="text-end">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('messageReply', $rs->id) }}" class="btn btn-outline-primary" title="Reply"><i class="fa fa-envelope"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="border-0">
                                                <div class="admin-empty-state">
                                                    <i class="fas fa-inbox d-block"></i>
                                                    <p class="mb-0">No messages yet. They will appear here when visitors use the contact form.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <footer class="py-4 bg-light mt-auto border-top">
            <div class="container-fluid px-4">
                <div class="d-flex align-items-center justify-content-between small text-muted">
                    <div>Copyright &copy; {{ date('Y') }} Abahizi Rwanda</div>
                </div>
            </div>
        </footer>
    </div>
</div>

@endsection
