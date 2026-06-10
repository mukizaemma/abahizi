<nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
        <div class="nav flex-column pt-2">

            <x-admin.nav-link
                :href="route('redirects')"
                icon="fa-inbox"
                :active="request()->routeIs(['redirects', 'dashboard', 'webMessages', 'messageReply'])"
            >
                Messages
            </x-admin.nav-link>

            <p class="admin-nav-section-title mb-0 mt-3">Homepage</p>
            <x-admin.nav-link
                :href="route('slides')"
                icon="fa-images"
                :active="request()->routeIs(['slides', 'editSlide', 'saveSlide', 'updateSlide', 'destroySlide'])"
            >
                Home slides
            </x-admin.nav-link>
            <x-admin.nav-link
                :href="route('images')"
                icon="fa-image"
                :active="request()->routeIs(['images', 'editGallery', 'saveGallery', 'updateGallery', 'destroyGallery'])"
            >
                Craft gallery
            </x-admin.nav-link>
            <x-admin.nav-link
                :href="route('blog.index')"
                icon="fa-newspaper"
                :active="request()->routeIs(['blog.index', 'editBlog', 'saveBlog', 'updateBlog', 'deleteBlog', 'publishBlog', 'unpublishBlog', 'deleteBlogImage'])"
            >
                News &amp; updates
            </x-admin.nav-link>

            <p class="admin-nav-section-title mb-0 mt-3">About</p>
            <x-admin.nav-link
                :href="route('about')"
                icon="fa-bullseye"
                :active="request()->routeIs(['about', 'background', 'saveAbout', 'saveBackg'])"
            >
                About &amp; story
            </x-admin.nav-link>
            <x-admin.nav-link
                :href="route('staff')"
                icon="fa-users"
                :active="request()->routeIs(['staff', 'editStaff', 'saveStaff', 'updateStaff', 'destroyStaff'])"
            >
                Team
            </x-admin.nav-link>
            <x-admin.nav-link
                :href="route('getTestimonials')"
                icon="fa-quote-right"
                :active="request()->routeIs(['getTestimonials', 'editTestimony', 'saveTestimony', 'updateTestimony', 'destroyTestimony'])"
            >
                Testimonials
            </x-admin.nav-link>

            <p class="admin-nav-section-title mb-0 mt-3">Factory</p>
            <x-admin.nav-link
                :href="route('factory.admin.overview')"
                icon="fa-industry"
                :active="request()->routeIs('factory.admin.*')"
            >
                Factory content
            </x-admin.nav-link>

            <p class="admin-nav-section-title mb-0 mt-3">Products</p>
            <x-admin.nav-link
                :href="route('catalogProducts.index')"
                icon="fa-store"
                :active="request()->routeIs(['catalogProducts.index', 'catalogProducts.create', 'catalogProducts.store', 'catalogProducts.edit', 'catalogProducts.update', 'catalogProducts.destroy', 'catalogProducts.deleteImage'])"
            >
                Products catalog
            </x-admin.nav-link>
            <x-admin.nav-link
                :href="route('productCategories.index')"
                icon="fa-tags"
                :active="request()->routeIs(['productCategories.index', 'productCategories.store', 'productCategories.update', 'productCategories.destroy'])"
            >
                Product categories
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

            <p class="admin-nav-section-title mb-0 mt-3">Impact</p>
            <x-admin.nav-link
                :href="route('impacts.index')"
                icon="fa-chart-line"
                :active="request()->routeIs(['impacts.index', 'editImpact', 'saveImpact', 'updateImpact', 'destroyImpact'])"
            >
                Impact stats
            </x-admin.nav-link>
            <x-admin.nav-link
                :href="route('impactReports.admin.index')"
                icon="fa-file-pdf"
                :active="request()->routeIs(['impactReports.admin.*', 'impactReports.admin.edit', 'impactReports.admin.gallery.store', 'impactReports.admin.gallery.destroy'])"
            >
                Impact reports
            </x-admin.nav-link>
            <x-admin.nav-link
                :href="route('communityImpact.admin.index')"
                icon="fa-hands-holding-heart"
                :active="request()->routeIs(['communityImpact.admin.index', 'getProjects', 'editProject', 'saveProject', 'updateProject', 'destroyProject', 'addProjectImage', 'deleteProjectImage'])"
            >
                Community impact
            </x-admin.nav-link>

            <p class="admin-nav-section-title mb-0 mt-3">Site</p>
            <x-admin.nav-link
                :href="route('admin.profile.edit')"
                icon="fa-user-circle"
                :active="request()->routeIs('admin.profile.*')"
            >
                My profile
            </x-admin.nav-link>
            <x-admin.nav-link
                :href="route('admin.users.index')"
                icon="fa-user-shield"
                :active="request()->routeIs(['admin.users.*'])"
            >
                Users
            </x-admin.nav-link>
            <x-admin.nav-link
                :href="route('settings')"
                icon="fa-cogs"
                :active="request()->routeIs(['settings', 'saveSetting'])"
            >
                Site settings
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
