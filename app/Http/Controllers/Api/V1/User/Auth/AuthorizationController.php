<?php

namespace App\Http\Controllers\Api\V1\User\Auth;

use Exception;
use Illuminate\Http\Request;
use App\Constants\GlobalConst;
use App\Http\Helpers\Response;
use Illuminate\Support\Carbon;
use App\Models\UserAuthorization;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Cars\CarBooking;
use App\Models\User;
use App\Traits\ControlDynamicInputFields;
use Illuminate\Support\Facades\Validator;
use App\Providers\Admin\BasicSettingsProvider;
use App\Notifications\User\Auth\SendAuthorizationCode;

class AuthorizationController extends Controller
{
    use ControlDynamicInputFields;

    public static function sendCode($user = null)
    {
        if(!$user && auth()->guard("api")->check() == false){
            throw new Exception(__("Access Denied! Unauthenticated"));
        }
        if(!$user){
            $user = auth()->guard("api")->user();
        }
        $data = [
            'user_id'   => $user->id,
            'code'      => generate_random_code(),
            'token'     => generate_unique_string("user_authorizations","token",200),
            'created_at'=> now(),
        ];

        DB::beginTransaction();
        try{
            UserAuthorization::where('user_id',$user->id)->delete();
            DB::table("user_authorizations")->insert($data);
            $user->notify(new SendAuthorizationCode((object) $data));
            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
            throw new Exception(__("Something Went Wrong! Please try again"));
        }
        return $data;
    }

    public function resendCode(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'token' => "required|string|exists:user_authorizations,token"
        ]);

        if($validator->fails()){
            return Response::error($validator->errors()->all(),[]);
        }

        $validated = $validator->validate();

        $user_authorize = UserAuthorization::where("token", $validated['token'])->first();

        if(!$user_authorize) return Response::error([__("Request token is invalid")],[],404);

        if(Carbon::now() <= $user_authorize->created_at->addMinutes(GlobalConst::USER_PASS_RESEND_TIME_MINUTE)){
            return Response::error([__('You can resend verification code after').Carbon::now()->diffInSeconds($user_authorize->created_at->addMinutes(GlobalConst::USER_PASS_RESEND_TIME_MINUTE)). __(' seconds')],['token' => $validated['token'], 'wait_time' =>(string)Carbon::now()->diffInSeconds($user_authorize->created_at->addMinutes(GlobalConst::USER_PASS_RESEND_TIME_MINUTE))],400);
        }

        $resend_code = generate_random_code();
        try {
            $user_authorize->update([
                'code'          => $resend_code,
                'created_at'    => now(),
            ]);

            $data = $user_authorize->toArray();
            $user_authorize->user->notify(new SendAuthorizationCode((object) $data));
        } catch (Exception $e) {
            return Response::error([__('Something went wrong! Please try again')],[$data],500);
        }
        return Response::success([__('Verification code resend successfully!')],['token' => $validated['token'],'code' => $resend_code,'wait_time' =>""],200);
    }

    public function verifyCode(Request $request){
        $validator = Validator::make($request->all(),[
            'token' =>"required|string|exists:user_authorizations,token",
            'code'  =>"required|integer",
        ]);

        if($validator->fails()) return Response::error($validator->errors()->all(),[],400);

        $validated = $validator->validate();

       if(!UserAuthorization::where("code",$request->code)->exists()){
        return Response::error([__("Invalid OTP, Please try again")],[],404);
       }

       $otp_exp_sec = BasicSettingsProvider::get()->otp_exp_seconds ?? GlobalConst::DEFAULT_TOKEN_EXP_SEC;
       $auth_column = UserAuthorization::where("token",$request->token)->where("code",$request->code)->first();
       if($auth_column->created_at->addSeconds($otp_exp_sec) < now()){
        $auth_column->delete();
        $this->authLogout($request);
        return Response::error([__("Session expired. Please try again")],[],440);
       }

       try{
        $auth_column->user->update([
            'email_verified' => true,
        ]);
       }catch(Exception $e){
            $auth_column->delete();
            $this->authLogout($request);
            return Response::error([__("Something went wrong! Please try again")],[],500);
       }
       $user = UserAuthorization::where("code",$request->code)->first();
       $user_id = $user->user_id;
       $user_info = User::where("id", $user_id)->first();
       $user_email = $user_info->email;

       CarBooking::where('email', $user_email)->update(['user_id' => $user_id]);
       return Response::success([__("Account Successfully verified")],[],200);
    }

    public function authLogout(Request $request) {
        $user_token = Auth::guard(get_auth_guard())->user()->token();
        $user_token->revoke();
    }

}
