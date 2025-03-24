<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Response;
use App\Models\Admin\BasicSettings;
use App\Models\Admin\SetupKyc;
use App\Models\Admin\SetupSeo;
use App\Providers\Admin\BasicSettingsProvider;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use Buglinjo\LaravelWebp\Facades\Webp;




class WebSettingsController extends Controller
{

    /**
     * Display The Basic Settings Page
     *
     * @return view
     */
    public function basicSettings()
    {
        $page_title = __("Basic Settings");
        $basic_settings   = BasicSettings::first();

        return view('admin.sections.web-settings.basic-settings', compact(
            'page_title',
            'basic_settings',
        ));
    }


    public function basicSettingsUpdate(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'base_color'        => 'required|string',
            'secondary_color'   => 'required|string',
            'country_code'      => 'nullable|string',
            'google_api_key'    => 'nullable|string',
            'web_version'       => 'required|string',
            'site_name'         => 'required|string',
            'site_title'        => 'required|string',
            'timezone'          => 'required|string',
            'otp_exp_seconds'   => 'required|string',
        ]);

        $validated = $validator->validate();

        $basic_settings = BasicSettings::first();
        if (!$basic_settings) return back()->with(['error' => [__('Basic settings not found!')]]);

        try {
            $basic_settings->update($validated);
            modifyEnv([
                "APP_NAME" => $validated['site_name'],
                "APP_TIMEZONE"  => $validated['timezone'],
            ]);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }

        return back()->with(['success' => [__('Basic settings updated successfully!')]]);
    }

    public function basicSettingsActivationUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status'                    => 'required|boolean',
            'input_name'                => 'required|string',
        ]);

        if ($validator->stopOnFirstFailure()->fails()) {
            $error = ['error' => $validator->errors()];
            return Response::error($error, null, 400);
        }
        $validated = $validator->validate();

        $basic_settings = BasicSettingsProvider::get();
        // Check Email configure
        if ($validated['input_name'] == "email_verification") {
            if (!$basic_settings->mail_config) {
                $warning = ['warning' => [__('You have to configure your system mail first.')]];
                return Response::warning($warning, null, 400);
            }
        }

        $validated['status'] = ($validated['status'] == true) ? false : true;

        if (!$basic_settings) {
            $error = ['error' => [__('Basic settings not found!')]];
            return Response::error($error, null, 404);
        }


        try {
            $basic_settings->update([
                $validated['input_name'] => $validated['status'],
            ]);
        } catch (Exception $e) {
            $error = ['error' => [__('Something went wrong! Please try again.')]];
            return Response::error($error, null, 500);
        }

        $success = ['success' => [__('Basic settings status updated successfully!')]];
        return Response::success($success, null, 200);
    }

    /**
     * Display The Image Assets Page
     *
     * @return view
     */
    public function imageAssets()
    {
        $page_title =__("Image Assets");
        $basic_settings = BasicSettingsProvider::get();
        return view('admin.sections.web-settings.image-assets', compact(
            'page_title',
            'basic_settings',
        ));
    }


    // public function imageAssetsUpdate(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'site_logo'         => 'nullable|image|mimes:png,jpeg,jpg,webp,svg',
    //         'site_logo_dark'    => 'nullable|image|mimes:png,jpeg,jpg,webp,svg',
    //         'site_fav'          => 'nullable|image|mimes:png,jpeg,jpg,webp,svg',
    //         'site_fav_dark'     => 'nullable|image|mimes:png,jpeg,jpg,webp,svg',
    //     ]);
    //     $validated = $validator->validate();

    //     $basic_settings = BasicSettingsProvider::get();
    //     if (!$basic_settings) {
    //         return back()->with(['error' => [__('Basic setting not found! Please run database seeder')]]);
    //     }

    //     $images = [];
    //     foreach ($validated as $input_name => $item) {
    //         if ($request->hasFile($input_name)) {
    //             $image = get_files_from_fileholder($request, $input_name);
    //             $upload_image = upload_files_from_path_dynamic($image, 'image-assets', $basic_settings->$input_name);
    //             $images[$input_name] = $upload_image;
    //         }
    //     }

    //     if (count($images) == 0) {
    //         return back()->with(['warning' => [__('No changes to update.')]]);
    //     }

    //     // update images to database
    //     try {
    //         $basic_settings->update($images);
    //     } catch (Exception $e) {
    //         return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
    //     }

    //     return back()->with(['success' => [__('Image assets updated successfully!.')]]);
    // }


