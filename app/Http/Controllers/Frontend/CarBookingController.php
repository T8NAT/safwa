<?php

namespace App\Http\Controllers\Frontend;

use Exception;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TemporaryData;
use App\Constants\GlobalConst;
use App\Models\Admin\Cars\Car;
use App\Models\Admin\SetupPage;
use App\Constants\LanguageConst;
use App\Models\Admin\SiteSections;
use App\Constants\SiteSectionConst;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Cars\CarBooking;
use App\Models\Admin\UserNotification;
use Illuminate\Support\Facades\Validator;
use App\Notifications\bookingConfirmation;
use Illuminate\Support\Facades\Notification;
use App\Notifications\carBookingNotification;
use App\Providers\Admin\BasicSettingsProvider;
use Illuminate\Validation\ValidationException;


class CarBookingController extends Controller
{
    public function booking($slug)
    {

        $page_title = setPageTitle(__("Car Booking"));
        $car = Car::where('slug', $slug)->first();
        if (!$car) abort(404);
        $footer_slug = Str::slug(SiteSectionConst::FOOTER_SECTION);
        $footer = SiteSections::getData($footer_slug)->first();
        $type =  Str::slug(GlobalConst::USEFUL_LINKS);
        $policies = SetupPage::orderBy('id')->where('type', $type)->where('status', 1)->get();
        $policy = SetupPage::orderBy('id')->where('type', $type)->where('status',1)->where('slug','privacy-policy')->first();
        $validated_user = auth()->user();
        $about_slug = Str::slug(SiteSectionConst::ABOUT_SECTION);
        $about = SiteSections::getData($about_slug)->first();
        $auth_slug = Str::slug(SiteSectionConst::AUTH_SECTION);
        $auth = SiteSections::getData($auth_slug)->first();
        $default = LanguageConst::NOT_REMOVABLE;
        return view('frontend.pages.car-booking', compact(
            'page_title',
            'car',
            'footer',
            'validated_user',
            'policies',
            'policy',
            'about',
            'auth',
            'default'
        ));
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'car'                => 'required',
            'location'           => 'required',
            'destination'        => 'required',
            'credentials'        => 'required|email',
            'pickup_time'        => 'required',
            'pickup_date'        => 'required',
            'mobile'             => 'nullable',
            'round_pickup_date'  => 'nullable',
            'round_pickup_time'  => 'nullable',
            'message'            => 'nullable',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput($request->all());
        }
        $validated  = $validator->validate();

        $pickupDateTime = Carbon::parse($validated['pickup_date'] . ' ' . $validated['pickup_time']);
        if ($pickupDateTime->isPast()) {
            return back()->with(['error' => [__('Pickup date and time must be in the future.')]]);
        }

        if (!empty($validated['round_pickup_date']) && !empty($validated['round_pickup_time'])) {
            $roundPickupDateTime = Carbon::parse($validated['round_pickup_date'] . ' ' . $validated['round_pickup_time']);
            if ($roundPickupDateTime->isPast()) {
                return back()->with(['error' => [__('Round pickup date and time must be in the future.')]]);
            }

            if ($roundPickupDateTime->lte($pickupDateTime)) {
                return back()->with(['error' => [__('Round pickup date and time must be greater than pickup date and time.')]]);
            }
        }
        $validated['email'] = $validated['credentials'];
        $validated['phone'] = $validated['mobile'];
        $validated['slug']  = Str::uuid();
        $car_slug           = $validated['car'];
        $findCar            = Car::where('slug', $car_slug)->first();
        if (!$findCar) {
            return back()->with(['error' => [__('Car not found!')]]);
        }

        if (auth()->check()) {
            $validated['user_id'] = auth()->user()->id;
        }
        else {
            $validated['user_id'] = null;
        }

        $validated['car_id'] = $findCar->id;

        $already_booked_car = CarBooking::where('car_id', $findCar->id)
            ->where('pickup_date', $validated['pickup_date'])
            ->count();
        if ($already_booked_car  > 0) {
            return back()->with(['error' => [__('This car is already booked at the selected date')]]);
        }

