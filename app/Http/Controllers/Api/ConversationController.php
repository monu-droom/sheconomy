<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Conversation;
use App\BusinessSetting;
use App\Message;
use App\User;
use Auth;
use App\Product;
use Mail;
use App\Mail\ConversationMailManager;

class ConversationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $user_type = Product::findOrFail($request->product_id)->user->user_type;

        $conversation = new Conversation;
        $conversation->sender_id = $request->user_id;
        $conversation->receiver_id = Product::findOrFail($request->product_id)->user->id;
        $conversation->title = $request->title;

        if($conversation->save()) {
            $message = new Message;
            $message->conversation_id = $conversation->id;
            $message->user_id = $request->user_id;
            $message->message = $request->message;

            if ($message->save()) {
                $this->send_message_to_seller($conversation, $message, $user_type, $user);
            }
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Message has been send to seller',
        ]);
    }

    public function send_message_to_seller($conversation, $message, $user_type, $user)
    {
        $array['view'] = 'emails.conversation';
        $array['subject'] = 'Sender:- '.$user->name;
        $array['from'] = env('MAIL_USERNAME');
        $array['content'] = 'Hi! You recieved a message from '.$user->name.'.';
        $array['sender'] = $user->name;

        if($user_type == 'admin') {
            $array['link'] = route('conversations.admin_show', encrypt($conversation->id));
        } else {
            $array['link'] = route('conversations.show', encrypt($conversation->id));
        }

        $array['details'] = $message->message;

        try {
            Mail::to($conversation->receiver->email)->queue(new ConversationMailManager($array));
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}