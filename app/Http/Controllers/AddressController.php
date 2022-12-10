<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Address;
use App\Phone;
use App\User;
use Auth;

class AddressController extends Controller
{

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        // return $request;
        $this->validate($request, [
            'address' => 'required',
            'country' => 'required',
            'province' => 'required',
            'phone' => ["required","regex:/01[0125][0-9]{8}$/","numeric"],
            'city' => 'required',
            'region' => 'required',
        ]);
        $address = new Address;
        if ($request->has('customer_id')) {
            $address->user_id = $request->customer_id;
        } else {
            $address->user_id = Auth::user()->id;
            if (Auth::user()->phone == null || Auth::user()->address == null) {
                $user = User::find(Auth::user()->id);
                $user->phone = $request->phone;
                $user->address = $request->address;
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

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

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
