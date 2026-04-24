<nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
        <div class="nav flex-column pt-2">

            <p class="admin-nav-section-title mb-0">Content Management</p>
            <x-admin.nav-link
                :href="route('settings')"
                icon="fa-cogs"
                :active="request()->routeIs('settings')"
            >
                Site settings
            </x-admin.nav-link>
            <x-admin.nav-link
                :href="route('about')"
                icon="fa-bullseye"
                :active="request()->routeIs(['about', 'background'])"
            >
                About
            </x-admin.nav-link>
            <x-admin.nav-link
                :href="route('factory.admin.overview')"
                icon="fa-industry"
                :active="request()->routeIs('factory.admin.*')"
            >
                Factory
            </x-admin.nav-link>
            <x-admin.nav-link
                :href="route('programs')"
                icon="fa-list-alt"
                :active="request()->routeIs(['programs', 'editProgram', 'saveProgram', 'updateProgram', 'destroyProgram', 'addProgramImage', 'deleteProgramImage'])"
            >
                Programs
            </x-admin.nav-link>
            <x-admin.nav-link
                :href="route('getProjects')"
                icon="fa-project-diagram"
                :active="request()->routeIs(['getProjects', 'editProject', 'saveProject', 'updateProject', 'destroyProject', 'addProjectImage', 'deleteProjectImage'])"
            >
                Initiatives
            </x-admin.nav-link>
            <x-admin.nav-link
                :href="route('blog.index')"
                icon="fa-newspaper"
                :active="request()->routeIs(['blog.index', 'editBlog', 'saveBlog', 'updateBlog', 'deleteBlog', 'publishBlog', 'unpublishBlog', 'deleteBlogImage'])"
            >
                Blogs / Updates
            </x-admin.nav-link>
            <x-admin.nav-link
                :href="route('getTestimonials')"
                icon="fa-quote-right"
                :active="request()->routeIs(['getTestimonials', 'editTestimony', 'saveTestimony', 'updateTestimony', 'destroyTestimony'])"
            >
                Testimonials
            </x-admin.nav-link>
            <x-admin.nav-link
                :href="route('slides')"
                icon="fa-images"
                :active="request()->routeIs(['slides', 'editSlide', 'saveSlide', 'updateSlide', 'destroySlide'])"
            >
                Home Slides
            </x-admin.nav-link>
            <x-admin.nav-link
                :href="route('images')"
                icon="fa-image"
                :active="request()->routeIs(['images', 'editGallery', 'saveGallery', 'updateGallery', 'destroyGallery'])"
            >
                Gallery Images
            </x-admin.nav-link>

            <p class="admin-nav-section-title mb-0 mt-3">Manufacturing</p>
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
            <x-admin.nav-link
                :href="route('partnershipInquiries.index')"
                icon="fa-handshake"
                :active="request()->routeIs('partnershipInquiries.index')"
            >
                Partnership inquiries
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
