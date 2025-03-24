<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Constants\GlobalConst;
use App\Http\Helpers\Response;
use App\Models\Admin\Language;
use App\Constants\LanguageConst;
use App\Models\Admin\Announcement;
use App\Models\Admin\SiteSections;
use App\Constants\SiteSectionConst;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin\AnnouncementCategory;

class SetupSectionsController extends Controller
{
    protected $languages;

    public function __construct()
    {
        $this->languages = Language::get();
    }

    /**
     * Register Sections with their slug
     * @param string $slug
     * @param string $type
     * @return string
     */
    public function section($slug, $type)
    {
        $sections = [
            'banner'    => [
                'view'      => "bannerView",
                'update'    => "bannerUpdate",
            ],
            'about'  => [
                'view'          => "aboutView",
                'update'        => "aboutUpdate",
            ],
            'app'  => [
                'view'          => "appView",
                'update'        => "appUpdate",
            ],
            'contact'  => [
                'view'          => "contactView",
                'update'        => "contactUpdate",
            ],
            'security'  => [
                'view'          => "securityView",
                'update'        => "securityUpdate",
                'itemStore'     => "securityItemStore",
                'itemUpdate'    => "securityItemUpdate",
                'itemDelete'    => "securityItemDelete",
            ],
            'service'  => [
                'view'      => "serviceView",
                'update'    => "serviceUpdate",
                'itemStore'     => "serviceItemStore",
                'itemUpdate'    => "serviceItemUpdate",
                'itemDelete'    => "serviceItemDelete",
            ],
            'statistics'    => [
                'view'          => "statisticsView",
                'update'        =>"statisticsUpdate",
                'itemStore'     => "statisticsItemStore",
                'itemUpdate'    => "statisticsItemUpdate",
                'itemDelete'    => "statisticsItemDelete",
            ],
            'why-choose-us'  => [
                'view'      => "whyChooseUsView",
                'update'    => "whyChooseUsUpdate",
                'itemStore'     => "whyChooseUsItemStore",
                'itemUpdate'    => "whyChooseUsItemUpdate",
                'itemDelete'    => "whyChooseUsItemDelete",
            ],
            'faq'  => [
                'view'      => "faqView",
                'update'    => "faqUpdate",
                'itemStore'     => "faqItemStore",
                'itemUpdate'    => "faqItemUpdate",
                'itemDelete'    => "faqItemDelete",
            ],
            'testimonial'  => [
                'view'          => "testimonialView",
                'update'        => "testimonialUpdate",
                'itemStore'     => "testimonialItemStore",
                'itemUpdate'    => "testimonialItemUpdate",
                'itemDelete'    => "testimonialItemDelete",
            ],
            'category'    => [
                'view'      => "categoryView",
            ],
            'announcement-section'    => [
                'view'      => "announcementView",
                'update'    => "announcementUpdate",
            ],
            'footer'  => [
                'view'          => "footerView",
                'update'        => "footerUpdate",
                'itemStore'     => "footerItemStore",
                'itemUpdate'    => "footerItemUpdate",
                'itemDelete'    => "footerItemDelete",
            ],
            'auth'  => [
                'view'          => "authView",
                'update'        => "authUpdate",
            ],
        ];

        if (!array_key_exists($slug, $sections)) abort(404);
        if (!isset($sections[$slug][$type])) abort(404);
        $next_step = $sections[$slug][$type];
        return $next_step;
    }

    /**
     * Method for getting specific step based on incoming request
     * @param string $slug
     * @return method
     */
    public function sectionView($slug)
    {
        $section = $this->section($slug, 'view');
        return $this->$section($slug);
    }

    /**
     * Method for distribute store method for any section by using slug
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     * @return method
     */
    public function sectionItemStore(Request $request, $slug)
    {
        $section = $this->section($slug, 'itemStore');
        return $this->$section($request, $slug);
    }

    /**
     * Method for distribute update method for any section by using slug
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     * @return method
     */
    public function sectionItemUpdate(Request $request, $slug)
    {
        $section = $this->section($slug, 'itemUpdate');
        return $this->$section($request, $slug);
    }

    /**
     * Method for distribute delete method for any section by using slug
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     * @return method
     */
    public function sectionItemDelete(Request $request, $slug)
    {
        $section = $this->section($slug, 'itemDelete');
        return $this->$section($request, $slug);
    }

    /**
     * Method for distribute update method for any section by using slug
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     * @return method
     */
    public function sectionUpdate(Request $request, $slug)
    {
        $section = $this->section($slug, 'update');
        return $this->$section($request, $slug);
    }

    /**
     * Method for show banner section page
     * @param string $slug
     * @return view
     */
    public function bannerView($slug)
    {
        $page_title = __("Banner Section");
        $section_slug = Str::slug(SiteSectionConst::BANNER_SECTION);
        $data = SiteSections::getData($section_slug)->first();
        $languages = $this->languages;

        return view('admin.sections.setup-sections.banner-section', compact(
            'page_title',
            'data',
            'languages',
            'slug'
        ));
    }

    /**
     * Method for update banner item
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function bannerUpdate(Request $request,$slug)
    {
        $basic_field_name = ['heading' => "required|string|max:100",'sub_heading' => "required|string|max:255",'button_name' => "nullable|string|max:50"];
        $validator = Validator::make($request->all(), [
            'button_link'      => "nullable|string|max:255",
        ]);
        if ($validator->fails()) return back()->withErrors($validator)->withInput();
        $validated = $validator->validate();
        $slug = Str::slug(SiteSectionConst::BANNER_SECTION);
        $section = SiteSections::where("key",$slug)->first();

        if ($section != null) {
            $section_data = json_decode(json_encode($section->value), true);
        } else {
            $section_data = [];
        }
        $section_data['image'] = $section->value->image ?? null;
        if($request->hasFile("image")) {
            $section_data['image']      = $this->imageValidate($request,"image",$section->value->image ?? null);
        }

        $section_data['language']  = $this->contentValidate($request,$basic_field_name);
        $section_data['button_link'] = $validated['button_link'];
        $update_data['value']  = $section_data;
        $update_data['key']    = $slug;

        try{
            SiteSections::updateOrCreate(['key' => $slug],$update_data);
        }catch(Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }

        return back()->with(['success' => [__('Banner updated successfully!')]]);
    }

    /**
     * Method for show ABOUT section page
     * @param string $slug
     * @return view
     */
    public function aboutView($slug)
    {
        $page_title = __("About Section");
        $section_slug = Str::slug(SiteSectionConst::ABOUT_SECTION);
        $data = SiteSections::getData($section_slug)->first();
        $languages = $this->languages;

        return view('admin.sections.setup-sections.about-section', compact(
            'page_title',
            'data',
            'languages',
            'slug'
        ));
    }

