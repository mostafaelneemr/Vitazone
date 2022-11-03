<?php

namespace App\Http\Controllers\Api\Vender;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Conversation;
use App\BusinessSetting;
use App\Message;
use Auth;
use App\Product;
use Mail;
use App\Mail\ConversationMailManager;

class ConversationController extends Controller
{
    public function index()
    {
        if (BusinessSetting::where('type', 'conversation_system')->first()->value == 1) {
            $conversations = Conversation::where('sender_id', auth('api')->user()->id)->orWhere('receiver_id', auth('api')->user()->id)->orderBy('created_at', 'desc')->get();
            return response()->json([
	            'conversations' => $conversations
	        ]);
        }
        return response()->json([
            'message' => 'Activate Conversation mode'
        ]);
    }
}
