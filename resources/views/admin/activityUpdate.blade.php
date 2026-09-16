@extends('layouts.adminbase')

@section('title', 'Edit Community Initiative')

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
                <div class="admin-page-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h1>Edit community initiative</h1>
                        <p class="text-muted mb-0">Update the story, gallery, and ways visitors can get involved.</p>
                    </div>
                    <a href="{{ route('communityImpact.admin.index') }}" class="btn btn-outline-primary">Back to community impact</a>
                </div>

                <div class="card mb-4">
                    <div class="card-body">
                        <form action="{{ route('updateProject', $data->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                <div class="col-lg-6">
                                    <label class="form-label">Initiative title</label>
                                    <input type="text" class="form-control" name="title" value="{{ $data->title }}" required>
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="Active" {{ ($data->status ?? 'Active') === 'Active' ? 'selected' : '' }}>Active — visible on Impact → Community</option>
                                        <option value="Inactive" {{ ($data->status ?? 'Active') === 'Inactive' ? 'selected' : '' }}>Inactive — hidden from public page</option>
                                    </select>
                                </div>
                                @if(($programs ?? collect())->isNotEmpty())
                                    <div class="col-12">
                                        <label class="form-label">Program <span class="text-muted small">(optional)</span></label>
                                        <select name="program_id" class="form-select">
                                            <option value="">None</option>
                                            @foreach($programs as $program)
                                                <option value="{{ $program->id }}" {{ (int)$data->program_id === (int)$program->id ? 'selected' : '' }}>
                                                    {{ $program->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                                <div class="col-12">
                                    <label class="form-label">Initiative details / description</label>
                                    <textarea rows="7" class="form-control" name="description" data-editor="rich" required>{!! $data->description !!}</textarea>
                                </div>
                                <div class="col-12">
                                    <div class="admin-image-card">
                                        <label class="form-label">Cover photo</label>
                                        <p class="text-muted small mb-2">This is the large photo at the top of the program page. Click Change this photo to update it.</p>
                                        <input type="file" class="form-control" name="image" accept="image/*">
                                        @if(!empty($data->image))
                                            <img src="{{ asset('storage/' . $data->image) }}" alt="{{ $data->title }}" class="admin-preview-img">
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12">
                                    @include('admin.includes.initiative-ways-editor', [
                                        'wayRows' => $data->normalizedInvolvementWays() ?: \App\Models\Activity::sampleInvolvementWays(),
                                    ])
                                </div>
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary px-4">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">Recent involvement requests</div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Way</th>
                                        <th>Donation</th>
                                        <th>Channel</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse(($involvements ?? collect()) as $row)
                                        <tr>
                                            <td class="text-nowrap">{{ $row->created_at }}</td>
                                            <td>{{ $row->names }}</td>
                                            <td><a href="mailto:{{ $row->email }}">{{ $row->email }}</a></td>
                                            <td>{{ $row->phone ?: '—' }}</td>
                                            <td>{{ $row->involvement_label }}</td>
                                            <td>
                                                @if($row->involvement_kind === 'donate')
                                                    {{ $row->donation_amount }}
                                                    @if($row->donation_period)
                                                        <span class="text-muted">({{ $row->donation_period === 'recurring' ? 'Recurring' : 'One-time' }})</span>
                                                    @endif
                                                @else
                                                    —
                                                @endif
                                            </td>
                                            <td>{{ $row->submission_channel === 'whatsapp' ? 'WhatsApp' : ($row->submission_channel === 'email' ? 'Email' : '—') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-muted p-4">No involvement requests yet. They appear here when visitors use the public form.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between flex-wrap gap-2 mb-3">
                            <div>
                                <h2 class="h5 mb-1">Program gallery</h2>
                                <p class="text-muted mb-0">These photos appear under the story on the public program page. Add several so visitors can see the work.</p>
                            </div>
                        </div>
                        <form action="{{ route('addProjectImage') }}" method="POST" enctype="multipart/form-data" class="admin-image-card mb-4">
                            @csrf
                            <input type="hidden" name="activity_id" value="{{ $data->id }}">
                            <label class="form-label">Add gallery photos</label>
                            <p class="text-muted small mb-2">Click Add photos, then upload new pictures or choose ones already on the website. Save is on this card.</p>
                            <input type="file" class="form-control" name="image[]" accept="image/*" multiple required>
                            <button type="submit" class="btn btn-primary mt-3">Save gallery photos</button>
                        </form>
                        @if($images->isEmpty())
                            <div class="admin-empty-state py-4">
                                <i class="fas fa-images d-block"></i>
                                <p class="mb-0">No gallery photos yet. Add the first ones above.</p>
                            </div>
                        @else
                            <div class="row g-3">
                                @foreach($images as $image)
                                    <div class="col-md-4 col-lg-3">
                                        <div class="border rounded-3 overflow-hidden h-100">
                                            <img src="{{ asset('storage/' . $image->image) }}" class="w-100" alt="Program gallery" style="height: 180px; object-fit: cover;">
                                            <form action="{{ route('deleteProjectImage', $image->id) }}" method="POST" class="p-2" onsubmit="return confirm('Remove this photo from the gallery?')">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-danger btn-sm w-100">Remove photo</button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </main>
        @include('admin.includes.footer')
    </div>
</div>

</div>

@endsection
