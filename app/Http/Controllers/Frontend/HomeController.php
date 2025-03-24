<?php

namespace App\Http\Controllers\Frontend;

use Exception;
use App\Models\Subscriber;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Constants\GlobalConst;
use App\Constants\LanguageConst;
use App\Http\Helpers\Response;
use App\Models\Admin\Cars\Car;
use App\Models\Admin\Language;
use App\Models\ContactMessage;
use App\Models\Admin\SetupPage;
use App\Models\Admin\AppSettings;
use App\Models\Admin\Announcement;
use App\Models\Admin\Cars\CarArea;
use App\Models\Admin\Cars\CarType;
use App\Models\Admin\SiteSections;
use App\Constants\SiteSectionConst;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\AdminNotification;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin\AnnouncementCategory;
use App\Providers\Admin\BasicSettingsProvider;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(BasicSettingsProvider $basic_settings)
    {
        $page_title = $basic_settings->get()?->site_name . " | " . $basic_settings->get()?->site_title;
        $lang = selectedLang();
        $default = LanguageConst::NOT_REMOVABLE;
        $banner_slug = Str::slug(SiteSectionConst::BANNER_SECTION);
        $banner = SiteSections::getData($banner_slug)->first();
        $pattern = '/\[(.*?)\]/';
        preg_match($pattern, $banner->value->language->$lang->heading ?? $banner->value->language->$default->heading, $matches);
        $coloredText = isset($matches[1]) ? $matches[1] : '';
        $remainingText = str_replace("[$coloredText]", '', $banner->value->language->$lang->heading ?? $banner->value->language->$default->heading);

        $security_slug = Str::slug(SiteSectionConst::SECURITY_SECTION);
        $security = SiteSections::getData($security_slug)->first();

        $why_choose_us_slug = Str::slug(SiteSectionConst::WHY_CHOOSE_US_SECTION);
        $why_choose_us = SiteSections::getData($why_choose_us_slug)->first();

        $footer_slug = Str::slug(SiteSectionConst::FOOTER_SECTION);
        $footer = SiteSections::getData($footer_slug)->first();
        $type =  Str::slug(GlobalConst::USEFUL_LINKS);
        $policies = SetupPage::orderBy('id')->where('type', $type)->where('status', 1)->get();
        $policy = SetupPage::orderBy('id')->where('type', $type)->where('status',1)->where('slug','privacy-policy')->first();

        $testimonial_slug = Str::slug(SiteSectionConst::TESTIMONIAL_SECTION);
        $testimonial = SiteSections::getData($testimonial_slug)->first();

        $app_slug = Str::slug(SiteSectionConst::APP_SECTION);
        $app = SiteSections::getData($app_slug)->first();

        $auth_slug = Str::slug(SiteSectionConst::AUTH_SECTION);
        $auth = SiteSections::getData($auth_slug)->first();

        $statistics_slug = Str::slug(SiteSectionConst::STATISTICS_SECTION);
        $statistics = SiteSections::getData($statistics_slug)->first();

        $car_types  = CarType::where('status',true)->get();
        $areas      = CarArea::where('status',true)->get();
        $cars = Car::where('status', true)
        ->whereHas('type', function ($query) {
            $query->where('status', true);
        })
        ->whereHas('branch', function ($query) {
            $query->where('status', true);
        })
        ->where(function ($query) {
            $query->whereHas('bookings', function ($subquery) {
                $subquery->where('status', '=', 3)->orWhere('status', '=', 1);
            })->orWhereDoesntHave('bookings');
        })
        ->get();
        $app_settings = AppSettings::first();
        return view('frontend.index', compact('page_title', 'lang','default', 'banner', 'coloredText', 'remainingText', 'security', 'why_choose_us', 'footer', 'policies','testimonial','app','statistics','auth','policy','areas','car_types','cars','app_settings'));
    }

    public function aboutView()
    {
        $page_title = setPageTitle(__("About Us"));
        $about_slug = Str::slug(SiteSectionConst::ABOUT_SECTION);
        $about = SiteSections::getData($about_slug)->first();
        $lang = selectedLang();
        $default = LanguageConst::NOT_REMOVABLE;
        $faq_slug = Str::slug(SiteSectionConst::FAQ_SECTION);
        $faq = SiteSections::getData($faq_slug)->first();
        $items = json_decode(json_encode($faq->value->items), true);
        $totalItems = count($items);
        $half = ceil($totalItems / 2);
        $app_slug = Str::slug(SiteSectionConst::APP_SECTION);
        $app = SiteSections::getData($app_slug)->first();
        $footer_slug = Str::slug(SiteSectionConst::FOOTER_SECTION);
        $footer = SiteSections::getData($footer_slug)->first();
        $type =  Str::slug(GlobalConst::USEFUL_LINKS);
        $auth_slug = Str::slug(SiteSectionConst::AUTH_SECTION);
        $auth = SiteSections::getData($auth_slug)->first();
        $app_settings = AppSettings::first();
        $policies = SetupPage::orderBy('id')->where('type', $type)->where('status', 1)->get();
        $policy = SetupPage::orderBy('id')->where('type', $type)->where('status',1)->where('slug','privacy-policy')->first();
        return view('frontend.pages.about', compact('page_title','lang','default','about','faq','half','totalItems','policy','policies','app','footer','auth','app_settings'));
    }
    public function contactView()
    {
        $page_title = setPageTitle(__("Contact"));
        $contact_slug = Str::slug(SiteSectionConst::CONTACT_SECTION);
        $contact = SiteSections::getData($contact_slug)->first();
        $lang = selectedLang();
        $default = LanguageConst::NOT_REMOVABLE;
        $app_slug = Str::slug(SiteSectionConst::APP_SECTION);
        $app = SiteSections::getData($app_slug)->first();
        $footer_slug = Str::slug(SiteSectionConst::FOOTER_SECTION);
        $footer = SiteSections::getData($footer_slug)->first();
        $type =  Str::slug(GlobalConst::USEFUL_LINKS);
        $auth_slug = Str::slug(SiteSectionConst::AUTH_SECTION);
        $auth = SiteSections::getData($auth_slug)->first();
        $app_settings = AppSettings::first();
        $policies = SetupPage::orderBy('id')->where('type', $type)->where('status', 1)->get();
        $policy = SetupPage::orderBy('id')->where('type', $type)->where('status',1)->where('slug','privacy-policy')->first();
        return view('frontend.pages.contact', compact('page_title', 'contact', 'lang','default','app','footer','policy','policies','auth','app_settings'));
    }
    public function findCarView()
    {
        $page_title = setPageTitle(__("Find Car"));
        $default = LanguageConst::NOT_REMOVABLE;
        $cars = Car::where('status', true)
        ->whereHas('type', function ($query) {
            $query->where('status', true);
        })
        ->whereHas('branch', function ($query) {
            $query->where('status', true);
        })
        ->where(function ($query) {
            $query->whereHas('bookings', function ($subquery) {
                $subquery->where('status', '=', 3)->orWhere('status', '=', 1);
            })->orWhereDoesntHave('bookings');
        })
        ->get();
        $areas = CarArea::where('status',true)->get();
        $types = CarType::where('status',true)->get();
        $app_slug = Str::slug(SiteSectionConst::APP_SECTION);
        $app = SiteSections::getData($app_slug)->first();
        $footer_slug = Str::slug(SiteSectionConst::FOOTER_SECTION);
        $footer = SiteSections::getData($footer_slug)->first();
        $auth_slug = Str::slug(SiteSectionConst::AUTH_SECTION);
        $auth = SiteSections::getData($auth_slug)->first();
        $type =  Str::slug(GlobalConst::USEFUL_LINKS);
        $policies = SetupPage::orderBy('id')->where('type', $type)->where('status', 1)->get();
        $policy = SetupPage::orderBy('id')->where('type', $type)->where('status',1)->where('slug','privacy-policy')->first();
        return view('frontend.pages.find-car', compact('page_title','cars','areas','types','app','footer','policy','policies','auth','default'));
    }
    public function servicesView()
    {
        $page_title = setPageTitle(__("Services"));
        $lang = selectedLang();
        $default = LanguageConst::NOT_REMOVABLE;
        $service_slug = Str::slug(SiteSectionConst::SERVICE_SECTION);
        $service = SiteSections::getData($service_slug)->first();
        $app_slug = Str::slug(SiteSectionConst::APP_SECTION);
        $app = SiteSections::getData($app_slug)->first();
        $footer_slug = Str::slug(SiteSectionConst::FOOTER_SECTION);
        $footer = SiteSections::getData($footer_slug)->first();
        $auth_slug = Str::slug(SiteSectionConst::AUTH_SECTION);
        $auth = SiteSections::getData($auth_slug)->first();
        $type =  Str::slug(GlobalConst::USEFUL_LINKS);
        $app_settings = AppSettings::first();
        $policies = SetupPage::orderBy('id')->where('type', $type)->where('status', 1)->get();
        $policy = SetupPage::orderBy('id')->where('type', $type)->where('status',1)->where('slug','privacy-policy')->first();
        return view('frontend.pages.services', compact('page_title', 'service','lang','default','app','footer','policy','policies','auth','app_settings'));
    }
    public function blogView()
    {
        $page_title = setPageTitle(__("Blog"));
        $lang = selectedLang();
        $default = LanguageConst::NOT_REMOVABLE;
        $announcement_slug = Str::slug(SiteSectionConst::ANNOUNCEMENT_SECTION);
        $announcement = SiteSections::getData($announcement_slug)->first();
        $latestAnnouncement = Announcement::active()->orderBy('id','DESC')->limit(4)->get();
        $app_slug = Str::slug(SiteSectionConst::APP_SECTION);
        $app = SiteSections::getData($app_slug)->first();
        $footer_slug = Str::slug(SiteSectionConst::FOOTER_SECTION);
        $footer = SiteSections::getData($footer_slug)->first();
        $type =  Str::slug(GlobalConst::USEFUL_LINKS);
        $auth_slug = Str::slug(SiteSectionConst::AUTH_SECTION);
        $auth = SiteSections::getData($auth_slug)->first();
        $app_settings = AppSettings::first();
        $policies = SetupPage::orderBy('id')->where('type', $type)->where('status', 1)->get();
        $policy = SetupPage::orderBy('id')->where('type', $type)->where('status',1)->where('slug','privacy-policy')->first();
        return view('frontend.pages.blog', compact('page_title','lang','default','announcement','latestAnnouncement','app','footer','policies','policy','auth','app_settings'));
    }
    /**
     * Method for search car
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
    */
    public function searchCar(Request $request)
    {
        $page_title = setPageTitle(__("Cars"));

        $validator = Validator::make($request->all(),[
            'area'   => 'nullable',
            'type'   => 'nullable',
        ]);
        if($validator->fails()) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }
        if($request->area && $request->type){

            $cars = Car::where('car_area_id', $request->area)
            ->where('car_type_id', $request->type)
            ->where('status', true)
            ->where(function ($query) {
                $query->whereHas('bookings', function ($subquery) {
                    $subquery->where('status', '=', 3)->orWhere('status', '=', 1);
                })->orWhereDoesntHave('bookings');
            })
            ->get();
        }
        else{
            $cars = Car::where('status', true)
            ->whereHas('type', function ($query) {
                $query->where('status', true);
            })
            ->whereHas('branch', function ($query) {
                $query->where('status', true);
            })
            ->where(function ($query) {
                $query->whereHas('bookings', function ($subquery) {
                    $subquery->where('status', '=', 3)->orWhere('status', '=', 1);
                })->orWhereDoesntHave('bookings');
            })
            ->get();
        }

        $areas = CarArea::where('status',true)->get();
        $searchArea = $request->area;
        $searchType = $request->type;
        $app_slug = Str::slug(SiteSectionConst::APP_SECTION);
        $app = SiteSections::getData($app_slug)->first();
        $footer_slug = Str::slug(SiteSectionConst::FOOTER_SECTION);
        $footer = SiteSections::getData($footer_slug)->first();
        $type =  Str::slug(GlobalConst::USEFUL_LINKS);
        $auth_slug = Str::slug(SiteSectionConst::AUTH_SECTION);
        $auth = SiteSections::getData($auth_slug)->first();
        $policies = SetupPage::orderBy('id')->where('type', $type)->where('status', 1)->get();
        $default = LanguageConst::NOT_REMOVABLE;
        $policy = SetupPage::orderBy('id')->where('type', $type)->where('status',1)->where('slug','privacy-policy')->first();
        return view('frontend.pages.find-car',compact(
            'page_title',
            'cars',
            'searchArea',
            'searchType',
            'areas',
            'app',
            'footer',
            'policies',
            'policy',
            'auth',
            'default',
        ));
    }

    public function getAreaTypes(Request $request)
    {

        $validator    = Validator::make($request->all(), [
            'area'  => 'required|integer',
        ]);
        if ($validator->fails()) {
            return Response::error($validator->errors()->all());
        }
        $area = CarArea::with(['types' => function ($type) {
            $type->with(['type'=> function($car_type){
                $car_type->where('status', true);
            }]);
        }])->find($request->area);
        if (!$area) return Response::error([__('Area Not Found')], 404);

        return Response::success([__('Data fetch successfully')], ['area' => $area], 200);
    }
    public function blogDetailsView($id, $slug)
    {
        $page_title = setPageTitle(__("Blog Details"));
        $categories = AnnouncementCategory::active()->latest()->get();
        $lang = selectedLang();
        $default = LanguageConst::NOT_REMOVABLE;
        $announcement = Announcement::where('id', $id)->where('slug', $slug)->first();

        $recentPost = Announcement::active()->where('id', "!=", $id)->latest()->limit(3)->get();
        $footer_slug = Str::slug(SiteSectionConst::FOOTER_SECTION);
        $footer = SiteSections::getData($footer_slug)->first();
        $type =  Str::slug(GlobalConst::USEFUL_LINKS);
        $auth_slug = Str::slug(SiteSectionConst::AUTH_SECTION);
        $auth = SiteSections::getData($auth_slug)->first();
        $policies = SetupPage::orderBy('id')->where('type', $type)->where('status', 1)->get();
        $policy = SetupPage::orderBy('id')->where('type', $type)->where('status',1)->where('slug','privacy-policy')->first();
        return view('frontend.pages.blog-details', compact('page_title', 'announcement', 'recentPost', 'categories','lang','default','footer','policies','policy','auth'));
    }
    public function blogByCategoryView($id)
    {
        $categories = AnnouncementCategory::active()->latest()->get();
        $category = AnnouncementCategory::findOrfail($id);
        $lang = selectedLang();
        $page_title = 'Category |'.' '. $category->name;

        $default = LanguageConst::NOT_REMOVABLE;
        $footer_slug = Str::slug(SiteSectionConst::FOOTER_SECTION);
        $footer = SiteSections::getData($footer_slug)->first();
        $type =  Str::slug(GlobalConst::USEFUL_LINKS);
        $auth_slug = Str::slug(SiteSectionConst::AUTH_SECTION);
        $auth = SiteSections::getData($auth_slug)->first();
        $policies = SetupPage::orderBy('id')->where('type', $type)->where('status', 1)->get();
        $policy = SetupPage::orderBy('id')->where('type', $type)->where('status',1)->where('slug','privacy-policy')->first();
        $announcements = Announcement::active()->where('category_id',$category->id)->latest()->paginate(9);
        $recentPost = Announcement::active()->latest()->limit(3)->get();
        $allAnnouncement = Announcement::active()->orderBy('id','DESC')->get();

        $allTags = [];
        foreach ($announcements as $announcement) {
            foreach ($announcement->tags as $tag) {
                if (!in_array($tag, $allTags)) {
                    array_push($allTags, $tag);
                }
            }
        }
        return view('frontend.pages.blog-by-category',compact('page_title','announcements','category','categories','recentPost','allTags','lang','default','footer','policies','policy','auth'));
    }
    public function usefulPage($slug)
    {
        $footer_slug = Str::slug(SiteSectionConst::FOOTER_SECTION);
        $footer = SiteSections::getData($footer_slug)->first();
        $type =  Str::slug(GlobalConst::USEFUL_LINKS);
        $auth_slug = Str::slug(SiteSectionConst::AUTH_SECTION);
        $auth = SiteSections::getData($auth_slug)->first();
        $policies = SetupPage::orderBy('id')->where('type', $type)->where('status', 1)->get();
        $policy = SetupPage::orderBy('id')->where('type', $type)->where('status',1)->where('slug','privacy-policy')->first();
        $lang = selectedLang();
        $default = LanguageConst::NOT_REMOVABLE;
        $page = SetupPage::where('slug', $slug)->where('status', 1)->first();
        if (empty($page)) {
            abort(404);
        }
        return view('frontend.sections.privacy-section', compact('page','footer','lang','default','auth','policy','policies'));
    }

    public function contactMessageStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string',
            'email'     => 'required|email|string',
            'message'   => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validate();

        $validated['created_at'] = now();
        $validated['reply'] = 0;
        try {
            $message = ContactMessage::create($validated);
            $notification_content = [
                'title'         => "Message",
                'message'       => __("A User Has sent a message"),
                'email'         => $validated['email'],
            ];
            AdminNotification::create([
                'admin_id' => 1,
                'type'     => "SIDE_NAV",
                'message'   => $notification_content,
            ]);
        } catch (Exception $e) {
            return back()->withErrors($validator)->withInput()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }

        return back()->with(['success' => [__('Message Send Successfully!')]]);
    }
    public function subscribersStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'     => 'required|email|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validate();

        $validated['created_at'] = now();
        $validated['reply'] = 0;
        try {
            $message = Subscriber::create($validated);
            $notification_content = [
                'title'         => "Subscriber",
                'message'       => __("A User Has subscribed!"),
                'email'         => $validated['email'],
            ];
            AdminNotification::create([
                'admin_id' => 1,
                'type'     => "SIDE_NAV",
                'message'   => $notification_content,
            ]);
        } catch (Exception $e) {
            return back()->withErrors($validator)->withInput()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }

        return back()->with(['success' => [__('Subscribed Successfully!')]]);
    }
    public function languageSwitch(Request $request)
    {
        $code = $request->target;
        $language = Language::where("code", $code)->first();
        if (!$language) {
            return back()->with(['error' => [__('Oops! Language Not Found!')]]);
        }
        Session::put('local', $code);
        Session::put('local_dir', $language->dir);

        return back()->with(['success' => [__('Language Switch to ') . $language->name]]);
    }
    public function redirectLogout(){
        if(auth()->check()){
            Auth::logout();
        }
        return redirect()->route('frontend.index');
    }
}
