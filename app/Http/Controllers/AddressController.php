<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Address;
use App\Phone;
use App\User;
use Auth;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request;
        $this->validate($request, [
            'address' => 'required',
            'country' => 'required',
            'province' => 'required',
            'phone' => 'required',
            'city' => 'required',
            'region' => 'required',
        ]);
        $address = new Address;
        if ($request->has('customer_id')) {
            $address->user_id = $request->customer_id;
        } else {
            $address->user_id = Auth::user()->id;
            if (Auth::user()->phone == null) {
                $user = User::find(Auth::user()->id);
                $user->phone = $request->phone;
                $user->save();
            }
            $default_address = 1;
            foreach (Auth::user()->addresses as $key => $addresss) {
                if ($addresss->set_default == 1) {
                    $default_address = 0;
                }
            }
        }
        $address->set_default = $default_address;
        $address->address = $request->address;
        $address->country = $request->country;
        $address->province = $request->province;
        $address->city = $request->city;
        $address->region = $request->region;
        $address->phone = $request->phone;
        $address->save();

        return back();
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
        //
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
        $address = Address::findOrFail($id);
        $adress_phone = $address->phone;
        if (!$address->set_default) {
            $address->delete();
            $phoneinAnotherAdress = Address::where(['user_id' => Auth::user()->id, 'phone' => $adress_phone])->get();
            if (sizeof($phoneinAnotherAdress) > 0) {
            } else {
                $phoneInPhones = Phone::where(['user_id' => Auth::user()->id, 'phone' => $adress_phone])->get();
                if (sizeof($phoneInPhones) > 0) {
                    $phoneInPhones[0]->delete();
                }
            }
            return back();
        }
        flash(translate('Default address can not be deleted'))->warning();
        return back();
    }

    public function set_default($id)
    {
        foreach (Auth::user()->addresses as $key => $address) {
            $address->set_default = 0;
            $address->save();
        }
        $address = Address::findOrFail($id);
        $address->set_default = 1;
        $address->save();

        return back();
    }
}
