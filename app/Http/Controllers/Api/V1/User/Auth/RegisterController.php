<?php

namespace App\Http\Controllers\Api\V1\User\Auth;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Traits\User\RegisteredUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin\Cars\CarBooking;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use App\Providers\Admin\BasicSettingsProvider;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    protected $basic_settings;

    use RegistersUsers, RegisteredUsers;

    public function __construct()
    {
        $this->basic_settings = BasicSettingsProvider::get();
    }
    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            return Response::error($validator->errors()->all(), []);
        }
        $validated = $validator->validate();
        $basic_settings                 = $this->basic_settings;
        $validated['email_verified']    = ($basic_settings->email_verification == 1) ? 0 : 1;
        $validated['password']          = Hash::make($validated['password']);
        $validated['username']          = make_username($validated['firstname'], $validated['lastname']);

        if (User::where("username", $validated['username'])->exists()) return Response::error([__('User already exists!')], [], 400);

        try {
            event(new Registered($user = $this->create($validated)));
        } catch (Exception $e) {
            return Response::error([__('Registration failed! Please try again')], ['user_info' => $validated], 500);
        }
        // get user with all information
        try {
            $user = User::find($user->id);
        } catch (Exception $e) {
            return Response::error([__('Failed to fetch user information. Please try again')], [], 500);
        }
        try {
            $token = $user->createToken("auth_token")->accessToken;
        } catch (Exception $e) {
            return Response::error([__('Failed to generate user token! Please try again')], [], 500);
        }
        return $this->registered($request, $user, $token);
    }

    public function validator(array $data)
    {
        $basic_settings = $this->basic_settings;
        $password_rule = "required|string|min:6";
        if ($basic_settings->secure_password) {
            $password_rule = ["required", Password::min(8)->letters()->mixedCase()->numbers()->symbols()->uncompromised()];
        }
        $agree_policy = $this->basic_settings->agree_policy == 1 ? 'required|in:on' : 'nullable';
        return Validator::make($data, [
            'firstname'     => 'required|string|max:60',
            'lastname'      => 'required|string|max:60',
            'email'         => 'required|string|email|max:150|unique:users,email',
            'password'      => $password_rule,
            'country'       => 'required|string',
            'mobile_code'    => 'required|string|max:10',
            'agree'         => $agree_policy,
        ]);
    }

    protected function guard()
    {
        return Auth::guard("api");
    }

    protected function create(array $data)
    {
        return User::create($data);
    }

    protected function registered(Request $request, $user, $token = null)
    {
        try {
            $mail_response = [];
            if ($user->email_verified == false) {
                $mail_response = AuthorizationController::sendCode($user);
            }
        } catch (Exception $e) {
            $user->delete();
            return Response::error([$e->getMessage()], [], 500);
        }
        return Response::success([__('User successfully registered')], [
            'token'     => $token,
            'user_info' => $user->only([
                'id',
                'firstname',
                'lastname',
                'username',
                'email',
                'country',
                'mobile_code',
                'email_verified',
            ]),
            'authorization' => [
                'status'    => count($mail_response) > 0 ? true : false,
                'token'     => $mail_response['token'] ?? "",
            ],
        ], 200);
    }
}
