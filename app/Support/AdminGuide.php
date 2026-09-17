<?php

namespace App\Support;

class AdminGuide
{
    /**
     * Short lessons for non-technical editors. One screen at a time.
     *
     * @return array<int, array{
     *     slug: string,
     *     title: string,
     *     minutes: int,
     *     summary: string,
     *     steps: array<int, array{title: string, body: string}>,
     *     manage_route: ?string,
     *     manage_label: ?string,
     *     routes: array<int, string>
     * }>
     */
    public static function lessons(): array
    {
        return [
            [
                'slug' => 'welcome',
                'title' => 'How this website is edited',
                'minutes' => 2,
                'summary' => 'You change the public site from this admin. Each menu item owns one part of the website. Empty fields keep the default text.',
                'steps' => [
                    [
                        'title' => 'One place for each page',
                        'body' => 'The left menu is the map of the website. Open the item that matches the page you want to change (for example Products, Our factory, or Impact pillars). You do not need to hunt in Site settings for titles.',
                    ],
                    [
                        'title' => 'Save often',
                        'body' => 'Use the Save button on that screen. After saving, open the public website in another tab to check the change. If a field is left empty, visitors still see the original default wording.',
                    ],
                    [
                        'title' => 'Photos',
                        'body' => 'Upload photos on the same screen as the text. Site gallery feeds the homepage “Follow Our Journey” strip (newest three photos) and the public Gallery page. Media library is a file store, not a public page.',
                    ],
                ],
                'manage_route' => null,
                'manage_label' => null,
                'routes' => ['admin.guide.show'],
            ],
            [
                'slug' => 'settings',
                'title' => 'Site settings',
                'minutes' => 3,
                'summary' => 'Brand name, logo, contact details, colours, and whether the Products page is public.',
                'steps' => [
                    [
                        'title' => 'Account settings',
                        'body' => 'Set the organisation name and logo. The logo appears in the header and footer of the public site.',
                    ],
                    [
                        'title' => 'Contacts',
                        'body' => 'Address, email, phones, social links, and the map code. Homepage “Get in touch” titles and the Contact page banner are also here — not on a separate copy page.',
                    ],
                    [
                        'title' => 'Visibility',
                        'body' => 'Turn the public Products page on or off. When it is off, Products disappears from the menu and the homepage “view more bags” button is hidden.',
                    ],
                    [
                        'title' => 'Default header',
                        'body' => 'This is only the fallback banner if a page has no photo of its own. Edit each page’s title and banner on that page’s screen.',
                    ],
                ],
                'manage_route' => 'settings',
                'manage_label' => 'Open Site settings',
                'routes' => ['settings', 'saveSetting'],
            ],
            [
                'slug' => 'homepage-hero',
                'title' => 'Homepage banner',
                'minutes' => 3,
                'summary' => 'The large image or video at the top of the home page, plus the headline and buttons.',
                'steps' => [
                    [
                        'title' => 'Headline and buttons',
                        'body' => 'Write the main headline and subtitle. Button labels, the circular seal, and highlight bar sit on this same screen.',
                    ],
                    [
                        'title' => 'Choose media',
                        'body' => 'Use sliding images, one still photo, or a video. Sliding images come from the slides you add below the hero form.',
                    ],
                    [
                        'title' => 'Impact strip',
                        'body' => 'The dark strip under the banner shows Impact numbers (jobs, families, bags exported, and any others you add). Edit those on About & homepage → Impact numbers. Impact pillars stay on their own page as program cards.',
                    ],
                ],
                'manage_route' => 'slides',
                'manage_label' => 'Open Homepage hero',
                'routes' => ['slides', 'saveHero', 'editSlide', 'saveSlide', 'updateSlide', 'destroySlide'],
            ],
            [
                'slug' => 'media',
                'title' => 'Media library',
                'minutes' => 1,
                'summary' => 'A file cupboard for images already on the server. It is not a public gallery.',
                'steps' => [
                    [
                        'title' => 'When to use it',
                        'body' => 'Use Media library to find or replace a file that is already stored. To add photos that visitors should see, use Site gallery, Products, Factory gallery, or the page you are editing.',
                    ],
                ],
                'manage_route' => 'mediaLibrary.index',
                'manage_label' => 'Open Media library',
                'routes' => ['mediaLibrary.*'],
            ],
            [
                'slug' => 'about',
                'title' => 'About, story, and values',
                'minutes' => 4,
                'summary' => 'Our Story, Mission & Vision, Core values, What We Do, and homepage story text.',
                'steps' => [
                    [
                        'title' => 'Use the tabs',
                        'body' => 'Mission & vision, Core values, Our story, Homepage & what we do, Impact numbers, and Section photos each have their own save button. Stay on the tab you are editing.',
                    ],
                    [
                        'title' => 'Homepage titles live here',
                        'body' => 'Our Story titles are on the Our story tab. Our Values titles are on Core values. The page banner for each public About page is the “Public page header” box on that tab.',
                    ],
                    [
                        'title' => 'Impact numbers vs pillars',
                        'body' => 'Large stats (jobs, families, training hours) are on Impact numbers — rename labels, change figures, and add extra rows. Those numbers fill the homepage banner strip. Health, education, and similar cards are on Impact pillars.',
                    ],
                ],
                'manage_route' => 'about',
                'manage_label' => 'Open About & homepage',
                'routes' => ['about', 'saveAbout', 'saveBackg', 'background'],
            ],
            [
                'slug' => 'factory',
                'title' => 'Our factory',
                'minutes' => 3,
                'summary' => 'The public Our Factory page: intro, what you offer, community impact, training, and factory photos.',
                'steps' => [
                    [
                        'title' => 'Match the public sections',
                        'body' => 'Overview, What we offer, Community impact, Training, and Gallery each match a block visitors see. Fill only the tab you need, then save.',
                    ],
                    [
                        'title' => 'Page banner',
                        'body' => 'The top banner of Our Factory is the public page header on this screen. Empty fields keep the default title.',
                    ],
                ],
                'manage_route' => 'factory.admin.overview',
                'manage_label' => 'Open Our factory',
                'routes' => ['factory.admin.*'],
            ],
            [
                'slug' => 'products',
                'title' => 'Products',
                'minutes' => 4,
                'summary' => 'Homepage bag photos, catalog items, the Products page gallery, and Our Craft titles.',
                'steps' => [
                    [
                        'title' => 'Homepage cards',
                        'body' => 'The three bag photos on the home page are under Products → Homepage cards, together with Our Craft titles.',
                    ],
                    [
                        'title' => 'Catalog vs gallery',
                        'body' => 'Final products are sellable items with descriptions. Products page gallery is extra photos on /products. Homepage bag photos and Our Craft titles are on Homepage cards. Categories group the catalog. Product story is a longer article if you use one.',
                    ],
                    [
                        'title' => 'Show or hide the page',
                        'body' => 'If the Products page should not appear in the menu, turn it off under Site settings → Visibility.',
                    ],
                ],
                'manage_route' => 'catalogProducts.index',
                'manage_label' => 'Open Products',
                'routes' => ['catalogProducts.*', 'productCategories.*', 'productStory.*'],
            ],
            [
                'slug' => 'impact',
                'title' => 'Impact pillars',
                'minutes' => 3,
                'summary' => 'The four impact cards on the home page banner, the dark impact band, and every Impact page.',
                'steps' => [
                    [
                        'title' => 'Add a pillar',
                        'body' => 'Give each card a title, optional number, short description, and photo. Newest items appear first. These same cards show on Impact, Employee Empowerment, Community, and Reports.',
                    ],
                    [
                        'title' => 'Section titles',
                        'body' => 'Homepage impact heading and buttons are on this screen, above the list. The Impact page banner is the public page header box here.',
                    ],
                ],
                'manage_route' => 'impacts.index',
                'manage_label' => 'Open Impact pillars',
                'routes' => ['impacts.*', 'editImpact', 'saveImpact', 'updateImpact', 'destroyImpact'],
            ],
            [
                'slug' => 'community',
                'title' => 'Community programs',
                'minutes' => 3,
                'summary' => 'Initiatives on the public Impact → Community page.',
                'steps' => [
                    [
                        'title' => 'Add an initiative',
                        'body' => 'Title, story, cover photo, and optional gallery. Active items appear as cards. Edit an item to add ways visitors can get involved.',
                    ],
                    [
                        'title' => 'Page banner',
                        'body' => 'The Community page title and header image are the public page header box at the top of this list.',
                    ],
                ],
                'manage_route' => 'communityImpact.admin.index',
                'manage_label' => 'Open Community programs',
                'routes' => ['communityImpact.admin.index', 'editProject', 'saveProject', 'updateProject', 'destroyProject'],
            ],
            [
                'slug' => 'reports',
                'title' => 'Impact reports',
                'minutes' => 2,
                'summary' => 'PDF reports visitors can read and download.',
                'steps' => [
                    [
                        'title' => 'Listing page',
                        'body' => 'Set the reports page title and introduction, then add each report with a heading, optional description, and PDF.',
                    ],
                    [
                        'title' => 'Banner',
                        'body' => 'The public page header on this screen is the banner at the top of Impact Reports.',
                    ],
                ],
                'manage_route' => 'impactReports.admin.index',
                'manage_label' => 'Open Impact reports',
                'routes' => ['impactReports.admin.*'],
            ],
            [
                'slug' => 'updates',
                'title' => 'Updates',
                'minutes' => 2,
                'summary' => 'News and stories on the Updates page.',
                'steps' => [
                    [
                        'title' => 'Draft or publish',
                        'body' => 'Write the story, add a cover photo, then save as a draft or publish. Unpublished items stay off the public site.',
                    ],
                    [
                        'title' => 'Homepage journey photos',
                        'body' => 'Updates are not the homepage photo strip. That strip uses Site gallery (next lesson).',
                    ],
                ],
                'manage_route' => 'blog.index',
                'manage_label' => 'Open Updates',
                'routes' => ['blog.index', 'editBlog', 'saveBlog', 'updateBlog', 'deleteBlog', 'publishBlog', 'unpublishBlog', 'deleteBlogImage'],
            ],
            [
                'slug' => 'gallery',
                'title' => 'Site gallery',
                'minutes' => 2,
                'summary' => 'Photos for the public Gallery page and the three homepage journey images.',
                'steps' => [
                    [
                        'title' => 'Add as many as you like',
                        'body' => 'Upload photos here. The public Gallery page shows all of them. The homepage “Follow Our Journey” section always shows only the three newest. Rows marked “Shown on homepage” are those three.',
                    ],
                    [
                        'title' => 'Click to enlarge',
                        'body' => 'On the home page, visitors click a photo to open it larger. Captions appear in that viewer when you add them.',
                    ],
                ],
                'manage_route' => 'images',
                'manage_label' => 'Open Site gallery',
                'routes' => ['images', 'saveGallery', 'editGallery', 'updateGallery', 'destroyGallery'],
            ],
            [
                'slug' => 'team',
                'title' => 'Our team',
                'minutes' => 2,
                'summary' => 'People who appear on Our Team and on Our Story.',
                'steps' => [
                    [
                        'title' => 'Add a person',
                        'body' => 'Name, role, photo, and optional social links. Lower order numbers appear first. Set display to Yes so the person shows on the site.',
                    ],
                    [
                        'title' => 'Page banner',
                        'body' => 'The Team page title and header image are the public page header box on this screen.',
                    ],
                ],
                'manage_route' => 'staff',
                'manage_label' => 'Open Our team',
                'routes' => ['staff', 'editStaff', 'saveStaff', 'updateStaff', 'destroyStaff', 'staff.moveUp', 'staff.moveDown'],
            ],
            [
                'slug' => 'testimonials',
                'title' => 'Testimonials',
                'minutes' => 1,
                'summary' => 'Quotes from partners, clients, or community members.',
                'steps' => [
                    [
                        'title' => 'Add a quote',
                        'body' => 'Write the quote, add a name, and a photo if you have one. These can appear on impact stories as well as the Testimonials page.',
                    ],
                ],
                'manage_route' => 'getTestimonials',
                'manage_label' => 'Open Testimonials',
                'routes' => ['getTestimonials', 'editTestimony', 'saveTestimony', 'updateTestimony', 'destroyTestimony'],
            ],
            [
                'slug' => 'inbox',
                'title' => 'Messages and orders',
                'minutes' => 2,
                'summary' => 'What visitors send through the website.',
                'steps' => [
                    [
                        'title' => 'Order requests',
                        'body' => 'Product or collection enquiries from the site. Reply using the contact details in the request.',
                    ],
                    [
                        'title' => 'Contact messages and forms',
                        'body' => 'Contact messages are from the Get in touch form. Form submissions include partnership and other channel forms.',
                    ],
                ],
                'manage_route' => 'orderRequests.index',
                'manage_label' => 'Open Order requests',
                'extra_links' => [
                    ['route' => 'redirects', 'label' => 'Contact messages'],
                    ['route' => 'partnershipInquiries.index', 'label' => 'Form submissions'],
                ],
                'routes' => ['orderRequests.index', 'redirects', 'dashboard', 'webMessages', 'messageReply', 'partnershipInquiries.index', 'handoverFeedback.index', 'handoverFeedback.show'],
            ],
            [
                'slug' => 'users',
                'title' => 'Users and your profile',
                'minutes' => 1,
                'summary' => 'Who can sign in to this admin.',
                'steps' => [
                    [
                        'title' => 'Users',
                        'body' => 'Add colleagues who should edit the site. Only give access to people you trust.',
                    ],
                    [
                        'title' => 'My profile',
                        'body' => 'Change your own name and password here.',
                    ],
                ],
                'manage_route' => 'admin.users.index',
                'manage_label' => 'Open Users',
                'extra_links' => [
                    ['route' => 'admin.profile.edit', 'label' => 'My profile'],
                ],
                'routes' => ['admin.users.*', 'admin.profile.*'],
            ],
        ];
    }

