<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class NotificationsController extends Controller
{
    public function notificationList()
    {
        $notifications = Notification::where('reciever_id', Auth::user()->id)->orderBy('id', 'desc')->get();
        $data['success'] = true;
        $data['data'] = $notifications;
        return response()->json($data);
    }

    public function markAsRead($id)
    {
        $notification = Notification::find($id);
        $notification->is_read = true;
        $notification->save();
        $data['success'] = true;
        return response()->json($data);
    }

    public function destroy($id)
    {
        Notification::destroy($id);
        return response()->json(['message' => trans('messages.Deleted successfully')], 200);
    }
}
