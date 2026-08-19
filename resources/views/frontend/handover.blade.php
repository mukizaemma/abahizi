@extends('layouts.frontbase')

@section('title', 'Website handover & user guide')

@section('meta_description', 'Handover report and CMS user guide for the Abahizi Rwanda website — what was delivered, how to manage content, and how to send feedback.')

@push('head')
    <meta name="robots" content="noindex, nofollow">
@endpush

@php
    $demoUrl = 'https://web.iremetech.com';
    $liveUrl = 'https://abahizirwanda.org';
    $loginUrl = url('/login');
    $adminEmail = 'admin@abahizirwanda.org';
    $adminPassword = 'Abahizi@2026';
    $openFeedback = $errors->any() || session('success');
    $startTab = $openFeedback ? 'feedback' : 'overview';
@endphp

@section('content')

<div class="ho-page" data-ho-start="{{ $startTab }}">
    <section class="ho-hero">
        <div class="container">
            <p class="ho-kicker">Prepared for Abahizi Rwanda management</p>
            <h1 class="ho-hero__title">Website handover &amp; user guide</h1>
            <p class="ho-hero__lead">On a phone, pick a section from the list. On a larger screen, use the tabs. You do not need to read everything at once.</p>
        </div>
    </section>

    <div class="ho-shell">
        <div class="container">
            <div class="ho-layout">
                <div class="ho-jump">
                    <label for="ho-jump-select">Go to section</label>
                    <select id="ho-jump-select">
                        <option value="overview">1. Overview</option>
                        <option value="access">2. Log in</option>
                        <option value="guide">3. User guide</option>
                        <option value="requests">4. Requests</option>
                        <option value="next">5. After approval</option>
                        <option value="feedback">6. Send feedback</option>
                    </select>
                </div>
                <nav class="ho-tabs" role="tablist" aria-label="Handover sections">
                    <button type="button" class="ho-tabs__btn is-active" role="tab" aria-selected="true" aria-controls="panel-overview" id="tab-overview" data-ho-tab="overview"><span class="ho-tabs__full">Overview</span><span class="ho-tabs__short">Overview</span></button>
                    <button type="button" class="ho-tabs__btn" role="tab" aria-selected="false" aria-controls="panel-access" id="tab-access" data-ho-tab="access"><span class="ho-tabs__full">Log in &amp; try</span><span class="ho-tabs__short">Log in</span></button>
                    <button type="button" class="ho-tabs__btn" role="tab" aria-selected="false" aria-controls="panel-guide" id="tab-guide" data-ho-tab="guide"><span class="ho-tabs__full">User guide</span><span class="ho-tabs__short">Guide</span></button>
                    <button type="button" class="ho-tabs__btn" role="tab" aria-selected="false" aria-controls="panel-requests" id="tab-requests" data-ho-tab="requests"><span class="ho-tabs__full">Visitor requests</span><span class="ho-tabs__short">Requests</span></button>
                    <button type="button" class="ho-tabs__btn" role="tab" aria-selected="false" aria-controls="panel-next" id="tab-next" data-ho-tab="next"><span class="ho-tabs__full">After approval</span><span class="ho-tabs__short">Go live</span></button>
                    <button type="button" class="ho-tabs__btn" role="tab" aria-selected="false" aria-controls="panel-feedback" id="tab-feedback" data-ho-tab="feedback"><span class="ho-tabs__full">Send feedback</span><span class="ho-tabs__short">Feedback</span></button>
                </nav>

                <div class="ho-panels">
                    <section class="ho-panel is-active" role="tabpanel" id="panel-overview" aria-labelledby="tab-overview" data-ho-panel="overview">
                        <h2>What has been delivered</h2>
                        <p class="ho-intro">The public website and the admin panel now work as one system. Your team can change text, photos, products, and settings in a browser. After you click Save, visitors see the update.</p>
                        <div class="ho-grid ho-grid--3">
                            <article class="ho-card">
                                <h3>New homepage</h3>
                                <p>Hero (slideshow, one image, or video), product gallery, impact stats, stories, partners, and contact — all editable from the admin.</p>
                            </article>
                            <article class="ho-card">
                                <h3>Factory &amp; products</h3>
                                <p>Factory story, gallery, and partner buttons. A product catalog you can show or hide. Visitors can request an order on the site.</p>
                            </article>
                            <article class="ho-card">
                                <h3>Impact &amp; community</h3>
                                <p>Impact numbers, community initiatives, testimonials, and downloadable impact reports.</p>
                            </article>
                            <article class="ho-card">
                                <h3>Brand look</h3>
                                <p>The site uses the logo colours only: yellow, black, and white. Fonts can still be changed in Site settings.</p>
                            </article>
                            <article class="ho-card">
                                <h3>Media library</h3>
                                <p>Reuse photos already on the site, upload a new file, or let the system shrink large images automatically.</p>
                            </article>
                            <article class="ho-card">
                                <h3>Request inbox</h3>
                                <p>Contact, partnership, product, and initiative requests are stored in the admin so you can follow up.</p>
                            </article>
                        </div>
                    </section>

                    <section class="ho-panel" role="tabpanel" id="panel-access" aria-labelledby="tab-access" data-ho-panel="access" hidden>
                        <h2>Log in and test the demo</h2>
                        <p class="ho-intro">Use these details to open the admin and try updating content. After you sign in, change the password under <strong>My profile</strong>.</p>
                        <div class="ho-access">
                            <div class="ho-access__note">
                                <p><strong>Demo now:</strong> <a href="{{ $demoUrl }}" target="_blank" rel="noopener noreferrer">web.iremetech.com</a></p>
                                <p class="mb-0"><strong>After approval:</strong> the site moves to <a href="{{ $liveUrl }}" target="_blank" rel="noopener noreferrer">abahizirwanda.org</a>. Login will be <code>abahizirwanda.org/login</code>.</p>
                            </div>
                            <div class="ho-creds">
                                <div class="ho-cred">
                                    <span class="ho-cred__label">Login page</span>
                                    <span class="ho-cred__value">{{ $demoUrl }}/login</span>
                                    <button type="button" class="ho-copy" data-copy="{{ $demoUrl }}/login">Copy</button>
                                </div>
                                <div class="ho-cred">
                                    <span class="ho-cred__label">Email</span>
                                    <span class="ho-cred__value">{{ $adminEmail }}</span>
                                    <button type="button" class="ho-copy" data-copy="{{ $adminEmail }}">Copy</button>
                                </div>
                                <div class="ho-cred">
                                    <span class="ho-cred__label">Password</span>
                                    <span class="ho-cred__value">{{ $adminPassword }}</span>
                                    <button type="button" class="ho-copy" data-copy="{{ $adminPassword }}">Copy</button>
                                </div>
                            </div>
                            <a class="ho-btn ho-btn--primary" href="{{ $loginUrl }}">Sign in to the admin</a>
                            <p class="ho-fine">Demo review only. Please change this password after your first login.</p>
                        </div>
                    </section>

                    <section class="ho-panel" role="tabpanel" id="panel-guide" aria-labelledby="tab-guide" data-ho-panel="guide" hidden>
                        <h2>User guide</h2>
                        <p class="ho-intro">The public site is what visitors see. The admin is where your team writes. You never edit code.</p>

                        <div class="ho-subtabs" role="tablist" aria-label="User guide topics">
                            <button type="button" class="ho-subtabs__btn is-active" data-ho-sub="start">First steps</button>
                            <button type="button" class="ho-subtabs__btn" data-ho-sub="content">Change content</button>
                            <button type="button" class="ho-subtabs__btn" data-ho-sub="news">Publish news</button>
                            <button type="button" class="ho-subtabs__btn" data-ho-sub="photos">Photos</button>
                        </div>

                        <div class="ho-subpanel is-active" data-ho-subpanel="start">
                            <ol class="ho-steps">
                                <li><strong>Sign in</strong> with the email and password on the Log in tab. You land in the dashboard.</li>
                                <li><strong>Open the menu.</strong> On a computer it is on the left. On a phone, tap the menu icon at the top left, then choose a page.</li>
                                <li><strong>Edit and save.</strong> Type new text or choose a photo, then tap Save. This is the same on every page.</li>
                                <li><strong>Check the public site</strong> in another tab and refresh. What you saved is what visitors see.</li>
                            </ol>
                            <p class="ho-callout">Add extra people under <strong>Users</strong> so more than one person can manage the site. Day-to-day publishing does not need a developer.</p>
                        </div>

                        <div class="ho-subpanel" data-ho-subpanel="content" hidden>
                            <p class="ho-intro">Find what you want to change, open that menu, then save.</p>
                            <div class="ho-guide-list">
                                <article class="ho-guide-item">
                                    <p class="ho-guide-item__need">Logo, phones, email, map, fonts</p>
                                    <p class="ho-guide-item__menu">Site settings</p>
                                    <p>Edit the fields and save. These details appear in the header, footer, and contact page.</p>
                                </article>
                                <article class="ho-guide-item">
                                    <p class="ho-guide-item__need">Homepage heading and background</p>
                                    <p class="ho-guide-item__menu">Homepage hero</p>
                                    <p>Set the heading and subheading. Choose sliding images, one banner, or a video.</p>
                                </article>
                                <article class="ho-guide-item">
                                    <p class="ho-guide-item__need">About story, stats, section photos</p>
                                    <p class="ho-guide-item__menu">About &amp; story</p>
                                    <p>Update the story and numbers. Use <em>Section backgrounds</em> for large photos, including the homepage impact band.</p>
                                </article>
                                <article class="ho-guide-item">
                                    <p class="ho-guide-item__need">Factory text and gallery</p>
                                    <p class="ho-guide-item__menu">Our factory</p>
                                    <p>Edit each factory section and add highlight photos.</p>
                                </article>
                                <article class="ho-guide-item">
                                    <p class="ho-guide-item__need">Handbags and categories</p>
                                    <p class="ho-guide-item__menu">Products</p>
                                    <p>Add or edit products. Turn the public catalog on or off in Site settings.</p>
                                </article>
                                <article class="ho-guide-item">
                                    <p class="ho-guide-item__need">Impact numbers</p>
                                    <p class="ho-guide-item__menu">Our impact</p>
                                    <p>Add titles and numbers. They appear on the homepage impact band.</p>
                                </article>
                                <article class="ho-guide-item">
                                    <p class="ho-guide-item__need">PDF reports</p>
                                    <p class="ho-guide-item__menu">Impact reports</p>
                                    <p>Upload a report and it appears on the public reports page.</p>
                                </article>
                                <article class="ho-guide-item">
                                    <p class="ho-guide-item__need">Team and testimonials</p>
                                    <p class="ho-guide-item__menu">Our team / Testimonials</p>
                                    <p>Add, edit, or hide people and quotes.</p>
                                </article>
                            </div>
                        </div>

                        <div class="ho-subpanel" data-ho-subpanel="news" hidden>
                            <ol class="ho-steps">
                                <li>Open <strong>Updates</strong> in the admin menu.</li>
                                <li>Add a story: title, article text, and a cover image.</li>
                                <li>Attach extra photos if you want a gallery on that story.</li>
                                <li>Publish it. It appears on <a href="{{ route('posts') }}">Updates</a> and on the homepage.</li>
                                <li>Unpublish or delete it later from the same list. No developer is required.</li>
                            </ol>
                        </div>

                        <div class="ho-subpanel" data-ho-subpanel="photos" hidden>
                            <p class="ho-intro">On any image field you can upload, reuse, or let the site resize the file.</p>
                            <div class="ho-grid ho-grid--3">
                                <article class="ho-card">
                                    <h3>Upload new</h3>
                                    <p>Choose a file from your computer. If it is larger than 700 KB, the admin resizes it before saving.</p>
                                </article>
                                <article class="ho-card">
                                    <h3>Choose from library</h3>
                                    <p>Pick a photo already on the site — hero slides, factory shots, products, and more.</p>
                                </article>
                                <article class="ho-card">
                                    <h3>Media gallery</h3>
                                    <p>See every file, where it is used, replace a photo everywhere, or delete unused files.</p>
                                </article>
                            </div>
                            <p class="ho-callout">Landscape photos around 1920×1080 work well for heroes. Square photos work well for team and products.</p>
                        </div>
                    </section>

                    <section class="ho-panel" role="tabpanel" id="panel-requests" aria-labelledby="tab-requests" data-ho-panel="requests" hidden>
                        <h2>How visitor requests work</h2>
                        <p class="ho-intro">Visitors choose WhatsApp or email, send the message in that app, then confirm on the website so the request is saved for you.</p>
                        <div class="ho-grid ho-grid--2">
                            <article class="ho-card">
                                <h3>What the visitor does</h3>
                                <ol class="ho-steps ho-steps--compact">
                                    <li>Opens Contact, a product order form, or Get involved.</li>
                                    <li>Enters name, phone, email, and message.</li>
                                    <li>Chooses <strong>WhatsApp</strong> or <strong>Email</strong>.</li>
                                    <li>Sends the prepared message in the new tab.</li>
                                    <li>Confirms on the website so it is stored.</li>
                                </ol>
                            </article>
                            <article class="ho-card">
                                <h3>Where you find it</h3>
                                <ol class="ho-steps ho-steps--compact">
                                    <li><strong>Messages</strong> — general contact inquiries. Use Reply to email them.</li>
                                    <li><strong>Orders</strong> — partnership or production inquiries.</li>
                                    <li><strong>Products → Order requests</strong> — questions about a specific product.</li>
                                    <li>Initiative “get involved” requests stay with that community programme.</li>
                                </ol>
                            </article>
                        </div>
                        <p class="ho-callout">Both a WhatsApp number and a site email must be filled in under Site settings, or public forms stay closed so messages are not lost.</p>
                    </section>

                    <section class="ho-panel" role="tabpanel" id="panel-next" aria-labelledby="tab-next" data-ho-panel="next" hidden>
                        <h2>After this demo is approved</h2>
                        <div class="ho-next">
                            <ul class="ho-list">
                                <li>The demo at <strong>web.iremetech.com</strong> moves to <strong>abahizirwanda.org</strong>.</li>
                                <li>The developer will need <strong>cPanel access</strong> (hosting login) to migrate files, the database, and email settings.</li>
                                <li>Source code can be shared when you need it — for backup, another developer, or your archive.</li>
                                <li>The developer stays available for future upgrades, training, and fixes. You are not left alone after go-live.</li>
                            </ul>
                            <p class="ho-support">
                                Questions during review:<br>
                                <strong>Phone / WhatsApp:</strong> <a href="tel:+250783807409">0783 807 409</a><br>
                                <strong>Email:</strong> <a href="mailto:info@iremetech.com">info@iremetech.com</a>
                            </p>
                        </div>
                    </section>

                    <section class="ho-panel" role="tabpanel" id="panel-feedback" aria-labelledby="tab-feedback" data-ho-panel="feedback" hidden>
                        <h2>Send feedback</h2>
                        <p class="ho-intro">Tell us what to keep or change before the site goes live. Super admin reads every note under <strong>Feedback</strong> in the admin menu.</p>

                        @if(session('success'))
                            <div class="ho-alert ho-alert--ok">{{ session('success') }}</div>
                        @endif
                        @if($errors->any())
                            <div class="ho-alert ho-alert--err">
                                {{ $errors->first('form') ?: 'Please complete the highlighted fields below.' }}
                            </div>
                        @endif

                        <form class="ho-form" action="{{ route('handoverFeedback') }}" method="POST">
                            @csrf
                            <div class="site-hp-field" aria-hidden="true">
                                <label for="ho_hp_url">Company URL</label>
                                <input type="text" name="hp_url" id="ho_hp_url" value="" tabindex="-1" autocomplete="off">
                            </div>

                            <fieldset class="ho-fieldset">
                                <legend>1. How can we reach you?</legend>
                                <div class="ho-form__grid">
                                    <label class="ho-field">
                                        <span>Your name <em>*</em></span>
                                        <input type="text" name="names" required maxlength="255" value="{{ old('names') }}" autocomplete="name" placeholder="Full name">
                                        @error('names')<span class="ho-field__error">{{ $message }}</span>@enderror
                                    </label>
                                    <label class="ho-field">
                                        <span>Email <em>*</em></span>
                                        <input type="email" name="email" required maxlength="255" value="{{ old('email') }}" autocomplete="email" placeholder="you@abahizirwanda.org">
                                        @error('email')<span class="ho-field__error">{{ $message }}</span>@enderror
                                    </label>
                                </div>
                            </fieldset>

                            <fieldset class="ho-fieldset">
                                <legend>2. Your overall view</legend>
                                <div class="ho-choices" role="radiogroup" aria-label="Your overall view">
                                    <label class="ho-choice">
                                        <input type="radio" name="intent" value="approve" required @checked(old('intent') === 'approve')>
                                        <span>This looks good</span>
                                    </label>
                                    <label class="ho-choice">
                                        <input type="radio" name="intent" value="change" @checked(old('intent') === 'change')>
                                        <span>Please change this</span>
                                    </label>
                                    <label class="ho-choice">
                                        <input type="radio" name="intent" value="question" @checked(old('intent') === 'question')>
                                        <span>I have a question</span>
                                    </label>
                                </div>
                                @error('intent')<span class="ho-field__error">{{ $message }}</span>@enderror
                            </fieldset>

                            <fieldset class="ho-fieldset">
                                <legend>3. Your notes</legend>
                                <label class="ho-field">
                                    <span>What should we know? <em>*</em></span>
                                    <textarea name="message" rows="5" required minlength="10" maxlength="20000" placeholder="Example: The homepage heading should mention Masoro. Or: I could not find where to add a team member.">{{ old('message') }}</textarea>
                                    <span class="ho-field__hint">At least 10 characters. Be as specific as you can.</span>
                                    @error('message')<span class="ho-field__error">{{ $message }}</span>@enderror
                                </label>
                            </fieldset>

                            <button type="submit" class="ho-btn ho-btn--primary ho-btn--block">Send feedback</button>
                        </form>
                    </section>
                </div>
                <div class="ho-pager">
                    <button type="button" class="ho-pager__btn" data-ho-prev>Back</button>
                    <button type="button" class="ho-pager__btn ho-pager__btn--next" data-ho-next>Next</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        var root = document.querySelector('.ho-page');
        if (!root) return;

        var order = ['overview', 'access', 'guide', 'requests', 'next', 'feedback'];
        var nextLabels = {
            overview: 'Next: Log in',
            access: 'Next: Guide',
            guide: 'Next: Requests',
            requests: 'Next: Go live',
            next: 'Next: Feedback',
            feedback: ''
        };

        function activateTab(name) {
            var buttons = root.querySelectorAll('[data-ho-tab]');
            var panels = root.querySelectorAll('[data-ho-panel]');
            buttons.forEach(function (btn) {
                var on = btn.getAttribute('data-ho-tab') === name;
                btn.classList.toggle('is-active', on);
                btn.setAttribute('aria-selected', on ? 'true' : 'false');
                if (on && btn.scrollIntoView) {
                    btn.scrollIntoView({ inline: 'center', block: 'nearest', behavior: 'smooth' });
                }
            });
            panels.forEach(function (panel) {
                var on = panel.getAttribute('data-ho-panel') === name;
                panel.classList.toggle('is-active', on);
                if (on) {
                    panel.removeAttribute('hidden');
                } else {
                    panel.setAttribute('hidden', '');
                }
            });
            var jump = document.getElementById('ho-jump-select');
            if (jump && jump.value !== name) {
                jump.value = name;
            }
            var idx = order.indexOf(name);
            var prevBtn = root.querySelector('[data-ho-prev]');
            var nextBtn = root.querySelector('[data-ho-next]');
            if (prevBtn) {
                prevBtn.disabled = idx <= 0;
            }
            if (nextBtn) {
                nextBtn.hidden = idx >= order.length - 1;
                nextBtn.textContent = nextLabels[name] || 'Next';
            }
            if (history.replaceState) {
                history.replaceState(null, '', '#' + name);
            }
        }

        root.querySelectorAll('[data-ho-tab]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                activateTab(btn.getAttribute('data-ho-tab'));
            });
        });

        var jump = document.getElementById('ho-jump-select');
        if (jump) {
            jump.addEventListener('change', function () {
                activateTab(jump.value);
            });
        }

        var prevBtn = root.querySelector('[data-ho-prev]');
        var nextBtn = root.querySelector('[data-ho-next]');
        if (prevBtn) {
            prevBtn.addEventListener('click', function () {
                var current = (window.location.hash || '').replace('#', '') || 'overview';
                var idx = order.indexOf(current);
                if (idx > 0) {
                    activateTab(order[idx - 1]);
                    window.scrollTo({ top: root.offsetTop - 12, behavior: 'smooth' });
                }
            });
        }
        if (nextBtn) {
            nextBtn.addEventListener('click', function () {
                var current = (window.location.hash || '').replace('#', '') || 'overview';
                var idx = order.indexOf(current);
                if (idx < order.length - 1) {
                    activateTab(order[idx + 1]);
                    window.scrollTo({ top: root.offsetTop - 12, behavior: 'smooth' });
                }
            });
        }

        root.querySelectorAll('[data-ho-sub]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var name = btn.getAttribute('data-ho-sub');
                root.querySelectorAll('[data-ho-sub]').forEach(function (b) {
                    b.classList.toggle('is-active', b === btn);
                });
                root.querySelectorAll('[data-ho-subpanel]').forEach(function (panel) {
                    var on = panel.getAttribute('data-ho-subpanel') === name;
                    panel.classList.toggle('is-active', on);
                    if (on) {
                        panel.removeAttribute('hidden');
                    } else {
                        panel.setAttribute('hidden', '');
                    }
                });
            });
        });

        var start = root.getAttribute('data-ho-start') || 'overview';
        var hash = (window.location.hash || '').replace('#', '');
        if (order.indexOf(hash) !== -1) {
            start = hash;
        }
        activateTab(start);

        document.querySelectorAll('[data-copy]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var value = btn.getAttribute('data-copy') || '';
                var done = function () {
                    var original = btn.textContent;
                    btn.textContent = 'Copied';
                    window.setTimeout(function () { btn.textContent = original; }, 1400);
                };
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(value).then(done).catch(done);
                } else {
                    done();
                }
            });
        });
    })();
</script>

@endsection
