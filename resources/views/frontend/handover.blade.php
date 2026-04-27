@extends('layouts.frontbase')

@section('title', 'Website Handover')

@section('content')

@include('frontend.includes.page-header', [
    'title' => 'Website Handover Report',
    'caption' => 'Summary of completed restructuring, demo access, CMS guidance, and support contacts.',
])

<section class="py-5 grey-bg">
    <div class="container">
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
                        <h2 class="h4 mb-3">CMS user guide (for management team)</h2>
                        <ol class="handover-steps mb-0">
                            <li>Open <a href="https://demo.iremetech.com/login" target="_blank" rel="noopener noreferrer">demo.iremetech.com/login</a> and sign in with the shared credentials.</li>
                            <li>From the dashboard, navigate to each section (Programs, Initiatives, About, Contact, Settings) and update content directly.</li>
                            <li>Use the single description/details fields for initiatives to keep content concise and consistent.</li>
                            <li>Upload cover images and gallery photos where relevant; review pages publicly after each save.</li>
                            <li>For major edits, coordinate internally and document changes so the team can revert or request refinements if needed.</li>
                            <li>After final approval, all confirmed updates from the demo will be moved to the live production website.</li>
                        </ol>
                    </div>
                </article>
            </div>

            <div class="col-12 col-xl-10">
                <article class="card border-0 shadow-sm handover-card">
                    <div class="card-body p-4 p-lg-5">
                        <h2 class="h4 mb-3">Support and clarifications</h2>
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
