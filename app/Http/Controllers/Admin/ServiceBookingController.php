<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Services\ServiceBooking;
use App\Models\Admin\UserNotification;
use App\Notifications\sendMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class ServiceBookingController extends Controller
{

    public function index(){

        $page_title = __("Bookings");
        $service_bookings = ServiceBooking::with(['service'])->orderByDesc("id")->paginate(10);

        return view('admin.sections.services.service-booking.index',compact('page_title', 'service_bookings'));
    }

    public function updateStatus(Request $request){
        $validator = Validator::make($request->all(),[
            'target'       => "required|integer|exists:service_bookings,id",
            'status'       => "required",
        ]);
        if($validator->fails()) return back()->withErrors($validator)->withInput()->with('modal','status-change');
        $validated = $validator->validate();
        $booking_info = ServiceBooking::findOrFail($validated['target']);
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
        $booking_request = ServiceBooking::find($validated['target']);
        $formData = [
            'subject' => $validated['subject'],
            'message' => $validated['message'],
        ];
        try{
            Notification::route("mail",$booking_request->email)->notify(new sendMessage($formData));
            $notification_content = [
                'title'   => __("Booking"),
                'message' =>__("A reply has been sent to your mail about your booking(Service: ").$booking_request->service->name.")"
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
        $booking = ServiceBooking::with(['service'])->where('slug',$slug)->first();

        return view('admin.sections.services.service-booking.view',compact('page_title', 'booking'));
    }
    public function fareCalculate(Request $request){
        $validator = Validator::make($request->all(),[
            'target'       => "required|integer|exists:service_bookings,id",
            'distance'     => "required|numeric|gt:0",
        ]);
        if($validator->fails()) return back()->withErrors($validator)->withInput()->with('modal','fare-add');
        $validated = $validator->validate();
        $booking_info = ServiceBooking::findOrFail($validated['target']);
        $validated['amount'] = $booking_info->service->price;
        $validated['distance'] = $validated['distance'];
        try {
            $booking_info->update($validated);
        }catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again')]]);
        }
        return redirect()->back()->with(['success' => [__('Fare Added Successfully!')]]);
    }
}
