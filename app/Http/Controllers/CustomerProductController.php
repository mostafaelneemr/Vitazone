<?php

namespace App\Http\Controllers;

use App\Models\GeneralSetting;
use Illuminate\Http\Request;
use App\CustomerProduct;
use App\Category;
use App\SubCategory;
use App\Brand;
use App\Models\BusinessSetting;
use App\SubSubCategory;
use App\Upload;
use App\Utility\CategoryUtility;
use Auth;
use ImageOptimizer;
use Illuminate\Support\Str;

class CustomerProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = CustomerProduct::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->paginate(10);
        return view('frontend.customer.products', compact('products'));
    }

    public function customer_product_index()
    {
        $products = CustomerProduct::all([
            '*', 'slug_' . locale() . ' as slug', 'name_' . locale() . ' as name'
        ]);
        return view('classified_products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Auth::user()->remaining_uploads > 0
        if (Auth::user()->user_type == "customer") {
            $categories = Category::where('level', 0)->where('published', true)->get();
            return view('frontend.customer.product_upload', compact('categories'));
        } elseif (Auth::user()->user_type == "seller") {
            $categories = Category::where('level', 0)->where('published', true)->get();
            return view('frontend.customer.product_upload', compact('categories'));
        } else {
            flash(translate('Your classified product upload limit has been reached. Please buy a package.'))->error();
            return redirect()->route('customer_packages_list_show');
        }
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
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name_en' => 'required|max:200',
            'name_ar' => 'required|max:200',
            'category_id' => 'required',
            'subcategory_id' => 'required',
            'unit_price' => 'required',
            'purchase_price' => 'required',
            'current_stock' => 'required',
            'min_qty' => 'required',

        ]);
        $slug_en = str_replace(' ', '-', $request->name_en);
        $slug_ar = str_replace(' ', '-', $request->name_ar);
        $wm = GeneralSetting::select('watermark_' . locale() . ' as watermark', 'x_direction', 'y_direction')->first()->watermark;
        if ($wm) {
            $watermark = our_url('/') . '/public/' . $wm;
        } else {
            $watermark = our_url('/') . '/public/frontend/images/logo/logo.png';
        }
        $customer_product = new CustomerProduct;
        $customer_product->name_en = $request->name_en;
        $customer_product->name_ar = $request->name_ar;
        $customer_product->added_by = $request->added_by;
        $customer_product->user_id = Auth::user()->id;
        $customer_product->category_id = $request->category_id;
        $customer_product->subcategory_id = $request->subcategory_id;
        $customer_product->subsubcategory_id = $request->subsubcategory_id;
        $customer_product->brand_id = $request->brand_id;
        $customer_product->conditon = $request->conditon;
        $customer_product->location_ar = $request->location_ar;
        $customer_product->location_en = $request->location_en;
        $photos = array();

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
        $customer_product->tags_en = implode('|', $request->tags_en);
        $customer_product->tags_ar = implode('|', $request->tags_ar);
        $customer_product->description_en = $request->description_en;
        $customer_product->description_ar = $request->description_ar;
        $customer_product->video_provider = $request->video_provider;
        $customer_product->video_link = $request->video_link;
        $customer_product->unit_price = $request->unit_price;
        $customer_product->unit_discount = $request->unit_discount;
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

        $customer_product->slug_en = $slug_en;
        $customer_product->slug_ar = $slug_ar;
        if ($customer_product->save()) {
            $user = Auth::user();
            $user->remaining_uploads -= 1;
            $user->save();
            flash(translate('Product has been inserted successfully'))->success();
            return redirect()->route('customer_products.index');
        } else {
            flash(translate('Something went wrong'))->error();
            return back();
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
    public function edit($id)
    {
        $categories = Category::where('level', 0)->where('published', true)->get();
        $product = CustomerProduct::find(decrypt($id));
        return view('frontend.customer.product_edit', compact('categories', 'product'));
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
        $this->validate($request, [
            'name_en' => 'required|max:200',
            'name_ar' => 'required|max:200',
            'category_id' => 'required',
            'subcategory_id' => 'required',
            'unit' => 'required',
        ]);
        $wm = GeneralSetting::select('watermark_' . locale() . ' as watermark', 'x_direction', 'y_direction')->first()->watermark;
        if ($wm) {
            $watermark = our_url('/') . '/public/' . $wm;
        } else {
            $watermark = our_url('/') . '/public/frontend/images/logo/logo.png';
        }
        $customer_product = CustomerProduct::find($id);
        $customer_product->name_en = $request->name_en;
        $customer_product->name_ar = $request->name_ar;
        $customer_product->status = '1';
        $customer_product->user_id = Auth::user()->id;
        $customer_product->category_id = $request->category_id;
        $customer_product->subcategory_id = $request->subcategory_id;
        $customer_product->subsubcategory_id = $request->subsubcategory_id;
        $customer_product->brand_id = $request->brand_id;
        $customer_product->conditon = $request->conditon;
        $customer_product->location_ar = $request->location_ar;
        $customer_product->location_en = $request->location_en;
        $photos = array();

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
        $customer_product->photos = implode(',', $photos);

        $customer_product->thumbnail_img  = $request->previous_thumbnail_img;
        if ($request->hasFile('thumbnail_img')) {
            $customer_product->thumbnail_img = $this->uploadToUploader($request->thumbnail_img);
        }

        $customer_product->unit = $request->unit;
        $customer_product->tags_en = implode('|', $request->tags_en);
        $customer_product->tags_ar = implode('|', $request->tags_ar);
        $customer_product->description_en = $request->description_en;
        $customer_product->description_ar = $request->description_ar;
        $customer_product->video_provider = $request->video_provider;
        $customer_product->video_link = $request->video_link;
        $customer_product->unit_price = $request->unit_price;
        $customer_product->unit_discount = $request->unit_discount;
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
        if ($customer_product->save()) {
            flash(translate('Product has been inserted successfully'))->success();
            return redirect()->route('customer_products.index');
        } else {
            flash(translate('Something went wrong'))->error();
            return back();
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
        $product = CustomerProduct::findOrFail($id);
        if (CustomerProduct::destroy($id)) {
            if (Auth::user()->user_type == "customer" || Auth::user()->user_type == "seller") {
                flash(translate('Product has been deleted successfully'))->success();
                return redirect()->route('customer_products.index');
            } else {
                return back();
            }
        }
    }

    public function updateStatus(Request $request)
    {
        $product = CustomerProduct::findOrFail($request->id);
        $product->status = $request->status;
        if ($product->save()) {
            return 1;
        }
        return 0;
    }

    public function updatePublished(Request $request)
    {
        $product = CustomerProduct::findOrFail($request->id);
        $product->published = $request->status;
        if ($product->save()) {
            return 1;
        }
        return 0;
    }

    public function customer_products_listing(Request $request)
    {
        return $this->search($request);
    }

    public function customer_product($slug)
    {
        $agent = new \Jenssegers\Agent\Agent;
        if (BusinessSetting::where('type', 'mobile_app')->first()->value == 1) {
            if ($agent->isMobile() == true || $agent->isTablet() == true) {
                if (is_numeric($slug)) {
                    return redirect(BusinessSetting::where('type', 'mobile_app_googleplay_link')->first()->value);
                    if (CustomerProduct::where('id', $slug)->first() != null) {
                        return  redirect()->route('shop.visit', CustomerProduct::where('id', $slug)->first()->{'slug_' . locale()});
                    }
                }
                $open_app = true;
            } else {
                if (CustomerProduct::where('id', $slug)->first() != null) {
                    return  redirect()->route('customer.product', CustomerProduct::where('id', $slug)->first()->{'slug_' . locale()});
                }
                $open_app = false;
            }
        } else {
            $open_app = false;
        }

        $customer_product = CustomerProduct::where('slug_ar', $slug)->orWhere('slug_en', $slug)->first();
        if ($customer_product->{'slug_' . locale()} != $slug) {
            return  redirect()->route('customer.product', $customer_product->{'slug_' . locale()});
        }


        $customer_product = CustomerProduct::select('*', 'name_' . locale() . ' as name', 'slug_' . locale() . ' as slug')->where('slug_' . locale(), $slug)->first();
        if ($customer_product != null) {
            return view('frontend.customer_product_details', compact('customer_product', 'open_app'));
        }
        abort(404);
    }

    public function listingByCategory(Request $request, $category_slug)
    {

        $category = Category::where('slug_ar', $category_slug)->orWhere('slug_en', $category_slug)->first();
        if ($category_slug == $category->slug_ar && locale() != 'ar') {
            return redirect()->route('customer_products.category', $category->slug_en);
        }
        if ($category_slug == $category->slug_en && locale() != 'en') {
            return redirect()->route('customer_products.category', $category->slug_ar);
        }
        if ($category != null) {
            return $this->search($request, $category->id);
        }
        abort(404);
    }

    public function search(Request $request, $category_id = null)
    {
        $query = $request->q;
        $brand_id = (Brand::where('slug_en', $request->brand)->orWhere('slug_ar', $request->brand)->first() != null) ? Brand::where('slug_en', $request->brand)->orWhere('slug_ar', $request->brand)->first()->id : null;
        $sort_by = $request->sort_by;
        $condition = $request->condition;
        $conditions = ['published' => 1];
        if ($brand_id != null) {
            $conditions = array_merge($conditions, ['brand_id' => $brand_id]);
        }
        $customer_products = CustomerProduct::where($conditions);

        if ($category_id != null) {
            $category_ids = CategoryUtility::children_ids($category_id);
            $category_ids[] = $category_id;
            $customer_products = $customer_products->whereIn('category_id', $category_ids);
        }
        if ($query != null) {
            $customer_products = $customer_products->where('name_' . locale(), 'like', '%' . $query . '%')->orWhere('tags', 'like', '%' . $query . '%');
        }
        if ($sort_by != null) {
            switch ($sort_by) {
                case '1':
                    $customer_products->orderBy('created_at', 'desc');
                    break;
                case '2':
                    $customer_products->orderBy('created_at', 'asc');
                    break;
                case '3':
                    $customer_products->orderBy('unit_price', 'asc');
                    break;
                case '4':
                    $customer_products->orderBy('unit_price', 'desc');
                    break;
                default:
                    break;
            }
        }
        if ($condition != null) {
            $customer_products->where('conditon', $condition);
        }

        $customer_products = filter_customer_products($customer_products)->paginate(12)->appends(request()->query());
        // return $customer_products;
        return view('frontend.customer_product_listing', compact('customer_products', 'query', 'category_id', 'brand_id', 'sort_by', 'condition'));
    }
}
