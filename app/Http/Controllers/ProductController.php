<?php

namespace App\Http\Controllers;

use App\Models\GeneralSetting;
use Illuminate\Http\Request;
use App\Product;
use App\ProductStock;
use App\Category;
use App\FlashDealProduct;
use App\Language;
use Auth;
use App\SubSubCategory;
use Session;
use ImageOptimizer;
use DB;
use CoreComponentRepository;
use Illuminate\Support\Str;

use App\Tags;
use App\Upload;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function admin_products(Request $request)
    {
        //CoreComponentRepository::instantiateShopRepository();
        $type = 'In House';
        $col_name = null;
        $query = null;
        $sort_search = null;

        $products = Product::where('added_by', 'admin');

        if ($request->type != null) {
            $var = explode(",", $request->type);
            $col_name = $var[0];
            $query = $var[1];
            $products = $products->orderBy($col_name, $query);
            $sort_type = $request->type;
        }
        if ($request->search != null) {
            $products = $products
                ->where('name_' . locale(), 'like', '%' . $request->search . '%');
            $sort_search = $request->search;
        }

        $products = $products->where('digital', 0)
            ->select(['*', 'name_' . locale() . ' as name', 'slug_' . locale() . ' as slug'])
            ->orderBy('created_at', 'desc')->paginate(15);

        return view('products.index', compact('products', 'type', 'col_name', 'query', 'sort_search'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function seller_products(Request $request)
    {
        $col_name = null;
        $query = null;
        $seller_id = null;
        $sort_search = null;
        $products = Product::where('added_by', 'seller');
        if ($request->has('user_id') && $request->user_id != null) {
            $products = $products->where('user_id', $request->user_id);
            $seller_id = $request->user_id;
        }
        if ($request->search != null) {
            $products = $products
                ->where('name_' . locale(), 'like', '%' . $request->search . '%');
            $sort_search = $request->search;
        }
        if ($request->type != null) {
            $var = explode(",", $request->type);
            $col_name = $var[0];
            $query = $var[1];
            $products = $products->orderBy($col_name, $query);
            $sort_type = $request->type;
        }

        $products = $products->orderBy('created_at', 'desc')->paginate(15);
        $type = 'Seller';

        return view('products.index', compact('products', 'type', 'col_name', 'query', 'seller_id', 'sort_search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::where('published', true)->select(['*', 'name_' . locale() . ' as name'])->get();
        $subsubcategory_id_multy = Category::where('published', true)->pluck('name_' . locale() . ' as name', 'id')->all();
        $tags = Tags::select(['*', 'name_' . locale() . ' as name'])->orderBy('created_at', 'desc')->get();
        return view('products.create', compact('subsubcategory_id_multy', 'tags', 'categories'));
    }

    public function seller_create()
    {
        $categories = Category::where('published', true)->select(['*', 'name_' . locale() . ' as name'])->get();
        $subsubcategory_id_multy = Category::where('published', true)->pluck('name_' . locale() . ' as name', 'id')->all();
        $tags = Tags::select(['*', 'name_' . locale() . ' as name'])->orderBy('created_at', 'desc')->get();
        return view('products.seller_create', compact('subsubcategory_id_multy', 'tags', 'categories'));
    }
    public function seller_admin_store(Request $request)
    {
        $slug_en = "";
        $slug_ar = "";
        $this->validate($request, [
            'user_id' => 'required',
            'category_id' => 'required',
            'name_en' => 'required|max:200',
            'name_ar' => 'required|max:200',
            'unit_price' => 'required',
            'purchase_price' => 'required',
            'current_stock' => 'required',
            'min_qty' => 'required',

        ]);

        $refund_request_addon = \App\Addon::where('unique_identifier', 'refund_request')->first();
        $product = new Product;
        $product->name_en = $request->name_en;
        if ($request->light_heavy_shipping) {
            $product->light_heavy_shipping = $request->light_heavy_shipping;
        }

        $product->name_ar = $request->name_ar;
        $product->category_id = $request->category_id;
        $product->added_by = $request->added_by;
        $product->published = 0;
        if ($request->button == 'publish') {
            $product->published = 1;
        }
        $str_en = str_replace(' ', '-', $request->name_en);
        $str_ar = str_replace(' ', '-', $request->name_ar);
        $slug_en = substr($str_en, 0, 70);
        $slug_ar = substr($str_ar, 0, 70);


        $product->user_id = $request->user_id;

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

        $product->photos = $request->photos;

        $product->thumbnail_img = $request->thumbnail_img;

        $product->unit = $request->unit;
        $product->min_qty = $request->min_qty;
        $tags_en = array();
        if ($request->tags_en[0] != null) {
            foreach (json_decode($request->tags_en[0]) as $key => $tag) {
                array_push($tags_en, $tag->value);
            }
        }
        $product->tags_en           = implode(',', $tags_en);
        $tags_ar = array();
        if ($request->tags_ar[0] != null) {
            foreach (json_decode($request->tags_ar[0]) as $key => $tag) {
                array_push($tags_ar, $tag->value);
            }
        }
        $product->tags_ar           = implode(',', $tags_ar);

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

        if ($request->has('meta_img')) {
            $product->meta_img = $request->meta_img;
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

        $product->pdf = $request->pdf;

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

                $data = array();
                foreach (json_decode($request[$str][0]) as $key => $eachValue) {
                    array_push($data, $eachValue->value);
                }

                $item['values'] = $data;
                array_push($choice_options, $item);
            }
        }

        if (!empty($request->choice_no)) {
            $product->attributes = json_encode($request->choice_no);
        } else {
            $product->attributes = json_encode(array());
        }

        $product->choice_options = json_encode($choice_options);

        //$variations = array();

        //$variations = array();
        $product->published = 1;
        if ($request->button != 'publish') {
            $product->published = 0;
        }
        if ($request->has('featured')) {
            $product->featured = 1;
        }
        if ($request->has('todays_deal')) {
            $product->todays_deal = 1;
        }

        $product->save();
        //Flash Deal
        if ($request->flash_deal_id) {
            $flash_deal_product = new FlashDealProduct;
            $flash_deal_product->flash_deal_id = $request->flash_deal_id;
            $flash_deal_product->product_id = $product->id;
            $flash_deal_product->discount = $request->flash_discount;
            $flash_deal_product->discount_type = $request->flash_discount_type;
            $flash_deal_product->save();
        }
        $product->subsubcategoryMany()->sync([$request->category_id]);
        $finalsubarr = array_merge($request->subsubcategory_id_multy, [$request->category_id]);
        $product->subsubcategoryMany()->sync($finalsubarr);

        $options = array();
        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $colors_active = 1;
            array_push($options, $request->colors);
        }

        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_' . $no;
                $data = array();
                foreach (json_decode($request[$name][0]) as $key => $item) {
                    array_push($data, $item->value);
                }
                array_push($options, $data);
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
        } else {
            $product_stock = new ProductStock;
            $product_stock->product_id = $product->id;
            $product_stock->price = $request->unit_price;
            $product_stock->qty = $request->current_stock;
            $product_stock->save();
        }

        $product->save();

        flash(translate('Product has been inserted successfully'))->success();

        if (\App\Addon::where('unique_identifier', 'seller_subscription')->first() != null && \App\Addon::where('unique_identifier', 'seller_subscription')->first()->activated) {
            $seller = User::find($request->user_id)->seller;
            $seller->remaining_uploads -= 1;
            $seller->save();
        }
        return redirect()->route('products.seller');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

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
    public function seller_store(Request $request)
    {
        // return $request;
        $slug_en = "";
        $slug_ar = "";
        if (Auth::user()->user_type == 'seller') {
            $this->validate($request, [
                'name_en' => 'required|max:200',
                'name_ar' => 'required|max:200',
                'country_ar' => 'required|max:200',
                'country_en' => 'required|max:200',
                'unit_price' => 'required',
                'purchase_price' => 'required',
                'current_stock' => 'required',
                'min_qty' => 'required',

            ]);
        } else {
            $this->validate($request, [
                'name_en' => 'required|max:200',
                'name_ar' => 'required|max:200',
                'country_ar' => 'required|max:200',
                'country_en' => 'required|max:200',
                'unit_price' => 'required',
                'purchase_price' => 'required',
                'current_stock' => 'required',
                'min_qty' => 'required',
                'slug_en' => 'required|unique:products,slug_en',
                'slug_ar' => 'required|unique:products,slug_ar',
            ]);
        }

        $product = new Product;
        $product->name_en = $request->name_en;
        if ($request->light_heavy_shipping) {
            $product->light_heavy_shipping = $request->light_heavy_shipping;
        }

        $product->name_ar = $request->name_ar;
        $product->added_by = $request->added_by;
        $product->published = 0;
        $str_en = str_replace(' ', '-', $request->name_en);
        $str_ar = str_replace(' ', '-', $request->name_ar);
        $slug_en = substr($str_en, 0, 70);
        $slug_ar = substr($str_ar, 0, 70);
        $check_slug = Product::where('slug_ar', $slug_ar)->orWhere('slug_en', $slug_en)->first();
        if ($check_slug != null) {
            $slug_en = $slug_en . '-v' . rand(1, 10);
            $slug_ar = $slug_ar . '-ن' . rand(1, 10);
        }

        $product->user_id = Auth::user()->id;

        if ($request->hash_tags != null) {
            $product->hashtag_ids = implode(',', $request->hash_tags);
        }
        $product->brand_id = $request->brand_id;
        $product->current_stock = $request->current_stock;
        $product->barcode = $request->barcode;


        $product->refundable = 1;


        $photos = array();
        if ($request->hasFile('photos')) {
            foreach ($request->photos as $key => $photo) {
                array_push($photos, $this->uploadToUploader($photo));
            }
        }
        $product->photos = implode(',', $photos);

        $product->thumbnail_img = $this->uploadToUploader($request->thumbnail_img);

        $product->unit = $request->unit;
        $product->min_qty = $request->min_qty;
        $product->tags_en = implode(',', $request->tags_en);
        $product->tags_ar = implode(',', $request->tags_ar);
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

        if ($request->has('meta_img')) {
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

        $product->slug_en = $slug_en; //preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug_en)).'-'.Str::random(5);
        $product->slug_ar = $slug_ar; //     str_replace(' ', '-', $request->slug_ar).'-'.Str::random(4);
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

                $data = array();
                $item['values'] = explode(',', implode('|', $request[$str]));
                array_push($choice_options, $item);
            }
        }

        if (!empty($request->choice_no)) {

            $product->attributes = json_encode($request->choice_no);
        } else {
            $product->attributes = json_encode(array());
        }
        // return $request;
        $product->choice_options = json_encode($choice_options);

        //$variations = array();
        // return $product;
        $product->save();
        // return $request;

        $categoryToConnect = null;
        if ($request->subcategory_id != null && $request->subsubcategory_id != null) {
            $categoryToConnect = $request->subsubcategory_id;
        } else {
            $categoryToConnect = $request->subcategory_id;
        }
        $product->subsubcategoryMany()->sync([$categoryToConnect]);
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
        // return $request;
        //Generates the combinations of customer choice options
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
        } else {
            $product_stock = new ProductStock;
            $product_stock->product_id = $product->id;
            $product_stock->price = $request->unit_price;
            $product_stock->qty = $request->current_stock;
            $product_stock->save();
        }

        $product->save();

        flash(translate('Product has been inserted successfully'))->success();
        if (\App\Addon::where('unique_identifier', 'seller_subscription')->first() != null && \App\Addon::where('unique_identifier', 'seller_subscription')->first()->activated) {
            $seller = Auth::user()->seller;
            $seller->remaining_uploads -= 1;
            $seller->save();
        }
        return redirect()->route('seller.products');
    }
    public function store(Request $request)
    {
        $slug_en = "";
        $slug_ar = "";
        if (Auth::user()->user_type == 'seller') {
            $this->validate($request, [
                'category_id' => 'required',
                'name_en' => 'required|max:200',
                'name_ar' => 'required|max:200',
                'unit_price' => 'required',
                'purchase_price' => 'required',
                'current_stock' => 'required',
                'min_qty' => 'required',

            ]);
        } else {
            $this->validate($request, [
                'name_en' => 'required|max:200',
                'name_ar' => 'required|max:200',
                'unit_price' => 'required',
                'purchase_price' => 'required',
                'current_stock' => 'required',
                'min_qty' => 'required',
                'slug_en' => 'required|unique:products,slug_en',
                'slug_ar' => 'required|unique:products,slug_ar',
            ]);
        }

        $refund_request_addon = \App\Addon::where('unique_identifier', 'refund_request')->first();
        $product = new Product;
        $product->category_id = $request->category_id;
        $product->name_en = $request->name_en;
        if ($request->light_heavy_shipping) {
            $product->light_heavy_shipping = $request->light_heavy_shipping;
        }

        $product->name_ar = $request->name_ar;
        $product->added_by = $request->added_by;
        if (Auth::user()->user_type == 'seller') {
            $product->published = 0;
            if ($request->button == 'publish') {
                $product->published = 1;
            }
            $str_en = str_replace(' ', '-', $request->name_en);
            $str_ar = str_replace(' ', '-', $request->name_ar);
            $slug_en = substr($str_en, 0, 70);
            $slug_ar = substr($str_ar, 0, 70);


            $product->user_id = Auth::user()->id;
        } else {
            $slug_en = str_replace(' ', '-', $request->slug_en);
            $slug_ar = str_replace(' ', '-', $request->slug_ar);
            $product->user_id = Auth::user()->id;
            //  $product->user_id = \App\User::where('user_type', 'admin')->first()->id;
        }
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

        $product->photos = $request->photos;

        $product->thumbnail_img = $request->thumbnail_img;

        $product->unit = $request->unit;
        $product->min_qty = $request->min_qty;
        $tags_en = array();
        if ($request->tags_en[0] != null) {
            foreach (json_decode($request->tags_en[0]) as $key => $tag) {
                array_push($tags_en, $tag->value);
            }
        }
        $product->tags_en           = implode(',', $tags_en);
        $tags_ar = array();
        if ($request->tags_ar[0] != null) {
            foreach (json_decode($request->tags_ar[0]) as $key => $tag) {
                array_push($tags_ar, $tag->value);
            }
        }
        $product->tags_ar           = implode(',', $tags_ar);
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

        if ($request->has('meta_img')) {
            $product->meta_img = $request->meta_img;
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

        $product->pdf = $request->pdf;

        $product->slug_en = $slug_en; //preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug_en)).'-'.Str::random(5);
        $product->slug_ar = $slug_ar; //     str_replace(' ', '-', $request->slug_ar).'-'.Str::random(4);
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

                $data = array();
                foreach (json_decode($request[$str][0]) as $key => $eachValue) {
                    array_push($data, $eachValue->value);
                }

                $item['values'] = $data;
                array_push($choice_options, $item);
            }
        }

        if (!empty($request->choice_no)) {
            $product->attributes = json_encode($request->choice_no);
        } else {
            $product->attributes = json_encode(array());
        }

        $product->choice_options = json_encode($choice_options);

        //$variations = array();
        $product->published = 1;
        if ($request->button != 'publish') {
            $product->published = 0;
        }
        if ($request->has('featured')) {
            $product->featured = 1;
        }
        if ($request->has('todays_deal')) {
            $product->todays_deal = 1;
        }

        $product->save();
        //Flash Deal
        if ($request->flash_deal_id) {
            $flash_deal_product = new FlashDealProduct;
            $flash_deal_product->flash_deal_id = $request->flash_deal_id;
            $flash_deal_product->product_id = $product->id;
            $flash_deal_product->discount = $request->flash_discount;
            $flash_deal_product->discount_type = $request->flash_discount_type;
            $flash_deal_product->save();
        }
        $product->subsubcategoryMany()->sync([$request->category_id]);
        $finalsubarr = array_merge($request->subsubcategory_id_multy, [$request->category_id]);
        $product->subsubcategoryMany()->sync($finalsubarr);

        //combinations start
        $options = array();
        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $colors_active = 1;
            array_push($options, $request->colors);
        }

        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_' . $no;
                $data = array();
                foreach (json_decode($request[$name][0]) as $key => $item) {
                    array_push($data, $item->value);
                }
                array_push($options, $data);
            }
        }

        //Generates the combinations of customer choice options
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
                // $item = array();
                // $item['price'] = $request['price_'.str_replace('.', '_', $str)];
                // $item['sku'] = $request['sku_'.str_replace('.', '_', $str)];
                // $item['qty'] = $request['qty_'.str_replace('.', '_', $str)];
                // $variations[$str] = $item;

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
        } else {
            $product_stock = new ProductStock;
            $product_stock->product_id = $product->id;
            $product_stock->price = $request->unit_price;
            $product_stock->qty = $request->current_stock;
            $product_stock->save();
        }


        $product->save();

        flash(translate('Product has been inserted successfully'))->success();
        if (Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff') {
            return redirect()->route('products.admin');
        } else {
            if (\App\Addon::where('unique_identifier', 'seller_subscription')->first() != null && \App\Addon::where('unique_identifier', 'seller_subscription')->first()->activated) {
                $seller = Auth::user()->seller;
                $seller->remaining_uploads -= 1;
                $seller->save();
            }
            return redirect()->route('seller.products');
        }
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
    public function admin_product_edit($id)
    {
        $product = Product::findOrFail(decrypt($id));
        // return $product;
        // $tags = json_decode($product->tags);
        $categories = Category::where('published', true)->select(['*', 'name_' . locale() . ' as name'])->get();
        $hashtags = Tags::select(['*', 'name_' . locale() . ' as name'])->orderBy('created_at', 'desc')->get();
        $subsubcategory_id_multy = Category::where('published', true)->pluck('name_' . locale() . ' as name', 'id')->all();
        $subsubcategory_id_multy_selected = $product->subsubcategoryMany->pluck('id')->all();
        // return $subsubcategory_id_multy_selected;

        return view('products.edit', compact('product', 'categories', 'hashtags', 'subsubcategory_id_multy', 'subsubcategory_id_multy_selected'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function seller_product_edit($id)
    {
        $product = Product::findOrFail(decrypt($id));
        // $tags = json_decode($product->tags);
        $categories = Category::where('published', true)->select(['*', 'name_' . locale() . ' as name'])->get();
        $hashtags = Tags::select(['*', 'name_' . locale() . ' as name'])->orderBy('created_at', 'desc')->get();
        $subsubcategory_id_multy_selected = $product->subsubcategoryMany->pluck('id')->all();
        $subsubcategory_id_multy = Category::where('published', true)->pluck('name_' . locale() . ' as name', 'id')->all();
        // return $subsubcategory_id_multy_selected;
        return view('products.edit', compact('product', 'categories', 'hashtags', 'subsubcategory_id_multy', 'subsubcategory_id_multy_selected'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    public function seller_update(Request $request, $id)
    {
        // return $request;
        $this->validate($request, [
            'name_en' => 'required|max:200',
            'name_ar' => 'required|max:200',
            'country_ar' => 'required|max:200',
            'country_en' => 'required|max:200',
            'unit_price' => 'required',
            'purchase_price' => 'required',
            'current_stock' => 'required',
            'min_qty' => 'required',

        ]);
        $product = Product::findOrFail($id);
        $product->name_en = $request->name_en;
        $product->name_ar = $request->name_ar;
        $product->category_id = $request->category_id;
        if ($request->hash_tags != null) {
            $product->hashtag_ids = implode(',', $request->hash_tags);
        }
        $product->subcategory_id = $request->subcategory_id;
        $product->brand_id = $request->brand_id;
        $product->current_stock = $request->current_stock;
        $product->barcode = $request->barcode;
        if ($request->light_heavy_shipping) {
            $product->light_heavy_shipping = $request->light_heavy_shipping;
        }

        $product->refundable = 1;

        if ($request->has('previous_photos')) {
            $photos = $request->previous_photos;
        } else {
            $photos = array();
        }
        if ($request->hasFile('photos')) {
            foreach ($request->photos as $key => $photo) {
                array_push($photos, $this->uploadToUploader($photo));
            }
        }
        $product->photos = implode(',', $photos);


        $product->thumbnail_img  = $request->previous_thumbnail_img;
        if ($request->hasFile('thumbnail_img')) {
            $product->thumbnail_img = $this->uploadToUploader($request->thumbnail_img);
        }
        $product->unit = $request->unit;
        $product->min_qty = $request->min_qty;
        $product->tags_en = implode(',', $request->tags_en);
        $product->tags_ar = implode(',', $request->tags_ar);
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

        $product->meta_img = $request->previous_meta_img;
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
        } else {
            $product_stock              = new ProductStock;
            $product_stock->product_id  = $product->id;
            $product_stock->price       = $request->unit_price;
            $product_stock->qty         = $request->current_stock;
            $product_stock->save();
        }

        $product->save();
        $categoryToConnect = null;
        if ($request->subcategory_id != null && $request->subsubcategory_id != null) {
            $categoryToConnect = $request->subsubcategory_id;
        } else {
            $categoryToConnect = $request->subcategory_id;
        }
        $product->subsubcategoryMany()->sync([$categoryToConnect]);

        flash(translate('Product has been updated successfully'))->success();

        return redirect()->route('seller.products');
    }
    public function update(Request $request, $id)
    {

        if (Auth::user()->user_type == 'seller') {
            $this->validate($request, [
                'name_en' => 'required|max:200',
                'name_ar' => 'required|max:200',
                'unit_price' => 'required',
                'purchase_price' => 'required',
                'current_stock' => 'required',
                'min_qty' => 'required',

            ]);
        } else {
            // return $request;
            $this->validate($request, [
                'category_id' => 'required',
                'name_en' => 'required|max:200',
                'name_ar' => 'required|max:200',
                'unit_price' => 'required',
                'purchase_price' => 'required',
                'current_stock' => 'required',
                'min_qty' => 'required',
                'slug_en' => 'required|unique:products,slug_en,' . $id,
                'slug_ar' => 'required|unique:products,slug_ar,' . $id,
            ]);
            // return $request;
        }
        $request->photos = str_replace("NaN,", "", $request->photos);
        $request->thumbnail_img = str_replace("NaN,", "", $request->thumbnail_img);
        $request->pdf = str_replace("NaN,", "", $request->pdf);

        $refund_request_addon = \App\Addon::where('unique_identifier', 'refund_request')->first();
        $product = Product::findOrFail($id);
        $product->name_en = $request->name_en;
        $product->name_ar = $request->name_ar;
        $product->category_id = $request->category_id;
        if ($request->hash_tags != null) {
            $product->hashtag_ids = implode(',', $request->hash_tags);
        }
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

        $product->photos  = $request->photos;
        $product->thumbnail_img  = $request->thumbnail_img;
        if ($product->added_by == 'seller' && Auth::user()->user_type == 'admin') {
            $product->user_id = $request->user_id;
        }
        if (Auth::user()->user_type == 'seller') {
            //            $product->user_id = Auth::user()->id;
        } else {
            $slug_en = str_replace(' ', '-', $request->slug_en);
            $slug_ar = str_replace(' ', '-', $request->slug_ar);
            //  $product->user_id = \App\User::where('user_type', 'admin')->first()->id;
            $product->slug_en = $slug_en;
            $product->slug_ar = $slug_ar;
        }
        $product->unit = $request->unit;
        $product->min_qty = $request->min_qty;
        $tags_en = array();
        if ($request->tags_en[0] != null) {
            foreach (json_decode($request->tags_en[0]) as $key => $tag) {
                array_push($tags_en, $tag->value);
            }
        }
        $product->tags_en           = implode(',', $tags_en);
        $tags_ar = array();
        if ($request->tags_ar[0] != null) {
            foreach (json_decode($request->tags_ar[0]) as $key => $tag) {
                array_push($tags_ar, $tag->value);
            }
        }
        $product->tags_ar           = implode(',', $tags_ar);
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

        str_replace("NaN,", "", $request->meta_img);
        $product->meta_img          = $request->meta_img;

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


        $product->pdf = $request->pdf;
        if ($request->has('featured')) {
            $product->featured = 1;
        } else {
            $product->featured = 0;
        }
        if ($request->has('todays_deal')) {
            $product->todays_deal = 1;
        } else {
            $product->todays_deal = 0;
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

                $data = array();
                foreach (json_decode($request[$str][0]) as $key => $eachValue) {
                    array_push($data, $eachValue->value);
                }

                $item['values'] = $data;
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
                $data = array();
                foreach (json_decode($request[$name][0]) as $key => $item) {
                    array_push($data, $item->value);
                }
                array_push($options, $data);
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
        } else {
            $product_stock              = new ProductStock;
            $product_stock->product_id  = $product->id;
            $product_stock->price       = $request->unit_price;
            $product_stock->qty         = $request->current_stock;
            $product_stock->save();
        }

        $product->save();
        //Flash Deal
        if ($request->flash_deal_id) {
            if ($product->flash_deal_product) {
                $flash_deal_product = FlashDealProduct::findOrFail($product->flash_deal_product->id);
            } else {
                $flash_deal_product = new FlashDealProduct;
            }

            $flash_deal_product->flash_deal_id = $request->flash_deal_id;
            $flash_deal_product->product_id = $product->id;
            $flash_deal_product->discount = $request->flash_discount;
            $flash_deal_product->discount_type = $request->flash_discount_type;
            $flash_deal_product->save();
            //            dd($flash_deal_product);
        }
        // return $request->category_id;
        $product->subsubcategoryMany()->sync([$request->category_id]);
        $finalsubarr = array_merge($request->subsubcategory_id_multy, [$request->category_id]);
        $product->subsubcategoryMany()->sync($finalsubarr);

        flash(translate('Product has been updated successfully'))->success();
        if ((Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff') && $product->added_by == 'admin') {
            if ($request->button != 'save') {
                return redirect()->route('products.admin');
            } else {
                return redirect()->route('products.admin.edit', encrypt($product->id));
            }
        } else {
            if ($request->button != 'save') {
                return redirect()->route('products.seller');
            } else {
                return redirect()->route('products.seller.edit', encrypt($product->id));
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if (Product::destroy($id)) {
            foreach (Language::all() as $key => $language) {
                $data = openJSONFile($language->code);
                unset($data[$product->name]);
                saveJSONFile($language->code, $data);
            }
            flash(translate('Product has been deleted successfully'))->success();
            if (Auth::user()->user_type == 'admin') {
                return redirect()->route('products.admin');
            } else {
                return redirect()->route('seller.products');
            }
        } else {
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }

    /**
     * Duplicates the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function duplicate($id)
    {
        $product = Product::find($id);
        $product_new = $product->replicate();
        $product_new->slug = substr($product_new->slug, 0, -5) . Str::random(5);

        if ($product_new->save()) {
            flash(translate('Product has been duplicated successfully'))->success();
            if (Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff') {
                return redirect()->route('products.admin');
            } else {
                return redirect()->route('seller.products');
            }
        } else {
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }

    public function get_products_by_subsubcategory(Request $request)
    {
        $products = Product::where('subsubcategory_id', $request->subsubcategory_id)->select(['id', 'name_' . locale() . ' as name'])->get();
        return $products;
    }

    public function get_products_by_brand(Request $request)
    {
        $products = Product::where('brand_id', $request->brand_id)->get();
        return view('partials.product_select', compact('products'));
    }

    public function updateTodaysDeal(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->todays_deal = $request->status;
        if ($product->save()) {
            return 1;
        }
        return 0;
    }

    public function updatePublished(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->published = $request->status;

        if ($product->added_by == 'seller' && \App\Addon::where('unique_identifier', 'seller_subscription')->first() != null && \App\Addon::where('unique_identifier', 'seller_subscription')->first()->activated) {
            $seller = $product->user->seller;
            if ($seller->invalid_at != null && Carbon::now()->diffInDays(Carbon::parse($seller->invalid_at), false) <= 0) {
                return 0;
            }
        }

        $product->save();
        return 1;
    }

    public function updateFeatured(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->featured = $request->status;
        if ($product->save()) {
            return 1;
        }
        return 0;
    }

    public function sku_combination(Request $request)
    {
        $options = array();
        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $colors_active = 1;
            array_push($options, $request->colors);
        } else {
            $colors_active = 0;
        }

        $unit_price = $request->unit_price;
        $product_name = $request->name;

        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_' . $no;
                $data = array();
                // return json_decode($request[$name]);
                foreach (json_decode($request[$name][0]) as $key => $item) {
                    array_push($data, $item->value);
                }
                array_push($options, $data);
            }
        }

        $combinations = combinations($options);
        return view('products.sku_combinations', compact('combinations', 'unit_price', 'colors_active', 'product_name'));
    }

    public function sku_combination_front(Request $request)
    {
        $options = array();
        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $colors_active = 1;
            array_push($options, $request->colors);
        } else {
            $colors_active = 0;
        }

        $unit_price = $request->unit_price;
        $product_name = $request->name;

        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_' . $no;
                $my_str = implode('', $request[$name]);
                array_push($options, explode(',', $my_str));
            }
        }

        $combinations = combinations($options);
        return view('partials.sku_combinations', compact('combinations', 'unit_price', 'colors_active', 'product_name'));
    }

    public function sku_combination_edit_front(Request $request)
    {
        $product = Product::findOrFail($request->id);

        $options = array();
        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $colors_active = 1;
            array_push($options, $request->colors);
        } else {
            $colors_active = 0;
        }

        $product_name = $request->name;
        $unit_price = $request->unit_price;

        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_' . $no;
                $my_str = implode('|', $request[$name]);
                array_push($options, explode(',', $my_str));
            }
        }

        $combinations = combinations($options);
        return view('partials.sku_combinations_edit', compact('combinations', 'unit_price', 'colors_active', 'product_name', 'product'));
    }

    public function sku_combination_edit(Request $request)
    {
        $product = Product::findOrFail($request->id);

        $options = array();
        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $colors_active = 1;
            array_push($options, $request->colors);
        } else {
            $colors_active = 0;
        }

        $product_name = $request->name;
        $unit_price = $request->unit_price;

        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_' . $no;
                $data = array();
                foreach (json_decode($request[$name][0]) as $key => $item) {
                    array_push($data, $item->value);
                }
                array_push($options, $data);
            }
        }

        $combinations = combinations($options);
        return view('products.sku_combinations_edit', compact('combinations', 'unit_price', 'colors_active', 'product_name', 'product'));
    }
}
