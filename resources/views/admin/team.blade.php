@extends('layouts.adminbase')

@section('title', 'Team')

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
                <div class="admin-page-header d-flex flex-wrap align-items-start justify-content-between gap-3">
                    <div>
                        <h1>Team</h1>
                        <p class="text-muted mb-0">Hide someone who has left, or edit a profile to put a new person in the same role. Only people marked Visible appear on Our Team and Our Story.</p>
                    </div>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStaffModal">
                        <i class="fa fa-plus me-1"></i> Add team member
                    </button>
                </div>

                @include('admin.includes.page-header-form', ['pageKey' => 'team'])
                @include('admin.includes.landing-copy-form', ['group' => 'team', 'title' => 'Team page titles'])

                @if(session()->has('success'))
                    <div class="alert alert-success">{{ session()->get('success') }}</div>
                @endif
                @if(session()->has('error'))
                    <div class="alert alert-danger">{{ session()->get('error') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive admin-table-wrap">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Order</th>
                                        <th>Photo</th>
                                        <th>Name</th>
                                        <th>Position</th>
                                        <th>Added</th>
                                        <th>Visible</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($team as $member)
                                        <tr>
                                            <td class="text-nowrap">
                                                <span class="badge bg-light text-dark border me-1">{{ $member->sort_order ?? $loop->iteration }}</span>
                                                <div class="btn-group btn-group-sm align-middle">
                                                    <form action="{{ route('staff.moveUp', $member->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-secondary btn-sm" title="Move up" @disabled($loop->first) aria-label="Move up">↑</button>
                                                    </form>
                                                    <form action="{{ route('staff.moveDown', $member->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-secondary btn-sm" title="Move down" @disabled($loop->last) aria-label="Move down">↓</button>
                                                    </form>
                                                </div>
                                            </td>
                                            <td>
                                                @if(!empty($member->image))
                                                    <img src="{{ asset('storage/images/staff/' . $member->image) }}" alt="{{ $member->names }}" class="rounded border" width="72" height="84" style="object-fit: cover;">
                                                @else
                                                    <span class="text-muted small">No photo</span>
                                                @endif
                                            </td>
                                            <td class="fw-semibold">{{ $member->names }}</td>
                                            <td>{{ $member->position }}</td>
                                            <td class="text-muted small text-nowrap">{{ $member->created_at?->format('M j, Y') ?? '—' }}</td>
                                            <td>
                                                @if($member->isVisibleOnSite())
                                                    <span class="badge bg-success">Visible</span>
                                                @else
                                                    <span class="badge bg-secondary">Hidden</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <div class="btn-group btn-group-sm">
                                                    <form action="{{ route('staff.toggleDisplay', $member->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @if($member->isVisibleOnSite())
                                                            <button type="submit" class="btn btn-outline-secondary" title="Hide from the public website">Hide</button>
                                                        @else
                                                            <button type="submit" class="btn btn-outline-success" title="Show on the public website">Show</button>
                                                        @endif
                                                    </form>
                                                    <a href="{{ route('editStaff', $member->id) }}" class="btn btn-outline-primary">Replace / edit</a>
                                                    <a href="{{ route('destroyStaff', $member->id) }}" class="btn btn-outline-danger" onclick="return confirm('Remove this team member from the list? Hidden people can stay in the list if you might need them later.')">Delete</a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="border-0">
                                                <div class="admin-empty-state">
                                                    <i class="fas fa-users d-block"></i>
                                                    <p class="mb-0">No team members yet. Add your first staff profile.</p>
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
        @include('admin.includes.footer')
    </div>
</div>

<div class="modal fade admin-form-modal" id="addStaffModal" tabindex="-1" aria-labelledby="addStaffModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <form class="admin-form-modal__form" action="{{ route('saveStaff') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addStaffModalLabel">Add team member</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label" for="staff_names">Full name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="staff_names" name="names" value="{{ old('names') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="staff_position">Position <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="staff_position" name="position" value="{{ old('position') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="staff_phone">Phone</label>
                            <input type="text" class="form-control" id="staff_phone" name="phone" value="{{ old('phone') }}" placeholder="+250 …">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="staff_email">Email</label>
                            <input type="email" class="form-control" id="staff_email" name="email" value="{{ old('email') }}" placeholder="name@example.com">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="staff_facebook">Facebook URL</label>
                            <input type="url" class="form-control" id="staff_facebook" name="facebook" value="{{ old('facebook') }}" placeholder="https://facebook.com/…">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="staff_instagram">Instagram URL</label>
                            <input type="url" class="form-control" id="staff_instagram" name="instagram" value="{{ old('instagram') }}" placeholder="https://instagram.com/…">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="staff_linkedin">LinkedIn URL</label>
                            <input type="url" class="form-control" id="staff_linkedin" name="linkedin" value="{{ old('linkedin') }}" placeholder="https://linkedin.com/in/…">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="staff_display">Public website</label>
                            <select class="form-select" id="staff_display" name="display">
                                <option value="Yes" @selected(old('display', 'Yes') === 'Yes')>Visible — show on Our Team and Our Story</option>
                                <option value="No" @selected(old('display') === 'No')>Hidden — keep in admin only</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="staff_sort_order">Display order</label>
                            <input type="number" min="1" class="form-control" id="staff_sort_order" name="sort_order" value="{{ old('sort_order') }}" placeholder="Auto (add to end)">
                            <small class="text-muted">Lower numbers appear first. Leave blank to append last.</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="staff_created_at">Date added</label>
                            <input type="datetime-local" class="form-control" id="staff_created_at" name="created_at" value="{{ old('created_at') }}">
                            <small class="text-muted">Optional. Defaults to now.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="staff_image">Profile photo <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="staff_image" name="image" accept="image/*" required>
                            <small class="text-muted">Recommended portrait size: 270×312 px (JPEG or PNG).</small>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="staff_bio">Biography</label>
                            <textarea id="staff_bio" rows="4" class="form-control" name="bio" data-editor="rich" data-editor-modal="true">{{ old('bio') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save me-1"></i> Save team member
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var modal = document.getElementById('addStaffModal');
        if (!modal) return;
        @if ($errors->any())
            bootstrap.Modal.getOrCreateInstance(modal).show();
        @endif
    });
</script>
@endpush
