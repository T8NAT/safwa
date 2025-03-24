<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Services\Service;
use App\Models\Admin\Services\ServiceArea;
use App\Models\Admin\Services\ServiceType;
use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Subscriber;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Helpers\Response;
use App\Models\ContactMessage;
use App\Models\Admin\Announcement;
use App\Models\Admin\SiteSections;
use App\Constants\SiteSectionConst;
use App\Constants\NotificationConst;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\AdminNotification;
use App\Models\Admin\Cars\Car;
use App\Models\Admin\Cars\CarArea;
use App\Models\Admin\Cars\CarType;
use App\Models\Admin\Schedules\DailySchedule;
use App\Models\UserSupportTicket;
use App\Providers\Admin\BasicSettingsProvider;
use Pusher\PushNotifications\PushNotifications;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $page_title = __("Dashboard");
        $users = User::count();
        $activeUsers = User::where('status', 1)->count();
        $bannedUsers = User::where('status', 0)->count();
        $verifiedUsers = User::where('email_verified', 1)->count();

        $announcements = Announcement::count();
        $activeAnnouncements = Announcement::where('status', 1)->count();
        $bannedAnnouncements = Announcement::where('status', 0)->count();

        $subscribers = Subscriber::count();
        $currentMonthSubscribers = Subscriber::whereMonth('created_at', Carbon::now()->month)->count();
        $currentYearSubscribers = Subscriber::whereYear('created_at', Carbon::now()->year)->count();

        $messages = ContactMessage::count();
        $repliedMessages = ContactMessage::where('reply', 1)->count();
        $unansweredMessages = ContactMessage::where('reply', 0)->count();

        $areas = ServiceArea::query()->count();
        $activeAreas = ServiceArea::query()->where('status', 1)->count();
        $bannedAreas = ServiceArea::query()->where('status', 0)->count();

//        $areas = CarArea::count();
//        $activeAreas = CarArea::where('status', 1)->count();
//        $bannedAreas = CarArea::where('status', 0)->count();

        $types = ServiceType::query()->count();
        $activeTypes = ServiceType::query()->where('status', 1)->count();
        $bannedTypes = ServiceType::query()->where('status', 0)->count();

//        $types = CarType::count();
//        $activeTypes = CarType::where('status', 1)->count();
//        $bannedTypes = CarType::where('status', 0)->count();

        $services = Service::query()->count();
        $activeServices = Service::query()->where('status', 1)->count();
        $bannedServices = Service::query()->where('status', 0)->count();


//        $cars = Car::count();
//        $activeCars = Car::where('status', 1)->count();
//        $bannedCars = Car::where('status', 0)->count();

        $tickets = UserSupportTicket::count();
        $activeTickets = UserSupportTicket::where('status', 2)->count();
        $solvedTickets = UserSupportTicket::where('status', 1)->count();
        $pendingTickets = UserSupportTicket::where('status', 3)->count();

        return view('admin.sections.dashboard.index',compact(
            'page_title',
            'users',
            'activeUsers',
            'verifiedUsers',
            'bannedUsers',
            'announcements',
            'activeAnnouncements',
            'bannedAnnouncements',
            'subscribers',
            'currentMonthSubscribers',
            'currentYearSubscribers',
            'messages',
            'repliedMessages',
            'unansweredMessages',
            'areas',
            'activeAreas',
            'bannedAreas',
            'types',
            'activeTypes',
            'bannedTypes',
            'services',
            'activeServices',
            'bannedServices',
            'tickets',
            'activeTickets',
            'solvedTickets',
            'pendingTickets',
        ));
    }


    /**
     * Logout Admin From Dashboard
     * @return view
     */
    public function logout(Request $request) {

        $push_notification_setting = BasicSettingsProvider::get()->push_notification_config;

        if($push_notification_setting) {
            $method = $push_notification_setting->method ?? false;

            if($method == "pusher") {
                $instant_id     = $push_notification_setting->instance_id ?? false;
                $primary_key    = $push_notification_setting->primary_key ?? false;

                if($instant_id && $primary_key) {
                    $pusher_instance = new PushNotifications([
                        "instanceId"    => $instant_id,
                        "secretKey"     => $primary_key,
                    ]);

                    $pusher_instance->deleteUser("".Auth::user()->id."");
                }
            }

        }

        $admin = auth()->user();
        try{
            $admin->update([
                'last_logged_out'   => now(),
                'login_status'      => false,
            ]);
        }catch(Exception $e) {
            // Handle Error
        }

        Auth::guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }


    /**
     * Function for clear admin notification
     */
    public function notificationsClear() {
        $admin = auth()->user();

        if(!$admin) {
            return false;
        }

        try{
            $admin->update([
                'notification_clear_at'     => now(),
            ]);
        }catch(Exception $e) {
            $error = ['error' => [__('Something went wrong! Please try again.')]];
            return Response::error($error,null,404);
        }

        $success = ['success' => [__('Notifications clear successfully!')]];
        return Response::success($success,null,200);
    }
}
