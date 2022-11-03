<?php

namespace App\Http\Controllers\Api\Vender;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Product;
use App\ProductStock;
use App\Category;
use App\Language;
use Auth;
use Session;
use ImageOptimizer;
use DB;
use CoreComponentRepository;
use Illuminate\Support\Str;
use Excel;
use App\ProductsApiImport;
use App\ProductsApiExport;

use App\CustomerProduct;


use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductDetailCollection;
use App\Http\Resources\SearchProductCollection;
use App\Http\Resources\FlashDealCollection;
use App\Http\Resources\RefundRequestCollection;
use App\Models\GeneralSetting;
use App\RefundRequest;
use Illuminate\Support\Facades\Validator;
use App\Upload;


use function GuzzleHttp\json_decode;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new ProductCollection(Product::where('user_id', auth('api')->user()->id)->paginate(10));
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
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name_en'              => 'required',
            'name_ar'              => 'required',
            'country_ar'              => 'required',
            'country_en'              => 'required',
            'category_id'              => 'required',
            'subcategory_id'              => 'required',
            'subsubcategory_id'              => 'required',
            'brand_id'              => 'required'
        ]);
        if ($validator->fails()) {
            $data['success'] = false;
            $data['errors'] = $validator->errors();
            return response()->json($data);
        }

        $refund_request_addon = \App\Addon::where('unique_identifier', 'refund_request')->first();
        $product = new Product;
        $product->name_ar = $request->name_ar;
        $product->name_en = $request->name_en;
        $product->added_by = "seller";

        if ($request->light_heavy_shipping) {
            $product->light_heavy_shipping = $request->light_heavy_shipping;
        }
        $product->published = 1;
        $str_en = str_replace(' ', '-', $request->name_en);
        $str_ar = str_replace(' ', '-', $request->name_ar);
        $slug_en = substr($str_en, 0, 70);
        $slug_ar = substr($str_ar, 0, 70);
        $check_slug = Product::where('slug_ar', $slug_ar)->orWhere('slug_en', $slug_en)->first();
        if ($check_slug != null) {
            $slug_en = $slug_en . '-v' . rand(1, 10);
            $slug_ar = $slug_ar . '-ن' . rand(1, 10);
        }
        $product->user_id = auth('api')->user()->id;
        if ($request->hash_tags != null) {
            $product->hashtag_ids = implode(',', $request->hash_tags);
        }

        $product->brand_id = $request->brand_id;
        $product->current_stock = $request->current_stock;
        $product->barcode = $request->barcode;
        if ($refund_request_addon != null && $refund_request_addon->activated == 1) {
            if ($request->refundable != null) {
                $product->refundable = 1;
            } else {
                $product->refundable = 0;
            }
        }
        $photos = array();
        if ($request->hasFile('photos')) {
            foreach ($request->photos as $key => $photo) {
                array_push($photos, $this->uploadToUploader($photo));
            }
            $product->photos = implode(',', $photos);
        }
        if ($request->hasFile('thumbnail_img')) {
            $product->thumbnail_img = $this->uploadToUploader($request->thumbnail_img);
        }

        $product->unit = $request->unit;
        $product->min_qty = $request->min_qty;
        $product->tags_en = $request->tags_en;
        $product->tags_ar = $request->tags_ar;
        $product->description_en = $request->description_en;
        $product->description_ar = $request->description_ar;
        $product->video_provider = $request->video_provider;
        $product->video_link = $request->video_link;
        $product->unit_price = $request->unit_price;
        $product->purchase_price = $request->purchase_price;
        $product->tax = $request->tax;
        $product->tax_type = $request->tax_type;
        $product->discount = $request->discount;
        $product->discount_type = $request->discount_type;
        $product->shipping_type = $request->shipping_type;
        $product->country_ar = $request->country_ar;
        $product->country_en = $request->country_en;

        if ($request->has('shipping_type')) {
            if ($request->shipping_type == 'free') {
                $product->shipping_cost = 0;
            } elseif ($request->shipping_type == 'flat_rate') {
                $product->shipping_cost = $request->flat_shipping_cost;
            }
        }
        $product->meta_title_en = $request->meta_title_en;
        $product->meta_title_ar = $request->meta_title_ar;
        $product->meta_description_en = $request->meta_description_en;
        $product->meta_description_ar = $request->meta_description_ar;
        if ($request->hasFile('meta_img')) {
            $product->meta_img = $this->uploadToUploader($request->meta_img);
        } else {
            $product->meta_img = $product->thumbnail_img;
        }

        if ($product->meta_title_en == null) {
            $product->meta_title_en = $product->name_en;
        }
        if ($product->meta_title_ar == null) {
            $product->meta_title_ar = $product->name_ar;
        }

        if ($product->meta_title_en == null) {
            $product->meta_description_en = $product->description_en;
        }
        if ($product->meta_description_ar == null) {
            $product->meta_description_ar = $product->description_ar;
        }


        if ($request->hasFile('pdf')) {
            $product->pdf = $this->uploadToUploader($request->pdf);
        }
        $product->slug_en = $slug_en;
        $product->slug_ar = $slug_ar;

        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $product->colors = json_encode($request->colors);
        } else {
            $colors = array();
            $product->colors = json_encode($colors);
        }
        $choice_options = array();

        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $str = 'choice_options_' . $no;

                $item['attribute_id'] = $no;
                $item['values'] = explode(',', implode('|', $request[$str]));

                array_push($choice_options, $item);
            }
        }

        if (!empty($request->choice_no)) {
            $product->attributes = json_encode($request->choice_no);
        } else {
            $product->attributes = json_encode(array());
        }

        $product->choice_options = json_encode($choice_options);
        $product->save();
        $product->subsubcategoryMany()->sync([$request->subsubcategory_id]);

        $options = array();
        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $colors_active = 1;
            array_push($options, $request->colors);
        }

        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_' . $no;
                $my_str = implode('|', $request[$name]);
                array_push($options, explode(',', $my_str));
            }
        }

        $combinations = combinations($options);
        if (count($combinations[0]) > 0) {
            $product->variant_product = 1;
            foreach ($combinations as $key => $combination) {
                $str = '';
                foreach ($combination as $key => $item) {
                    if ($key > 0) {
                        $str .= '-' . str_replace(' ', '', $item);
                    } else {
                        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
                            $color_name = \App\Color::where('code', $item)->first()->name;
                            $str .= $color_name;
                        } else {
                            $str .= str_replace(' ', '', $item);
                        }
                    }
                }
                $product_stock = ProductStock::where('product_id', $product->id)->where('variant', $str)->first();
                if ($product_stock == null) {
                    $product_stock = new ProductStock;
                    $product_stock->product_id = $product->id;
                }
                // return $str;
                $product_stock->variant = $str;
                $product_stock->price = $request['price_' . str_replace('.', '_', $str)];
                $product_stock->sku = $request['sku_' . str_replace('.', '_', $str)];
                $product_stock->qty = $request['qty_' . str_replace('.', '_', $str)];
                $product_stock->save();
            }
        }

        $product->save();

        if (\App\Addon::where('unique_identifier', 'seller_subscription')->first() != null && \App\Addon::where('unique_identifier', 'seller_subscription')->first()->activated) {
            $seller = auth('api')->user()->seller;
            $seller->remaining_uploads -= 1;
            $seller->save();
        }
        $product = Product::find($product->id);
        $product->photos = json_decode($product->photos);
        return response()->json([
            'success' => true,
            'product' => $product
        ]);
    }

    public function getProductForEdit($id)
    {
        $product = Product::find($id);
        $category_id = 0;
        $level = 0;
        $product->category_id = 0;
        $product->subcategory_id = 0;
        $product->subsubcategory_id = 0;
        foreach ($product->subsubcategoryMany as $key => $value) {
            if ($value->level >= $level) {
                $category_id = $value->id;
                $level = $value->level;
            }
        }
        if ($level == 0) {
            $product->category_id = $category_id;
            $product->subcategory_id = 0;
            $product->subsubcategory_id = 0;
        } elseif ($level == 1) {
            $product->category_id = Category::find($category_id)->parent_id;
            $product->subcategory_id = $category_id;
            $product->subsubcategory_id = 0;
        } elseif ($level == 2) {
            $product->category_id = Category::find(Category::find($category_id)->parent_id)->parent_id;
            $product->subcategory_id = Category::find($category_id)->parent_id;
            $product->subsubcategory_id = $category_id;
        }
        $product->photos = $this->convertPhotos(explode(',', $product->photos));
        $product->thumbnail_img = api_asset($product->thumbnail_img);
        $product->pdf = api_asset($product->pdf);
        $product->meta_img = api_asset($product->meta_img);
        return response()->json([
            'success' => true,
            'product' => $product
        ]);
    }
    private function convertPhotos($data)
    {
        $result = array();
        foreach ($data as $key => $item) {
            array_push($result, api_asset($item));
        }
        return $result;
    }
    public function refundRequests()
    {
        return new RefundRequestCollection(RefundRequest::where('seller_id', auth('api')->user()->id)->latest()->paginate(10));
    }

    public function seller_reviews()
    {
        $reviews = DB::table('reviews')
            ->orderBy('id', 'desc')
            ->join('products', 'reviews.product_id', '=', 'products.id')
            ->join('users', 'reviews.user_id', '=', 'users.id')
            ->where('products.user_id', auth('api')->user()->id)
            ->select('reviews.id', 'reviews.comment', 'reviews.status', 'reviews.rating', 'products.id AS product_id', 'products.name_' . locale() . ' AS product_name', 'users.name AS user_name', 'users.email AS user_email')
            ->distinct()
            ->paginate(9);

        foreach ($reviews as $key => $value) {
            $review = \App\Review::find($value->id);
            $review->viewed = 1;
            $review->save();
        }
        $data['success'] = true;
        $data['reviews'] = $reviews;
        return response()->json($data);
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
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name_en' => 'required|max:200',
            'name_ar' => 'required|max:200',
            'country_ar' => 'required|max:200',
            'country_en' => 'required|max:200',
            'category_id' => 'required',
            'subcategory_id' => 'required',
            'subsubcategory_id' => 'required',
            'unit_price' => 'required',
            'purchase_price' => 'required',
            'current_stock' => 'required',
            'min_qty' => 'required',

        ]);
        if ($validator->fails()) {
            $data['success'] = false;
            $data['errors'] = $validator->errors();
            return response()->json($data);
        }

        $refund_request_addon = \App\Addon::where('unique_identifier', 'refund_request')->first();
        $product = Product::findOrFail($request->id);
        $product->name_en = $request->name_en;
        $product->name_ar = $request->name_ar;
        $product->category_id = $request->category_id;
        if ($request->hash_tags != null) {
            $product->hashtag_ids = implode(',', $request->hash_tags);
        }
        $product->subcategory_id = $request->subcategory_id;
        $product->subsubcategory_id = $request->subsubcategory_id;
        $product->brand_id = $request->brand_id;
        $product->current_stock = $request->current_stock;
        $product->barcode = $request->barcode;
        if ($request->light_heavy_shipping) {
            $product->light_heavy_shipping = $request->light_heavy_shipping;
        }

        if ($refund_request_addon != null && $refund_request_addon->activated == 1) {
            if ($request->refundable != null) {
                $product->refundable = 1;
            } else {
                $product->refundable = 0;
            }
        }

        if ($request->has('previous_photos')) {
            $photos = explode(',', $product->photos);
        } else {
            $photos = array();
        }

        if ($request->hasFile('photos')) {
            foreach ($request->photos as $key => $photo) {
                array_push($photos, $this->uploadToUploader($photo));
            }
            $product->photos = implode(',', $photos);
        }

        if ($request->hasFile('thumbnail_img')) {
            $product->thumbnail_img = $this->uploadToUploader($request->thumbnail_img);
        }


        $product->unit = $request->unit;
        $product->min_qty = $request->min_qty;

        $product->tags_en = $request->tags_en;
        $product->tags_ar = $request->tags_ar;
        $product->description_en = $request->description_en;
        $product->description_ar = $request->description_ar;
        $product->video_provider = $request->video_provider;
        $product->video_link = $request->video_link;
        $product->unit_price = $request->unit_price;
        $product->purchase_price = $request->purchase_price;
        $product->tax = $request->tax;
        $product->tax_type = $request->tax_type;
        $product->discount = $request->discount;
        $product->shipping_type = $request->shipping_type;
        if ($request->has('shipping_type')) {
            if ($request->shipping_type == 'free') {
                $product->shipping_cost = 0;
            } elseif ($request->shipping_type == 'flat_rate') {
                $product->shipping_cost = $request->flat_shipping_cost;
            }
        }
        $product->discount_type = $request->discount_type;
        $product->meta_title_en = $request->meta_title_en;
        $product->meta_title_ar = $request->meta_title_ar;
        $product->meta_description_en = $request->meta_description_en;
        $product->meta_description_ar = $request->meta_description_ar;
        $product->country_ar = $request->country_ar;
        $product->country_en = $request->country_en;
        if ($request->hasFile('meta_img')) {
            $product->meta_img = $this->uploadToUploader($request->meta_img);
        }

        if ($product->meta_title_en == null) {
            $product->meta_title_en = $product->name_en;
        }
        if ($product->meta_title_ar == null) {
            $product->meta_title_ar = $product->name_ar;
        }

        if ($product->meta_title_en == null) {
            $product->meta_description_en = $product->description_en;
        }
        if ($product->meta_description_ar == null) {
            $product->meta_description_ar = $product->description_ar;
        }


        if ($request->hasFile('pdf')) {
            $product->pdf = $this->uploadToUploader($request->pdf);
        }


        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $product->colors = json_encode($request->colors);
        } else {
            $colors = array();
            $product->colors = json_encode($colors);
        }

        $choice_options = array();

        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $str = 'choice_options_' . $no;

                $item['attribute_id'] = $no;
                $item['values'] = explode(',', implode('|', $request[$str]));

                array_push($choice_options, $item);
            }
        }

        if ($product->attributes != json_encode($request->choice_attributes)) {
            foreach ($product->stocks as $key => $stock) {
                $stock->delete();
            }
        }

        if (!empty($request->choice_no)) {
            $product->attributes = json_encode($request->choice_no);
        } else {
            $product->attributes = json_encode(array());
        }

        $product->choice_options = json_encode($choice_options);

        foreach (Language::all() as $key => $language) {
            $data = openJSONFile($language->code);
            unset($data[$product->name]);
            $data[$request->name] = "";
            saveJSONFile($language->code, $data);
        }

        //combinations start
        $options = array();
        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $colors_active = 1;
            array_push($options, $request->colors);
        }

        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_' . $no;
                $my_str = implode('|', $request[$name]);
                array_push($options, explode(',', $my_str));
            }
        }

        $combinations = combinations($options);
        if (count($combinations[0]) > 0) {
            $product->variant_product = 1;
            foreach ($combinations as $key => $combination) {
                $str = '';
                foreach ($combination as $key => $item) {
                    if ($key > 0) {
                        $str .= '-' . str_replace(' ', '', $item);
                    } else {
                        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
                            $color_name = \App\Color::where('code', $item)->first()->name;
                            $str .= $color_name;
                        } else {
                            $str .= str_replace(' ', '', $item);
                        }
                    }
                }

                $product_stock = ProductStock::where('product_id', $product->id)->where('variant', $str)->first();
                if ($product_stock == null) {
                    $product_stock = new ProductStock;
                    $product_stock->product_id = $product->id;
                }

                $product_stock->variant = $str;
                $product_stock->price = $request['price_' . str_replace('.', '_', $str)];
                $product_stock->sku = $request['sku_' . str_replace('.', '_', $str)];
                $product_stock->qty = $request['qty_' . str_replace('.', '_', $str)];

                $product_stock->save();
            }
        }

        $product->save();
        $product->subsubcategoryMany()->sync([$request->subsubcategory_id]);
        $product = Product::find($product->id);
        $product->photos = json_decode($product->photos);
        return response()->json([
            'success' => true,
            'product' => $product
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if ($product->photos != null) {
            $photos = json_decode($product->photos);
            foreach ($photos as $key => $value) {
                if (file_exists(public_path() . '/' . $value))
                    unlink(public_path() . '/' . $value);
            }
        }

        if (file_exists(public_path() . '/' . $product->thumbnail_img) && $product->thumbnail_img != null)
            unlink(public_path() . '/' . $product->thumbnail_img);
        if (file_exists(public_path() . '/' . $product->pdf) && $product->pdf != null)
            unlink(public_path() . '/' . $product->pdf);
        if (file_exists(public_path() . '/' . $product->meta_img) && $product->meta_img != null)
            unlink(public_path() . '/' . $product->meta_img);
        if (Product::destroy($id)) {
            foreach (Language::all() as $key => $language) {
                $data = openJSONFile($language->code);
                unset($data[$product->name]);
                saveJSONFile($language->code, $data);
            }
            $returnData['success'] = true;
        } else {
            $returnData['success'] = true;
        }
        return response()->json($returnData, 200);
    }

    public function bulk_upload(Request $request)
    {
        if ($request->hasFile('bulk_file')) {
            Excel::import(new ProductsApiImport, request()->file('bulk_file'));
            return response()->json([
                'success' => true
            ]);
        }
        return response()->json([
            'success' => false
        ]);
    }

    public function export()
    {
        if (auth('api')->user()->id) {
            return Excel::download(new ProductsApiExport, 'products.xlsx');
        } else {
            return response()->json([
                'auth' => false
            ]);
        }
    }

    public function customer_products_index()
    {
        $products = CustomerProduct::where('user_id', auth('api')->user()->id)->orderBy('created_at', 'desc')->get();
        return response()->json([
            'products' => $products
        ]);
    }

    public function customer_products_store(Request $request)
    {
        $customer_product = new CustomerProduct;
        $customer_product->name = $request->name;
        $customer_product->added_by = 'customer';
        $customer_product->user_id = auth('api')->user()->id;
        $customer_product->category_id = $request->category_id;
        $customer_product->subcategory_id = $request->subcategory_id;
        $customer_product->subsubcategory_id = $request->subsubcategory_id;
        $customer_product->brand_id = $request->brand_id;
        $customer_product->conditon = $request->conditon;
        $customer_product->location = $request->location;
        $photos = array();

        // print_r($request->photos);
        // exit();


        if ($request->hasFile('photos')) {
            foreach ($request->photos as $key => $photo) {
                $path = $photo->store('uploads/customer_products/photos');
                array_push($photos, $path);
                // ImageOptimizer::optimize(base_path('public/').$path);
            }
            $customer_product->photos = json_encode($photos);
        }

        if ($request->hasFile('thumbnail_img')) {
            $customer_product->thumbnail_img = $request->thumbnail_img->store('uploads/customer_products/thumbnail');
            // ImageOptimizer::optimize(base_path('public/').$customer_product->thumbnail_img);
        }

        $customer_product->unit = $request->unit;


        if ($request->tags)
            $customer_product->tags = implode('|', json_decode($request->tags, true));

        $customer_product->description = $request->description;
        $customer_product->video_provider = $request->video_provider;
        $customer_product->video_link = $request->video_link;
        $customer_product->unit_price = $request->unit_price;
        $customer_product->meta_title = $request->meta_title;
        $customer_product->meta_description = $request->meta_description;
        if ($request->hasFile('meta_img')) {
            $customer_product->meta_img = $request->meta_img->store('uploads/customer_products/meta');
            // ImageOptimizer::optimize(base_path('public/').$customer_product->meta_img);
        }
        $customer_product->slug = strtolower(preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)) . '-' . Str::random(5));
        if ($customer_product->save()) {
            $user = auth('api')->user();
            $user->remaining_uploads -= 1;
            $user->save();
            return response()->json([
                'customer_product' => $customer_product
            ]);
        } else {
            return response()->json([
                'customer_product' => '{}'
            ]);
        }
    }
}