    /**
     * Method for update ABOUT section information
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function aboutUpdate(Request $request, $slug)
    {

        $basic_field_name = ['title' => "required|string|max:100", 'section_title' => "required|string|max:100", 'description' => "required|string|max:2000"];

        $slug = Str::slug(SiteSectionConst::ABOUT_SECTION);
        $section = SiteSections::where("key", $slug)->first();
        if ($section != null) {
            $section_data = json_decode(json_encode($section->value), true);
        } else {
            $section_data = [];
        }
        if ($request->hasFile("image")) {
            $section_data['image']      = $this->imageValidate($request, "image", $section->value->image ?? null);
        }

        $section_data['language']  = $this->contentValidate($request, $basic_field_name);
        $update_data['value']  = $section_data;
        $update_data['key']    = $slug;

        try {
            SiteSections::updateOrCreate(['key' => $slug], $update_data);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }

        return back()->with(['success' => [__('About updated successfully!')]]);
    }
     /**
     * Method for show app section page
     * @param string $slug
     * @return view
     */
    public function appView($slug)
    {
        $page_title = __("App Section");
        $section_slug = Str::slug(SiteSectionConst::APP_SECTION);
        $data = SiteSections::getData($section_slug)->first();
        $languages = $this->languages;

        return view('admin.sections.setup-sections.app-section', compact(
            'page_title',
            'data',
            'languages',
            'slug'
        ));
    }

    /**
     * Method for update app section information
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function appUpdate(Request $request, $slug)
    {

        $basic_field_name = ['title' => "required|string|max:100",'description' => "required|string|max:2000"];

        $slug = Str::slug(SiteSectionConst::APP_SECTION);
        $section = SiteSections::where("key", $slug)->first();
        if ($section != null) {
            $section_data = json_decode(json_encode($section->value), true);
        } else {
            $section_data = [];
        }
        if ($request->hasFile("image")) {
            $section_data['image']      = $this->imageValidate($request, "image", $section->value->image ?? null);
        }

        $section_data['language']  = $this->contentValidate($request, $basic_field_name);
        $update_data['value']  = $section_data;
        $update_data['key']    = $slug;

        try {
            SiteSections::updateOrCreate(['key' => $slug], $update_data);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }

        return back()->with(['success' => [__('App updated successfully!')]]);
    }
    /**
     * Method for getting specific step based on incoming request
     * @param string $slug
     * @return method
     */
    public function contactView($slug)
    {
        $page_title =__("Contact Section");
        $section_slug = Str::slug(SiteSectionConst::CONTACT_SECTION);
        $data = SiteSections::getData($section_slug)->first();
        $languages = $this->languages;

        return view('admin.sections.setup-sections.contact-section', compact(
            'page_title',
            'data',
            'languages',
            'slug'
        ));
    }

    /**
     * Method for update contact section information
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function contactUpdate(Request $request, $slug)
    {
        $basic_field_name = ['section_title' => "required|string|max:100",'title' => "required|string|max:500",'description_title' => "required|string|max:100", 'description' => "required|string|max:2000", 'location_title' => "required|string|max:100", 'location' => "required|string|max:100", 'call_title' => "required|string|max:100", 'mobile' => "required|string|max:100",'email_address' => "required|string|email|max:100", 'email_title' => "required|string|max:100"];

        $slug = Str::slug(SiteSectionConst::CONTACT_SECTION);
        $section = SiteSections::where("key", $slug)->first();
        if ($section != null) {
            $section_data = json_decode(json_encode($section->value), true);
        } else {
            $section_data = [];
        }
        $section_data['language']  = $this->contentValidate($request, $basic_field_name);
        $update_data['value']  = $section_data;
        $update_data['key']    = $slug;

        try {
            SiteSections::updateOrCreate(['key' => $slug], $update_data);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }

        return back()->with(['success' => [__('Section updated successfully!')]]);
    }
    /**
     * Method for show service section page
     * @param string $slug
     * @return view
     */
    public function serviceView($slug)
    {
        $page_title = __("Service Section");
        $section_slug = Str::slug(SiteSectionConst::SERVICE_SECTION);
        $data = SiteSections::getData($section_slug)->first();
        $languages = $this->languages;

        return view('admin.sections.setup-sections.service-section', compact(
            'page_title',
            'data',
            'languages',
            'slug'
        ));
    }

    /**
     * Method for update service section information
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function serviceUpdate(Request $request, $slug)
    {

        $basic_field_name = ['title' => "required|string|max:100", 'section_title' => "required|string|max:100",'description' => "required|string|max:2000"];

        $slug = Str::slug(SiteSectionConst::SERVICE_SECTION);
        $section = SiteSections::where("key", $slug)->first();
        if ($section != null) {
            $section_data = json_decode(json_encode($section->value), true);
        } else {
            $section_data = [];
        }

        $section_data['language']  = $this->contentValidate($request, $basic_field_name);
        $update_data['value']  = $section_data;
        $update_data['key']    = $slug;

        try {
            SiteSections::updateOrCreate(['key' => $slug], $update_data);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }

        return back()->with(['success' => [__('Service Section updated successfully!')]]);
    }

    /**
     * Method for store service item
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function serviceItemStore(Request $request, $slug)
    {
        $basic_field_name = [
            'item_title'         => "required|string|max:255",
            'item_description'   => "required|string|max:2000",
            'item_section_icon'   => "required|string|max:100",
        ];

        $language_wise_data = $this->contentValidate($request, $basic_field_name, "serviceItem-add");


        if ($language_wise_data instanceof RedirectResponse) return $language_wise_data;
        $slug = Str::slug(SiteSectionConst::SERVICE_SECTION);
        $section = SiteSections::where("key", $slug)->first();

        if ($section != null) {
            $section_data = json_decode(json_encode($section->value), true);
        } else {
            $section_data = [];
        }
        $unique_id = uniqid();

        $section_data['items'][$unique_id]['language'] = $language_wise_data;
        $section_data['items'][$unique_id]['id'] = $unique_id;

        $update_data['key'] = $slug;
        $update_data['value']  = $section_data;


        try {
            SiteSections::updateOrCreate(['key' => $slug], $update_data);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again')]]);
        }

        return back()->with(['success' => [__('Service item added successfully!')]]);
    }

    /**
     * Method for update service item
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function serviceItemUpdate(Request $request, $slug)
    {
        $request->validate([
            'target'    => "required|string",
        ]);

        $basic_field_name = [
            'item_title_edit'           => "required|string|max:255",
            'item_description_edit'     => "required|string|max:2000",
            'item_section_icon_edit'     => "required|string|max:100",

        ];

        $slug = Str::slug(SiteSectionConst::SERVICE_SECTION);
        $section = SiteSections::getData($slug)->first();
        if (!$section) return back()->with(['error' => [__('Section not found!')]]);
        $section_values = json_decode(json_encode($section->value), true);

        if (!isset($section_values['items'])) return back()->with(['error' => [__('Service item not found!')]]);
        if (!array_key_exists($request->target, $section_values['items'])) return back()->with(['error' => [__('Service item is invalid!')]]);


        $language_wise_data = $this->contentValidate($request, $basic_field_name, "serviceItem-edit");
        if ($language_wise_data instanceof RedirectResponse) return $language_wise_data;

        $language_wise_data = array_map(function ($language) {
            return replace_array_key($language, "_edit");
        }, $language_wise_data);

        $section_values['items'][$request->target]['language'] = $language_wise_data;

        try {
            $section->update([
                'value' => $section_values,
            ]);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again')]]);
        }

        return back()->with(['success' => [__('Service Item updated successfully!')]]);
    }


      /**
     * Method for delete service item
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function serviceItemDelete(Request $request, $slug)
    {
        $request->validate([
            'target'    => 'required|string',
        ]);
        $slug = Str::slug(SiteSectionConst::SERVICE_SECTION);
        $section = SiteSections::getData($slug)->first();
        if (!$section) return back()->with(['error' => [__('Section not found!')]]);
        $section_values = json_decode(json_encode($section->value), true);
        if (!isset($section_values['items'])) return back()->with(['error' => [__('Service item not found!')]]);
        if (!array_key_exists($request->target, $section_values['items'])) return back()->with(['error' => [__('Service item is invalid!')]]);

        try {
            unset($section_values['items'][$request->target]);
            $section->update([
                'value'     => $section_values,
            ]);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }

        return back()->with(['success' => [__('Service item deleted successfully!')]]);
    }
    /**
     * Method for show security section page
     * @param string $slug
     * @return view
     */
    public function securityView($slug)
    {
        $page_title = __("Security Section");
        $section_slug = Str::slug(SiteSectionConst::SECURITY_SECTION);
        $data = SiteSections::getData($section_slug)->first();
        $languages = $this->languages;

        return view('admin.sections.setup-sections.security-section', compact(
            'page_title',
            'data',
            'languages',
            'slug'
        ));
    }

