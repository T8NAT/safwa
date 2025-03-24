<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Illuminate\Http\Request;
use App\Notifications\sendMessage;
use App\Http\Controllers\Controller;
use App\Models\Admin\Cars\CarBooking;
use App\Models\Admin\UserNotification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;

class CarBookingController extends Controller
{
    public function index(){

        $page_title = __("Bookings");
        $car_bookings = CarBooking::with(['cars'])->orderByDesc("id")->paginate(10);

        return view('admin.sections.cars.car-booking.index',compact(
            'page_title',
            'car_bookings'
        ));
    }

    public function updateStatus(Request $request){
        $validator = Validator::make($request->all(),[
            'target'       => "required|integer|exists:car_bookings,id",
            'status'       => "required",
        ]);
        if($validator->fails()) return back()->withErrors($validator)->withInput()->with('modal','status-change');
        $validated = $validator->validate();
        $booking_info = CarBooking::findOrFail($validated['target']);
        try {
            $booking_info->update($validated);
        }catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again')]]);
        }
        return redirect()->back()->with(['success' => [__('Status updated successfully!')]]);
    }

    public function reply(Request $request){
        $validator = Validator::make($request->all(),[
            'target'        => "required|integer|exists:car_bookings,id",
            'subject'       => "required|string|max:255",
            'message'       => "required|string|max:3000",
        ]);

        if($validator->fails()) return back()->withErrors($validator)->withInput()->with('modal','send-reply');
        $validated = $validator->validate();
        $booking_request = CarBooking::find($validated['target']);
        $formData = [
            'subject' => $validated['subject'],
            'message' => $validated['message'],
        ];
        try{
            Notification::route("mail",$booking_request->email)->notify(new sendMessage($formData));
            $notification_content = [
                'title'   => __("Booking"),
                'message' =>__("A reply has been sent to your mail about your booking(Car: ").$booking_request->cars->car_model.")"
            ];
            UserNotification::create([
                'user_id'   => $booking_request->user_id,
                'message'   => $notification_content,

            ]);

        }catch(Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again')]]);
        }
        return back()->with(['success' => [__('Reply sent successfully!')]]);
    }

    public function bookingDetails($slug){
        $page_title = __("Booking Details");
        $booking = CarBooking::with(['cars'])->where('slug',$slug)->first();

        return view('admin.sections.cars.car-booking.view',compact(
            'page_title',
            'booking'
        ));
    }
    public function fareCalculate(Request $request){
        $validator = Validator::make($request->all(),[
            'target'       => "required|integer|exists:car_bookings,id",
            'distance'     => "required|numeric|gt:0",
        ]);
        if($validator->fails()) return back()->withErrors($validator)->withInput()->with('modal','fare-add');
        $validated = $validator->validate();
        $booking_info = CarBooking::findOrFail($validated['target']);
        $validated['amount'] = $validated['distance'] * $booking_info->cars->fees;
        $validated['distance'] = $validated['distance'];
        try {
            $booking_info->update($validated);
        }catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again')]]);
        }
        return redirect()->back()->with(['success' => [__('Fare Added Successfully!')]]);
    }
}
