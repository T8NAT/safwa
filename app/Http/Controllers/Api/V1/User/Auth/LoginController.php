<?php

namespace App\Http\Controllers\Api\V1\User\Auth;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Constants\GlobalConst;
use App\Http\Helpers\Response;
use App\Traits\User\LoggedInUsers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\AuthenticatesUsers;


class LoginController extends Controller
{
    protected $request_data;

    use AuthenticatesUsers, LoggedInUsers;

    public function login(Request $request)
    {
        $this->request_data = $request;

        $validator = Validator::make($request->all(),[
            'credentials'   => 'required|string',
            'password'      => 'required|string',
        ]);
        if($validator->fails()) {
            return Response::error($validator->errors()->all(),[]);
        }
        $validated = $validator->validate();

        if(!User::where($this->username(),$validated['credentials'])->exists()) {
            
            return Response::error([__("User doesn't exists!")],[],404);
        }
        $user = User::where($this->username(),$validated['credentials'])->first();

        if(!$user) return Response::error([__("User doesn't exists!")]);

        if(Hash::check($validated['password'],$user->password)) {
            if($user->status != GlobalConst::ACTIVE) return Response::error([__("Your account is temporary banded. Please contact with system admin")]);
            // User authenticated
            $token = $user->createToken("auth_token")->accessToken;
            return $this->authenticated($request,$user,$token);
        }
        return Response::error([__("Credentials didn't match")]);
    }

    protected function credentials(Request $request)
    {
        $request->merge(['status' => true]);
        $request->merge([$this->username() => $request->credentials]);
        return $request->only($this->username(),'password','status');
    }

    public function username()
    {
        $request = $this->request_data->all();
        $credentials = $request['credentials'];
        if(filter_var($credentials,FILTER_VALIDATE_EMAIL)){
            return "email";
        }
        return "username";
    }

    public function guard()
    {
        return Auth::guard("api");
    }

    protected function authenticated(Request $request, $user, $token = null)
    {
        try{
            $mail_response = [];
            if($user->email_verified == false){
                $mail_response = AuthorizationController::sendCode($user);
            }
        }catch(Exception $e){
            return Response::error([$e->getMessage()],[],500);
        }

        $this->createLoginLog($user);

        return Response::success([__('User Successfully Logged In')],[
            'token'     => $token,
            'user_info' => $user->only([
                'id',
                'firstname',
                'lastname',
                'username',
                'email',
                'email_verified',
            ]),
            'authorization' =>[
                'status'    =>count($mail_response) > 0 ? true : false,
                'token'     =>$mail_response['token'] ?? "",
            ],
        ],200);
    }
}