    /**
     * Method for update security section information
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function securityUpdate(Request $request, $slug)
    {

        $basic_field_name = ['title' => "required|string|max:100", 'section_title' => "required|string|max:100"];

        $slug = Str::slug(SiteSectionConst::SECURITY_SECTION);
        $section = SiteSections::where("key", $slug)->first();
        if ($section != null) {
            $section_data = json_decode(json_encode($section->value), true);
        } else {
            $section_data = [];
        }

        $section_data['language']  = $this->contentValidate($request, $basic_field_name);
        $update_data['value']  = $section_data;
        $update_data['key']    = $slug;

        try {
            SiteSections::updateOrCreate(['key' => $slug], $update_data);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }

        return back()->with(['success' => [__('Security Section updated successfully!')]]);
    }

    /**
     * Method for store security item
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function securityItemStore(Request $request, $slug)
    {
        $basic_field_name = [
            'item_title'         => "required|string|max:255",
            'item_description'   => "required|string|max:2000",
            'item_section_icon'   => "required|string|max:100",
        ];

        $language_wise_data = $this->contentValidate($request, $basic_field_name, "securityItem-add");


        if ($language_wise_data instanceof RedirectResponse) return $language_wise_data;
        $slug = Str::slug(SiteSectionConst::SECURITY_SECTION);
        $section = SiteSections::where("key", $slug)->first();

        if ($section != null) {
            $section_data = json_decode(json_encode($section->value), true);
        } else {
            $section_data = [];
        }
        $unique_id = uniqid();

        $section_data['items'][$unique_id]['language'] = $language_wise_data;
        $section_data['items'][$unique_id]['id'] = $unique_id;

        $update_data['key'] = $slug;
        $update_data['value']  = $section_data;


        try {
            SiteSections::updateOrCreate(['key' => $slug], $update_data);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again')]]);
        }

        return back()->with(['success' => [__('Security item added successfully!')]]);
    }

    /**
     * Method for update security item
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function securityItemUpdate(Request $request, $slug)
    {
        $request->validate([
            'target'    => "required|string",
        ]);

        $basic_field_name = [
            'item_title_edit'           => "required|string|max:255",
            'item_description_edit'     => "required|string|max:2000",
            'item_section_icon_edit'     => "required|string|max:100",

        ];

        $slug = Str::slug(SiteSectionConst::SECURITY_SECTION);
        $section = SiteSections::getData($slug)->first();
        if (!$section) return back()->with(['error' => [__('Section not found!')]]);
        $section_values = json_decode(json_encode($section->value), true);

        if (!isset($section_values['items'])) return back()->with(['error' => [__('Security item not found!')]]);
        if (!array_key_exists($request->target, $section_values['items'])) return back()->with(['error' => [__('Security item is invalid!')]]);


        $language_wise_data = $this->contentValidate($request, $basic_field_name, "securityItem-edit");
        if ($language_wise_data instanceof RedirectResponse) return $language_wise_data;

        $language_wise_data = array_map(function ($language) {
            return replace_array_key($language, "_edit");
        }, $language_wise_data);

        $section_values['items'][$request->target]['language'] = $language_wise_data;

        try {
            $section->update([
                'value' => $section_values,
            ]);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again')]]);
        }

        return back()->with(['success' => [__('Security Item updated successfully!')]]);
    }


      /**
     * Method for delete security item
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function securityItemDelete(Request $request, $slug)
    {
        $request->validate([
            'target'    => 'required|string',
        ]);
        $slug = Str::slug(SiteSectionConst::SECURITY_SECTION);
        $section = SiteSections::getData($slug)->first();
        if (!$section) return back()->with(['error' => [__('Section not found!')]]);
        $section_values = json_decode(json_encode($section->value), true);
        if (!isset($section_values['items'])) return back()->with(['error' => [__('Security item not found!')]]);
        if (!array_key_exists($request->target, $section_values['items'])) return back()->with(['error' => [__('Security item is invalid!')]]);

        try {
            unset($section_values['items'][$request->target]);
            $section->update([
                'value'     => $section_values,
            ]);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }

        return back()->with(['success' => [__('Security item deleted successfully!')]]);
    }
     /**
     * Method for show statistics section page
     * @param string $slug
     * @return view
     */
    public function statisticsView($slug)
    {
        $page_title = __("Statistics Section");
        $section_slug = Str::slug(SiteSectionConst::STATISTICS_SECTION);
        $data = SiteSections::getData($section_slug)->first();
        $languages = $this->languages;

        return view('admin.sections.setup-sections.statistics-section', compact(
            'page_title',
            'data',
            'languages',
            'slug'
        ));
    }
     /**
     * Method for update statistics section information
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function statisticsUpdate(Request $request, $slug)
    {

        $slug = Str::slug(SiteSectionConst::STATISTICS_SECTION);
        $section = SiteSections::where("key", $slug)->first();
        if ($section != null) {
            $section_data = json_decode(json_encode($section->value), true);
        } else {
            $section_data = [];
        }
        if ($request->hasFile("image")) {
            $section_data['image']      = $this->imageValidate($request, "image", $section->value->image ?? null);
        }

        $update_data['value']  = $section_data;
        $update_data['key']    = $slug;

        try {
            SiteSections::updateOrCreate(['key' => $slug], $update_data);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }

        return back()->with(['success' => [__('Statistics updated successfully!')]]);
    }
    /**
     * Method for store statistics item
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function statisticsItemStore(Request $request, $slug)
    {

        $basic_field_name = [
            'heading'          => "required|numeric",
            'sub_heading'      => "required|string|max:100",
            'section_icon'     => "required|string|max:100",
        ];


        $language_wise_data = $this->contentValidate($request, $basic_field_name, "statistics-add");


        if ($language_wise_data instanceof RedirectResponse) return $language_wise_data;
        $slug = Str::slug(SiteSectionConst::STATISTICS_SECTION);
        $section = SiteSections::where("key", $slug)->first();

        if ($section != null) {
            $section_data = json_decode(json_encode($section->value), true);
        } else {
            $section_data = [];
        }
        $unique_id = uniqid();

        $section_data['items'][$unique_id]['language'] = $language_wise_data;
        $section_data['items'][$unique_id]['id'] = $unique_id;


        $update_data['key'] = $slug;
        $update_data['value']   = $section_data;


        try {
            SiteSections::updateOrCreate(['key' => $slug], $update_data);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again')]]);
        }

        return back()->with(['success' => [__('Statistics item added successfully!')]]);
    }

    /**
     * Method for update statistics item
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function statisticsItemUpdate(Request $request, $slug)
    {

        $request->validate([
            'target'    => "required|string",
        ]);

        $basic_field_name = [
            'heading_edit'       => "required|numeric",
            'sub_heading_edit'   => "required|string|max:100",
            'section_icon_edit'  => "required|string|max:100",
        ];

        $slug = Str::slug(SiteSectionConst::STATISTICS_SECTION);
        $section = SiteSections::getData($slug)->first();

        if (!$section) return back()->with(['error' => [__('Statistics not found!')]]);
        $section_values = json_decode(json_encode($section->value), true);
        if (!isset($section_values['items'])) return back()->with(['error' => [__('Statistics item not found!')]]);
        if (!array_key_exists($request->target, $section_values['items'])) return back()->with(['error' => [__('Statistics item is invalid!')]]);


        $language_wise_data = $this->contentValidate($request, $basic_field_name, "statistics-edit");
        if ($language_wise_data instanceof RedirectResponse) return $language_wise_data;

        $language_wise_data = array_map(function ($language) {
            return replace_array_key($language, "_edit");
        }, $language_wise_data);

        $section_values['items'][$request->target]['language'] = $language_wise_data;

        try {
            $section->update([
                'value' => $section_values,
            ]);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again')]]);
        }

        return back()->with(['success' => [__('Information updated successfully!')]]);
    }

    /**
     * Method for delete statistics item
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function statisticsItemDelete(Request $request, $slug)
    {
        $request->validate([
            'target'    => 'required|string',
        ]);
        $slug = Str::slug(SiteSectionConst::STATISTICS_SECTION);
        $section = SiteSections::getData($slug)->first();
        if (!$section) return back()->with(['error' => [__('Statistics not found!')]]);
        $section_values = json_decode(json_encode($section->value), true);
        if (!isset($section_values['items'])) return back()->with(['error' => [__('Statistics item not found!')]]);
        if (!array_key_exists($request->target, $section_values['items'])) return back()->with(['error' => [__('Statistics item is invalid!')]]);

        try {
            unset($section_values['items'][$request->target]);
            $section->update([
                'value'     => $section_values,
            ]);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }

        return back()->with(['success' => [__('Statistics item deleted successfully!')]]);
    }


    /**
     * Method for show why choose us section page
     * @param string $slug
     * @return view
     */
    public function whyChooseUsView($slug)
    {
        $page_title = __("Why Choose Us Section");
        $section_slug = Str::slug(SiteSectionConst::WHY_CHOOSE_US_SECTION);
        $data = SiteSections::getData($section_slug)->first();
        $languages = $this->languages;

        return view('admin.sections.setup-sections.why-choose-us-section', compact(
            'page_title',
            'data',
            'languages',
            'slug'
        ));
    }

