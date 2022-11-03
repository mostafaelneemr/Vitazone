<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Shop;
use App\User;
use App\Seller;
use App\BusinessSetting;
use Auth;
use Hash;
use App\Notifications\EmailVerificationNotification;
use App\Upload;

class ShopController extends Controller
{

    public function __construct()
    {
        $this->middleware('user', ['only' => ['index']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shop = Auth::user()->shop;
        return view('frontend.seller.shop', compact('shop'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::check() && Auth::user()->user_type == 'admin') {
            flash(translate('Admin can not be a seller'))->error();
            return back();
        } else {
            return view('frontend.seller_form');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'name_ar' => 'required',
            'name_en' => 'required',
            'address_en' => 'required',
            'address_ar' => 'required',
        ]);
        $user = null;
        if (!Auth::check()) {
            if (User::where('email', $request->email)->first() != null) {
                flash(translate('Email already exists!'))->error();
                return back();
            }
            if ($request->password == $request->password_confirmation) {
                $user = new User;
                $user->name = $request->name;
                $user->email = $request->email;
                $user->user_type = "seller";
                $user->password = Hash::make($request->password);
                $user->save();
            } else {
                flash(translate('Sorry! Password did not match.'))->error();
                return back();
            }
        } else {
            $user = Auth::user();
            if ($user->customer != null) {
                $user->customer->delete();
            }
            $user->user_type = "seller";
            $user->save();
        }

        $seller = new Seller;
        $seller->user_id = $user->id;
        $seller->save();

        if (Shop::where('user_id', $user->id)->first() == null) {
            $shop = new Shop;
            $shop->user_id = $user->id;
            $shop->name_ar = $request->name_ar;
            $shop->name_en = $request->name_en;

            $shop->address_ar = $request->address_ar;
            $shop->address_en = $request->address_en;
            //            $shop->slug_ar = preg_replace( '/\s+/', '-', $request->name_ar ) . '-' . $shop->id;
            $shop->slug = preg_replace('/\s+/', '-', $request->name_en) . '-' . $shop->id;

            $shop->meta_title_ar = $request->meta_title_ar;
            $shop->meta_title_en = $request->meta_title_en;
            $shop->meta_description_en = $request->meta_description_en;
            $shop->meta_description_ar = $request->meta_description_ar;

            if ($shop->save()) {
                auth()->login($user, false);
                if (BusinessSetting::where('type', 'email_verification')->first()->value != 1) {
                    $user->email_verified_at = date('Y-m-d H:m:s');
                    $user->save();
                } else {
                    $user->notify(new EmailVerificationNotification());
                }

                flash(translate('Your Shop has been created successfully!'))->success();
                return redirect()->route('shops.index');
            } else {
                $seller->delete();
                $user->user_type == 'customer';
                $user->save();
            }
        }

        flash(translate('Sorry! Something went wrong.'))->error();
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
                $upload->user_id = Auth::user()->id;
                $upload->type = $type[$upload->extension];
                $upload->file_size = $file->getSize();
                $upload->save();
            }
            return $upload->id;
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //        dd($request->all());

        $shop = Shop::find($id);
        $thRequest = $request->except(['_method', '_token', 'previous_sliders']);
        if ($request->hasFile('logo')) {
            $thRequest['logo'] = $this->uploadToUploader($request->logo);
        }
        if ($request->has('name_ar') && $request->has('address')) {
            $request->validate([
                'name_ar' => 'required',
                'name_en' => 'required',
                'address_en' => 'required',
                'address_ar' => 'required',
            ]);
            $shop->name_ar = $request->name_ar;
            $shop->name_en = $request->name_en;
            $shop->address_ar = $request->address_ar;
            $shop->address_en = $request->address_en;
            $thRequest['slug_ar'] = preg_replace('/\s+/', '-', $request->name_ar) . '-' . $shop->id;
            $thRequest['slug_en'] = preg_replace('/\s+/', '-', $request->name_en) . '-' . $shop->id;

            $shop->meta_title_ar = $request->meta_title_ar;
            $shop->meta_title_en = $request->meta_title_en;
            $shop->meta_description_en = $request->meta_description_en;
            $shop->meta_description_ar = $request->meta_description_ar;


            if ($request->has('pick_up_point_id')) {
                $thRequest['pick_up_point_id'] = json_encode($request->pick_up_point_id);
            } else {
                $thRequest['pick_up_point_id'] = json_encode(array());
            }
        } elseif ($request->has('facebook') || $request->has('google') || $request->has('twitter') || $request->has('youtube') || $request->has('instagram')) {
            $shop->facebook = $request->facebook;
            $shop->google = $request->google;
            $shop->twitter = $request->twitter;
            $shop->youtube = $request->youtube;
        } elseif ($request->has('previous_sliders') || $request->hasFile('sliders')) {
            if ($request->has('previous_sliders')) {
                $sliders = $request->previous_sliders;
            } else {
                $sliders = array();
            }
            if ($request->hasFile('sliders')) {
                foreach ($request->sliders as $key => $slider) {
                    array_push($sliders, $this->uploadToUploader($slider));
                }
            }
            $shop->sliders = implode(',', $sliders);
            $thRequest['sliders'] = implode(',', $sliders);
        }

        if ($shop->update($thRequest)) {
            flash(translate('Your Shop has been updated successfully!'))->success();
            return back();
        }

        flash(translate('Sorry! Something went wrong.'))->error();
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function verify_form(Request $request)
    {
        if (Auth::user()->seller->verification_info == null) {
            $shop = Auth::user()->shop;
            return view('frontend.seller.verify_form', compact('shop'));
        } else {
            flash(translate('Sorry! You have sent verification request already.'))->error();
            return back();
        }
    }

    public function verify_form_store(Request $request)
    {
        $data = array();
        $i = 0;
        foreach (json_decode(BusinessSetting::where('type', 'verification_form')->first()->value) as $key => $element) {
            $item = array();
            if ($element->type == 'text') {
                $item['type'] = 'text';
                $item['label'] = $element->label;
                $item['value'] = $request['element_' . $i];
            } elseif ($element->type == 'select' || $element->type == 'radio') {
                $item['type'] = 'select';
                $item['label'] = $element->label;
                $item['value'] = $request['element_' . $i];
            } elseif ($element->type == 'multi_select') {
                $item['type'] = 'multi_select';
                $item['label'] = $element->label;
                $item['value'] = json_encode($request['element_' . $i]);
            } elseif ($element->type == 'file') {
                $item['type'] = 'file';
                $item['label'] = $element->label;
                $item['value'] = $request['element_' . $i]->store('uploads/verification_form');
            }
            array_push($data, $item);
            $i++;
        }
        $seller = Auth::user()->seller;
        $seller->verification_info = json_encode($data);
        if ($seller->save()) {
            flash(translate('Your shop verification request has been submitted successfully!'))->success();
            return redirect()->route('dashboard');
        }

        flash(translate('Sorry! Something went wrong.'))->error();
        return back();
    }
}