    public static function find(string $slug): ?array
    {
        foreach (static::lessons() as $index => $lesson) {
            if ($lesson['slug'] === $slug) {
                $lesson['index'] = $index;

                return $lesson;
            }
        }

        return null;
    }

    public static function firstSlug(): string
    {
        return static::lessons()[0]['slug'];
    }

    /**
     * @return array{previous: ?array, next: ?array, current: array, position: int, total: int}
     */
    public static function navigation(string $slug): array
    {
        $lessons = static::lessons();
        $total = count($lessons);
        $current = static::find($slug) ?? $lessons[0];
        $index = (int) ($current['index'] ?? 0);

        return [
            'current' => $current,
            'previous' => $index > 0 ? $lessons[$index - 1] : null,
            'next' => $index < $total - 1 ? $lessons[$index + 1] : null,
            'position' => $index + 1,
            'total' => $total,
            'lessons' => $lessons,
        ];
    }

    public static function matchCurrentRequest(): ?array
    {
        $name = (string) (request()->route()?->getName() ?? '');
        if ($name === '') {
            return null;
        }

        foreach (static::lessons() as $lesson) {
            foreach ($lesson['routes'] as $pattern) {
                if (request()->routeIs($pattern)) {
                    return $lesson;
                }
            }
        }

        return null;
    }

    public static function urlForLesson(?array $lesson): string
    {
        $slug = $lesson['slug'] ?? static::firstSlug();

        return route('admin.guide.show', ['slug' => $slug]);
    }
}
