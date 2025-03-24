<?php

namespace App\Http\Controllers\User;


use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\Cars\CarBooking;
use App\Models\Admin\UserNotification;

class HistoryController extends Controller
{
    public function index(){
        $breadcrumb    = __("History");
        $page_title    = __("| User History");
        $booking       = CarBooking::with(['cars','user'])->where('user_id',auth::user()->id)->paginate(6);
        $user          = auth()->user();
        $notifications = UserNotification::where('user_id',$user->id)->latest()->take(10)->get();
        return view('user.sections.history.index',compact(
            'breadcrumb',
            'page_title',
            'booking',
            'user',
            'notifications'
        ));
    }

    public function bookingDetails($slug){
        $breadcrumb    = __("Booking Details");
        $page_title    = __("| Booking Details");
        $booking = CarBooking::with(['cars'])->where('slug',$slug)->first();
        
        return view('user.sections.history.details',compact(
            'breadcrumb',
            'page_title',
            'booking',
        ));
    }
}
