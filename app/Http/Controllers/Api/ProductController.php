<?php

namespace App\Http\Controllers\Api;

use App\CustomerProduct;
use App\GeneralSetting;
use App\Http\Controllers\SearchController;
use App\Http\Resources\CustomerProductCollection;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductDetailCollection;
use App\Http\Resources\SearchProductCollection;
use App\Http\Resources\FlashDealCollection;
use App\Http\Resources\ProductListingCollection;
use App\Models\Attribute;
use App\Models\Brand;
use App\Models\BusinessSetting;
use App\Models\Category;
use App\Models\FlashDeal;
use App\Models\FlashDealProduct;
use App\Models\Product;
use App\Models\Shop;
use App\Models\Color;
use App\Models\Seller;
use App\Models\Tags;
use App\ProductSubSubCategory;
use App\Utility\CategoryUtility;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Upload;

class ProductController extends Controller
{
    public function index()
    {
        return new ProductCollection(Product::where('published', 1)->latest()->paginate(10));
    }

    public function show($id)
    {
        return new ProductDetailCollection(Product::where('id', $id)->get());
    }

    public function admin()
    {
        return new ProductCollection(Product::where(['added_by' => 'admin', 'published' => 1])->latest()->paginate(10));
    }

    public function seller()
    {
        return new ProductCollection(Product::where(['added_by' => 'seller', 'published' => 1])->latest()->paginate(10));
    }

    public function category($id)
    {
        $this->category_ids = CategoryUtility::children_ids($id);
        $this->category_ids[] = $id;
        $products = Product::select(['products.*'])
            ->join('product_sub_sub_categories', function ($join) {
                $join->on('products.id', '=', 'product_sub_sub_categories.product_id')
                    ->whereIn('product_sub_sub_categories.subsubcategory_id', $this->category_ids);
            })->where('published', 1)
            ->distinct()->latest()->paginate(10);
        return new ProductCollection($products);
    }

    public function subCategory($id)
    {
        $this->category_ids = CategoryUtility::children_ids($id);
        $this->category_ids[] = $id;
        $products = Product::select(['products.*'])
            ->join('product_sub_sub_categories', function ($join) {
                $join->on('products.id', '=', 'product_sub_sub_categories.product_id')
                    ->whereIn('product_sub_sub_categories.subsubcategory_id', $this->category_ids);
            })->where('published', 1)
            ->distinct()->latest()->paginate(10);
        return new ProductCollection($products);
    }

    public function subSubCategory($id)
    {
        $this->category_ids = CategoryUtility::children_ids($id);
        $this->category_ids[] = $id;
        $products = Product::select(['products.*'])
            ->join('product_sub_sub_categories', function ($join) {
                $join->on('products.id', '=', 'product_sub_sub_categories.product_id')
                    ->whereIn('product_sub_sub_categories.subsubcategory_id', $this->category_ids);
            })->where('published', 1)
            ->distinct()->latest()->paginate(10);
        return new ProductCollection($products);
    }

    public function brand($id)
    {
        return new ProductCollection(Product::where(['published' => 1, 'brand_id' => $id])->latest()->paginate(10));
    }

    public function todaysDeal()
    {
        return new ProductCollection(Product::where(['published' => 1, 'todays_deal' => 1])->latest()->get());
    }

    public function flashDeal()
    {
        $flash_deals = FlashDeal::where('status', 1)->where('featured', 1)->where('start_date', '<=', strtotime(date('d-m-Y')))->where('end_date', '>=', strtotime(date('d-m-Y')))->get();
        return new FlashDealCollection($flash_deals);
    }

    public function featured()
    {
        return new ProductCollection(Product::where(['published' => 1, 'featured' => 1])->latest()->get());
    }

    public function bestSeller()
    {
        return new ProductCollection(Product::where('published', 1)->orderBy('num_of_sale', 'desc')->limit(20)->get());
    }

