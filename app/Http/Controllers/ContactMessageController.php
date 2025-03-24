<?php

namespace App\Http\Controllers;

use App\Models\Admin\UserNotification;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use App\Models\ContactMessage;
use App\Notifications\sendMessage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;


class ContactMessageController extends Controller
{
    public function index()
    {
        $page_title = __("Contact Messages");
        $messages = ContactMessage::orderBy('id', 'desc')->paginate(10);
        return view('admin.sections.contact.index',compact(
            'page_title',
            'messages'
        ));
    }

    public function messageDetails()
    {
        $page_title = __("Message Details");
        return view('admin.sections.contact.message-details', compact(
            'page_title',
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
        $data = ContactMessage::find($id);
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
                'title'         => "Message",
                'message'       => __("An Admin Has Replied to your message"),
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

    public function messageDelete(Request $request)
    {
        $request->validate([
            'target'    => 'required|string',
        ]);

        $message = ContactMessage::findOrFail($request->target);

        try{
            $message->delete();
        }catch(Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }

        return back()->with(['success' => [__('Message deleted successfully!')]]);
    }
}
