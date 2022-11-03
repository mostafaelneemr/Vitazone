<?php

namespace App\Http\Controllers;

use App\User;
use App\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return $_GET['search'];
        if (isset($_GET['search']) && $_GET['search'] != null) {
            $this->search = $_GET['search'];
            $wallets =  Wallet::select(['wallets.*', 'users.id as user_id', 'users.name as username'])
                ->join('users', function ($join) {
                    $join->on('wallets.user_id', '=', 'users.id')
                        ->where('users.name', 'like', "%{$this->search}%");
                })->orderBy('created_at', 'desc')->paginate(9);
            // $wallets = Wallet::with(['user' => function($q){
            //     $q->where('name', 'like', "%{$this->search}%");
            // }])->orderBy('created_at','desc')->paginate(9);
        } else {
            $wallets = Wallet::with('user')->orderBy('created_at', 'desc')->paginate(9);
        }
        //    return $wallets;
        return view('wallets.index', compact('wallets'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::user()->user_type == 'admin' || in_array('23', json_decode(Auth::user()->staff->role->permissions))) {
            $users = User::where('user_type', 'customer')->select('id', 'name')->get();
            // return $users;
            return view('wallets.add', compact('users'));
        } else {
            flash(translate('You not have this permission'))->error();
            return redirect('admin/wallets');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::user()->user_type == 'admin' || in_array('18', json_decode(Auth::user()->staff->role->permissions))) {
            $user = User::find($request->user_id);
            $wallet = new Wallet;
            $wallet->user_id = $user->id;
            $wallet->amount = $request->amount;
            $wallet->payment_method = 'from_admin';
            $wallet->approval = 1;
            $wallet->payment_details = 'Charged By admin';
            $wallet->save();
            $user1 = $wallet->user;
            $user1->balance = $user->balance + $wallet->amount;
            $user1->save();
            if ($user1->device_token != null) {
                if ($user1->lang = 'ar') {
                    $notification_title = 'تم شحن محفظتك';
                    $notification_text = ' تم شحن محفظتك بمبلغ' . $wallet->amount;
                } else {
                    $notification_title = 'Your wallet charged';
                    $notification_text = 'Your wallet charged with' . $wallet->amount;
                }
                $notification_body['reciever_id'] = $user1->id;
                $notification_body['type'] = 'wallet';
                $notification_body['user_id'] = $user1->id;
                $notification_body['title'] = $notification_title;
                $notification_body['text'] = $notification_text;
                $notification_body['body'] = $notification_text;
                $notification_body['click_action'] = 'MedicalApp';
                $notification_body['sound'] = true;
                $notification_body['icon'] = 'logo';
                $notification_body['android_channel_id'] = 'android_channel_id';
                $notification_body['high_priority'] = 'high_priority';
                $notification_body['show_in_foreground'] = true;
                sendNotification($notification_body, $user1->device_token);
            }
            flash(translate('Wallet charged successfuly'))->success();
        } else {
            flash(translate('You not have this permission'))->error();
        }

        return redirect('admin/wallets');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Auth::user()->user_type == 'admin' || in_array('18', json_decode(Auth::user()->staff->role->permissions))) {
            $wallet = Wallet::with('user')->findOrFail(decrypt($id));
            $user = User::findOrFail($wallet->user_id);
            $user->balance = $user->balance + $wallet->amount;
            $user->save();
            $wallet->approval = 1;
            $wallet->save();
            flash(translate('Charge Approved successfuly'))->success();
        } else {
            flash(translate('You not have this permission'))->error();
        }

        return redirect('admin/wallets');
    }

    public function reject($id)
    {
        if (Auth::user()->user_type == 'admin' || in_array('18', json_decode(Auth::user()->staff->role->permissions))) {
            $wallet = Wallet::with('user')->findOrFail(decrypt($id));
            $wallet->approval = 2;
            $wallet->save();
            flash(translate('Charge Rejected successfuly'))->success();
        } else {
            flash(translate('You not have this permission'))->error();
        }

        return redirect('admin/wallets');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
