<?php

namespace App\Http\Controllers\Api\V1\Settings;

use Exception;
use Illuminate\Http\Request;
use App\Http\Helpers\Response;
use App\Models\Admin\Language;
use App\Models\Admin\AppSettings;
use App\Models\Admin\BasicSettings;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Models\Admin\AppOnboardScreens;
use App\Models\Admin\Currency;

class BasicSettingsController extends Controller
{
    public function basicSettings()
    {
        $basicSettings = BasicSettings::first();

        $splash_screen = AppSettings::orderByDesc('id')->get()->map(function ($data) {
            return [
                'image' => $data->splash_screen_image,
                'version' => $data->version,
            ];
        });

        $app_url = [
            'android_url' => $basicSettings->android_url,
            'iso_url' => $basicSettings->iso_url,
        ];

        $onboard_screen = AppOnboardScreens::orderByDesc('id')->where('status', 1)->get()->map(function ($data) {
            return [
                'title' => $data->title,
                'sub_title' => $data->sub_title,
                'image' => $data->image,
                'status' => $data->status,
            ];
        });
        $currency = Currency::default();
        $currency_code = $currency->code;
        $data = [
            'basic_settings' => [
                'id' => $basicSettings->id,
                'site_name' => $basicSettings->site_name,
                'site_title' => $basicSettings->site_title,
                'currency_code'=>  $currency_code,
                'timezone' => $basicSettings->timezone,
            ],
            'all_logo'  => [
                'site_logo_dark' => $basicSettings->site_logo_dark,
                'site_logo' => $basicSettings->site_logo,
                'site_fav_dark' => $basicSettings->site_fav_dark,
                'site_fav' => $basicSettings->site_fav,
            ],
            'web_links' => [
                'privacy-policy' => url('/page/privacy-policy'),
                'about-us' => Route::has('about') ? route('about') : url('/about'),
                'contact-us' => Route::has('contact') ? route('contact') : url('/contact'),
            ],
            'languages' => Language::get()->map(function ($language) {
                return [
                    'id' => $language->id,
                    'name' => $language->name,
                    'code' => $language->code,
                    'status' => $language->status,
                ];
            }),
            'splash_screen' => $splash_screen,
            'onboard_screens' => $onboard_screen,
            'app_image_paths' => [
                'base_url'          => url("/"),
                'path_location'     => files_asset_path_basename("app-images"),
                'default_image'     => files_asset_path_basename("default"),
            ],
            'image_assets' => [
                'base_url'          => url("/"),
                'path_location'     => files_asset_path_basename("image-assets"),
                'default_image'     => files_asset_path_basename("default"),
            ]
        ];

        $message = [__('Basic Settings fetched successfully!')];
        return Response::success($message, $data);
    }

    public function getLanguages()
    {
        try {
            $api_languages = get_api_languages();
        } catch (Exception $e) {
            return Response::error([$e->getMessage()], [], 500);
        }

        return Response::success([__("Language data fetch successfully!")], [
            'languages' => $api_languages,
        ], 200);
    }
    public function splashScreen(){
        $splash_screen = AppSettings::orderByDesc('id')->get()->map(function ($data) {
            return [
                'image' => $data->splash_screen_image,
                'version' => $data->version,
            ];
        });
        $data = [
            'splash_screen' => $splash_screen,
            'app_image_paths' => [
                'base_url'          => url("/"),
                'path_location'     => files_asset_path_basename("app-images"),
                'default_image'     => files_asset_path_basename("default"),
            ]
        ];

        $message = [__('Splash Screen fetched successfully!')];
        return Response::success($message, $data);
    }
    public function onboardScreen(){
        $basicSettings = BasicSettings::first();
        $onboard_screen = AppOnboardScreens::orderByDesc('id')->where('status', 1)->get()->map(function ($data) {
            return [
                'title' => $data->title,
                'sub_title' => $data->sub_title,
                'image' => $data->image,
                'status' => $data->status,
            ];
        });
        $data = [
            'onboard_screens' => $onboard_screen,
            'app_image_paths' => [
                'base_url'          => url("/"),
                'path_location'     => files_asset_path_basename("app-images"),
                'default_image'     => files_asset_path_basename("default"),
            ]
        ];

        $message = [__('Onboard Screen fetched successfully!')];
        return Response::success($message, $data);
    }
}