public function imageAssetsUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'site_logo'         => 'nullable|image|mimes:png,jpeg,jpg,webp,svg|max:2048',
            'site_logo_dark'    => 'nullable|image|mimes:png,jpeg,jpg,webp,svg|max:2048',
            'site_fav'          => 'nullable|image|mimes:png,jpeg,jpg,webp,svg|max:2048',
            'site_fav_dark'     => 'nullable|image|mimes:png,jpeg,jpg,webp,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        $basic_settings = BasicSettingsProvider::get();
        if (!$basic_settings) {
            return back()->with(['error' => [__('Basic setting not found! Please run database seeder')]]);
        }

        $images = [];
        foreach ($validated as $input_name => $value) {
            if ($request->hasFile($input_name)) {
                try {
                    $image = $request->file($input_name);
                  //تم الاستدعاء من خلال الكلاس واستخدام نطاق المساعدة  
                    $upload_image = uploadImage($image, 'image-assets', $basic_settings->$input_name ?? null);
                    $images[$input_name] = $upload_image;
                } catch (\Exception $e) {
                    return back()->with(['error' => [$e->getMessage()]]); // عرض رسالة الخطأ التي تم إنشاؤها في دالة uploadImage
                }
            }
        }

        if (empty($images)) {
            return back()->with(['warning' => [__('No changes to update.')]]);
        }

        try {
            $basic_settings->update($images);
        } catch (\Exception $e) {
            \Log::error('Error updating image assets: ' . $e->getMessage());
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }

        return back()->with(['success' => [__('Image assets updated successfully!.')]]);
    }

// function uploadImage($image, $destination_path, $old_file = null)
// {
//     // استخدم المسار المؤقت
//     $save_path = sys_get_temp_dir();
//     $destination_path_for_file =  'backend/images/web-settings/image-assets'; // define destination folder
//     $destination_path_for_file_public = 'public/backend/images/web-settings/image-assets';

//     // التحقق من وجود المجلد الدائم
//     if (!File::exists($destination_path_for_file_public)) {
//         try {
//             File::makeDirectory($destination_path_for_file_public, 0775, true); // إنشاء المجلد إذا لم يكن موجودًا
//         } catch (\Exception $e) {
//             \Log::error('Error creating directory: ' . $e->getMessage());
//             throw new \Exception(__('Error creating directory. Please check permissions.'));
//         }
//     }

//     // التحقق من أذونات الكتابة على المجلد الدائم
//     if (!is_writable($destination_path_for_file_public)) {
//       // سجل معلومات المستخدم الحالي
//       \Log::info('Current user: ' . exec('whoami'));
//       // سجل أذونات المجلد
//       \Log::info('Permissions: ' . substr(sprintf('%o', fileperms($destination_path_for_file_public)), -4));
//         \Log::error('Directory is not writable: ' . $destination_path_for_file_public);
//         throw new \Exception(__('Directory is not writable. Please check permissions on the permanent directory.'));
//     }

//      $filename = uniqid() . '.' . $image->getClientOriginalExtension(); // إنشاء اسم ملف فريد
//      $temp_path = $save_path . '/' . $filename; // تعريف المتغير هنا

//         // التحقق من إمكانية الكتابة إلى المجلد المؤقت
//         $test_file = $save_path . '/test.txt';
//         if (file_put_contents($test_file, 'test') === false) {
//             \Log::error('Failed to write to temporary directory.');
//             throw new \Exception(__('Failed to write to temporary directory.'));
//         } else {
//             \Log::info('Successfully wrote to temporary directory.');
//             unlink($test_file); // حذف الملف التجريبي
//         }

