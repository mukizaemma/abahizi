<?php

namespace App\Http\Controllers;
use App\Models\News;
use App\Models\OrderRequest;

use App\Models\Team;
// use Google\Recaptcha\Recaptcha;
use App\Models\About;
use App\Models\Event;
use App\Models\Image;
use App\Models\Slide;
use App\Models\Donate;
use App\Models\Impact;
use App\Models\Member;
use App\Models\Country;
use App\Models\Gallery;
use App\Models\FactoryGalleryImage;
use App\Models\Message;
use App\Models\Partner;
use App\Models\Program;
use App\Models\Setting;
use App\Models\Activity;
use App\Models\AnnualReport;
use App\Models\ImpactReportPage;
use ReCaptcha\ReCaptcha;
use App\Models\Testimony;
use App\Models\Volunteer;
use App\Mail\ReplyMessage;
use App\Models\Background;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Service;
use App\Models\Sponsorship;
use App\Models\Projectimage;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\Concerns\ValidatesFormChannelSubmission;
use App\Support\FormChannelService;
use App\Support\PageHeaderService;

class HomeController extends Controller
{
    use ValidatesFormChannelSubmission;
    public function redirects(){
        if(Auth::user()->hasAdminPanelAccess()){
            $slides = Slide::latest()->get();
            $messages = Message::all();
            // $members = Member::latest()->get();

            return view('admin.dashboard',[
                'slides'=>$slides,
                'messages'=>$messages
                ]);
        }
        else{


            $programs = Program::oldest()->get();
            $about = Background::firstOrEmpty();
            $mission = About::firstOrEmpty();
            $news = News::latest()->paginate(2);
            $homeGallery = DB::table('galleries')->latest()->get();
            $events = DB::table('events')->latest()->get();
            $slides = DB::table('slides')->latest()->get();
            $testimonials = DB::table('testimonies')->latest()->get();
            $staff = DB::table('teams')->orderby('id','asc')->where('display','Yes')->get();

            return view('frontend.home', [
                'programs' =>$programs,
                'homeGallery' =>$homeGallery,
                'events' =>$events,
                'slides' =>$slides,
                'testimonials' =>$testimonials,
                'staff' =>$staff,
                'about' =>$about,
                'mission' => $mission,
                'news' => $news,
            ]);
        }
    }

    public function index(){
        $background = Background::latest()->get();
        $programs = Activity::oldest()->get();
        $about = Background::firstOrEmpty();
        $mission = About::firstOrEmpty();
        $homeGallery = Gallery::latest()->get();
        $slides = Slide::oldest()->get();
        $testimonials = Testimony::query()
            ->where(function ($q) {
                $q->whereNull('status')
                    ->orWhere('status', 'Publish')
                    ->orWhere('status', 'Active');
            })
            ->latest()
            ->take(3)
            ->get();
        $partners = Partner::latest()->get();
        $staff = Team::query()->where('display', 'Yes')->latest()->get();

        $today = Carbon::today()->toDateString();

        $event = Event::where('status', 'Active')
            ->where('date', '>=', $today)
            ->orderBy('date', 'asc') // earliest upcoming
            ->first();

        $setting = Setting::firstOrEmpty();
        $homeProducts = collect();
        if (($setting->show_products_publicly ?? false) && Schema::hasTable('products')) {
            $homeProducts = Product::query()
                ->active()
                ->with('category')
                ->latest()
                ->take(3)
                ->get();
        }

        $recentUpdates = Schema::hasTable('news')
            ? News::query()->whereNotNull('published_at')->latest('published_at')->take(3)->get()
            : collect();

        return view('frontend.home', [
            'background' =>$background,
            'programs' =>$programs,
            'homeGallery' =>$homeGallery,
            'event' =>$event,
            'slides' =>$slides,
            'testimonials' =>$testimonials,
            'partners' =>$partners,
            'staff' =>$staff,
            'about' =>$about,
            'mission' =>$mission,
            'homeProducts' => $homeProducts,
            'recentUpdates' => $recentUpdates,
        ]);
    }

