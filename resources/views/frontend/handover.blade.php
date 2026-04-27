@extends('layouts.frontbase')

@section('title', 'Website Handover')

@section('content')

<section class="py-5 grey-bg">
    <div class="container">
        <div class="row justify-content-center mb-4 mb-lg-5">
            <div class="col-12 col-xl-10">
                <div class="card border-0 shadow-sm handover-card">
                    <div class="card-body p-4 p-lg-5 text-center">
                        <h1 class="mb-2">Website Handover Report</h1>
                        <p class="mb-0 text-muted">Summary of completed restructuring, demo access, CMS guidance, and support contacts.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center g-4">
            <div class="col-12 col-xl-10">
                <article class="card border-0 shadow-sm handover-card">
                    <div class="card-body p-4 p-lg-5">
                        <h2 class="h4 mb-3">What has been completed</h2>
                        <ul class="handover-list mb-0">
                            <li>Restructured key pages and content to speak clearly to existing clients and new collaboration partners.</li>
                            <li>Clarified the two main programs and organized initiatives under each program in a simpler, easier-to-follow way.</li>
                            <li>Focused initiative presentation on essential information only: title, concise description/details, cover image, and gallery (if available).</li>
                            <li>Improved clarity of the story around problem, solution, and impact by simplifying content structure and editing for readability.</li>
                            <li>Refined page layouts and alignment on major public sections (contact, programs, initiative pages) for a cleaner user experience.</li>
                            <li>Added practical anti-spam and form quality controls to reduce dummy/unrealistic submissions.</li>
                        </ul>
                    </div>
                </article>
            </div>

            <div class="col-12 col-xl-10">
                <article class="card border-0 shadow-sm handover-card">
                    <div class="card-body p-4 p-lg-5">
                        <h2 class="h4 mb-3">Content and field verification</h2>
                        <p class="mb-0">
                            We conducted an on-site content review to ensure information reflects real operations and verified facts, instead of relying on unrealistic or unverified content.
                            This supports a more authentic brand story for partners, buyers, donors, and institutions.
                        </p>
                    </div>
                </article>
            </div>

            <div class="col-12 col-xl-10">
                <article class="card border-0 shadow-sm handover-card">
                    <div class="card-body p-4 p-lg-5">
                        <h2 class="h4 mb-3">Photography guidance shared</h2>
                        <p class="mb-0">
                            A separate document detailing required photography has been shared to help the team capture visuals that better tell the right story.
                            A professional photography session can be arranged separately and charged independently if needed.
                        </p>
                    </div>
                </article>
            </div>

            <div class="col-12 col-xl-10">
                <article class="card border-0 shadow-sm handover-card handover-card--accent">
                    <div class="card-body p-4 p-lg-5">
                        <h2 class="h4 mb-3">Demo access and timeline</h2>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="handover-meta h-100">
                                    <p class="handover-meta__label mb-1">Demo website</p>
                                    <p class="mb-0"><a href="https://demo.iremetech.com" target="_blank" rel="noopener noreferrer">demo.iremetech.com</a></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="handover-meta h-100">
                                    <p class="handover-meta__label mb-1">Login URL</p>
                                    <p class="mb-0"><a href="https://demo.iremetech.com/login" target="_blank" rel="noopener noreferrer">demo.iremetech.com/login</a></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="handover-meta h-100">
                                    <p class="handover-meta__label mb-1">Username</p>
                                    <p class="mb-0">info@abahizirwanda.org</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="handover-meta h-100">
                                    <p class="handover-meta__label mb-1">Password</p>
                                    <p class="mb-0"><code>password</code></p>
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-warning mt-4 mb-0">
                            The demo environment is active for <strong>3 days</strong> from handover. Please review and approve content within this period.
                            Once logged in, the password can be changed at any time.
                        </div>
                    </div>
                </article>
            </div>

            <div class="col-12 col-xl-10">
                <article class="card border-0 shadow-sm handover-card">
                    <div class="card-body p-4 p-lg-5">
                        <h2 class="h4 mb-4">Website user guide (CMS)</h2>

                        <h3 class="h5 mb-2">1) Login and account security</h3>
                        <ol class="handover-steps mb-4">
                            <li>Go to <a href="https://demo.iremetech.com/login" target="_blank" rel="noopener noreferrer">demo.iremetech.com/login</a>.</li>
                            <li>Sign in using the shared credentials.</li>
                            <li>After first login, update the password for better security.</li>
                            <li>If login fails, contact support immediately using the contacts below.</li>
                        </ol>

                        <h3 class="h5 mb-2">2) Settings and contacts</h3>
                        <ol class="handover-steps mb-4">
                            <li>Open <strong>Settings</strong> from the admin menu.</li>
                            <li>Update official contact channels: phone numbers, email, address, and social links.</li>
                            <li>Update the embedded Google map code from the same settings area when needed.</li>
                            <li>Save and verify changes on the public <strong>Contact</strong> page.</li>
                        </ol>

                        <h3 class="h5 mb-2">3) About section</h3>
                        <ol class="handover-steps mb-4">
                            <li>Use the <strong>About</strong> management page to update mission, vision, model, and key story sections.</li>
                            <li>Keep language concise and practical for partners, buyers, and collaborators.</li>
                            <li>Preview public pages after each update to ensure flow and clarity.</li>
                        </ol>

                        <h3 class="h5 mb-2">4) Programs and initiatives (CRUD)</h3>
                        <ol class="handover-steps mb-4">
                            <li>Create, edit, and delete programs from the <strong>Programs</strong> section.</li>
                            <li>Under each program, manage initiatives in the <strong>Initiatives</strong> section.</li>
                            <li>For each initiative, keep only: title, one clear description/details field, cover image, and gallery (optional).</li>
                            <li>Avoid splitting initiative content into many fields to keep public pages concise and consistent.</li>
                        </ol>

                        <h3 class="h5 mb-2">5) Slides and gallery</h3>
                        <ol class="handover-steps mb-4">
                            <li>Manage homepage banners in <strong>Slides</strong>.</li>
                            <li>Manage supporting image storytelling in <strong>Gallery</strong>.</li>
                            <li>Use high-quality, relevant images from real field/project work only.</li>
                        </ol>

                        <h3 class="h5 mb-2">6) Products</h3>
                        <ol class="handover-steps mb-0">
                            <li>Use the <strong>Products</strong> management area to create, edit, and organize product items.</li>
                            <li>Ensure names, descriptions, and images are accurate and market-ready.</li>
                            <li>Confirm changes on public product pages before final approval.</li>
                        </ol>
                    </div>
                </article>
            </div>

            <div class="col-12 col-xl-10">
                <article class="card border-0 shadow-sm handover-card">
                    <div class="card-body p-4 p-lg-5">
                        <h2 class="h4 mb-3">Support and clarifications</h2>
                        <p class="mb-2">After approval, all confirmed updates from the demo will be moved to the live website.</p>
                        <p class="mb-2">We remain available for further virtual training for your team if needed.</p>
                        <p class="mb-2">For login issues, clarifications, or requested modifications:</p>
                        <p class="mb-1"><strong>Phone/WhatsApp:</strong> <a href="tel:0783807409">0783807409</a></p>
                        <p class="mb-0"><strong>Email:</strong> <a href="mailto:info@iremetech.com">info@iremetech.com</a></p>
                    </div>
                </article>
            </div>
        </div>
    </div>
</section>

@endsection
