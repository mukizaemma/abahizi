<footer class="py-4 bg-light mt-auto admin-guide-footer">
    <div class="container-fluid px-4">
        @php
            $guideLesson = \App\Support\AdminGuide::matchCurrentRequest();
            $guideUrl = \App\Support\AdminGuide::urlForLesson($guideLesson);
            $onGuide = request()->routeIs('admin.guide.show');
        @endphp
        <div class="admin-guide-footer__help">
            @if($onGuide)
                <span class="text-muted">You are in the user guide. Use Next and Previous to move between lessons.</span>
            @elseif($guideLesson)
                <div>
                    <strong>Need help with this page?</strong>
                    Open the <a href="{{ $guideUrl }}">{{ $guideLesson['title'] }}</a> lesson — a short walkthrough with a button that returns here.
                </div>
            @else
                <div>
                    <strong>Need help?</strong>
                    Open the <a href="{{ route('admin.guide.show') }}">user guide</a> for step-by-step website management.
                </div>
            @endif
        </div>
        <div class="d-flex align-items-center justify-content-between small flex-wrap gap-2">
            <div class="text-muted">&copy; {{ date('Y') }} <a href="https://iremetech.com/" target="_blank" rel="noopener">Ireme Technologies</a></div>
            <div>
                <a href="{{ route('admin.guide.show') }}">User guide</a>
                @if($guideLesson && ! $onGuide)
                    &middot;
                    <a href="{{ $guideUrl }}">This page</a>
                @endif
            </div>
        </div>
    </div>
</footer>