    public function related($id)
    {
        $product = Product::find($id);
        $this->category_ids = array_column($product->subsubcategoryMany->toArray(), 'id');
        $products = Product::select(['products.*'])
            ->join('product_sub_sub_categories', function ($join) {
                $join->on('products.id', '=', 'product_sub_sub_categories.product_id')
                    ->whereIn('product_sub_sub_categories.subsubcategory_id', $this->category_ids);
            })->where('products.id', '!=', $id)->where('published', 1)->limit(10)->get();
        return new ProductCollection($products);
    }

    public function topFromSeller($id)
    {
        $product = Product::find($id);
        return new ProductCollection(Product::where(['published' => 1, 'user_id' => $product->user_id])->orderBy('num_of_sale', 'desc')->limit(4)->get());
    }

    public function search()
    {
        $key = request('key');
        $value = request('value');
        $scope = request('sort_by');
        // return $value;
        $collection = Product::query();
        if ($key == 'category') {
            $categories = array_column(Category::select('id')->where('name_' . locale(), 'like', "%{$value}%")->get()->toArray(), 'id');
            $this->category_ids = $categories;
            foreach ($categories as $keyy => $value) {
                $this->category_ids = array_merge($this->category_ids, CategoryUtility::children_ids($value));
            }
            $products = array_column(ProductSubSubCategory::whereIn('subsubcategory_id', $this->category_ids)->get()->toArray(), 'product_id');
            $collection->whereIn('id', $products);
        }
        if ($key == 'brand') {
            $brands = Brand::select('id')->where('name_' . locale(), 'like', "%{$value}%")->get()->pluck('id');
            $collection->whereIn('brand_id', $brands);
        }
        if ($key == 'shop') {
            $shops = Shop::select('user_id')->where('name_' . locale(), 'like', "%{$value}%")->get()->pluck('id');
            $collection->whereIn('user_id', $shops);
        }
        if ($key == 'product' || !$key) {
            $collection->where('name_' . locale(), 'like', "%{$value}%")->orWhere('tags_' . locale(), 'like', "%{$value}%");
        }


        switch ($scope) {

            case 'price_low_to_high':
                $collection = new SearchProductCollection($collection->orderBy('unit_price', 'asc')->where('published', 1)->paginate(10));
                $collection->appends(['key' => $key, 'value' => $value, 'scope' => $scope]);
                return $collection;

            case 'price_high_to_low':
                $collection = new SearchProductCollection($collection->orderBy('unit_price', 'desc')->where('published', 1)->paginate(10));
                $collection->appends(['key' => $key, 'value' => $value, 'scope' => $scope]);
                return $collection;

            case 'new_arrival':
                $collection = new SearchProductCollection($collection->orderBy('created_at', 'desc')->where('published', 1)->paginate(10));
                $collection->appends(['key' => $key, 'value' => $value, 'scope' => $scope]);
                return $collection;

            case 'popularity':
                $collection = new SearchProductCollection($collection->orderBy('num_of_sale', 'desc')->where('published', 1)->paginate(10));
                $collection->appends(['key' => $key, 'value' => $value, 'scope' => $scope]);
                return $collection;

            case 'top_rated':
                $collection = new SearchProductCollection($collection->orderBy('rating', 'desc')->where('published', 1)->paginate(10));
                $collection->appends(['key' => $key, 'value' => $value, 'scope' => $scope]);
                return $collection;
            default:
                $collection = new SearchProductCollection($collection->orderBy('num_of_sale', 'desc')->where('published', 1)->paginate(10));
                $collection->appends(['key' => $key, 'value' => $value, 'scope' => $scope]);
                return $collection;
        }
    }

