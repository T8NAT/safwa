<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Models\Admin\Services\ServiceBooking;
use Illuminate\Http\Request;
use App\Http\Helpers\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Cars\CarBooking;

class HistoryController extends Controller
{
    public function bookingList()
    {
//        if(auth::user()){
//            $booking_list = CarBooking::with(['cars','user'])->where('user_id',auth::user()->id)->get();
//            if($booking_list){
//                $message = [__('Booking list Fetched Successfully!')];
//                return Response::success($message, $booking_list);
//            }
//            else{
//                $message = [__('Booking list not found!')];
//                return Response::error($message,[],404);
//            }
//        }
//        else{
//            $message = [__('Booking list not found!')];
//            return Response::error($message,[],404);
//        }

        if(auth::user()){
            $booking_list = ServiceBooking::with(['service','user'])->where('user_id',auth::user()->id)->get();
            if($booking_list){
                $message = [__('Booking list Fetched Successfully!')];
                return Response::success($message, $booking_list);
            }
            else{
                $message = [__('Booking list not found!')];
                return Response::error($message,[],404);
            }
        }
        else{
            $message = [__('Booking list not found!')];
            return Response::error($message,[],404);
        }
    }
}
