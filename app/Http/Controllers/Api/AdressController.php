<?php

namespace App\Http\Controllers\Api;

use App\Address;
use App\City;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\OtpConfiguration;
use App\Page;
use App\Phone;
use App\Provinces;
use App\Region;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdressController extends Controller
{
    public function getCountries()
    {
        if (isset($_SERVER['HTTP_LANG'])) {
            $countries = Country::where('status', true)->select('id', 'name_' . $_SERVER['HTTP_LANG'] . ' AS name')->get();
        } else {
            $countries =  Country::where('status', true)->select('id', 'name_ar AS name')->get();
        }
        return response()->json([
            'data' => $countries
        ]);
    }

    public function getProvincesByCountryId($id)
    {
        if (isset($_SERVER['HTTP_LANG'])) {
            $Provinces = Provinces::where(['country_id' => $id, 'status' => true])->select('id', 'name_' . $_SERVER['HTTP_LANG'] . ' AS name')->get();
        } else {
            $Provinces =  Provinces::where(['country_id' => $id, 'status' => true])->select('id', 'name_ar AS name')->get();
        }
        return response()->json([
            'data' => $Provinces
        ]);
    }

    public function getCitiesByProvinceId($id)
    {
        if (isset($_SERVER['HTTP_LANG'])) {
            $cities = City::where(['province_id' => $id, 'status' => true])->select('id', 'name_' . $_SERVER['HTTP_LANG'] . ' AS name')->get();
        } else {
            $cities =  City::where(['province_id' => $id, 'status' => true])->select('id', 'name_ar AS name')->get();
        }
        return response()->json([
            'data' => $cities
        ]);
    }

    public function getRegionsByCityId($id)
    {
        if (isset($_SERVER['HTTP_LANG'])) {
            $regions = Region::where(['city_id' => $id, 'status' => true])->select('id', 'name_' . $_SERVER['HTTP_LANG'] . ' AS name')->get();
        } else {
            $regions = Region::where(['city_id' => $id, 'status' => true])->select('id', 'name_ar AS name')->get();
        }
        return response()->json([
            'data' => $regions
        ]);
    }

    public function addNewAdress(Request $request)
    {
        if (!isset($request->user_id) || !isset($request->address) || !isset($request->country) || !isset($request->province) || !isset($request->city) || !isset($request->region) || !isset($request->postal_code) || !isset($request->phone)) {
            return response()->json([
                'success' => false,
                'message' => trans('messages.Missing fields')
            ]);
        } else {
            $check_default = Address::where('user_id', $request->user_id)->first();
            if ($check_default == null) {
                $set_default = 1;
            } else {
                $set_default = 0;
            }
            $adress = Address::create([
                'user_id' => $request->user_id,
                'address' => $request->address,
                'country' => $request->country,
                'province' => $request->province,
                'city' => $request->city,
                'region' => $request->region,
                'postal_code' => $request->postal_code,
                'phone' => $request->phone,
                'set_default' => $set_default,
            ]);
            return response()->json([
                'success' => true,
                'message' => trans('messages.Your adress has been placed successfully')
            ]);
        }
    }

    public function editUserAddress(Request $request)
    {
        if (!isset($request->id) || !isset($request->address) || !isset($request->country) || !isset($request->province) || !isset($request->city) || !isset($request->region) || !isset($request->postal_code) || !isset($request->phone)) {
            return response()->json([
                'success' => false,
                'message' => trans('messages.Missing fields')
            ]);
        } else {
            $adress = Address::find($request->id);
            $adress->address = $request->address;
            $adress->country = $request->country;
            $adress->province = $request->province;
            $adress->city = $request->city;
            $adress->region = $request->region;
            $adress->postal_code = $request->postal_code;
            $adress->phone = $request->phone;
            if ($request->set_default == 1) {
                foreach (Auth::user()->addresses as $key => $address) {
                    $setAddNotDef = Address::find($address->id);
                    $setAddNotDef->set_default = 0;
                    $setAddNotDef->save();
                }
            }
            $adress->set_default = $request->set_default;
            $adress->save();
            return response()->json([
                'success' => true,
                'message' => trans('messages.Your adress has been updated successfully')
            ]);
        }
    }

    public function getMyAdresses()
    {
        $user = Auth::user();
        $this->lang = $_SERVER['HTTP_LANG'];
        $adresss = Address::where('user_id', $user->id)->select('id', 'address', 'postal_code', 'phone', 'country', 'province', 'city', 'region', 'set_default')->with([
            'addressCountry' => function ($country) {
                $country->select('id', 'name_' . $this->lang . ' AS name');
            },
            'addressProvince' => function ($province) {
                $province->select('id', 'name_' . $this->lang . ' AS name');
            },
            'addressCity' => function ($city) {
                $city->select('id', 'name_' . $this->lang . ' AS name');
            },
            'addressRegion' => function ($region) {
                $region->select('id', 'name_' . $this->lang . ' AS name', 'shipping_cost', 'shipping_cost_high', 'shipping_duration', 'shipping_duration_high');
            }
        ])->get();
        if (sizeof($adresss) > 0) {
            return response()->json(['data' => $adresss]);
        } else {
            return response()->json(['message' => trans('messages.No Adresses Found')]);
        }
    }

    public function getStaticPagesList()
    {
        $lang = $_SERVER['HTTP_LANG'];
        $pages = Page::where('mobile_apear', true)->select('id', 'title_' . $lang . ' AS title', 'icon')->get();
        foreach ($pages as $key => $page) {
            $pages[$key]->icon = api_asset($page->icon);
        }
        if (sizeof($pages) > 0) {
            $data['success'] = true;
            $data['data'] = $pages;
        } else {
            $data['success'] = false;
            $data['message'] = trans('messages.There is no pages to show');
        }
        return response()->json($data);
    }
    public function getStaticPage($id)
    {
        $page = Page::where('id', $id)->select('title_' . locale() . ' AS title', 'content_' . locale() . ' AS content', 'cover_photo_mobile AS cover_photo')->first();
        $page->cover_photo = api_asset($page->cover_photo);
        $page->site_link = url('/', [$id]);
        if ($page) {
            return response()->json(['data' => $page]);
        } else {
            return response()->json(['message' => trans('messages.Page Not Found')]);
        }
    }
    private function sendOtpMessage($phone, $smsCod)
    {
        if (OtpConfiguration::where('type', 'VictoryLink')->first()->value == 1) {
            $userName = env("VIKTORY_USER");
            $Password = env("VIKTORY_SECRET");
            $SMSText = "Your " . env('APP_NAME') . " code is: " . $smsCod . "Thank you";
            $SMSLang = "e";
            $SMSSender = env("VIKTORY_SENDER");
            $SMSReceiver = $phone;
            $url = 'https://smsvas.vlserv.com/KannelSending/service.asmx/SendSMS';
            $params = "?UserName={$userName}&Password={$Password}&SMSText={$SMSText}&SMSLang={$SMSLang}&SMSSender={$SMSSender}&SMSReceiver={$SMSReceiver}";
            $client = new Client();
            $response = $client->request('GET', "{$url}{$params}", [
                'headers'  => ['Content-Type' => 'application/x-www-form-urlencoded'],
            ]);
        } elseif (OtpConfiguration::where('type', 'Misrsms')->first()->value == 1) {
            // return $to;
            $username = env("MISRSMS_USER");
            $password = env("MISRSMS_SECRET");
            $Msignature = env('MISRSMS_SIGNTURE');
            // $Msignature = '7232181697';
            $Token = env('MISRSMS_TOKEN');
            // $Token = '62c93092-63c5-414b-a6ef-1d807ea407a5';
            $Mobile = $phone;
            $url = 'https://smsmisr.com/api/OTPV2';
            $params = [
                "Username" => $username,
                "password" => $password,
                "Msignature" => $Msignature,
                "Token" => $Token,
                "Mobile" => $Mobile,
                "Code" => $smsCod
            ];
            $params = json_encode($params);

            $ch = curl_init(); // Initialize cURL
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($params),
                'accept:application/json'
            ));

            $response = curl_exec($ch);
            curl_close($ch);
        }
    }
    public function sendOtpCode(Request $request)
    {
        $user = Auth::user();
        for ($i = 0; $i <= 10; $i++) {
            $smsCod = rand(1, 9999);
            $newCode = str_split($smsCod, 1);
            if (!in_array(0, $newCode) && sizeof($newCode) == 4) {
                break;
            }
        }
        $checkphone_exist = Phone::where(['phone' => $request->phone, 'user_id' => $user->id])->get();
        if (sizeof($checkphone_exist) > 0) {
            if ($checkphone_exist[0]->status == 'has_attempts') {
                if ($checkphone_exist[0]->attempts_num >= 3) {
                    $checkphone_exist[0]->status = 'blocked';
                    $checkphone_exist[0]->save();
                    $data['success'] = false;
                    $data['status'] = 'blocked';
                    $data['message'] = trans('messages.This phone number was bloked .');
                    return response()->json($data);
                } else {
                    $this->sendOtpMessage($request->phone, $smsCod);
                    $checkphone_exist[0]->attempts_num += 1;
                    $checkphone_exist[0]->v_code = $smsCod;
                    $checkphone_exist[0]->save();
                    $data['success'] = true;
                    $data['status'] = 'code_send';
                    $data['message'] = trans('messages.Verification Code was sent to phone.');
                    return response()->json($data);
                }
            } elseif ($checkphone_exist[0]->status == 'blocked') {
                $data['success'] = false;
                $data['status'] = 'blocked';
                $data['message'] = trans('messages.This phone number was bloked .');
                return response()->json($data);
            } elseif ($checkphone_exist[0]->status == 'actived') {
                $data['success'] = false;
                $data['status'] = 'active';
                $data['message'] = trans('messages.This phone number already Actived');
                return response()->json($data);
            }
        } else {
            $this->sendOtpMessage($request->phone, $smsCod);
            $save_phone = Phone::create([
                'user_id' => $user->id,
                'phone' => $request->phone,
                'v_code' => $smsCod,
                'attempts_num' => 1,
            ]);
            $data['success'] = true;
            $data['status'] = 'code_send';
            $data['message'] =  trans('messages.Verification Code was sent to phone.');
            return response()->json($data);
        }
    }
    public function activePhone(Request $request)
    {
        $checkphone_exist = Phone::where(['phone' => $request->phone, 'v_code' => $request->code])->get();
        if (sizeof($checkphone_exist) > 0) {
            $checkphone_exist[0]->status = 'actived';
            $checkphone_exist[0]->save();
            return response()->json(['success' => true, 'message' => trans('messages.Phone Verified.')]);
        } else {
            return response()->json(['success' => false, 'message' => trans('messages.Incorrect code.')]);
        }
    }

    public function getMyVerifiedPhones()
    {
        $phones = Phone::where(['user_id' => Auth::user()->id, 'status' => 'actived'])->select('id', 'phone')->get();
        $data['success'] = true;
        $data['data'] = $phones;
        return response()->json($data);
    }

    public function changeUserLang($lang)
    {
        $user = Auth::user();
        $user->lang = $lang;
        $user->save();
        $data['success'] = true;
        return response()->json($data);
    }

    public function set_default_adress($id)
    {
        foreach (Auth::user()->addresses as $key => $address) {
            $address->set_default = 0;
            $address->save();
        }
        $address = Address::findOrFail($id);
        $address->set_default = 1;
        $address->save();
        $data['success'] = true;
        return response()->json($data);
    }

    public function delete_user_adress($id)
    {
        $address = Address::findOrFail($id);
        $adress_phone = $address->phone;
        $address->delete();
        $phoneinAnotherAdress = Address::where(['user_id' => Auth::user()->id, 'phone' => $adress_phone])->get();
        if (sizeof($phoneinAnotherAdress) > 0) {
        } else {
            $phoneInPhones = Phone::where(['user_id' => Auth::user()->id, 'phone' => $adress_phone])->get();
            if (sizeof($phoneInPhones) > 0) {
                $phoneInPhones[0]->delete();
            }
        }
        $data['success'] = true;
        return response()->json($data);
    }
}
