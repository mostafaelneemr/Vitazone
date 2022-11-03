<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Subscriber;
use Mail;
use App\Mail\EmailManager;

class NewsletterController extends Controller
{
    public function index(Request $request)
    {
        $users = User::all();
        $subscribers = Subscriber::all();
        return view('newsletters.index', compact('users', 'subscribers'));
    }

    public function firNotification()
    {
        return view('newsletters.firNotification');
    }

    public function sendFirNotification(Request $request)
    {
        $this->sendTobicNotification('fire_notification_ar', $request->subject_ar, $request->content_ar, uploaded_asset($request->banner_ar),$request->link);
        $this->sendTobicNotification('fire_notification_en', $request->subject_en, $request->content_en, uploaded_asset($request->banner_en),$request->link);
        flash(translate('Notifications has been sent.'))->success();
        return redirect()->route('newsletters.firNotification');
    }

    private function sendTobicNotification($fire_notification, $subject, $content, $banner,$link)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $api_key = 'Key=AAAAH4ld0dc:APA91bGRGtGqkh3J7KAIuNAgXkY6rsrtg7fPT2j0dGtYRNIMq2evkERtwCaD_uPovdAVu7JBL0K7jzNI5kVS3DyNLfzujWHs_OxfdbnQindYinfYCPYxKipj2__xWGXsjwWPSqT7zDPb';
        $fields = array(
            'to' => '/topics/' . $fire_notification,
            'data' => array(
                "type" => 'fire_notification',
            ),
            'notification' => array(
                "type" => 'fire_notification',
                "link" => $link,
                "title" => $subject,
                "text" => $content,
                "body" => $content,
                "image" => $banner,
                "click_action" => 'HomeActivity',
                "sound" => true,
                "icon" => "logo",
                "android_channel_id" => "fcm_default_channel",
                "high_priority" => "high",
                "show_in_foreground" => true
            ),
            'android' => array(
                "priority" => "high"
            ),
            'priority' => 10
        );
        $headers = array(
            'Content-Type:application/json',
            'Authorization:' . $api_key
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('FCM Send Error: ' . curl_error($ch));
        }
        curl_close($ch);
        return true;
    }

    public function send(Request $request)
    {
        if (env('MAIL_USERNAME') != null) {
            // return $request;
            if ($request->has('user_emails')) {
                foreach ($request->user_emails as $key => $email) {
                    $array['view'] = 'emails.newsletter';
                    $array['subject'] = $request->subject;
                    $array['from'] = env('MAIL_USERNAME');
                    $array['content'] = $request->content;

                    try {
                        Mail::to($email)->queue(new EmailManager($array));
                    } catch (\Exception $e) {
                        // dd($e);
                    }
                }
            }

            //sends newsletter to subscribers
            if ($request->has('subscriber_emails')) {
                foreach ($request->subscriber_emails as $key => $email) {
                    $array['view'] = 'emails.newsletter';
                    $array['subject'] = $request->subject;
                    $array['from'] = env('MAIL_USERNAME');
                    $array['content'] = $request->content;

                    try {
                        Mail::to($email)->queue(new EmailManager($array));
                    } catch (\Exception $e) {
                        // dd($e);
                    }
                }
            }
        } else {
            flash(translate('Please configure SMTP first'))->error();
            return back();
        }

        flash(translate('Newsletter has been send'))->success();
        return redirect()->route('admin.dashboard');
    }

    public function testEmail(Request $request)
    {
        $array['view'] = 'emails.newsletter';
        $array['subject'] = "SMTP Test";
        $array['from'] = env('MAIL_USERNAME');
        $array['content'] = "This is a test email.";

        try {
            Mail::to($request->email)->queue(new EmailManager($array));
        } catch (\Exception $e) {
            dd($e);
        }

        flash(translate('An email has been sent.'))->success();
        return back();
    }
}