//          //Copy image from temporary to permanent destination
//         try {
//              // تغيير حجم الصورة (اختياري)
//               $img = Image::make($image);
//              \Log::info('Image make  successfully: ' );
//              // مثال: تغيير الحجم إلى 800 بكسل كحد أقصى للعرض
//             $img->resize(800, null, function ($constraint) {
//                  $constraint->aspectRatio();
//                  $constraint->upsize(); // منع التكبير إذا كانت الصورة أصغر
//                 });
//              \Log::info('Image resized successfully: ' );
//             $img->save($temp_path); // image saved temporary
//              \Log::info('Image saved temporary successfully: ' . $temp_path);
//             \Log::info('Attempting to move file from: ' . $temp_path . ' to: ' .  $destination_path_for_file_public. '/' .$filename); // log the attempt

//             if (rename($temp_path, $destination_path_for_file_public. '/' .$filename)) {
//                   // File moved successfully
//                 \Log::info('Successfully moved temporary file.'); // log success
//             } else {
//                 $errors = error_get_last();
//                 \Log::error('Move failed, error: ' . print_r($errors, true)); // log failure info
//                 throw new \Exception(__('Failed to move temporary file.'));
//             }
//              // Create UploadedFile object  [ نقل إلى هنا ]
//           $file_instance = new \Illuminate\Http\UploadedFile(
//               $destination_path_for_file_public. '/' .$filename, //تم التعديل ليطابق المسار الصحيح
//               $filename,
//               File::mimeType($destination_path_for_file_public. '/' .$filename), //تم التعديل ليطابق المسار الصحيح
//               File::size($destination_path_for_file_public. '/' .$filename), //تم التعديل ليطابق المسار الصحيح
//               true, // changed null to true
//               true // Mark as test, prevents issues with move_uploaded_file
//           );

//           // تحويل إلى WebP (اختياري) [ نقل إلى هنا ]
//          $webp_path = $destination_path_for_file_public . '/' . pathinfo($filename, PATHINFO_FILENAME) . '.webp';
//          Webp::make($file_instance)->save($webp_path); // create webp image from temporary save path to destination path
//           } catch (\Exception $e) {
//               \Log::error('Error processing image: ' . $e->getMessage());
//               throw new \Exception(__('Error processing image. Please try again.'));
//         }


//      // التحقق من وجود الملف المؤقت  [ حذف هذا التحقق ]
//     //  if (!file_exists($temp_path)) {
//     //      \Log::error('Temporary file not found: ' . $temp_path);
//     //      throw new \Exception(__('Temporary file not found. Please check permissions.'));
//     //  }

//         //  \Log::info('Temporary file path: ' . $temp_path);



//       //After all process delete the temp image saved in temp folder
//       if (file_exists($temp_path)) {
//         unlink($temp_path);
//       }

//       return $filename; // إرجاع اسم الملف
//       }
    
//     function get_files_path($slug)
// {
//     $data = files_path($slug);
//     $path = $data->path;
//   //  create_asset_dir($path); // لا تحتاج إلى إنشاء الدليل هنا بعد الآن

//     return public_path($path);
// }
    
    /**
     * Display The SEO Setup Page
     *
     * @return view
     */
    public function setupSeo()
    {
        $page_title = "Setup SEO";
        $setup_seo = SetupSeo::first();
        return view('admin.sections.web-settings.setup-seo', compact(
            'page_title',
            'setup_seo',
        ));
    }


    public function setupSeoUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image'         => 'nullable|image|mimes:png,jpg,webp,svg,jpeg',
            'title'         => 'required|string|max:120',
            'desc'          => 'nullable|string|max:255',
            'tags'          => 'nullable|array',
            'tags.*'        => 'nullable|string|max:30',
        ]);
        $validated = $validator->validate();
        $validated = Arr::except($validated, ['image']);

        $setup_seo = SetupSeo::first();

        if ($request->hasFile('image')) {
            $image = get_files_from_fileholder($request, 'image');
            $upload_image = upload_files_from_path_dynamic($image, 'seo', $setup_seo->image);
            $validated['image'] = $upload_image;
        }

        try {
            $setup_seo->update($validated);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }

        return back()->with(['success' => [__('SEO information updated successfully!')]]);
    }

}
