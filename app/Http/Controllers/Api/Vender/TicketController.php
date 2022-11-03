<?php

namespace App\Http\Controllers\Api\Vender;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Ticket;
use App\User;
use Auth;
use App\TicketReply;
use App\Mail\SupportMailManager;
use Mail;


class TicketController extends Controller
{
    public function index()
    {
    	$tickets = Ticket::where('user_id', auth('api')->user()->id)->orderBy('created_at', 'desc')->get();
        return response()->json([
            'tickets' => $tickets
        ]);
    }

    public function store(Request $request)
    {

        $ticket = new Ticket;
        $ticket->code = max(100000, (Ticket::latest()->first() != null ? Ticket::latest()->first()->code + 1 : 0)).date('s');
        $ticket->user_id = auth('api')->user()->id;
        $ticket->subject = $request->subject;
        $ticket->details = $request->details;

        if($request->attachments){
            $files = array();

        // if($request->hasFile('attachments')){
        //     foreach ($request->attachments as $key => $attachment) {
                $item['name'] = $request->attachments->getClientOriginalName();
                $item['path'] = $request->attachments->store('uploads/support_tickets/');
                array_push($files, $item);
            // }
            $ticket->files = json_encode($files);
        // }
        }

        $ticket->save();

        return response()->json([
            'ticket' => $ticket
        ]);
    }
}
