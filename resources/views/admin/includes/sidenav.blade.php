@php
    $inboxOpen = request()->routeIs([
        'redirects',
        'dashboard',
        'webMessages',
        'messageReply',
        'partnershipInquiries.index',
    ]);
    $canViewHandoverFeedback = Auth::user()?->canViewHandoverFeedback();
    $feedbackUnread = 0;
    if ($canViewHandoverFeedback && \Illuminate\Support\Facades\Schema::hasTable('handover_feedbacks')) {
        $feedbackUnread = \App\Models\HandoverFeedback::query()->unread()->count();
    }
@endphp

<nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
        <div class="nav flex-column pt-2">

            <div class="sb-sidenav-menu-heading">Site</div>
            <x-admin.nav-link
                :href="route('settings')"
                icon="fa-cogs"
                :active="request()->routeIs(['settings', 'saveSetting'])"
            >
                Site settings
            </x-admin.nav-link>
            <x-admin.nav-link
                :href="route('slides')"
                icon="fa-images"
                :active="request()->routeIs(['slides', 'saveHero', 'editSlide', 'saveSlide', 'updateSlide', 'destroySlide'])"
            >
                Homepage hero
            </x-admin.nav-link>
            <x-admin.nav-link
                :href="route('mediaLibrary.index')"
                icon="fa-photo-video"
                :active="request()->routeIs(['mediaLibrary.*'])"
            >
                Media library
            </x-admin.nav-link>

            <div class="sb-sidenav-menu-heading">Pages</div>
            <x-admin.nav-link
                :href="route('about')"
                icon="fa-bullseye"
                :active="request()->routeIs(['about', 'saveAbout', 'saveBackg'])"
            >
                About &amp; homepage
            </x-admin.nav-link>
            <x-admin.nav-link
                :href="route('factory.admin.overview')"
                icon="fa-industry"
                :active="request()->routeIs('factory.admin.*')"
            >
                Our factory
            </x-admin.nav-link>

            <x-admin.nav-link
                :href="route('catalogProducts.index')"
                icon="fa-store"
                :active="request()->routeIs(['catalogProducts.*', 'productCategories.*', 'productStory.*'])"
            >
                Products
            </x-admin.nav-link>

            <x-admin.nav-link
                :href="route('impacts.index')"
                icon="fa-chart-line"
                :active="request()->routeIs(['impacts.index', 'editImpact', 'saveImpact', 'updateImpact', 'destroyImpact'])"
            >
                Impact pillars
            </x-admin.nav-link>
            <x-admin.nav-link
                :href="route('communityImpact.admin.index')"
                icon="fa-hands-helping"
                :active="request()->routeIs(['communityImpact.admin.index', 'editProject', 'saveProject', 'updateProject', 'destroyProject'])"
            >
                Community programs
            </x-admin.nav-link>
            <x-admin.nav-link
                :href="route('impactReports.admin.index')"
                icon="fa-file-pdf"
                :active="request()->routeIs(['impactReports.admin.*'])"
            >
                Impact reports
            </x-admin.nav-link>
            <x-admin.nav-link
                :href="route('blog.index')"
                icon="fa-newspaper"
                :active="request()->routeIs(['blog.index', 'editBlog', 'saveBlog', 'updateBlog', 'deleteBlog', 'publishBlog', 'unpublishBlog', 'deleteBlogImage'])"
            >
                Updates
            </x-admin.nav-link>
            <x-admin.nav-link
                :href="route('images')"
                icon="fa-th"
                :active="request()->routeIs(['images', 'saveGallery', 'editGallery', 'updateGallery', 'destroyGallery'])"
            >
                Site gallery
            </x-admin.nav-link>
            <x-admin.nav-link
                :href="route('staff')"
                icon="fa-users"
                :active="request()->routeIs(['staff', 'editStaff', 'saveStaff', 'updateStaff', 'destroyStaff', 'staff.moveUp', 'staff.moveDown'])"
            >
                Our team
            </x-admin.nav-link>
            <x-admin.nav-link
                :href="route('getTestimonials')"
                icon="fa-quote-right"
                :active="request()->routeIs(['getTestimonials', 'editTestimony', 'saveTestimony', 'updateTestimony', 'destroyTestimony'])"
            >
                Testimonials
            </x-admin.nav-link>

            <div class="sb-sidenav-menu-heading">Inbox</div>
            <x-admin.nav-link
                :href="route('orderRequests.index')"
                icon="fa-clipboard-list"
                :active="request()->routeIs('orderRequests.index')"
            >
                Order requests
            </x-admin.nav-link>
            <a
                class="nav-link d-flex align-items-center{{ $inboxOpen ? '' : ' collapsed' }}{{ $inboxOpen ? ' active' : '' }}"
                href="#"
                data-bs-toggle="collapse"
                data-bs-target="#collapseInbox"
                aria-expanded="{{ $inboxOpen ? 'true' : 'false' }}"
                aria-controls="collapseInbox"
            >
                <div class="sb-nav-link-icon"><i class="fa fa-inbox"></i></div>
                <span>Messages</span>
                <div class="sb-sidenav-collapse-arrow"><i class="fa fa-angle-down"></i></div>
            </a>
            <div
                class="collapse{{ $inboxOpen ? ' show' : '' }}"
                id="collapseInbox"
                data-bs-parent="#sidenavAccordion"
            >
                <nav class="sb-sidenav-menu-nested nav">
                    <x-admin.nav-link
                        :href="route('redirects')"
                        icon="fa-envelope"
                        :active="request()->routeIs(['redirects', 'dashboard', 'webMessages', 'messageReply'])"
                    >
                        Contact messages
                    </x-admin.nav-link>
                    <x-admin.nav-link
                        :href="route('partnershipInquiries.index')"
                        icon="fa-handshake"
                        :active="request()->routeIs('partnershipInquiries.index')"
                    >
                        Form submissions
                    </x-admin.nav-link>
                </nav>
            </div>
            @if($canViewHandoverFeedback)
                <x-admin.nav-link
                    :href="route('handoverFeedback.index')"
                    icon="fa-comment-dots"
                    :active="request()->routeIs(['handoverFeedback.index', 'handoverFeedback.show'])"
                    :badge="$feedbackUnread"
                >
                    Feedback
                </x-admin.nav-link>
            @endif

            <div class="sb-sidenav-menu-heading">Account</div>
            <x-admin.nav-link
                :href="route('admin.users.index')"
                icon="fa-user-shield"
                :active="request()->routeIs(['admin.users.*'])"
            >
                Users
            </x-admin.nav-link>
            <x-admin.nav-link
                :href="route('admin.profile.edit')"
                icon="fa-user-circle"
                :active="request()->routeIs('admin.profile.*')"
            >
                My profile
            </x-admin.nav-link>

        </div>
    </div>
    <div class="sb-sidenav-footer px-3 py-3">
        <div class="small text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.08em; opacity: 0.7;">Signed in</div>
        <div class="text-white small fw-semibold text-truncate" title="{{ Auth::user()->name ?? '' }}">
            {{ Auth::user()->name ?? 'Admin' }}
        </div>
    </div>
</nav>
