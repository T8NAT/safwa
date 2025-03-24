<?php

namespace App\Http\Controllers\Api\V1;

use App\Constants\GlobalConst;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Response;
use App\Http\Resources\ServiceResource;
use App\Models\Admin\Coupons\Coupon;
use App\Models\Admin\Services\Service;
use App\Models\Admin\Services\ServiceArea;
use App\Models\Admin\Services\ServiceBooking;
use App\Models\Admin\Services\ServiceType;
use App\Models\Admin\UserNotification;
use App\Models\TemporaryData;
use App\Notifications\bookingConfirmation;
use App\Notifications\carBookingNotification;
use App\Notifications\ServiceBookingNotification;
use App\Providers\Admin\BasicSettingsProvider;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function serviceArea()
    {
        $service_area = ServiceArea::all();
        $message = [__('Service Area Fetched Successfully!')];
        return Response::success($message, $service_area);
    }
    public function serviceType()
    {
        $car_type = ServiceType::all();
        $message = [__('Service Type Fetched Successfully!')];
        return Response::success($message, $car_type);
    }
    public function viewService()
    {
        $services = Service::where('status', true)
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
        $service_data = [
            'base_url'  => url('/'),
            'services'=> ServiceResource::collection($services),
        ];
        $message = [__('Services Fetched Successfully!')];
        return Response::success($message, ['services' => $service_data], 200);
    }
    public function getAreaTypes(Request $request)
    {
        $validator    = Validator::make($request->all(), [
            'area'  => 'required|integer',
        ]);
        if ($validator->fails()) {
            return Response::error($validator->errors()->all());
        }
        $area = ServiceArea::with(['types' => function ($type) {
            $type->with(['type' => function ($car_type) {
                $car_type->where('status', true);
            }]);
        }])->find($request->area);
        if (!$area) return Response::error([__('Area Not Found')], 404);

        return Response::success([__('Types fetch successfully')], ['area' => $area], 200);
    }
    public function searchService(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'area'   => 'nullable',
            'type'   => 'nullable',
        ]);
        if ($validator->fails()) {
            return Response::error($validator->errors()->all());
        }
        if ($request->area && $request->type) {

            $services = Service::where('service_area_id', $request->area)
                ->where('service_type_id', $request->type)
                ->where('status', true)
                ->where(function ($query) {
                    $query->whereHas('bookings', function ($subquery) {
                        $subquery->where('status', '=', 3)->orWhere('status', '=', 1);
                    })->orWhereDoesntHave('bookings');
                })
                ->get();
        } else {
            $services = Service::where('status', true)
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
        $service_data = [
            'base_url'  => url('/'),
//            'image_path' => files_asset_path_basename("site-section"),
            'services'=> ServiceResource::collection($services),
        ];
        return Response::success([__('Types fetch successfully')], ['services' => $service_data], 200);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service'            => 'required',
            'location'           => 'required',
            'destination'        => 'required',
            'credentials'        => 'required|email',
            'pickup_time'        => 'required',
            'pickup_date'        => 'required',
            'mobile'             => 'nullable',
            'round_pickup_date'  => 'nullable',
            'round_pickup_time'  => 'nullable',
            'message'            => 'nullable',
            'coupon'             => 'nullable|string|exists:coupons,code'
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
        $service_slug = $validated['service'];
        $findService = Service::where('slug', $service_slug)->first();
        if (!$findService) {
            return Response::error([__('Service not found!')], [], 404);
        }

        if (auth()->guard('api')->check()) {
            $validated['user_id'] = auth()->guard('api')->user()->id;
        } else {
            $validated['user_id'] = null;
        }

        $validated['service_id'] = $findService->id;

        $coupon = null;
        $discount_amount = 0;
        $original_price = $findService->price;

        if ($validated['coupon']) {
            $coupon = Coupon::query()->where('code', $validated['coupon'])
                ->where('status', true)
                ->where('expiry_date', '>=', Carbon::now()->toDateString())
                ->first();

            if (!$coupon) {
                return Response::error([__('Invalid or expired coupon code.')], [], 422);
            }

            $discount_amount = ($original_price * $coupon->discount) / 100;

            $discount_amount = min($discount_amount, $original_price);
        }
        $final_price = $original_price - $discount_amount;
        $validated['original_price'] = $original_price;
        $validated['discount_amount'] = $discount_amount;
        $validated['final_price'] = $final_price;

        $already_booked_service = ServiceBooking::where('service_id', $findService->id)
            ->where('pickup_date', $validated['pickup_date'])
            ->count();
        if ($already_booked_service  > 0) {
            return Response::error([__('This service is already booked at the selected date.')], []);
        }

        try {
            $service_booking = TemporaryData::create([
                'token' => generate_unique_string("temporary_datas", "token", 20),
                'value' => $validated,
            ]);
            return Response::success([__('Booking data stored in the temporary table')], ['token' => $service_booking->token, 'data' => $validated], 200);
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
        $service = Service::where('id', $temp_booking->value->service_id)->first();
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
            $booking_data = ServiceBooking::create([
                'service_id'    => $temp_data->value->service_id,
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
                'original_price' => $temp_data->value->original_price,
                'discount_amount' => $temp_data->value->discount_amount,
                'final_price' => $temp_data->value->final_price,
            ]);

            $confirm_booking = ServiceBooking::with('service')->where('slug', $booking_data->slug)->first();
            $auth_column->delete();
            Notification::route("mail", $confirm_booking->email)->notify(new ServiceBookingNotification($confirm_booking));
            if (auth()->guard('api')->check()) {
                $notification_content = [
                    'title'   => __("Booking"),
                    'message' => __("Your Booking (Service Name: ") . $confirm_booking->service->name .
                        __(", Service Type: ") . $confirm_booking->service->type->name .
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
        return Response::success(['success' => [__('Congratulations! Service Booking Confirmed Successfully.')]], [], 200);
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
