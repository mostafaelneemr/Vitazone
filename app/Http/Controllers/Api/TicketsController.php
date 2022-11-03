<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\SupportTicketController;
use App\Http\Resources\TicketCollection;
use App\Models\Ticket;
use App\TicketReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Upload;

class TicketsController extends Controller
{

    public function index()
    {

        $tickets = Ticket::where('user_id', \auth()->id())->with(['ticketreplies' => function ($query) {
            $query->select(['id', 'ticket_id', 'reply', 'files', 'created_at']);
        }])->latest()->get();
        $tickets = new TicketCollection($tickets);
        foreach ($tickets as $key => $ticket) {
            foreach ($ticket->ticketreplies as $key2 => $replie) {
                $tickets[$key]->ticketreplies[$key2]->files = json_decode($replie->files);
            }
        }
        return $tickets;
    }

    private function uploadToUploader($file)
    {
        $type = array(
            "jpg" => "image",
            "jpeg" => "image",
            "png" => "image",
            "svg" => "image",
            "webp" => "image",
            "gif" => "image",
            "mp4" => "video",
            "mpg" => "video",
            "mpeg" => "video",
            "webm" => "video",
            "ogg" => "video",
            "avi" => "video",
            "mov" => "video",
            "flv" => "video",
            "swf" => "video",
            "mkv" => "video",
            "wmv" => "video",
            "wma" => "audio",
            "aac" => "audio",
            "wav" => "audio",
            "mp3" => "audio",
            "zip" => "archive",
            "rar" => "archive",
            "7z" => "archive",
            "doc" => "document",
            "txt" => "document",
            "docx" => "document",
            "pdf" => "document",
            "csv" => "document",
            "xml" => "document",
            "ods" => "document",
            "xlr" => "document",
            "xls" => "document",
            "xlsx" => "document"
        );

        if ($file) {
            $upload = new Upload;
            $upload->extension = strtolower($file->getClientOriginalExtension());

            if (isset($type[$upload->extension])) {
                $upload->file_original_name = null;
                $arr = explode('.', $file->getClientOriginalName());
                for ($i = 0; $i < count($arr) - 1; $i++) {
                    if ($i == 0) {
                        $upload->file_original_name .= $arr[$i];
                    } else {
                        $upload->file_original_name .= "." . $arr[$i];
                    }
                }
                $upload->file_name = $file->store('uploads/all');
                $upload->user_id = auth('api')->user()->id;
                $upload->type = $type[$upload->extension];
                $upload->file_size = $file->getSize();
                $upload->save();
            }
            return $upload->id;
        }
    }

    public function store(Request $request)
    {
        // return $request;
        $request->validate([
            'subject' => 'required'
        ]);
        $ticket = new \App\Ticket;
        $ticket->code = max(100000, (Ticket::latest()->first() != null ? Ticket::latest()->first()->code + 1 : 0)) . date('s');
        $ticket->user_id = Auth::user()->id;
        $ticket->subject = $request->subject;
        $ticket->details = $request->details;
        $files = array();
        if ($request->hasFile('attachments')) {
            foreach ($request->attachments as $key => $attachment) {
                array_push($files, $this->uploadToUploader($attachment));
            }
            $ticket->files = implode(',', $files);
        }

        if ($ticket->save()) {
            $support = new SupportTicketController();
            $support->send_support_mail_to_admin($ticket);
            return response()->json(['success' => true, 'message' => trans('messages.Ticket is successfully submitted')], 200);
        }

        return response()->json(['success' => false, 'message' => trans('messages.some thing is wrong')], 200);
    }

    public function replay(Request $request)
    {
        $request->validate([
            'reply' => 'required',
            'ticket_id' => 'required|exists:tickets,id',
        ]);
        $ticket_reply = new TicketReply;
        $ticket_reply->ticket_id = $request->ticket_id;
        $ticket_reply->user_id = Auth::user()->id;
        $ticket_reply->reply = $request->reply;
        $files = array();
        if ($request->hasFile('attachments')) {
            foreach ($request->attachments as $key => $attachment) {
                array_push($files, $this->uploadToUploader($attachment));
            }
            $ticket_reply->files = implode(',', $files);
        }

        $ticket_reply->ticket->viewed = 0;
        $ticket_reply->ticket->status = 'pending';
        $ticket_reply->ticket->save();
        if ($ticket_reply->save()) {
            return response()->json(['success' => true, 'message' => trans('messages.Reply has been sent successfully')], 200);
        } else {
            return response()->json(['success' => false, 'message' => trans('messages.some thing is wrong')], 200);
        }
    }

    public function getTicket($id)
    {
        $ticket = Ticket::with(['ticketreplies' => function ($query) {
            $query->select(['id', 'user_id', 'ticket_id', 'reply', 'files', 'created_at'])->orderBy('created_at','DESC');
        }])->findOrFail($id);
        $ticket->files = $this->convertPhotos(explode(',', $ticket->files));
        $ticket->ticketreplies = $this->ticketreplies($ticket->ticketreplies);
        return response()->json(['success' => true, 'data' => $ticket], 200);
    }

    protected function ticketreplies($ticketreplies)
    {
        foreach ($ticketreplies as $key => $ticketreply) {
            $ticketreplies[$key]['files'] = $this->convertPhotos(explode(',', $ticketreply->files));
        }
        return $ticketreplies;
    }

    protected function convertPhotos($data)
    {
        $result = [];
        foreach ($data as $key => $item) {
            $file_detail = \App\Upload::where('id', $item)->first();
            if ($file_detail) {
                $result[$key]['path'] = api_asset($item);
                $result[$key]['name'] = $file_detail->file_original_name . '.' . $file_detail->extension;
            }
        }
        return $result;
    }
}