    public function backgroundDetails(){

        $programs = Program::latest()->get();
        $partners = Partner::oldest()->get();
        $staff = Team::query()->where('display', 'Yes')->oldest()->get();
        $about = Background::firstOrEmpty();
        $mission = About::firstOrEmpty();
        $testimonials = DB::table('testimonies')->paginate(3);
        return view('frontend.about',['about'=>$about,'mission'=>$mission,'testimonials' =>$testimonials,'programs'=>$programs, 'partners'=>$partners, 'staff'=>$staff]);
    }
    public function team(){
        $programs = Program::latest()->get();
        $team = Team::query()->where('display', 'Yes')->where('category', 'Administration')->oldest()->get();
        $advisors = Team::query()->where('display', 'Yes')->where('category', 'Advisors')->oldest()->get();
        $about = Background::firstOrEmpty();
        return view('frontend.team',['team'=>$team,'programs'=>$programs,'about'=>$about,'advisors'=>$advisors]);
    }
    public function testimonials(){
        $programs = Program::latest()->get();
        $testimonials = Testimony::query()
            ->where(function ($q) {
                $q->whereNull('status')
                    ->orWhere('status', 'Publish')
                    ->orWhere('status', 'Active');
            })
            ->latest()
            ->get();
        $about = Background::firstOrEmpty();
        return view('frontend.testimonials',['testimonials'=>$testimonials,'programs'=>$programs, 'about'=>$about]);
    }
    public function testimony($id){
        $testimony = Testimony::findOrFail($id);
        $programs = Program:: latest()->get();
        $about = Background::firstOrEmpty();
        $testimonials = Testimony::where('id', '!=', $testimony->id)->paginate(6);
        return view('frontend.testimony',['testimony'=>$testimony, 'programs'=>$programs,'testimonials'=>$testimonials,'about'=>$about]);
    }
    public function showPrograms(){
        $programs = Activity::with('images')->oldest()->get();
        $about = Background::firstOrEmpty();
        return view('frontend.programs',['programs'=>$programs, 'about'=>$about]);
    }
    public function singleProgram($slug){
        $program = Program::with('activities')->where('slug',$slug)->firstOrFail();
        $programs = Program::where('id' ,'!=',$program->id)->oldest()->get();
        $about = Background::firstOrEmpty();
        $gallery = Gallery::latest()->get();
        $news = News::latest()->paginate(9);
        return view('frontend.activities',['program'=>$program, 'programs'=>$programs, 'about'=>$about, 'gallery'=>$gallery,'news'=>$news]);
    }

    public function project($slug){
        $eager = ['images', 'program'];
        if (Schema::hasTable('programimages')) {
            $eager[] = 'program.images';
        }

        $activity = Activity::with($eager)->where('slug', $slug)->first();
        if (!$activity) {
            $program = Program::with('activities')->where('slug', $slug)->firstOrFail();
            $programs = Program::where('id', '!=', $program->id)->oldest()->get();
            $about = Background::firstOrEmpty();
            $gallery = Gallery::latest()->get();
            $news = News::latest()->paginate(9);

            return view('frontend.activities', [
                'program' => $program,
                'programs' => $programs,
                'about' => $about,
                'gallery' => $gallery,
                'news' => $news,
            ]);
        }

        $images = $activity->images;

        $programGallery = collect();
        if (Schema::hasTable('programimages') && $activity->program) {
            $programGallery = $activity->program->images()->latest()->get();
        }

        $relatedActivities = collect();
        if ($activity->program_id) {
            $relatedActivities = Activity::query()
                ->where('program_id', $activity->program_id)
                ->where('id', '!=', $activity->id)
                ->oldest()
                ->get();
        }

        $about = Background::firstOrEmpty();
        $news = News::latest()->paginate(9);

        return view('frontend.activity', [
            'activity' => $activity,
            'relatedActivities' => $relatedActivities,
            'about' => $about,
            'images' => $images,
            'programGallery' => $programGallery,
            'news' => $news,
        ]);
    }
    public function campaigns(){
        $programs = Program::oldest()->get();
        $about = Background::firstOrEmpty();
        return view('frontend.campaigns',['about'=>$about,'programs'=>$programs]);
    }
    public function campaign($slug){
        $about = Background::firstOrEmpty();
        $programs = Program::oldest()->get();
        $testimonials = DB::table('testimonies')->paginate(6);
        return view('frontend.campaign',['about'=>$about, 'testimonials'=>$testimonials,'programs'=>$programs]);
    }

