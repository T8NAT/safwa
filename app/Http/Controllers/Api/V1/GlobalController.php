<?php

namespace App\Http\Controllers\Api\V1;

use Exception;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TemporaryData;
use App\Constants\GlobalConst;
use App\Http\Helpers\Response;
use App\Models\Admin\Cars\Car;
use App\Models\Admin\Cars\CarArea;
use App\Models\Admin\Cars\CarType;
use App\Models\Admin\SiteSections;
use App\Constants\SiteSectionConst;
use App\Http\Controllers\Controller;
use App\Models\Admin\Cars\CarBooking;
use App\Models\Admin\UserNotification;
use Illuminate\Support\Facades\Validator;
use App\Notifications\bookingConfirmation;
use App\Models\Admin\Schedules\ScheduleDay;
use Illuminate\Support\Facades\Notification;
use App\Models\Admin\Schedules\DailySchedule;
use App\Notifications\carBookingNotification;
use App\Providers\Admin\BasicSettingsProvider;

class GlobalController extends Controller
{
    public function carArea()
    {
        $car_area = CarArea::all();
        $message = [__('Car Area Fetched Successfully!')];
        return Response::success($message, $car_area);
    }
    public function carType()
    {
        $car_type = CarType::all();
        $message = [__('Car Type Fetched Successfully!')];
        return Response::success($message, $car_type);
    }
    public function viewCar()
    {
        $cars = Car::where('status', true)
            ->whereHas('type', function ($query) {
                $query->where('status', true);
            })
            ->whereHas('branch', function ($query) {
                $query->where('status', true);
            })
            ->where(function ($query) {
                $query->whereHas('bookings', function ($subquery) {
                    $subquery->where('status', '=', 3)->orWhere('status', '=', 1);
                })->orWhereDoesntHave('bookings');
            })
            ->get();
        $car_data = [
            'base_url'  => url('/'),
            'image_path' => files_asset_path_basename("site-section"),
            'cars'      => $cars,
        ];
        $message = [__('Cars Fetched Successfully!')];
        return Response::success($message, ['cars' => $car_data], 200);
    }
    public function getAreaTypes(Request $request)
    {
        $validator    = Validator::make($request->all(), [
            'area'  => 'required|integer',
        ]);
        if ($validator->fails()) {
            return Response::error($validator->errors()->all());
        }
        $area = CarArea::with(['types' => function ($type) {
            $type->with(['type' => function ($car_type) {
                $car_type->where('status', true);
            }]);
        }])->find($request->area);
        if (!$area) return Response::error([__('Area Not Found')], 404);

        return Response::success([__('Types fetch successfully')], ['area' => $area], 200);
    }
    public function searchCar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'area'   => 'nullable',
            'type'   => 'nullable',
        ]);
        if ($validator->fails()) {
            return Response::error($validator->errors()->all());
        }
        if ($request->area && $request->type) {

            $cars = Car::where('car_area_id', $request->area)
                ->where('car_type_id', $request->type)
                ->where('status', true)
                ->where(function ($query) {
                    $query->whereHas('bookings', function ($subquery) {
                        $subquery->where('status', '=', 3)->orWhere('status', '=', 1);
                    })->orWhereDoesntHave('bookings');
                })
                ->get();
        } else {
            $cars = Car::where('status', true)
                ->whereHas('type', function ($query) {
                    $query->where('status', true);
                })
                ->whereHas('branch', function ($query) {
                    $query->where('status', true);
                })
                ->where(function ($query) {
                    $query->whereHas('bookings', function ($subquery) {
                        $subquery->where('status', '=', 3)->orWhere('status', '=', 1);
                    })->orWhereDoesntHave('bookings');
                })
                ->get();
        }
        $car_data = [
            'base_url'  => url('/'),
            'image_path' => files_asset_path_basename("site-section"),
            'cars'      => $cars,
        ];
        return Response::success([__('Types fetch successfully')], ['cars' => $car_data], 200);
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
            return Response::error($validator->errors()->all(), []);
        }
        $validated = $validator->validate();
        $pickupDateTime = Carbon::parse($validated['pickup_date'] . ' ' . $validated['pickup_time']);
        if ($pickupDateTime->isPast()) {
            return Response::error([__('Pickup date and time must be in the future.')], []);
        }

        if (!empty($validated['round_pickup_date']) && !empty($validated['round_pickup_time'])) {
            $roundPickupDateTime = Carbon::parse($validated['round_pickup_date'] . ' ' . $validated['round_pickup_time']);
            if ($roundPickupDateTime->isPast()) {
                return Response::error([__('Round pickup date and time must be in the future.')], []);
            }
            if ($roundPickupDateTime->lte($pickupDateTime)) {
                return Response::error([__('Round pickup date and time must be greater than pickup date and time.')], []);
            }
        }
        $validated['email'] = $validated['credentials'];
        $validated['phone'] = $validated['mobile'];
        $validated['slug']  = Str::uuid();
        $car_slug = $validated['car'];
        $findCar = Car::where('slug', $car_slug)->first();
        if (!$findCar) {
            return Response::error([__('Car not found!')], [], 404);
        }

        if (auth()->guard('api')->check()) {
            $validated['user_id'] = auth()->guard('api')->user()->id;
        } else {
            $validated['user_id'] = null;
        }

        $validated['car_id'] = $findCar->id;

        $already_booked_car = CarBooking::where('car_id', $findCar->id)
            ->where('pickup_date', $validated['pickup_date'])
            ->count();
        if ($already_booked_car  > 0) {
            return Response::error([__('This car is already booked at the selected date.')], []);
        }

        try {
            $car_booking = TemporaryData::create([
                'token' => generate_unique_string("temporary_datas", "token", 20),
                'value' => $validated,
            ]);
            return Response::success([__('Booking data stored in the temporary table')], ['token' => $car_booking->token, 'data' => $validated], 200);
        } catch (Exception $e) {
            return Response::error(['error' => [__('Something Went Wrong! Please try again.')]], [], 500);
        }
    }
    public function confirm(Request $request)
    {
        $temp_booking =  TemporaryData::where('token', $request->token)->first();
        $temp_data = json_decode(json_encode($temp_booking->value), true);
        $send_code = generate_random_code();
        $temp_data['verification_code'] = $send_code;
        $car = Car::where('id', $temp_booking->value->car_id)->first();
        if (!$temp_booking)  return Response::error([__('Booking data not found!')], [], 404);
        $data = [
            'verification_code' => $send_code,
            'token'     => $request->token,
        ];
        try {
            $temp_booking->update([
                'value' => $temp_data,
            ]);
            Notification::route("mail", $temp_booking->value->email)->notify(new bookingConfirmation((object) $data));
        } catch (Exception $e) {
            return Response::error(['error' => [__('Something Went Wrong! Please try again.')]], [], 500);
        }
        return Response::success([__('Please check your email to get the OTP')], ['token' => $request->token, 'data' => $temp_booking->value], 200);
    }
    public function mailVerify(Request $request)
    {
        $request->merge(['token' => $request->token]);
        $request->validate([
            'token'     => "required|string|exists:temporary_datas,token",
            'code'      => "required",
        ]);
        $temp_data = TemporaryData::where('token', $request->token)->first();
        $temporary_data = json_decode(json_encode($temp_data->value), true);

        if (!isset($temporary_data['verification_code'])) {
            return Response::error([__('Verification code not found in temporary data')], [], 404);
        }
        // $code = explode($request->code);
        $otp_exp_sec = BasicSettingsProvider::get()->otp_exp_seconds ?? GlobalConst::DEFAULT_TOKEN_EXP_SEC;
        $auth_column = TemporaryData::where("token", $request->token)->where('value->verification_code', $request->code)->first();

        if (!$auth_column) {
            return Response::error([__('Invalid OTP Code.')], []);
        }
        if ($auth_column->created_at->addSeconds($otp_exp_sec) < now()) {
            return Response::error(['error' => [__('Session expired. Please try again')]], []);
        }

        try {
            $booking_data = CarBooking::create([
                'car_id'    => $temp_data->value->car_id,
                'user_id'   => auth()->guard('api')->user()->id ?? null,
                'slug'      => $temp_data->value->slug,
                'phone'     => $temp_data->value->phone,
                'email'     => $temp_data->value->email,
                'trip_id'   => generate_unique_code(),
                'location'  => $temp_data->value->location,
                'destination' => $temp_data->value->destination,
                'pickup_time'   => $temp_data->value->pickup_time,
                'round_pickup_time' => $temp_data->value->round_pickup_time,
                'pickup_date'   => $temp_data->value->pickup_date,
                'round_pickup_date' => $temp_data->value->round_pickup_date,
                'message'           => $temp_data->value->message ?? "",
                'status'            => 1,
            ]);

            $confirm_booking = CarBooking::with('cars')->where('slug', $booking_data->slug)->first();
            $auth_column->delete();
            Notification::route("mail", $confirm_booking->email)->notify(new carBookingNotification($confirm_booking));
            if (auth()->guard('api')->check()) {
                $notification_content = [
                    'title'   => __("Booking"),
                    'message' => __("Your Booking (Car Model: ") . $confirm_booking->cars->car_model .
                        __(", Car Number: ") . $confirm_booking->cars->car_number .
                        __(", Pick-up Date: ") . ($confirm_booking->pickup_date ? Carbon::parse($confirm_booking->pickup_date)->format('d-m-Y') : '') .
                        __(", Pick-up Time: ") . ($confirm_booking->pickup_time ? Carbon::parse($confirm_booking->pickup_time)->format('h:i A') : '') . __(") Successfully booked."),
                ];
                UserNotification::create([
                    'user_id'   => auth()->guard('api')->user()->id,
                    'message'   => $notification_content,
                ]);
            }
        } catch (Exception $e) {
            return Response::error(['error' => [__('Something Went Wrong! Please try again.')]], [], 500);
        }
        return Response::success(['success' => [__('Congratulations! Car Booking Confirmed Successfully.')]], [], 200);
    }
    public function mailResendToken(Request $request)
    {
        $temporary_data = TemporaryData::where("token", $request->token)->first();
        $form_data = json_decode(json_encode($temporary_data->value), true);
        $resend_code = generate_random_code();
        $form_data['verification_code'] = $resend_code;
        try {
            $temporary_data->update([
                'created_at' => now(),
                'value' => $form_data,
            ]);
            $data = [
                'verification_code' => $resend_code,
                'token' => $request->token,
            ];
            Notification::route("mail", $temporary_data->value->email)->notify(new bookingConfirmation((object) $data));
        } catch (Exception $e) {
            return Response::error(['error' => [__('Something Went Wrong! Please try again.')]], [], 500);
        }
        return Response::success(['success' => [__('Mail OTP Resend Success!')]], ['token' => $request->token], 200);
    }
}