    /**
     * Method for update whyChooseUs section information
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function whyChooseUsUpdate(Request $request, $slug)
    {

        $basic_field_name = ['title' => "required|string|max:100", 'section_title' => "required|string|max:100", 'description'   => "required|string|max:2000"];

        $slug = Str::slug(SiteSectionConst::WHY_CHOOSE_US_SECTION);
        $section = SiteSections::where("key", $slug)->first();
        if ($section != null) {
            $section_data = json_decode(json_encode($section->value), true);
        } else {
            $section_data = [];
        }

        $section_data['language']  = $this->contentValidate($request, $basic_field_name);
        $update_data['value']  = $section_data;
        $update_data['key']    = $slug;

        try {
            SiteSections::updateOrCreate(['key' => $slug], $update_data);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }

        return back()->with(['success' => [__('Why Choose Us Section updated successfully!')]]);
    }

    /**
     * Method for store whyChooseUs item
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function whyChooseUsItemStore(Request $request, $slug)
    {
        $basic_field_name = [
            'item_title'         => "required|string|max:255",
            'item_description'   => "required|string|max:2000",
        ];

        $language_wise_data = $this->contentValidate($request, $basic_field_name, "whyChooseItem-add");


        if ($language_wise_data instanceof RedirectResponse) return $language_wise_data;
        $slug = Str::slug(SiteSectionConst::WHY_CHOOSE_US_SECTION);
        $section = SiteSections::where("key", $slug)->first();

        if ($section != null) {
            $section_data = json_decode(json_encode($section->value), true);
        } else {
            $section_data = [];
        }
        $unique_id = uniqid();

        $section_data['items'][$unique_id]['language'] = $language_wise_data;
        $section_data['items'][$unique_id]['id'] = $unique_id;

        $update_data['key'] = $slug;
        $update_data['value']   = $section_data;


        try {
            SiteSections::updateOrCreate(['key' => $slug], $update_data);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again')]]);
        }

        return back()->with(['success' => [__('Why Choose Us item added successfully!')]]);
    }

    /**
     * Method for update whyChooseUs item
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function whyChooseUsItemUpdate(Request $request, $slug)
    {
        $request->validate([
            'target'    => "required|string",
        ]);

        $basic_field_name = [
            'item_title_edit'           => "required|string|max:255",
            'item_description_edit'     => "required|string|max:2000",

        ];

        $slug = Str::slug(SiteSectionConst::WHY_CHOOSE_US_SECTION);
        $section = SiteSections::getData($slug)->first();
        if (!$section) return back()->with(['error' => [__('Section not found!')]]);
        $section_values = json_decode(json_encode($section->value), true);

        if (!isset($section_values['items'])) return back()->with(['error' => [__('Why Choose Us item not found!')]]);
        if (!array_key_exists($request->target, $section_values['items'])) return back()->with(['error' => [__('Why Choose Us item is invalid!')]]);


        $language_wise_data = $this->contentValidate($request, $basic_field_name, "whyChooseUsItem-edit");
        if ($language_wise_data instanceof RedirectResponse) return $language_wise_data;

        $language_wise_data = array_map(function ($language) {
            return replace_array_key($language, "_edit");
        }, $language_wise_data);

        $section_values['items'][$request->target]['language'] = $language_wise_data;

        try {
            $section->update([
                'value' => $section_values,
            ]);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again')]]);
        }

        return back()->with(['success' => [__('Why Choose Us Item updated successfully!')]]);
    }


      /**
     * Method for delete whyChooseUs item
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function whyChooseUsItemDelete(Request $request, $slug)
    {
        $request->validate([
            'target'    => 'required|string',
        ]);
        $slug = Str::slug(SiteSectionConst::WHY_CHOOSE_US_SECTION);
        $section = SiteSections::getData($slug)->first();
        if (!$section) return back()->with(['error' => [__('Section not found!')]]);
        $section_values = json_decode(json_encode($section->value), true);
        if (!isset($section_values['items'])) return back()->with(['error' => [__('Why Choose Us item not found!')]]);
        if (!array_key_exists($request->target, $section_values['items'])) return back()->with(['error' => [__('Why Choose Us item is invalid!')]]);

        try {
            unset($section_values['items'][$request->target]);
            $section->update([
                'value'     => $section_values,
            ]);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }

        return back()->with(['success' => [__('Why Choose Us item deleted successfully!')]]);
    }
     /**
     * Method for show faq section page
     * @param string $slug
     * @return view
     */
    public function faqView($slug)
    {
        $page_title = __("FAQ Section");
        $section_slug = Str::slug(SiteSectionConst::FAQ_SECTION);
        $data = SiteSections::getData($section_slug)->first();
        $languages = $this->languages;

        return view('admin.sections.setup-sections.faq-section', compact(
            'page_title',
            'data',
            'languages',
            'slug'
        ));
    }