    public function advancedSearch(Request $request)
    {
        $conditions = [];
        if ($request->brand_id != null) {
            $conditions = array_merge($conditions, ['brand_id' => $request->brand_id]);
        }
        if ($request->seller_id != null) {
            $conditions = array_merge($conditions, ['user_id' => Seller::findOrFail($request->seller_id)->user->id]);
        }
        if ($request->category_id != null) {
            $this->category_id = $request->category_id;
            $products = Product::select(['products.*'])
                ->join('product_sub_sub_categories', function ($join) {
                    $join->on('products.id', '=', 'product_sub_sub_categories.product_id')
                        ->where('product_sub_sub_categories.subsubcategory_id', $this->category_id);
                })
                ->distinct()->where($conditions);
        } else {
            $products = Product::select(['products.*'])->where($conditions);
        }
        if ($request->min_price != null && $request->max_price != null) {
            $products = $products->whereBetween('unit_price', [$request->min_price, $request->max_price]);
        }

        if ($request->search_query != null) {
            $products = $products->where('name_' . locale(), 'like', "%{$request->search_query}%")->orWhere('tags_' . locale(), 'like', "%{$request->search_query}%");
        }
        if ($request->sort_by != null) {
            switch ($request->sort_by) {
                case 'price_low_to_high':
                    $products->orderBy('unit_price', 'asc');
                case 'price_high_to_low':
                    $products->orderBy('unit_price', 'desc');
                case 'new_arrival':
                    $products->orderBy('created_at', 'desc');
                case 'popularity':
                    $products->orderBy('num_of_sale', 'desc');
                case 'top_rated':
                    $products->orderBy('rating', 'desc');
                default:
                    $products->orderBy('created_at', 'desc');
            }
        }
        $non_paginate_products = filter_products($products->orderBy('unit_price', 'desc'))->get();
        
        //Attribute Filter
        $attributes = array();
        foreach ($non_paginate_products as $key => $product) {
            if ($product->attributes != null && is_array(json_decode($product->attributes))) {
                foreach (json_decode($product->attributes) as $key => $value) {
                    $flag = false;
                    $pos = 0;
                    foreach ($attributes as $key => $attribute) {
                        if ($attribute['id'] == $value) {
                            $flag = true;
                            $pos = $key;
                            break;
                        }
                    }
                    if (!$flag) {
                        $item['id'] = $value;
                        $item['values'] = array();
                        foreach (json_decode($product->choice_options) as $key => $choice_option) {
                            if ($choice_option->attribute_id == $value) {
                                $item['values'] = $choice_option->values;
                                break;
                            }
                        }
                        array_push($attributes, $item);
                    } else {
                        foreach (json_decode($product->choice_options) as $key => $choice_option) {
                            if ($choice_option->attribute_id == $value) {
                                foreach ($choice_option->values as $key => $value) {
                                    if (!in_array($value, $attributes[$pos]['values'])) {
                                        array_push($attributes[$pos]['values'], $value);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $selected_attributes = array();
        foreach ($attributes as $key => $attribute) {
            if ($request->has('attribute_' . $attribute['id'])) {
                $request['attribute_' . $attribute['id']] = explode(',', $request['attribute_' . $attribute['id']]);
                foreach ($request['attribute_' . $attribute['id']] as $key => $value) {
                    $str = '"' . $value . '"';
                   $products = $products->where('choice_options', 'like', '%' . $str . '%');
                }

                $item['id'] = $attribute['id'];
                $item['values'] = $request['attribute_' . $attribute['id']];
                array_push($selected_attributes, $item);
            }
        }


        //Color Filter
        $all_colors = array();

        foreach ($non_paginate_products as $key => $product) {
            if ($product->colors != null) {
                foreach (json_decode($product->colors) as $key => $color) {
                    if (!in_array($color, $all_colors)) {
                        array_push($all_colors, $color);
                    }
                }
            }
        }

        $selected_color = null;

        if ($request->has('color')) {
            $str = '"' . $request->color . '"';
            $products = $products->where('colors', 'like', '%' . $str . '%');
            $selected_color = $request->color;
        }
        $original_min_price = 0;
        $original_max_price = 0;
        $getminmaxprice = filter_products($products->orderBy('unit_price', 'desc'))->get();
        if (sizeof($getminmaxprice) > 0) {
            $original_min_price = $getminmaxprice[sizeof($getminmaxprice) - 1]->unit_price;
            $original_max_price = $getminmaxprice[0]->unit_price;
        }
        $data['products'] = new ProductListingCollection(filter_products($products)->paginate(10));
        $data['all_colors'] = $all_colors;
        $data['selected_color'] = $selected_color;
        $data['query'] = $request->search_query;
        $data['category_id'] = $request->category_id;
        $data['seller_id'] = $request->seller_id;
        $data['brand_id'] = $request->brand_id;
        $data['sort_by'] = $request->sort_by;
        $data['min_price'] = $request->min_price;
        $data['max_price'] = $request->max_price;
        $data['original_max_price'] = (float) round($original_max_price, BusinessSetting::where('type', 'no_of_decimals')->first()->value);
        $data['original_min_price'] = (float) round($original_min_price, BusinessSetting::where('type', 'no_of_decimals')->first()->value);
        $data['attributes'] = $this->convertToChoiceOptions($attributes);
        $data['selected_attributes'] = $this->convertToChoiceOptions($selected_attributes);
        return response()->json($data);
    }
    protected function convertToChoiceOptions($data)
    {
        $result = array();
        foreach ($data as $key => $choice) {
            $item['id'] = $choice['id'];
            $item['name'] = Attribute::find($choice['id'])->{'name_' . locale()};
            $item['options'] = $choice['values'];
            array_push($result, $item);
        }
        return $result;
    }

    public function variantPrice(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $str = '';
        $tax = 0;

        if ($request->has('color') && $request['color'] != '') {
            $data['color'] = $request['color'];
            $str = Color::where('code', $request['color'])->first()->name;
        }
        foreach ($request->choice as $option) {
            $str .= $str != '' ? '-' . str_replace(' ', '', $option['name']) : str_replace(' ', '', $option['name']);
        }
        if ($str != null && $product->variant_product) {
            $product_stock = $product->stocks->where('variant', $str)->first();
            $price = $product_stock->price;
            $stockQuantity = $product_stock->qty;
        } else {
            $price = $product->unit_price;
            $stockQuantity = $product->current_stock;
        }

        //discount calculation
        $flash_deals = FlashDeal::where('status', 1)->get();
        $inFlashDeal = false;
        foreach ($flash_deals as $key => $flash_deal) {
            if ($flash_deal != null && $flash_deal->status == 1 && strtotime(date('d-m-Y')) >= $flash_deal->start_date && strtotime(date('d-m-Y')) <= $flash_deal->end_date && FlashDealProduct::where('flash_deal_id', $flash_deal->id)->where('product_id', $product->id)->first() != null) {
                $flash_deal_product = FlashDealProduct::where('flash_deal_id', $flash_deal->id)->where('product_id', $product->id)->first();
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

        return response()->json([
            'product_id' => $product->id,
            'variant_product' => $product->variant_product,
            'variant' => $str,
            'price' => (float) round($price, BusinessSetting::where('type', 'no_of_decimals')->first()->value),
            'in_stock' => $stockQuantity < 1 ? false : true,
            'stockQuantity' => $stockQuantity
        ]);
    }

    public function home()
    {
        return new ProductCollection(Product::where('published', 1)->inRandomOrder()->take(50)->get());
    }

    public function ProductTags($hashtag_ids)
    {
        $tags = array();
        $hastags = explode(',', $hashtag_ids);
        foreach ($hastags as $key => $value) {
            $tag = Tags::select(['*', 'name_' . locale() . ' as name'])->find($value);
            $tags[$key] = $tag->name;
        }

        return response()->json([
            'tags' => $tags,
        ]);
    }

    public function ads()
    {
        $conditions = ['user_id' => auth('api')->user()->id];
        $customer_products = CustomerProduct::where($conditions)->get();
        return new CustomerProductCollection($customer_products);
    }

    public function allAds()
    {
        $conditions = ['published' => 1];
        $customer_products = CustomerProduct::where($conditions)->get();
        return new CustomerProductCollection($customer_products);
    }

    public function getAdsRemainingUploads()
    {
        $data['success'] = true;
        $data['remaining_uploads'] = auth('api')->user()->remaining_uploads;
        return response()->json($data);
    }

    public function viewAd($id)
    {
        $customer_product = CustomerProduct::with(['user' => function ($q) {
            $q->select('id', 'name', 'email', 'phone', 'avatar', 'avatar_original');
        }])->select(
            'customer_products.id',
            'customer_products.name_' . locale() . ' as name',
            'customer_products.location_' . locale() . ' as location',
            'customer_products.tags_' . locale() . ' as tags',
            'customer_products.description_' . locale() . ' as description',
            'customer_products.meta_title_' . locale() . ' as meta_title',
            'customer_products.meta_description_' . locale() . ' as meta_description',
            'customer_products.slug_' . locale() . ' as slug',
            'customer_products.added_by',
            'customer_products.user_id',
            'customer_products.category_id',
            'customer_products.subcategory_id',
            'customer_products.subsubcategory_id',
            'customer_products.brand_id',
            'customer_products.photos',
            'customer_products.thumbnail_img',
            'customer_products.video_provider',
            'customer_products.video_link',
            'customer_products.unit',
            'customer_products.conditon',
            'customer_products.unit_price',
            'customer_products.unit_discount',
            'customer_products.meta_img',
            'customer_products.pdf',
        )->where('id', $id)->first();
        $customer_product->photos = json_encode($this->convertPhotos(explode(',', $customer_product->photos)));
        $customer_product->thumbnail_img = api_asset($customer_product->thumbnail_img);
        $customer_product->meta_img = api_asset($customer_product->meta_img);
        $customer_product->pdf = api_asset($customer_product->pdf);
        $data['success'] = true;
        $data['data'] = $customer_product;
        return response()->json($data);
    }

    private function convertPhotos($data)
    {
        $result = array();
        foreach ($data as $key => $item) {
            array_push($result, api_asset($item));
        }
        return $result;
    }

    public function deleteAd($id)
    {
        $ad = CustomerProduct::findOrFail($id);
        $ad->delete();
        $data['success'] = true;
        return response()->json($data);
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
    public function add_ad(Request $request)
    {
        $customer_product = new CustomerProduct;
        $customer_product->name_en = $request->name_en;
        $customer_product->name_ar = $request->name_ar;
        $customer_product->added_by = 'customer';
        $customer_product->user_id = auth('api')->user()->id;
        $customer_product->category_id = $request->category_id;
        $customer_product->subcategory_id = $request->subcategory_id;
        $customer_product->subsubcategory_id = $request->subsubcategory_id;
        $customer_product->brand_id = $request->brand_id;
        $customer_product->conditon = $request->conditon;
        $customer_product->unit_discount = $request->unit_discount;
        $customer_product->location_ar = $request->location_ar;
        $customer_product->location_en = $request->location_en;
        $photos = array();
        if ($request->hasFile('photos')) {
            foreach ($request->photos as $key => $photo) {
                array_push($photos, $this->uploadToUploader($photo));
            }
        }
        $customer_product->photos = implode(',', $photos);
        if ($request->hasFile('thumbnail_img')) {
            $customer_product->thumbnail_img = $this->uploadToUploader($request->thumbnail_img);
        }

        $customer_product->unit = $request->unit;
        if ($request->tags_en)
            $customer_product->tags_en = $request->tags_en;
        if ($request->tags_ar)
            $customer_product->tags_ar = $request->tags_ar;

        $customer_product->description_en = $request->description_en;
        $customer_product->description_ar = $request->description_ar;
        $customer_product->video_provider = $request->video_provider;
        $customer_product->video_link = $request->video_link;
        $customer_product->unit_price = $request->unit_price;
        $customer_product->meta_title_en = $request->meta_title_en;
        $customer_product->meta_title_ar = $request->meta_title_ar;
        $customer_product->meta_description_en = $request->meta_description_en;
        $customer_product->meta_description_ar = $request->meta_description_ar;
        if ($request->hasFile('meta_img')) {
            $customer_product->meta_img = $this->uploadToUploader($request->meta_img);
        }
        if ($request->hasFile('pdf')) {
            $customer_product->pdf = $this->uploadToUploader($request->pdf);
        }
        // $customer_product->slug_en = strtolower(preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name_en)) . '-' . Str::random(5));
        // $customer_product->slug_ar = strtolower(preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name_ar)) . '-' . Str::random(5));
        $customer_product->slug_en = str_replace(' ', '-', $request->name_en);
        $customer_product->slug_ar = str_replace(' ', '-', $request->name_ar);
        if ($customer_product->save()) {
            $user = auth('api')->user();
            $user->remaining_uploads -= 1;
            $user->save();
            $data['success'] = true;
            $data['data'] = $customer_product;
            return response()->json($data);
        } else {
            $data['success'] = false;
            $data['data'] = [];
            return response()->json($data);
        }
    }
    public function edit_ad(Request $request)
    {
        $customer_product = CustomerProduct::find($request->id);
        // return $customer_product;
        $customer_product->name_en = $request->name_en;
        $customer_product->name_ar = $request->name_ar;
        $customer_product->added_by = 'customer';
        $customer_product->user_id = auth('api')->user()->id;
        $customer_product->category_id = $request->category_id;
        $customer_product->subcategory_id = $request->subcategory_id;
        $customer_product->subsubcategory_id = $request->subsubcategory_id;
        $customer_product->brand_id = $request->brand_id;
        $customer_product->conditon = $request->conditon;
        $customer_product->unit_discount = $request->unit_discount;
        $customer_product->location_ar = $request->location_ar;
        $customer_product->location_en = $request->location_en;
        $photos = array();
        if ($request->hasFile('photos')) {
            foreach ($request->photos as $key => $photo) {
                array_push($photos, $this->uploadToUploader($photo));
            }
            $customer_product->photos = implode(',', $photos);
        }

        if ($request->hasFile('thumbnail_img')) {
            $customer_product->thumbnail_img = $this->uploadToUploader($request->thumbnail_img);
        }

        $customer_product->unit = $request->unit;
        if ($request->tags_en)
            $customer_product->tags_en = $request->tags_en;
        if ($request->tags_ar)
            $customer_product->tags_ar = $request->tags_ar;

        $customer_product->description_en = $request->description_en;
        $customer_product->description_ar = $request->description_ar;
        $customer_product->video_provider = $request->video_provider;
        $customer_product->video_link = $request->video_link;
        $customer_product->unit_price = $request->unit_price;
        $customer_product->meta_title_en = $request->meta_title_en;
        $customer_product->meta_title_ar = $request->meta_title_ar;
        $customer_product->meta_description_en = $request->meta_description_en;
        $customer_product->meta_description_ar = $request->meta_description_ar;
        if ($request->hasFile('meta_img')) {
            $customer_product->meta_img = $this->uploadToUploader($request->meta_img);
        }
        if ($request->hasFile('pdf')) {
            $customer_product->pdf = $this->uploadToUploader($request->pdf);
        }
        // $customer_product->slug_en = strtolower(preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name_en)) . '-' . Str::random(5));
        // $customer_product->slug_ar = strtolower(preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name_ar)) . '-' . Str::random(5));
        $customer_product->slug_en = str_replace(' ', '-', $request->name_en);
        $customer_product->slug_ar = str_replace(' ', '-', $request->name_ar);
        if ($customer_product->save()) {
            $user = auth('api')->user();
            $user->remaining_uploads -= 1;
            $user->save();
            $data['success'] = true;
            $data['data'] = $customer_product;
            return response()->json($data);
        } else {
            $data['success'] = false;
            $data['data'] = [];
            return response()->json($data);
        }
    }
    // {

    //     $slug_en = str_replace( ' ', '-', $request->name_en );
    //     $slug_ar = str_replace( ' ', '-', $request->name_ar );
    //     $wm = GeneralSetting::select( 'watermark_' . locale() . ' as watermark', 'x_direction', 'y_direction' )->first()->watermark;
    //     if ($wm) {
    //         $watermark = our_url( '/' ) . '/public/' . $wm;
    //     } else {
    //         $watermark = our_url( '/' ) . '/public/frontend/images/logo/logo.png';
    //     }
    //     $customer_product = new CustomerProduct;
    //     $customer_product->name_en = $request->name_en;
    //     $customer_product->name_ar = $request->name_ar;
    //     $customer_product->added_by = $request->added_by;
    //     $customer_product->user_id = Auth::user()->id;
    //     $customer_product->category_id = $request->category_id;
    //     $customer_product->subcategory_id = $request->subcategory_id;
    //     $customer_product->subsubcategory_id = $request->subsubcategory_id;
    //     $customer_product->brand_id = $request->brand_id;
    //     $customer_product->conditon = $request->conditon;
    //     $customer_product->location_ar = $request->location_ar;
    //     $customer_product->location_en = $request->location_en;
    //     $photos = array();
    //     if ($request->hasFile( 'photos' )) {
    //         foreach ($request->photos as $key => $photo) {
    //             $extension = $photo->getClientOriginalExtension();
    //             $rand = random_int( 500 , 1100 ) . '_';
    //             $path = $photo->storeAs( 'uploads/customer_products/photos', $rand . str_replace(' ','_',$request->name_en) . '.' . $extension );
    //             array_push( $photos, $path );
    //             $img = str_replace( 'uploads/customer_products/photos/', '', $path );
    //             addWatermark( $watermark, 'public/uploads/customer_products/photos', $img, $wm->x_direction ?? 0, $wm->y_direction ?? 0 );
    //         }
    //         $customer_product->photos = json_encode( $photos );
    //     }

    //     if ($request->hasFile( 'thumbnail_img' )) {
    //         $customer_product->thumbnail_img = $request->thumbnail_img->store( 'uploads/customer_products/thumbnail' );
    //     }

    //     $customer_product->unit = $request->unit;
    //     $customer_product->tags_en = implode( '|', $request->tags_en );
    //     $customer_product->tags_ar = implode( '|', $request->tags_ar );
    //     $customer_product->description_en = $request->description_en;
    //     $customer_product->description_ar = $request->description_ar;
    //     $customer_product->video_provider = $request->video_provider;
    //     $customer_product->video_link = $request->video_link;
    //     $customer_product->unit_price = $request->unit_price;
    //     $customer_product->meta_title_en = $request->meta_title_en;
    //     $customer_product->meta_title_ar = $request->meta_title_ar;
    //     $customer_product->meta_description_en = $request->meta_description_en;
    //     $customer_product->meta_description_ar = $request->meta_description_ar;
    //     if ($request->hasFile( 'meta_img' )) {
    //         $customer_product->meta_img = $request->meta_img->store( 'uploads/customer_products/meta' );
    //     }
    //       if($request->hasFile('pdf')){
    //         $customer_product->pdf = $request->pdf->store('uploads/products/pdf');
    //     }

    //     $customer_product->slug_en = $slug_en;
    //     $customer_product->slug_ar = $slug_ar;
    //     if ($customer_product->save()) {
    //         $user = Auth::user();
    //         $user->remaining_uploads -= 1;
    //         $user->save();
    //         $data['success'] = true;
    //         $data['message'] = trans('messages.Product has been inserted successfully');
    //     } else {
    //         $data['success'] = false;
    //         $data['message'] = trans('messages.Something went wrong');

    //     }
    //     return response()->json($data);

    // }

}
