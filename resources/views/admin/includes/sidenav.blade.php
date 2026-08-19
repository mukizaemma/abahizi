@php
    $productsOpen = request()->routeIs([
        'catalogProducts.*',
        'productCategories.*',
        'productStory.*',
        'orderRequests.*',
    ]);
@endphp

<nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
        <div class="nav flex-column pt-2">

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
                :href="route('about')"
                icon="fa-bullseye"
                :active="request()->routeIs(['about', 'background', 'saveAbout', 'saveBackg'])"
            >
                About &amp; story
            </x-admin.nav-link>
            <x-admin.nav-link
                :href="route('factory.admin.overview')"
                icon="fa-industry"
                :active="request()->routeIs('factory.admin.*')"
            >
                Our factory
            </x-admin.nav-link>

            <a
                class="nav-link d-flex align-items-center{{ $productsOpen ? '' : ' collapsed' }}{{ $productsOpen ? ' active' : '' }}"
                href="#"
                data-bs-toggle="collapse"
                data-bs-target="#collapseProducts"
                aria-expanded="{{ $productsOpen ? 'true' : 'false' }}"
                aria-controls="collapseProducts"
            >
                <div class="sb-nav-link-icon"><i class="fa fa-store"></i></div>
                <span>Products</span>
                <div class="sb-sidenav-collapse-arrow"><i class="fa fa-angle-down"></i></div>
            </a>
            <div
                class="collapse{{ $productsOpen ? ' show' : '' }}"
                id="collapseProducts"
                data-bs-parent="#sidenavAccordion"
            >
                <nav class="sb-sidenav-menu-nested nav">
                    <x-admin.nav-link
                        :href="route('catalogProducts.index')"
                        icon="fa-box"
                        :active="request()->routeIs(['catalogProducts.index', 'catalogProducts.create', 'catalogProducts.store', 'catalogProducts.edit', 'catalogProducts.update', 'catalogProducts.destroy', 'catalogProducts.deleteImage'])"
                    >
                        Products catalog
                    </x-admin.nav-link>
                    <x-admin.nav-link
                        :href="route('productCategories.index')"
                        icon="fa-tags"
                        :active="request()->routeIs(['productCategories.index', 'productCategories.store', 'productCategories.update', 'productCategories.destroy'])"
                    >
                        Categories
                    </x-admin.nav-link>
                    <x-admin.nav-link
                        :href="route('productStory.index')"
                        icon="fa-check-circle"
                        :active="request()->routeIs(['productStory.index', 'productStory.heading', 'productStory.store', 'productStory.update', 'productStory.destroy'])"
                    >
                        Product story
                    </x-admin.nav-link>
                    <x-admin.nav-link
                        :href="route('orderRequests.index')"
                        icon="fa-clipboard-list"
                        :active="request()->routeIs('orderRequests.index')"
                    >
                        Order requests
                    </x-admin.nav-link>
                </nav>
            </div>

            <x-admin.nav-link
                :href="route('impacts.index')"
                icon="fa-chart-line"
                :active="request()->routeIs(['impacts.index', 'editImpact', 'saveImpact', 'updateImpact', 'destroyImpact'])"
            >
                Our impact
            </x-admin.nav-link>
            <x-admin.nav-link
                :href="route('impactReports.admin.index')"
                icon="fa-file-pdf"
                :active="request()->routeIs(['impactReports.admin.*'])"
            >
                Impact reports
            </x-admin.nav-link>
            <x-admin.nav-link
                :href="route('redirects')"
                icon="fa-inbox"
                :active="request()->routeIs(['redirects', 'dashboard', 'webMessages', 'messageReply'])"
            >
                Messages
            </x-admin.nav-link>
            <x-admin.nav-link
                :href="route('partnershipInquiries.index')"
                icon="fa-handshake"
                :active="request()->routeIs('partnershipInquiries.index')"
            >
                Orders
            </x-admin.nav-link>
            <x-admin.nav-link
                :href="route('blog.index')"
                icon="fa-newspaper"
                :active="request()->routeIs(['blog.index', 'editBlog', 'saveBlog', 'updateBlog', 'deleteBlog', 'publishBlog', 'unpublishBlog', 'deleteBlogImage'])"
            >
                Updates
            </x-admin.nav-link>
            <x-admin.nav-link
                :href="route('mediaLibrary.index')"
                icon="fa-photo-video"
                :active="request()->routeIs(['mediaLibrary.*'])"
            >
                Media gallery
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
