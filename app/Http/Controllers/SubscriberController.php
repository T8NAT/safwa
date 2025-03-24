<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use App\Notifications\sendMessage;
use App\Models\Admin\UserNotification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;

class SubscriberController extends Controller
{
    public function index()
    {
        $page_title = __("Subscribers");
        $subscribers = Subscriber::orderBy('id', 'desc')->paginate(10);

        return view('admin.sections.subscriber.index',compact(
            'page_title',
            'subscribers'
        ));
    }

    public function reply(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'subject'   => 'required|string',
            'message'   => 'required|string',
            'id'        => 'required',
            'user_email' => 'required|email',
        ]);

        $id = $request->input('id');

        if($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $validated = $validator->validate();
        $data = Subscriber::find($id);
        if(!$data)
        {
            abort(404);
        }
        $formData = [
            'subject' => $validated['subject'],
            'message' => $validated['message'],
        ];

        $data->reply = 1;
        $data->save();
        $userEmail = $validated['user_email'];
        $user = User::where('email', $userEmail)->first();
        if ($user) {
            $notification_content = [
                'title'         => "Subscription",
                'message'       => "Check your mail",
            ];
            UserNotification::create([
                'user_id' => $user->id,
                'message'   => $notification_content,
            ]);
        }
        try{
            Notification::route('mail',$data->email)->notify(new sendMessage($formData));
        }catch(Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }
        return back()->with(['success' => [__('Email send successfully!')]]);
    }
}
