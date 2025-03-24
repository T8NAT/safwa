<?php

namespace App\Http\Controllers\Api\V1\User;

use Exception;
use Illuminate\Http\Request;
use App\Http\Helpers\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use App\Providers\Admin\BasicSettingsProvider;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    public function index()
    {
        $page_title = "User Profile";
        $user = Auth::user();
        return Response::success([__('User Profile data fetch successfully!')],['user_info' =>$user, 'page_title' =>$page_title],200);
    }

    public function update(Request $request)
    {
        $validated = Validator::make($request->all(),[
            'firstname'     => "required|string|max:60",
            'lastname'      => "required|string|max:60",
            'country'       => "required|string|max:50",
            'mobile_code'   => "nullable|string|max:10",
            'mobile'        => "nullable|string|max:20",
            'state'         => "nullable|string|max:50",
            'city'          => "nullable|string|max:50",
            'address'       => "nullable|string|max:250",
            'zip'           => "nullable|string",
            'image'         => "nullable|image|mimes:jpg,png,svg,webp|max:10240",
        ])->validate();
        $validated['mobile_code']       = remove_speacial_char($validated['mobile_code']);
        $validated['mobile']            = remove_speacial_char($validated['mobile']);
        $validated['full_mobile']       = $validated['mobile_code'] . $validated['mobile'];
        $validated['address']       = [
            'state'     => $validated['state'] ?? "",
            'city'      => $validated['city'] ?? "",
            'zip'       => $validated['zip'] ?? "",
            'address'   => $validated['address'] ?? "",
        ];

        if($request->hasFile("image")) {
            $image = upload_file($validated['image'],'user-profile',auth()->user()->image);
            $upload_image = upload_files_from_path_dynamic([$image['dev_path']],'user-profile');
            delete_file($image['dev_path']);
            $validated['image']     = $upload_image;
        }

        try{
            auth()->user()->update($validated);
        }catch(Exception $e) {
            return Response::error([__('Something went wrong! Please try again')],['error' =>$e],500);
        }
        $user = Auth::user();
        return Response::success([__('Profile data updated successfully!')],['user_info' =>$user],200);
    }

    public function passwordUpdate(Request $request)
    {
        $basic_settings = BasicSettingsProvider::get();
        $passowrd_rule = "required|string|min:6|confirmed";
        if($basic_settings->secure_password) {
            $passowrd_rule = ["required",Password::min(8)->letters()->mixedCase()->numbers()->symbols()->uncompromised(),"confirmed"];
        }

        $request->validate([
            'current_password'      => "required|string",
            'password'              => $passowrd_rule,
        ]);

        if(!Hash::check($request->current_password,auth()->user()->password)) {
            throw ValidationException::withMessages([
                'current_password'      => __("Current password didn't match"),
            ]);
        }

        try{
            auth()->user()->update([
                'password'  => Hash::make($request->password),
            ]);
        }catch(Exception $e) {
            return Response::error([__('Something went wrong! Please try again')],['error' =>$e],500);
        }
        return Response::success([__('Password changed successfully!')],[],200);

    }
    public function deleteProfile(){
        $user = Auth::guard(get_auth_guard())->user();
        if(!$user){
            $message = ['success' =>  [__("Oops! User does not exists")]];
            return Response::error($message, []);
        }
        try {
            $user->status            = 0;
            $user->deleted_at        = now();
            $user->save();
            $user->token()->revoke();
        } catch (Exception $e) {
            return Response::error([__('Something went wrong, please try again!')], []);
        }
        return Response::success([__('Your account deleted successfully!')], $user);
    }

}