    public function upcomingEvents(){
        $events = Event::where('status','Active')->latest()->get();
        return view('frontend.events',['events'=>$events]);
    }
    
    public function event($slug){
        $event = Event::where('slug', $slug)->firstOrFail();
        return view('frontend.event',['event'=>$event]);
    }
    public function posts(){
        $news = News::latest()->paginate(20);
        $programs = Program::latest()->get();
        $about = Background::firstOrEmpty();
        return view('frontend.blogs',['news'=>$news,'programs'=>$programs, 'about'=>$about]);
    }

    public function postSingle($slug){
        $blogs = News::latest()->get();
        $blog = News::where('slug',$slug)->firstOrFail();
        $images = $blog->blogimages ?? collect();
        $relatedBlogs = News::where('id','!=',$blog->id)->latest()->take(9);
        $programs = Program::latest()->get();
        $about = Background::firstOrEmpty();
        return view('frontend.blog',['blog'=>$blog,'blogs'=>$blogs,'relatedBlogs'=>$relatedBlogs,
        'programs'=>$programs,'about'=>$about,'images'=>$images]);
    }

public function gallery(){
    $gallery = Projectimage::latest()->take(9)->get();
    $programs = Activity::with('images')->get();

    return view('frontend.gallery', [
        'gallery' => $gallery,
        'programs' => $programs
    ]);
}


    public function contacts(Request $request){
        $contact = Setting::firstOrEmpty();
        $programs = Program::latest()->get();
        $about = Background::firstOrEmpty();
        $product = null;
        if ($request->filled('product') && Schema::hasTable('products')) {
            $product = Product::query()->active()->where('slug', (string) $request->input('product'))->first();
        }

        return view('frontend.contact', [
            'programs' => $programs,
            'contact' => $contact,
            'about' => $about,
            'product' => $product,
        ]);
    }


    public function sendMessage(Request $request){
        $availability = FormChannelService::availability(Setting::firstOrEmpty());
        if (! $availability['channels_ready']) {
            return back()
                ->withInput()
                ->withErrors(['form' => 'Submissions are unavailable until both a valid site email and WhatsApp number are configured in admin settings.']);
        }

        $ipKey = 'contact-message:ip:' . $request->ip();
        if (RateLimiter::tooManyAttempts($ipKey, 5)) {
            return back()
                ->withInput()
                ->withErrors(['form' => 'Too many attempts. Please wait a few minutes and try again.']);
        }

        RateLimiter::hit($ipKey, 10 * 60);

        $channelGate = $this->validateFormChannelGate($request, 'contact');

        $allowedInterests = array_keys(FormChannelService::contactInterestLabels());

        $validated = $request->validate([
            'names' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:64'],
            'email' => ['required', 'email', 'max:255'],
            'organization' => ['nullable', 'string', 'max:255'],
            'interests' => ['nullable', 'array'],
            'interests.*' => ['string', 'in:' . implode(',', $allowedInterests)],
            'message' => ['required', 'string', 'min:10', 'max:20000'],
            'product_reference' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'max:0'],
            'started_at' => ['nullable', 'integer'],
        ]);

        $phoneDigits = preg_replace('/\D+/', '', (string) $validated['phone']);
        if (strlen($phoneDigits) < 10) {
            return back()
                ->withInput()
                ->withErrors(['phone' => 'Enter a valid phone number with at least 10 digits.']);
        }