        try {
            $car_booking = TemporaryData::create([
                'token' => generate_unique_string("temporary_datas","token",20),
                'value' => $validated,
            ]);
            return redirect()->route('frontend.car.booking.preview', $car_booking->token);
        }catch (Exception $e) {
            return back()->with(['error' => [__('Something Went Wrong! Please try again.')]]);
        }
    }

    public function preview($token){

        $page_title = setPageTitle(__("Booking Preview"));
        $footer_slug = Str::slug(SiteSectionConst::FOOTER_SECTION);
        $footer = SiteSections::getData($footer_slug)->first();
        $type =  Str::slug(GlobalConst::USEFUL_LINKS);
        $policies = SetupPage::orderBy('id')->where('type', $type)->where('status', 1)->get();
        $policy = SetupPage::orderBy('id')->where('type', $type)->where('status',1)->where('slug','privacy-policy')->first();
        $validated_user = auth()->user();
        $about_slug = Str::slug(SiteSectionConst::ABOUT_SECTION);
        $about = SiteSections::getData($about_slug)->first();
        $customer = TemporaryData::where('token', $token)->first();
        $auth_slug = Str::slug(SiteSectionConst::AUTH_SECTION);
        $auth = SiteSections::getData($auth_slug)->first();
        $car = Car::where('id', $customer->value->car_id)->first();
        $default = LanguageConst::NOT_REMOVABLE;
        return view('frontend.pages.booking-preview',compact(
            'page_title',
            'about',
            'customer',
            'footer',
            'policy',
            'policies',
            'car',
            'auth',
            'default'
        ));
    }

    public function confirm($token)
    {
        $temp_booking =  TemporaryData::where('token', $token)->first();
        $temp_data = json_decode(json_encode($temp_booking->value),true);
        $send_code = generate_random_code();
        $temp_data['verification_code'] = $send_code;
        $car = Car::where('id', $temp_booking->value->car_id)->first();
        if(!$temp_booking) return back()->with(['error' => [__('Booking Not Found!')]]);
        $data = [
            'verification_code' => $send_code,
            'token'     => $token,
        ];
        try{
            $temp_booking->update([
                'value' => $temp_data,
            ]);
            Notification::route("mail", $temp_booking->value->email)->notify(new bookingConfirmation((object) $data));
        }catch(Exception $e){
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }
        return redirect()->route('frontend.car.booking.mail', ['token' => $token])->with(['Success'  => [__('Please check your email to get the OTP')]]);
    }

    public function showMailForm($token)
    {
        $page_title = setPageTitle(__("Mail Verification"));
        return view('admin.sections.cars.verify-booking', compact("page_title", "token"));
    }

    public function mailVerify(Request $request, $token)
    {
        $request->merge(['token' => $token]);
        $request->validate([
            'token'     => "required|string|exists:temporary_datas,token",
            'code'      => "required",
        ]);
            $temp_data = TemporaryData::where('token', $token)->first();
            $temporary_data = json_decode(json_encode($temp_data->value),true);
            if (!isset($temporary_data['verification_code'])) {
                return redirect()->back()->with(['error' => [__('Verification code not found in temporary data')]]);
            }
            $code = implode($request->code);
            $otp_exp_sec = BasicSettingsProvider::get()->otp_exp_seconds ?? GlobalConst::DEFAULT_TOKEN_EXP_SEC;
            $auth_column = TemporaryData::where("token", $request->token)->where('value->verification_code', $code)->first();

            if (!$auth_column) {
                return redirect()->back()->with(['error' => [__('Invalid otp code')]]);
            }
            if ($auth_column->created_at->addSeconds($otp_exp_sec) < now()) {
                return redirect()->route('frontend.car.booking.preview', $token)->with(['error' => [__('Session expired. Please try again')]]);
            }
            try {
                $booking_data = CarBooking::create([
                    'car_id'    => $temp_data->value->car_id,
                    'user_id'   => auth()->user()->id ?? null,
                    'slug'      => $temp_data->value->slug,
                    'phone'     => $temp_data->value->phone,
                    'email'     => $temp_data->value->email,
                    'location'  => $temp_data->value->location,
                    'destination' => $temp_data->value->destination,
                    'trip_id'     => generate_unique_code(),
                    'pickup_time'   => $temp_data->value->pickup_time,
                    'round_pickup_time' => $temp_data->value->round_pickup_time,
                    'pickup_date'   => $temp_data->value->pickup_date,
                    'round_pickup_date' => $temp_data->value->round_pickup_date,
                    'message'           => $temp_data->value->message ?? "",
                    'status'            => 1,
                ]);

                $confirm_booking = CarBooking::with('cars')->where('slug',$booking_data->slug)->first();
                $auth_column->delete();
                Notification::route("mail", $confirm_booking->email)->notify(new carBookingNotification($confirm_booking));
                if(auth()->check()){
                    $notification_content = [
                        'title'   => __("Booking"),
                        'message' => __("Your Booking (Car Model: ") . $confirm_booking->cars->car_model .
                                    __(", Car Number: ") . $confirm_booking->cars->car_number .
                                    __(", Pick-up Date: ") . ($confirm_booking->pickup_date ? Carbon::parse($confirm_booking->pickup_date)->format('d-m-Y') : '') .
                                    __(", Pick-up Time: ") . ($confirm_booking->pickup_time ? Carbon::parse($confirm_booking->pickup_time)->format('h:i A') : '') . __(") Successfully booked."),
                    ];
                    UserNotification::create([
                        'user_id'   => auth()->user()->id,
                        'message'   => $notification_content,
                    ]);
                }
            }catch (Exception $e) {
            return redirect()->route('frontend.car.booking.preview',$token)->with(['error' => [__('Something went wrong! Please try again')]]);
        }
        return redirect()->intended(route("frontend.find.car"))->with(['success' => [__('Congratulations! Car Booking Confirmed Successfully.')]]);
    }

    public function mailResendToken($token)
    {
        $temporary_data = TemporaryData::where("token", $token)->first();
        $form_data = json_decode(json_encode($temporary_data->value),true);
        $resend_code = generate_random_code();
        $form_data['verification_code'] = $resend_code;
        try {
            $temporary_data->update([
                'value'          => $form_data,
            ]);
            $data = [
                'verification_code' => $resend_code,
                'token' => $token,
            ];
            Notification::route("mail", $temporary_data->value->email)->notify(new bookingConfirmation((object) $data));
        } catch (Exception $e) {
            throw ValidationException::withMessages([
                'code'      => __("Something went wrong! Please try again."),
            ]);
        }
        return redirect()->route('frontend.car.booking.mail', $token)->with(['success' => [__('Mail OTP Resend Success!')]]);
    }
}
