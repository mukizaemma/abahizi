@extends('layouts.adminbase')

@section('title', 'User guide')

@section('sidebar')
    @parent
@endsection

@section('content')
@php
    $current = $nav['current'];
    $manageUrl = ! empty($current['manage_route']) ? route($current['manage_route']) : null;
@endphp
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        @include('admin.includes.sidenav')
    </div>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4 py-4">
                <div class="admin-guide">
                    <div class="admin-guide__hero">
                        <p class="admin-guide__kicker">Website management course</p>
                        <h1>User guide</h1>
                        <p class="text-muted mb-0">One short lesson at a time. Use Next and Previous. Open <strong>Manage this section</strong> when you are ready to edit.</p>
                    </div>

                    <div class="admin-guide__progress" role="status">
                        <div class="d-flex justify-content-between small mb-2">
                            <span>Lesson {{ $nav['position'] }} of {{ $nav['total'] }}</span>
                            <span>{{ $current['minutes'] }} min</span>
                        </div>
                        <div class="progress" style="height: 0.45rem;">
                            <div class="progress-bar" style="width: {{ round(($nav['position'] / max($nav['total'], 1)) * 100) }}%"></div>
                        </div>
                    </div>

                    <div class="admin-guide__lessons" role="navigation" aria-label="Lessons">
                        @foreach($nav['lessons'] as $i => $lesson)
                            <a
                                class="admin-guide__chip{{ $lesson['slug'] === $current['slug'] ? ' is-active' : '' }}"
                                href="{{ route('admin.guide.show', ['slug' => $lesson['slug']]) }}"
                            >
                                <span>{{ $i + 1 }}</span>
                                {{ $lesson['title'] }}
                            </a>
                        @endforeach
                    </div>

                    <article class="admin-guide__card card">
                        <div class="card-body p-4 p-lg-5">
                            <h2 class="admin-guide__title">{{ $current['title'] }}</h2>
                            <p class="admin-guide__summary lead">{{ $current['summary'] }}</p>

                            <ol class="admin-guide__steps">
                                @foreach($current['steps'] as $step)
                                    <li>
                                        <h3>{{ $step['title'] }}</h3>
                                        <p>{{ $step['body'] }}</p>
                                    </li>
                                @endforeach
                            </ol>

                            @if($manageUrl)
                                <div class="admin-guide__actions">
                                    <a href="{{ $manageUrl }}" class="btn btn-primary btn-lg">
                                        <i class="fa fa-external-link-alt me-1"></i> Manage this section
                                    </a>
                                    @foreach($current['extra_links'] ?? [] as $link)
                                        @if(! empty($link['route']))
                                            <a href="{{ route($link['route']) }}" class="btn btn-outline-primary btn-lg">
                                                {{ $link['label'] }}
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </article>

                    <div class="admin-guide__pager">
                        @if($nav['previous'])
                            <a class="btn btn-outline-secondary" href="{{ route('admin.guide.show', ['slug' => $nav['previous']['slug']]) }}">
                                ← Previous: {{ $nav['previous']['title'] }}
                            </a>
                        @else
                            <span></span>
                        @endif
                        @if($nav['next'])
                            <a class="btn btn-primary" href="{{ route('admin.guide.show', ['slug' => $nav['next']['slug']]) }}">
                                Next: {{ $nav['next']['title'] }} →
                            </a>
                        @else
                            <span class="text-muted align-self-center">You have finished the guide.</span>
                        @endif
                    </div>
                </div>
            </div>
        </main>
        @include('admin.includes.footer')
    </div>
</div>
@endsection
