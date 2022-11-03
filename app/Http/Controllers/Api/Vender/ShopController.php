<?php

namespace App\Http\Controllers\Api\Vender;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Shop;
use App\Upload;

class ShopController extends Controller
{
    public function index()
    {
        $shop = Shop::where('user_id', auth('api')->user()->id)->first();
        $shop->logo = api_asset($shop->logo);
        $shop->sliders = $this->convertPhotos(explode(',', $shop->sliders));
        return response()->json([
            'shop' => $shop
        ]);
    }
    private function convertPhotos($data)
    {
        $result = array();
        foreach ($data as $key => $item) {
            array_push($result, api_asset($item));
        }
        return json_encode($result);
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
    public function edit_setting(Request $request)
    {

        $shopper = Shop::where('user_id', auth('api')->user()->id)->first();
        $shop = Shop::find($shopper->id);

        $shop->name_ar = $request->name_ar;
        $shop->name_en = $request->name_en;
        $shop->shipping_cost = $request->shipping_cost;
        $shop->address_ar = $request->address_ar;
        $shop->address_en = $request->address_en;
        $shop->slug = preg_replace('/\s+/', '-', $request->name_en) . '-' . $shop->id;

        $shop->meta_title_ar = $request->meta_title_ar;
        $shop->meta_title_en = $request->meta_title_en;
        $shop->meta_description_ar = $request->meta_description_ar;
        $shop->meta_description_en = $request->meta_description_en;
        if ($request->hasFile('logo')) {
            $shop->logo = $this->uploadToUploader($request->logo);
        }
        if ($request->pick_up_point_id) {
            $shop->pick_up_point_id = json_encode($request->pick_up_point_id);
        }

        // $shop->pick_up_point_id = json_encode(array());

        $shop->facebook = $request->facebook;
        $shop->google = $request->google;
        $shop->twitter = $request->twitter;
        $shop->youtube = $request->youtube;
        // $sliders = $request->previous_sliders;
        // return $request->sliders;
        if ($request->hasFile('sliders')) {
            $sliders = array();

            foreach ($request->sliders as $key => $slider) {
                array_push($sliders, $this->uploadToUploader($slider));
            }
            $shop->sliders = implode(',', $sliders);
        }

        $shop->save();
        $shop->sliders = json_decode($shop->sliders);
        return response()->json([
            'shop' => $shop
        ]);
    }
}