        if (FormChannelService::normalizeEmail($validated['email']) === null) {
            return back()
                ->withInput()
                ->withErrors(['email' => 'Enter a valid email address.']);
        }

        $startedAt = (int) ($request->input('started_at') ?? 0);
        if ($startedAt > 0 && (time() - $startedAt) < 3) {
            return back()
                ->withInput()
                ->withErrors(['form' => 'Form submitted too quickly. Please review your details and try again.']);
        }

        foreach (['names', 'organization', 'message'] as $field) {
            $value = (string) ($validated[$field] ?? '');
            if (FormChannelService::containsSpamLinks($value)) {
                return back()
                    ->withInput()
                    ->withErrors([$field => 'Please remove links from this field.']);
            }
        }

        $interestsText = FormChannelService::formatContactInterests((array) $request->input('interests', []));
        $storedMessage = trim($validated['message']);
        $meta = [];
        if (! empty($validated['organization'])) {
            $meta[] = 'Organisation: ' . $validated['organization'];
        }
        if ($interestsText !== null) {
            $meta[] = 'Topics: ' . $interestsText;
        }
        if (! empty($validated['product_reference'])) {
            $meta[] = 'Product: ' . $validated['product_reference'];
        }
        if ($meta !== []) {
            $storedMessage = implode("\n", $meta) . "\n\n" . $storedMessage;
        }

        Message::create([
            'names' => $validated['names'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'message' => $storedMessage,
            'submission_channel' => $channelGate['channel'],
        ]);

        return redirect()
            ->route('contacts')
            ->with('success', 'Thank you. We recorded your inquiry after you sent it via ' . FormChannelService::channelLabel($channelGate['channel']) . '. Our team will follow up shortly.');
    }

    public function webMessages(){

        $messages = Message::all();
        return view('admin.dashboard', ['messages'=>$messages]);
    }

    public function messageReply($id){

        $data = Message::find($id);
        return view('admin.emails.messageReply',['data'=>$data]);
    }

    public function sendReply(Request $request)
    {
        $data = [
            'email' => $request->email,
            'reply' => $request->reply,
        ];
        Mail::to($request->email)->send(new ReplyMessage($data));
        return redirect()->back()->with('success', 'Reply sent successfully');
    }


    public function members(){
        $countries = Country::all();
        return view('frontend.becomeMember',[
            'countries'=>$countries,
            ]);
    }
    public function volunteer(){
        return view('frontend.volunteer');
    }
    public function donate(){
        $countries = Country::all();
        $children = Sponsorship::where('status','Not Sponsored')->get();
        return view('frontend.donate',[
            'countries'=>$countries,
            'children'=>$children
            ]);
    }

    public function saveDonation(Request $request){
        $data = new donate();
        $data->names = $request->names;
        $data ->email = $request->email;
        $data ->amount = $request->amount;
        $data ->program_id = $request->program_id;
        $data ->period = $request->period;
        $data ->country = $request->country;

        $stored = $data->save();

        if($stored){
            return redirect()->back()->with('success', 'Thank you for pledging to sponsor our Child. We will get back to you soon for more details!');
        }

    }

    public function saveMember(Request $request){
        $data = new Member();
        $data->names = $request->names;
        $data ->phone = $request->phone;
        $data ->address = $request->address;
        $data ->gender = $request->gender;
        $data ->martual = $request->martual;
        $data ->membership = $request->membership;
        $data ->dateJoined = $request->dateJoined;

        $stored = $data->save();

        if($stored){
            return redirect()->back()->with('success', 'Thank you for your membership. We will get back to you soon for more details');
        }

    }


    public function programDetail($id){
        $data = Program::find($id);
        return view('frontend.programDetails',['data'=>$data]);
    }