    /**
     * Method for update faq section information
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function faqUpdate(Request $request, $slug)
    {
        $basic_field_name = ['title' => "required|string|max:100", 'section_title' => "required|string|max:100"];

        $slug = Str::slug(SiteSectionConst::FAQ_SECTION);
        $section = SiteSections::where("key", $slug)->first();
        if ($section != null) {
            $section_data = json_decode(json_encode($section->value), true);
        } else {
            $section_data = [];
        }
        if ($request->hasFile("image")) {
            $section_data['image']      = $this->imageValidate($request, "image", $section->value->image ?? null);
        }

        $section_data['language']  = $this->contentValidate($request, $basic_field_name);
        $update_data['value']  = $section_data;
        $update_data['key']    = $slug;

        try {
            SiteSections::updateOrCreate(['key' => $slug], $update_data);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }

        return back()->with(['success' => [__('FAQ updated successfully!')]]);
    }

     /**
     * Method for store faq item
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function faqItemStore(Request $request, $slug)
    {
        $basic_field_name = [
            'item_title'         => "required|string|max:255",
            'item_description'   => "required|string|max:2000",
        ];

        $language_wise_data = $this->contentValidate($request, $basic_field_name, "faq-add");


        if ($language_wise_data instanceof RedirectResponse) return $language_wise_data;
        $slug = Str::slug(SiteSectionConst::FAQ_SECTION);
        $section = SiteSections::where("key", $slug)->first();

        if ($section != null) {
            $section_data = json_decode(json_encode($section->value), true);
        } else {
            $section_data = [];
        }
        $unique_id = uniqid();

        $section_data['items'][$unique_id]['language'] = $language_wise_data;
        $section_data['items'][$unique_id]['id'] = $unique_id;

        $update_data['key'] = $slug;
        $update_data['value']   = $section_data;


        try {
            SiteSections::updateOrCreate(['key' => $slug], $update_data);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again')]]);
        }

        return back()->with(['success' => [__('FAQ item added successfully!')]]);
    }

    /**
     * Method for update faq item
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function faqItemUpdate(Request $request, $slug)
    {
        $request->validate([
            'target'    => "required|string",
        ]);

        $basic_field_name = [
            'item_title_edit'           => "required|string|max:255",
            'item_description_edit'     => "required|string|max:2000",
        ];

        $slug = Str::slug(SiteSectionConst::FAQ_SECTION);
        $section = SiteSections::getData($slug)->first();
        if (!$section) return back()->with(['error' => [__('Section not found!')]]);
        $section_values = json_decode(json_encode($section->value), true);

        if (!isset($section_values['items'])) return back()->with(['error' => [__('FAQ item not found!')]]);
        if (!array_key_exists($request->target, $section_values['items'])) return back()->with(['error' => [__('FAQ item is invalid!')]]);


        $language_wise_data = $this->contentValidate($request, $basic_field_name, "faq-edit");
        if ($language_wise_data instanceof RedirectResponse) return $language_wise_data;

        $language_wise_data = array_map(function ($language) {
            return replace_array_key($language, "_edit");
        }, $language_wise_data);

        $section_values['items'][$request->target]['language'] = $language_wise_data;

        try {
            $section->update([
                'value' => $section_values,
            ]);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again')]]);
        }

        return back()->with(['success' => [__('Information updated successfully!')]]);
    }

    /**
     * Method for delete faq item
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function faqItemDelete(Request $request, $slug)
    {
        $request->validate([
            'target'    => 'required|string',
        ]);
        $slug = Str::slug(SiteSectionConst::FAQ_SECTION);
        $section = SiteSections::getData($slug)->first();
        if (!$section) return back()->with(['error' => [__('Section not found!')]]);
        $section_values = json_decode(json_encode($section->value), true);
        if (!isset($section_values['items'])) return back()->with(['error' => [__('FAQ item not found!')]]);
        if (!array_key_exists($request->target, $section_values['items'])) return back()->with(['error' => [__('FAQ item is invalid!')]]);

        try {
            unset($section_values['items'][$request->target]);
            $section->update([
                'value'     => $section_values,
            ]);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }

        return back()->with(['success' => [__('FAQ item deleted successfully!')]]);
    }
    /**
     * Method for show testimonial section page
     * @param string $slug
     * @return view
     */
    public function testimonialView($slug)
    {
        $page_title = __("Testimonial Section");
        $section_slug = Str::slug(SiteSectionConst::TESTIMONIAL_SECTION);
        $data = SiteSections::getData($section_slug)->first();
        $languages = $this->languages;

        return view('admin.sections.setup-sections.testimonial-section', compact(
            'page_title',
            'data',
            'languages',
            'slug'
        ));
    }

