@extends('layouts.adminbase')

@section('title', 'Feedback from ' . $feedback->names)

@section('content')
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        @include('admin.includes.sidenav')
    </div>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4 py-4">
                <div class="admin-page-header d-flex flex-wrap justify-content-between align-items-start gap-3">
                    <div>
                        <h1 class="mb-1">{{ $feedback->names }}</h1>
                        <p class="text-muted mb-0">{{ $feedback->created_at->format('M j, Y \a\t H:i') }}</p>
                    </div>
                    <a class="btn btn-outline-secondary" href="{{ route('handoverFeedback.index') }}">Back to list</a>
                </div>

                <div class="card">
                    <div class="card-body">
                        <dl class="admin-detail-list mb-4">
                            <div>
                                <dt>Email</dt>
                                <dd><a href="mailto:{{ $feedback->email }}">{{ $feedback->email }}</a></dd>
                            </div>
                            <div>
                                <dt>Next step</dt>
                                <dd>{{ $feedback->intentLabel() }}</dd>
                            </div>
                            @foreach(\App\Models\HandoverFeedback::ratingQuestions() as $field => $question)
                                <div>
                                    <dt>{{ $question }}</dt>
                                    <dd>{{ $feedback->ratingLabel($feedback->{$field}) }}</dd>
                                </div>
                            @endforeach
                        </dl>
                        <h2 class="h6 text-uppercase text-muted">Notes</h2>
                        @if(filled($feedback->message))
                            <div class="admin-feedback-body">{{ $feedback->message }}</div>
                        @else
                            <p class="text-muted mb-0">No notes were added.</p>
                        @endif
                    </div>
                    <div class="card-footer d-flex flex-wrap gap-2">
                        <a class="btn btn-primary" href="mailto:{{ $feedback->email }}?subject={{ rawurlencode('Re: website handover feedback') }}">Reply by email</a>
                        <form method="POST" action="{{ route('handoverFeedback.destroy', $feedback) }}" onsubmit="return confirm('Delete this feedback?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
        @include('admin.includes.footer')
    </div>
</div>
@endsection