    public function setting(){
        $data = Setting::first();
        if($data===null)
        {
            $data = new Setting();
            $data->title = 'Company Name';
            $data->save();
            $data = Setting::first();
        }

        return view('admin.settings', ['data'=>$data]);
    }



    public function saveSetting(Request $request){
        $data = Setting::firstOrEmpty();
        $data->company = $request->input('company');
        $data->address = $request->input('address');
        $data->phone = $request->input('phone');
        $data->phone1 = $request->input('phone1');
        $data->phone2 = $request->input('phone2');
        $data->email = $request->input('email');
        $data->keywords = $request->input('keywords');
        $data->facebook = $request->input('facebook');
        $data->instagram = $request->input('instagram');
        $data->youtube = $request->input('youtube');

        // Theme options (safe if migration hasn't run yet)
        if (Schema::hasColumn('settings', 'primary_color')) {
            $data->primary_color = $request->input('primary_color') ?: '#fad200';
        }
        if (Schema::hasColumn('settings', 'secondary_color')) {
            $data->secondary_color = $request->input('secondary_color') ?: '#2c2c2c';
        }
        if (Schema::hasColumn('settings', 'neutral_color')) {
            $data->neutral_color = $request->input('neutral_color') ?: '#b0b0b0';
        }
        if (Schema::hasColumn('settings', 'font_family')) {
            $data->font_family = $request->input('font_family') ?: 'Poppins';
        }
        if (Schema::hasColumn('settings', 'show_products_publicly')) {
            $data->show_products_publicly = $request->boolean('show_products_publicly');
        }
        if (Schema::hasColumn('settings', 'show_products_page')) {
            $data->show_products_page = $request->boolean('show_products_page');
        }
        if (Schema::hasColumn('settings', 'accept_order_requests')) {
            $data->accept_order_requests = $request->boolean('accept_order_requests');
        }
        if (Schema::hasColumn('settings', 'page_header_caption')) {
            $data->page_header_caption = $request->input('page_header_caption');
        }
        if (Schema::hasColumn('settings', 'google_map_embed_code')) {
            $data->google_map_embed_code = $request->input('google_map_embed_code');
        }
        if (Schema::hasColumn('settings', 'hero_video_url')) {
            $data->hero_video_url = $request->input('hero_video_url');
        }
        if (Schema::hasColumn('settings', 'hero_headline')) {
            $data->hero_headline = $request->input('hero_headline');
        }
        if (Schema::hasColumn('settings', 'hero_subheadline')) {
            $data->hero_subheadline = $request->input('hero_subheadline');
        }
        if (Schema::hasColumn('settings', 'page_headers')) {
            $headers = is_array($data->page_headers) ? $data->page_headers : [];
            $inputHeaders = (array) $request->input('page_headers', []);

            foreach (PageHeaderService::editablePageKeys() as $pageKey) {
                $existing = (array) ($headers[$pageKey] ?? []);
                $incoming = (array) ($inputHeaders[$pageKey] ?? []);

                if (array_key_exists('caption', $incoming)) {
                    $existing['caption'] = trim((string) $incoming['caption']);
                }

                $fileKey = "page_headers.{$pageKey}.image";
                if ($request->hasFile($fileKey)) {
                    $path = $request->file($fileKey)->store('public/images/page-headers');
                    $existing['image'] = 'page-headers/' . basename($path);
                }

                $headers[$pageKey] = $existing;
            }

            $data->page_headers = $headers;
        }

        if ($request->hasFile('logo') && request('logo') != '') {
            $dir = 'public/images';

            if (File::exists($dir)) {
                unlink($dir);
            }
            $path = $request->file('logo')->store($dir);
            $fileName = str_replace($dir, '', $path);

            $data->logo = $fileName;
        }

        if (Schema::hasColumn('settings', 'page_header_image') && $request->hasFile('page_header_image') && request('page_header_image') != '') {
            $dir = 'public/images';

            $path = $request->file('page_header_image')->store($dir);
            $fileName = str_replace($dir, '', $path);

            $data->page_header_image = $fileName;
        }

        if (Schema::hasColumn('settings', 'hero_poster') && $request->hasFile('hero_poster')) {
            $path = $request->file('hero_poster')->store('public/images/page-headers');
            $data->hero_poster = 'page-headers/' . basename($path);
        }

        $data->save();

        return redirect()->back()->with('success', 'Setting has been updated successfully');
    }