    /**
     * Method for update testimonial section information
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function testimonialUpdate(Request $request, $slug)
    {
        $basic_field_name = ['title' => "required|string|max:100", 'section_title' => "required|string|max:100"];

        $slug = Str::slug(SiteSectionConst::TESTIMONIAL_SECTION);
        $section = SiteSections::where("key", $slug)->first();
        if ($section != null) {
            $section_data = json_decode(json_encode($section->value), true);
        } else {
            $section_data = [];
        }
        $section_data['language']  = $this->contentValidate($request, $basic_field_name);
        $update_data['value']  = $section_data;
        $update_data['key']    = $slug;

        try {
            SiteSections::updateOrCreate(['key' => $slug], $update_data);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }

        return back()->with(['success' => [__('Testimonials updated successfully!')]]);
    }

    /**
     * Method for store testimonial item
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function testimonialItemStore(Request $request, $slug)
    {
        $basic_field_name = [
            'item_description'   => "required|string|max:2000",
            'item_name'          => "required|string|max:255",
        ];
        $language_wise_data = $this->contentValidate($request, $basic_field_name, "testimonial-add");


        if ($language_wise_data instanceof RedirectResponse) return $language_wise_data;
        $slug = Str::slug(SiteSectionConst::TESTIMONIAL_SECTION);
        $section = SiteSections::where("key", $slug)->first();

        if ($section != null) {
            $section_data = json_decode(json_encode($section->value), true);
        } else {
            $section_data = [];
        }
        $unique_id = uniqid();

        $section_data['items'][$unique_id]['language'] = $language_wise_data;
        $section_data['items'][$unique_id]['id'] = $unique_id;
        $section_data['items'][$unique_id]['created_at'] = now();
        $section_data['items'][$unique_id]['image'] = "";
        if ($request->hasFile("image")) {
            $section_data['items'][$unique_id]['image'] = $this->imageValidate($request, "image", $section->value->items->image ?? null);
        }
        $update_data['key'] = $slug;
        $update_data['value']   = $section_data;

        try {
            SiteSections::updateOrCreate(['key' => $slug], $update_data);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again')]]);
        }

        return back()->with(['success' => [__('Testimonial item added successfully!')]]);
    }

    /**
     * Method for update testimonial item
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function testimonialItemUpdate(Request $request, $slug)
    {

        $request->validate([
            'target'    => "required|string",
        ]);

        $basic_field_name = [
            'item_name_edit'          => "required|string|max:255",
            'item_description_edit'   => "required|string|max:2000",
        ];

        $slug = Str::slug(SiteSectionConst::TESTIMONIAL_SECTION);
        $section = SiteSections::getData($slug)->first();
        if (!$section) return back()->with(['error' => [__('Section not found!')]]);
        $section_values = json_decode(json_encode($section->value), true);

        if (!isset($section_values['items'])) return back()->with(['error' => [__('Testimonial item not found!')]]);
        if (!array_key_exists($request->target, $section_values['items'])) return back()->with(['error' => [__('Testimonial item is invalid!')]]);

        $request->merge(['old_image' => $section_values['items'][$request->target]['image'] ?? null]);

        $language_wise_data = $this->contentValidate($request, $basic_field_name, "testimonial-edit");
        if ($language_wise_data instanceof RedirectResponse) return $language_wise_data;

        $language_wise_data = array_map(function ($language) {
            return replace_array_key($language, "_edit");
        }, $language_wise_data);

        $section_values['items'][$request->target]['language'] = $language_wise_data;
        $section_values['items'][$request->target]['created_at'] = now();

        if ($request->hasFile("image")) {
            $section_values['items'][$request->target]['image']    = $this->imageValidate($request, "image", $section_values['items'][$request->target]['image'] ?? null);
        }

        try {
            $section->update([
                'value' => $section_values,
            ]);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again')]]);
        }

        return back()->with(['success' => [__('Information updated successfully!')]]);
    }

    /**
     * Method for delete testimonial item
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function testimonialItemDelete(Request $request, $slug)
    {
        $request->validate([
            'target'    => 'required|string',
        ]);
        $slug = Str::slug(SiteSectionConst::TESTIMONIAL_SECTION);
        $section = SiteSections::getData($slug)->first();
        if (!$section) return back()->with(['error' => [__('Section not found!')]]);
        $section_values = json_decode(json_encode($section->value), true);
        if (!isset($section_values['items'])) return back()->with(['error' => [__('Testimonial item not found!')]]);
        if (!array_key_exists($request->target, $section_values['items'])) return back()->with(['error' => [__('Testimonial item is invalid!')]]);

        try {
            $image_link = get_files_path('site-section') . '/' . $section_values['items'][$request->target]['image'];
            unset($section_values['items'][$request->target]);
            delete_file($image_link);
            $section->update([
                'value'     => $section_values,
            ]);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }

        return back()->with(['success' => [__('Testimonial item deleted successfully!')]]);
    }
    /**
     * Method for show category section page
     * @param string $slug
     * @return view
     */
    public function categoryView()
    {
        $page_title = __("Announcement Category");
        $allCategory = AnnouncementCategory::orderByDesc('id')->paginate(10);
        return view('admin.sections.setup-sections.announcement-category', compact(
            'page_title',
            'allCategory',
        ));
    }
    public function storeCategory(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:200|unique:announcement_categories,name',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('modal', 'category-add');
        }
        $validated = $validator->validate();
        $slugData = Str::slug($request->name);
        $makeUnique = AnnouncementCategory::where('slug',  $slugData)->first();
        if ($makeUnique) {
            return back()->with(['error' => [$request->name . ' ' . __('Category Already Exists!')]]);
        }
        $admin = Auth::user();

