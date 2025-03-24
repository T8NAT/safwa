<?php

namespace App\Http\Controllers\Api\V1\User;

use Exception;
use Illuminate\Http\Request;
use App\Http\Helpers\Response;
use App\Models\Admin\BasicSettings;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\UserNotification;

class DashboardController extends Controller
{
    public function index()
    {
        $basic_settings = BasicSettings::first();
        $user = Auth::user();
        $page_title = ($basic_settings->site_name ?? 'Home') . ' - ' . ($basic_settings->site_title ?? 'Home');
        return Response::success([__('User dashboard data fetch successfully!')],['user_info' =>$user, 'page_title' =>$page_title],200);
    }
    public function notification()
    {
        $user = Auth::user();
        $notifications  = UserNotification::where('user_id',$user->id)->latest()->take(10)->get();
        return Response::success([__('User Notification data fetched successfully!')],['notifications' =>$notifications],200);
    }
    public function logout(Request $request) {
        $user = Auth::guard(get_auth_guard())->user();
        $token = $user->token();
        try{
            $token->revoke();
        }catch(Exception $e) {
            return Response::error([__('Something went wrong! Please try again')],[],500);
        }
        return Response::success([__('Logout success!')],[],200);
    }
}