    public function about(){
        $data = About::first();
        if($data===null)
        {
            $data = new About();
            $data->vision = 'Alleviate poverty among single-teen mothers in Rutsiro District by providing tailoring trainings';
            $data->save();
            $data = About::first();
        }

        $background = Background::firstOrEmpty();

        return view('admin.about', ['data'=>$data, 'background' => $background]);
    }

    public function saveAbout(Request $request, $id){
        $data = About::firstOrEmpty();
        $data->mission = $request->input('mission');
        $data->vision = $request->input('vision');
        $data->values = $request->input('values');
        if (Schema::hasColumn('abouts', 'core_values_list') && $request->has('core_values_list')) {
            $data->core_values_list = $request->input('core_values_list');
        }


        if ($request->hasFile('backImage') && request('backImage') != '') {
            $dir = 'public/images';

            if (File::exists($dir)) {
                unlink($dir);
            }
            $path = $request->file('backImage')->store($dir);
            $fileName = str_replace($dir, '', $path);

            $data->backImage = $fileName;
        }

        $data->save();

        return redirect()->back()->with('success', 'Setting has been updated successfully');
    }

    public function logoutUser(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function registerUser(){
        return view('frontend.registerUser');
    }

    public function loginUser(){
        return view('frontend.loginUser');
    }

    public function deleteDonation($id){
        $data = Donation::find($id);
        $data->delete($id);
        return redirect()->back()->with('warning','Donation has been deleted!');
    }

    public function ourMission(){
        $about = Background::firstOrEmpty();
        $mission = About::firstOrEmpty();
        return view('frontend.our-mission', compact('about', 'mission'));
    }

    public function whatWeDo(){
        $about = Background::firstOrEmpty();
        return view('frontend.what-we-do', compact('about'));
    }

    public function ourApproach(){
        $about = Background::firstOrEmpty();
        return view('frontend.our-approach', compact('about'));
    }

    public function ourModel(){
        $about = Background::firstOrEmpty();
        return view('frontend.our-model', compact('about'));
    }

    public function ourFactory(){
        $about = Background::firstOrEmpty();
        $factoryGallery = Schema::hasTable('factory_gallery_images')
            ? FactoryGalleryImage::query()->latest()->take(6)->get()
            : Gallery::query()->latest()->take(6)->get();
        $services = Service::query()->active()->orderBy('sort_order')->orderBy('title')->get();

        return view('frontend.our-factory', compact('about', 'factoryGallery', 'services'));
    }

    public function manufacturing()
    {
        return redirect()->route('ourFactory', [], 301);
    }

    public function ourServices(){
        $about = Background::firstOrEmpty();
        $services = Service::query()->active()->orderBy('sort_order')->orderBy('title')->get();
        return view('frontend.our-services', compact('about', 'services'));
    }

    public function serviceShow($slug){
        $about = Background::firstOrEmpty();
        $service = Service::query()->active()->where('slug', $slug)->firstOrFail();
        return view('frontend.service-single', compact('about', 'service'));
    }

    public function ourProducts(Request $request){
        $about = Background::firstOrEmpty();
        $setting = Setting::firstOrEmpty();
        if (Schema::hasColumn('settings', 'show_products_page') && !($setting->show_products_page ?? true)) {
            abort(404);
        }
        $categories = ProductCategory::query()->active()->orderBy('sort_order')->orderBy('name')->get();

        $products = collect();
        if ($setting->show_products_publicly ?? false) {
            $query = Product::query()->active()->with('category');

            if ($request->filled('category')) {
                $query->where('product_category_id', (int) $request->input('category'));
            }

            if ($request->filled('q')) {
                $term = trim((string) $request->input('q'));
                $query->where(function ($q) use ($term) {
                    $q->where('title', 'like', '%' . $term . '%')
                        ->orWhere('description', 'like', '%' . $term . '%')
                        ->orWhere('color', 'like', '%' . $term . '%');
                });
            }

            $products = $query->orderBy('sort_order')->orderBy('title')->get();
        }

        $catalogEnabled = (bool) ($setting->show_products_publicly ?? false);
        $hasCatalogProducts = $catalogEnabled
            && Schema::hasTable('products')
            && Product::query()->active()->exists();

        return view('frontend.our-products', compact('about', 'products', 'categories', 'setting', 'catalogEnabled', 'hasCatalogProducts'));
    }

    public function productShow($slug){
        $about = Background::firstOrEmpty();
        $setting = Setting::firstOrEmpty();
        if (!($setting->show_products_publicly ?? false)) {
            abort(404);
        }
        $product = Product::query()
            ->active()
            ->where('slug', $slug)
            ->with(['category', 'images'])
            ->firstOrFail();

        return view('frontend.product-detail', compact('about', 'product', 'setting'));
    }

    public function requestOrder(Request $request)
    {
        $params = array_filter([
            'product' => $request->input('product'),
        ]);

        return redirect()->route('contacts', $params);
    }

    public function storeOrderRequest(Request $request)
    {
        $setting = Setting::firstOrEmpty();

        if (! ($setting->show_products_publicly ?? false)) {
            abort(404);
        }

        if (! ($setting->accept_order_requests ?? true)) {
            return back()
                ->withInput()
                ->withErrors(['form' => 'Product orders are not being accepted at the moment. Please use the contact page.']);
        }

        $availability = FormChannelService::availability($setting);
        if (! $availability['channels_ready']) {
            return back()
                ->withInput()
                ->withErrors(['form' => 'Orders are unavailable until both a valid site email and WhatsApp number are configured in admin settings.']);
        }

        $ipKey = 'product-order:ip:' . $request->ip();
        if (RateLimiter::tooManyAttempts($ipKey, 5)) {
            return back()
                ->withInput()
                ->withErrors(['form' => 'Too many attempts. Please wait a few minutes and try again.']);
        }

        RateLimiter::hit($ipKey, 10 * 60);

        $channelGate = $this->validateFormChannelGate($request, 'order');

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:64'],
            'email' => ['required', 'email', 'max:255'],
            'product_description' => ['required', 'string', 'min:10', 'max:20000'],
            'product_slug' => ['nullable', 'string', 'max:255'],
            'product_reference' => ['nullable', 'string', 'max:255'],
            'product_id' => ['nullable', 'integer', 'exists:products,id'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:99999'],
            'website' => ['nullable', 'max:0'],
            'started_at' => ['nullable', 'integer'],
        ]);

        $phoneDigits = preg_replace('/\D+/', '', (string) $validated['phone']);
        if (strlen($phoneDigits) < 10) {
            return back()
                ->withInput()
                ->withErrors(['phone' => 'Enter a valid phone number with at least 10 digits.']);
        }

        if (FormChannelService::normalizeEmail($validated['email']) === null) {
            return back()
                ->withInput()
                ->withErrors(['email' => 'Enter a valid email address you actively use.']);
        }

        $startedAt = (int) ($request->input('started_at') ?? 0);
        if ($startedAt > 0 && (time() - $startedAt) < 3) {
            return back()
                ->withInput()
                ->withErrors(['form' => 'Form submitted too quickly. Please review your details and try again.']);
        }

        if (FormChannelService::containsSpamLinks((string) $validated['product_description'])) {
            return back()
                ->withInput()
                ->withErrors(['product_description' => 'Please remove links from your order details.']);
        }

        $productId = $validated['product_id'] ?? null;
        $productReference = trim((string) ($validated['product_reference'] ?? ''));

        if ($productId === null && $request->filled('product_slug')) {
            $product = Product::query()->active()->where('slug', (string) $request->input('product_slug'))->first();
            if ($product) {
                $productId = $product->id;
                if ($productReference === '') {
                    $productReference = $product->title;
                }
            }
        }

        $orderDetails = trim((string) $validated['product_description']);
        if (! empty($validated['quantity'])) {
            $orderDetails = 'Quantity: ' . (int) $validated['quantity'] . "\n\n" . $orderDetails;
        }

        OrderRequest::create([
            'full_name' => $validated['full_name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'product_description' => $orderDetails,
            'product_id' => $productId,
            'product_reference' => $productReference !== '' ? $productReference : null,
            'submission_channel' => $channelGate['channel'],
        ]);

        $redirectUrl = route('ourProducts');
        if ($productId) {
            $slug = (string) ($request->input('product_slug') ?: Product::find($productId)?->slug);
            if ($slug !== '') {
                $redirectUrl = route('productShow', $slug);
            }
        }

        return redirect()
            ->to($redirectUrl . '#product-order-form')
            ->with('order_success', 'Thank you. We recorded your order after you sent it via ' . FormChannelService::channelLabel($channelGate['channel']) . '. Our team will follow up shortly.');
    }

    public function impactReportsIndex()
    {
        $about = Background::firstOrEmpty();
        $page = ImpactReportPage::firstOrSingleton();
        $reports = AnnualReport::query()->active()->ordered()->get();

        return view('frontend.impact-reports', compact('about', 'page', 'reports'));
    }

    public function impactReportShow($slug)
    {
        $about = Background::firstOrEmpty();
        $report = AnnualReport::query()
            ->active()
            ->with('images')
            ->where('slug', $slug)
            ->firstOrFail();

        if (empty($report->pdf)) {
            abort(404);
        }

        $galleryImages = Schema::hasTable('annual_report_images')
            ? $report->images
            : collect();

        return view('frontend.impact-report-show', compact('about', 'report', 'galleryImages'));
    }

    public function impactPage(){
        $tab = request('tab');
        if ($tab === 'empower') {
            return redirect()->route('impactEmployeeEmpowerment', [], 301);
        }
        if ($tab === 'improve') {
            return redirect()->route('impactCommunity', [], 301);
        }
        if ($tab === 'reports') {
            return redirect()->route('impactReports', [], 301);
        }

        $about = Background::firstOrEmpty();
        $hubCommunityImage = Activity::query()
            ->where(function ($q) {
                $q->whereNull('status')
                    ->orWhere('status', 'Active');
            })
            ->whereNotNull('image')
            ->where('image', '!=', '')
            ->latest()
            ->first();
        $hubReportImage = Schema::hasTable('annual_reports')
            ? AnnualReport::query()->active()->with('images')->ordered()->first()
            : null;

        return view('frontend.impact', compact('about', 'hubCommunityImage', 'hubReportImage'));
    }

    public function impactEmployeeEmpowerment()
    {
        $about = Background::firstOrEmpty();
        $impacts = Impact::query()->where('status', 'Active')->latest()->get();
        $testimonials = Testimony::query()
            ->where(function ($q) {
                $q->whereNull('status')
                    ->orWhere('status', 'Publish')
                    ->orWhere('status', 'Active');
            })
            ->latest()
            ->take(8)
            ->get();

        return view('frontend.impact-employee-empowerment', compact('about', 'impacts', 'testimonials'));
    }

    public function impactCommunity()
    {
        $about = Background::firstOrEmpty();
        $initiatives = Activity::query()
            ->where(function ($q) {
                $q->whereNull('status')
                    ->orWhere('status', 'Active');
            })
            ->latest()
            ->get();

        return view('frontend.impact-community', compact('about', 'initiatives'));
    }

    public function handoverPage()
    {
        $about = Background::firstOrEmpty();

        return view('frontend.handover', compact('about'));
    }


}