        $validated['admin_id']      = $admin->id;
        $validated['name']          = $request->name;
        $validated['slug']          = $slugData;
        try {
            AnnouncementCategory::create($validated);
            return back()->with(['success' => [__('Category Saved Successfully!')]]);
        } catch (Exception $e) {
            return back()->withErrors($validator)->withInput()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }
    }
    public function categoryUpdate(Request $request)
    {
        $target = $request->target;
        $category = AnnouncementCategory::where('id', $target)->first();
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:200',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('modal', 'edit-category');
        }
        $validated = $validator->validate();

        $slugData = Str::slug($request->name);
        $makeUnique = AnnouncementCategory::where('id', "!=", $category->id)->where('slug',  $slugData)->first();
        if ($makeUnique) {
            return back()->with(['error' => [$request->name . ' ' . __('Category Already Exists!')]]);
        }
        $admin = Auth::user();
        $validated['admin_id']      = $admin->id;
        $validated['name']          = $request->name;
        $validated['slug']          = $slugData;

        try {
            $category->fill($validated)->save();
            return back()->with(['success' => [__('Category Updated Successfully!')]]);
        } catch (Exception $e) {
            return back()->withErrors($validator)->withInput()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }
    }
    public function categoryStatusUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status'                    => 'required|boolean',
            'data_target'               => 'required|string',
        ]);
        if ($validator->stopOnFirstFailure()->fails()) {
            $error = ['error' => $validator->errors()];
            return Response::error($error, null, 400);
        }
        $validated = $validator->safe()->all();
        $category_id = $validated['data_target'];

        $category = AnnouncementCategory::where('id', $category_id)->first();
        if (!$category) {
            $error = ['error' => [__('Category record not found in our system.')]];
            return Response::error($error, null, 404);
        }

        try {
            $category->update([
                'status' => ($validated['status'] == true) ? false : true,
            ]);
        } catch (Exception $e) {
            $error = ['error' => [__('Something went wrong! Please try again.')]];
            return Response::error($error, null, 500);
        }

        $success = ['success' => [__('Category status updated successfully!')]];
        return Response::success($success, null, 200);
    }
    public function categoryDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'target'        => 'required|string|exists:announcement_categories,id',
        ]);
        $validated = $validator->validate();
        $category = AnnouncementCategory::where("id", $validated['target'])->first();

        try {
            $category->delete();
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }

        return back()->with(['success' => [__('Category deleted successfully!')]]);
    }
    public function categorySearch(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'text'  => 'required|string',
        ]);

        if ($validator->fails()) {
            $error = ['error' => $validator->errors()];
            return Response::error($error, null, 400);
        }

        $validated = $validator->validate();

        $allCategory = AnnouncementCategory::search($validated['text'])->select()->limit(10)->get();
        return view('admin.components.search.category-search', compact(
            'allCategory',
        ));
    }
    public function announcementView($slug)
    {
        $page_title = __("Announcement Section");
        $section_slug = Str::slug(SiteSectionConst::ANNOUNCEMENT_SECTION);
        $data = SiteSections::getData($section_slug)->first();
        $languages = $this->languages;
        $categories = AnnouncementCategory::where('status', 1)->latest()->get();
        $announcements = Announcement::latest()->paginate(10);

        return view('admin.sections.setup-sections.announcement-section', compact(
            'page_title',
            'data',
            'languages',
            'slug',
            'categories',
            'announcements'
        ));
    }
    public function announcementUpdate(Request $request, $slug)
    {
        $basic_field_name = ['section_title' => "required|string|max:100", 'title' => "required|string|max:100"];

        $slug = Str::slug(SiteSectionConst::ANNOUNCEMENT_SECTION);
        $section = SiteSections::where("key", $slug)->first();
        $data['language']  = $this->contentValidate($request, $basic_field_name);
        $update_data['value']  = $data;
        $update_data['key']    = $slug;

        try {
            SiteSections::updateOrCreate(['key' => $slug], $update_data);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }

        return back()->with(['success' => [__('Section updated successfully!')]]);
    }
    public function announcementItemStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|integer',
            'en_name'     => "required|string",
            'en_details'  => "required|string",
            'tags'        => 'nullable|array',
            'tags.*'      => 'nullable|string|max:30',
            'image'       => 'required|image|mimes:png,jpg,jpeg,svg,webp',
        ]);


        $name_filed = [
            'name'     => "required|string",
        ];
        $details_filed = [
            'details'     => "required|string",
        ];

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('modal', 'announcement-add');
        }
        $validated = $validator->validate();

        // Multiple language data set
        $language_wise_name = $this->contentValidate($request, $name_filed);
        $language_wise_details = $this->contentValidate($request, $details_filed);

        $name_data['language'] = $language_wise_name;
        $details_data['language'] = $language_wise_details;

        $validated['category_id']  = $request->category_id;
        $validated['admin_id']     = Auth::user()->id;
        $validated['name']         = $name_data;
        $validated['details']      = $details_data;
        $validated['slug']         = Str::slug($name_data['language']['en']['name']);
        $validated['tag']          = $request->tags;
        $validated['created_at']   = now();


        // Check Image File is Available or not
        if ($request->hasFile('image')) {
            $image = get_files_from_fileholder($request, 'image');
            $upload = upload_files_from_path_dynamic($image, 'announcement');
            $validated['image'] = $upload;
        }

        try {
            Announcement::create($validated);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again')]]);
        }

        return back()->with(['success' => [__('Announcement item added successfully!')]]);
    }
    public function announcementEdit($id)
    {
        $page_title = __("Announcement Edit");
        $languages = $this->languages;
        $data = Announcement::findOrFail($id);
        $categories = AnnouncementCategory::where('status', 1)->latest()->get();

        return view('admin.components.modals.site-section.edit-announcement-item', compact(
            'page_title',
            'languages',
            'data',
            'categories',
        ));
    }
    public function announcementItemUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id'   => 'required|integer',
            'en_name'       => "required|string",
            'en_details'    => "required|string",
            'tags'          => 'nullable|array',
            'tags.*'        => 'nullable|string|max:30',
            'image'         => 'nullable|image|mimes:png,jpg,jpeg,svg,webp',
            'target'        => 'required|integer',
        ]);


        $name_filed = [
            'name'     => "required|string",
        ];
        $details_filed = [
            'details'     => "required|string",
        ];

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('modal', 'announcement-edit');
        }
        $validated = $validator->validate();
        $announcement = Announcement::findOrFail($validated['target']);

        // Multiple language data set
        $language_wise_name = $this->contentValidate($request, $name_filed);
        $language_wise_details = $this->contentValidate($request, $details_filed);

        $name_data['language'] = $language_wise_name;
        $details_data['language'] = $language_wise_details;

        $validated['category_id']   = $request->category_id;
        $validated['admin_id']      = Auth::user()->id;
        $validated['name']          = $name_data;
        $validated['details']       = $details_data;
        $validated['slug']          = Str::slug($name_data['language']['en']['name']);
        $validated['tag']           = $request->input('tags', []);
        $validated['created_at']      = now();
        if (!is_array($validated['tag'])) {
            $validated['tag'] = [];
        }
        // Check Image File is Available or not
        if ($request->hasFile('image')) {
            $image = get_files_from_fileholder($request, 'image');
            $upload = upload_files_from_path_dynamic($image, 'announcement', $announcement->image);
            $validated['image'] = $upload;
        }

        try {
            $announcement->update($validated);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again')]]);
        }
        return redirect()->back()->with(['success' => [__('Announcement item updated successfully!')]]);
    }

    public function announcementItemDelete(Request $request)
    {
        $request->validate([
            'target'    => 'required|string',
        ]);

        $announcement = Announcement::findOrFail($request->target);

        try {
            $image_link = get_files_path('announcement') . '/' . $announcement->image;
            delete_file($image_link);
            $announcement->delete();
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }

        return back()->with(['success' => [__('Announcement delete successfully!')]]);
    }
    public function announcementStatusUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status'                    => 'required|boolean',
            'data_target'               => 'required|string',
        ]);
        if ($validator->stopOnFirstFailure()->fails()) {
            $error = ['error' => $validator->errors()];
            return Response::error($error, null, 400);
        }
        $validated = $validator->safe()->all();
        $announcement_id = $validated['data_target'];

        $announcement = Announcement::where('id', $announcement_id)->first();
        if (!$announcement) {
            $error = ['error' => [__('Announcement record not found in our system.')]];
            return Response::error($error, null, 404);
        }

        try {
            $announcement->update([
                'status' => ($validated['status'] == true) ? false : true,
            ]);
        } catch (Exception $e) {
            $error = ['error' => [__('Something went wrong! Please try again.')]];
            return Response::error($error, null, 500);
        }

        $success = ['success' => [__('Announcement status updated successfully!')]];
        return Response::success($success, null, 200);
    }
    /**
     * Method for show Footer section page
     * @param string $slug
     * @return view
     */
    public function footerView($slug)
    {
        $page_title = __("Footer Section");
        $section_slug = Str::slug(SiteSectionConst::FOOTER_SECTION);
        $data = SiteSections::getData($section_slug)->first();

        $languages = $this->languages;
        return view('admin.sections.setup-sections.footer-section', compact(
            'page_title',
            'data',
            'languages',
            'slug'
        ));
    }
    /**
     * Method for update Footer section information
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function footerUpdate(Request $request, $slug)
    {

        $basic_field_name = ['footer_text' => "required|string|max:100", 'short_description' => "required|string|max:500",'subscribe_title' => "required|string|max:500",'icon_title' => "required|string|max:100"];

        $slug = Str::slug(SiteSectionConst::FOOTER_SECTION);
        $section = SiteSections::where("key", $slug)->first();
        if ($section != null) {
            $section_data = json_decode(json_encode($section->value), true);
        } else {
            $section_data = [];
        }
        if ($request->hasFile("image")) {
            $section_data['image']      = $this->imageValidate($request, "image", $section->value->image ?? null);
        }

        $section_data['language']  = $this->contentValidate($request, $basic_field_name);
        $update_data['value']  = $section_data;
        $update_data['key']    = $slug;

        try {
            SiteSections::updateOrCreate(['key' => $slug], $update_data);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }

        return back()->with(['success' => [__('Footer updated successfully!')]]);
    }

    /**
     * Method for store footer item
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function footerItemStore(Request $request, $slug)
    {
        $validator = Validator::make($request->all(), [
            'item_name'        => 'required|string|max:100',
            'item_link'        => 'required|string|url|max:100',
            'item_social_icon' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('modal', 'social-add');
        }
        $validated = $validator->validate();

        $slug = Str::slug(SiteSectionConst::FOOTER_SECTION);
        $section = SiteSections::where("key", $slug)->first();

        if ($section != null) {
            $section_data = json_decode(json_encode($section->value), true);
        } else {
            $section_data = [];
        }
        $unique_id = uniqid();
        $section_data['items'][$unique_id] = $validated;
        $section_data['items'][$unique_id]['id'] = $unique_id;

        $update_data['key'] = $slug;
        $update_data['value']   = $section_data;
        try {
            SiteSections::updateOrCreate(['key' => $slug], $update_data);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again')]]);
        }

        return back()->with(['success' => [__('Social icon added successfully!')]]);
    }

    /**
     * Method for update social icon
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function footerItemUpdate(Request $request, $slug)
    {
        $validator = Validator::make($request->all(), [
            'item_name_edit'        => 'required|string|max:100',
            'item_link_edit'        => 'required|string|url|max:100',
            'item_social_icon_edit' => 'required|string|max:100',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('modal', 'social-edit');
        }
        $validated = $validator->validate();
        $validated = replace_array_key($validated, "_edit");

        $slug = Str::slug(SiteSectionConst::FOOTER_SECTION);
        $section = SiteSections::getData($slug)->first();
        if (!$section) return back()->with(['error' => [__('Section not found!')]]);
        $section_values = json_decode(json_encode($section->value), true);

        if (!isset($section_values['items'])) return back()->with(['error' => [__('Social Icon not found!')]]);
        if (!array_key_exists($request->target, $section_values['items'])) return back()->with(['error' => [__('Social Icon is invalid!')]]);



        $section_values['items'][$request->target] = $validated;

        try {
            $section->update([
                'value' => $section_values,
            ]);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again')]]);
        }

        return back()->with(['success' => [__('Information updated successfully!')]]);
    }

    /**
     * Method for delete social icon
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function footerItemDelete(Request $request, $slug)
    {
        $request->validate([
            'target'    => 'required|string',
        ]);
        $slug = Str::slug(SiteSectionConst::FOOTER_SECTION);
        $section = SiteSections::getData($slug)->first();
        if (!$section) return back()->with(['error' => [__('Section not found!')]]);
        $section_values = json_decode(json_encode($section->value), true);
        if (!isset($section_values['items'])) return back()->with(['error' => [__('Social Icon not found!')]]);
        if (!array_key_exists($request->target, $section_values['items'])) return back()->with(['error' => [__('Social Icon is invalid!')]]);
        try {
            unset($section_values['items'][$request->target]);
            $section->update([
                'value'     => $section_values,
            ]);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }

        return back()->with(['success' => [__('Social Icon deleted successfully!')]]);
    }

    /**
     * Method for show auth section page
     * @param string $slug
     * @return view
     */
    public function authView($slug)
    {
        $page_title = __("Auth Section");
        $section_slug = Str::slug(SiteSectionConst::AUTH_SECTION);
        $data = SiteSections::getData($section_slug)->first();
        $languages = $this->languages;

        return view('admin.sections.setup-sections.auth-section', compact(
            'page_title',
            'data',
            'languages',
            'slug'
        ));
    }

    /**
     * Method for update auth section information
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function authUpdate(Request $request, $slug)
    {
        $basic_field_name = ['login_heading' => "required|string|max:100", 'login_sub_heading' => "required|string|max:500", 'register_heading' => "required|string|max:100", 'register_sub_heading' => "required|string|max:500",'forgot_heading' => "required|string|max:100", 'forgot_sub_heading' => "required|string|max:500",];

        $slug = Str::slug(SiteSectionConst::AUTH_SECTION);
        $section = SiteSections::where("key", $slug)->first();
        if ($section != null) {
            $section_data = json_decode(json_encode($section->value), true);
        } else {
            $section_data = [];
        }
        if ($request->hasFile("login_image")) {
            $section_data['login_image']      = $this->imageValidate($request, "login_image", $section->value->login_image ?? null);
        }
        if ($request->hasFile("register_image")) {
            $section_data['register_image']      = $this->imageValidate($request, "register_image", $section->value->register_image ?? null);
        }

        $section_data['language']  = $this->contentValidate($request, $basic_field_name);
        $update_data['value']  = $section_data;
        $update_data['key']    = $slug;

        try {
            SiteSections::updateOrCreate(['key' => $slug], $update_data);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }

        return back()->with(['success' => [__('Auth updated successfully!')]]);
    }


    /**
     * Method for get languages form record with little modification for using only this class
     * @return array $languages
     */
    public function languages()
    {
        $languages = Language::whereNot('code', LanguageConst::NOT_REMOVABLE)->select("code", "name")->get()->toArray();
        $languages[] = [
            'name'      => LanguageConst::NOT_REMOVABLE_CODE,
            'code'      => LanguageConst::NOT_REMOVABLE,
        ];
        return $languages;
    }

    /**
     * Method for validate request data and re-decorate language wise data
     * @param object $request
     * @param array $basic_field_name
     * @return array $language_wise_data
     */
    public function contentValidate($request, $basic_field_name, $modal = null)
    {
        $languages = $this->languages();

        $current_local = get_default_language_code();
        $validation_rules = [];
        $language_wise_data = [];
        foreach ($request->all() as $input_name => $input_value) {
            foreach ($languages as $language) {
                $input_name_check = explode("_", $input_name);
                $input_lang_code = array_shift($input_name_check);
                $input_name_check = implode("_", $input_name_check);
                if ($input_lang_code == $language['code']) {
                    if (array_key_exists($input_name_check, $basic_field_name)) {
                        $langCode = $language['code'];
                        if ($current_local == $langCode) {
                            $validation_rules[$input_name] = $basic_field_name[$input_name_check];
                        } else {
                            $validation_rules[$input_name] = str_replace("required", "nullable", $basic_field_name[$input_name_check]);
                        }
                        $language_wise_data[$langCode][$input_name_check] = $input_value;
                    }
                    break;
                }
            }
        }
        if ($modal == null) {
            $validated = Validator::make($request->all(), $validation_rules)->validate();
        } else {
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput()->with("modal", $modal);
            }
            $validated = $validator->validate();
        }

        return $language_wise_data;
    }

    /**
     * Method for validate request image if have
     * @param object $request
     * @param string $input_name
     * @param string $old_image
     * @return boolean|string $upload
     */
    public function imageValidate($request, $input_name, $old_image)
    {
        if ($request->hasFile($input_name)) {
            $image_validated = Validator::make($request->only($input_name), [
                $input_name         => "image|mimes:png,jpg,webp,jpeg,svg",
            ])->validate();

            $image = get_files_from_fileholder($request, $input_name);
            $upload = upload_files_from_path_dynamic($image, 'site-section', $old_image);
            return $upload;
        }

        return false;
    }
}
