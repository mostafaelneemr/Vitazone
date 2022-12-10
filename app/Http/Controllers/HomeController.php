<?php

namespace App\Http\Controllers;

use App\Address;
use App\Models\ModelSetting;
use FontLib\Table\Type\loca;
use Illuminate\Http\Request;
use Session;
use Auth;
use Hash;
use App\Category;
use App\FlashDeal;
use App\Brand;
use App\SubCategory;
use App\SubSubCategory;
use App\Product;
use App\PickupPoint;
use App\CustomerPackage;
use App\CustomerProduct;
use App\User;
use App\Seller;
use App\Shop;
use App\Color;
use App\Order;
use App\BusinessSetting;
use App\Http\Controllers\SearchController;
use ImageOptimizer;
use Cookie;
use Illuminate\Support\Str;
use App\Mail\SecondEmailVerifyMailManager;
use App\Models\AppSettings;
use App\Page;
use App\ProductSubSubCategory;
use App\Tags;
use App\Utility\CategoryUtility;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Mail;

use function GuzzleHttp\json_decode;

class HomeController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('frontend.user_login');
    }

    public function registration(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        if ($request->has('referral_code')) {
            Cookie::queue('referral_code', $request->referral_code, 43200);
        }
        return view('frontend.user_registration');
    }

    // public function user_login(Request $request)
    // {
    //     $user = User::whereIn('user_type', ['customer', 'seller'])->where('email', $request->email)->first();
    //     if($user != null){
    //         if(Hash::check($request->password, $user->password)){
    //             if($request->has('remember')){
    //                 auth()->login($user, true);
    //             }
    //             else{
    //                 auth()->login($user, false);
    //             }
    //             return redirect()->route('dashboard');
    //         }
    //     }
    //     return back();
    // }

    public function cart_login(Request $request)
    {
        $user = User::whereIn('user_type', ['customer', 'seller'])->where('email', $request->email)->orWhere('phone', $request->email)->first();
        if ($user != null) {
            updateCartSetup();
            if (Hash::check($request->password, $user->password)) {
                if ($request->has('remember')) {
                    auth()->login($user, true);
                } else {
                    auth()->login($user, false);
                }
            }
        }
        return back();
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function admin_dashboard()
    {
        return view('dashboard');
    }

    /**
     * Show the customer/seller dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        if (Auth::user()->user_type == 'seller') {
            return view('frontend.seller.dashboard');
        } elseif (Auth::user()->user_type == 'customer') {
            return view('frontend.customer.dashboard');
        } else {
            abort(404);
        }
    }

    public function profile(Request $request)
    {
        $addresses = Address::where('user_id', Auth::user()->id)->get();
        if (sizeof($addresses) > 0) {
            $default_phone = false;
            foreach ($addresses as $key => $address) {
                if ($address->set_default == true) {
                    $default_phone = $address->phone;
                }
            }
            if ($default_phone == false) {
                $default_phone = $addresses[0]->phone;
            }
        } else {
            $default_phone = false;
        }
        if (Auth::user()->user_type == 'customer') {
            return view('frontend.customer.profile', compact('default_phone'));
        } elseif (Auth::user()->user_type == 'seller') {
            return view('frontend.seller.profile', compact('default_phone'));
        }
    }

    public function customer_update_profile(Request $request)
    {
         $this->validate($request, [
            'name' => 'required|string|min:3',
            'phone' => ["required","digits:11","regex:/01[0125][0-9]{8}$/","numeric"], 
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        if (env('DEMO_MODE') == 'On') {
            flash(translate('Sorry! the action is not permitted in demo '))->error();
            return back();
        }

        $user = Auth::user();
        $user->name = $request->name;
        $user->address = $request->address;
        $user->country = $request->country;
        $user->city = $request->city;
        $user->postal_code = $request->postal_code;
        $user->phone = $request->phone;

        if ($request->new_password != null && ($request->new_password == $request->confirm_password)) {
            $user->password = Hash::make($request->new_password);
        }

        if ($request->hasFile('photo')) {
            $user->avatar_original = $request->photo->store('uploads/users');
        }

        if ($user->save()) {
            flash(translate('Your Profile has been updated successfully!'))->success();
            return back();
        }

        flash(translate('Sorry! Something went wrong.'))->error();
        return back();
    }


    public function seller_update_profile(Request $request)
    {
        if (env('DEMO_MODE') == 'On') {
            flash(translate('Sorry! the action is not permitted in demo '))->error();
            return back();
        }

        $user = Auth::user();
        $user->name = $request->name;
        $user->address = $request->address;
        $user->country = $request->country;
        $user->city = $request->city;
        $user->postal_code = $request->postal_code;
        $user->phone = $request->phone;

        if ($request->new_password != null && ($request->new_password == $request->confirm_password)) {
            $user->password = Hash::make($request->new_password);
        }

        if ($request->hasFile('photo')) {
            $user->avatar_original = $request->photo->store('uploads');
        }

        $seller = $user->seller;
        $seller->cash_on_delivery_status = $request->cash_on_delivery_status;
        $seller->bank_payment_status = $request->bank_payment_status;
        $seller->bank_name = $request->bank_name;
        $seller->bank_acc_name = $request->bank_acc_name;
        $seller->bank_acc_no = $request->bank_acc_no;
        $seller->bank_routing_no = $request->bank_routing_no;

        if ($user->save() && $seller->save()) {
            flash(translate('Your Profile has been updated successfully!'))->success();
            return back();
        }

        flash(translate('Sorry! Something went wrong.'))->error();
        return back();
    }

    /**
     * Show the application frontend home.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $close = session()->get('closemodel');

        $model_setting = ModelSetting::first();
        if (isset($close) && $close == true) {
            $open = false;
        } else {
            $open = true;
        }

        return view('frontend.index', compact('open', 'model_setting'));
    }

    public function flash_deal_details($slug)
    {
        $FlashDeal = FlashDeal::where('slug_ar', $slug)->OrWhere('slug_en', $slug)->first();
        if ($FlashDeal->{'slug_' . locale()} != $slug) {
            return  redirect()->route('flash-deal-details', $FlashDeal->{'slug_' . locale()});
        }
        $flash_deal = FlashDeal::where('slug_' . locale(), $slug)->first();
        if ($flash_deal != null)
            return view('frontend.flash_deal_details', compact('flash_deal'));
        else {
            abort(404);
        }
    }
    public function all_flash_deals()
    {
        $today = strtotime(date('Y-m-d H:i:s'));

        $data['all_flash_deals'] = FlashDeal::where('status', 1)
            ->where('start_date', "<=", $today)
            ->where('end_date', ">", $today)
            ->orderBy('created_at', 'desc')
            ->get();

        return view("frontend.flash_deal.all_flash_deal_list", $data);
    }
    public function load_featured_section()
    {
        return view('frontend.partials.featured_products_section');
    }

    public function load_best_selling_section()
    {
        return view('frontend.partials.best_selling_section');
    }

    public function load_home_categories_section()
    {
        return view('frontend.partials.home_categories_section');
    }

    public function load_best_sellers_section()
    {
        return view('frontend.partials.best_sellers_section');
    }

    public function repay_ordre_fawry_done()
    {
        $re_code = explode('-', json_decode($_GET['chargeResponse'])->merchantRefNumber);
        $order_code = $re_code[0] . '-' . $re_code[1];
        $order = Order::where('code', $order_code)->first();
        $order->payment_merchent_ref = $re_code[2];
        $order->payment_refrence = json_decode($_GET['chargeResponse'])->fawryRefNumber;
        $order->payment_type = 'fawry';
        // return $order;
        $order->save();
        flash(translate('You paid with fawry successfully'))->success();
        return  redirect()->route('orders.track', ['order_code' => $order_code]);
    }

    public function repay_ordre_paysky_done()
    {
        $order = Order::where('code', $_GET['order_code'])->first();
        $order->payment_type = 'paysky';
        $order->payment_status = 'paid';
        foreach ($order->orderDetails as $key => $orderDetail) {
            $orderDetail->payment_status = 'paid';
            $orderDetail->save();
        }
        $order->save();
        flash(translate('You paid with paysky successfully'))->success();
        return  redirect()->route('orders.track', ['order_code' => $_GET['order_code']]);
    }

    public function repay_ordre_faile()
    {
        if (isset($_GET['order_code'])) {
            $re_code = explode('-', $_GET['merchantRefNum']);
            $order_code = $re_code[0] . '-' . $re_code[1];
            flash(translate('Sorry! Something went wrong.'))->error();
            return  redirect()->route('orders.track', ['order_code' => $order_code]);
        } else {

            flash(translate('Sorry! Something went wrong.'))->error();
            return  redirect()->route('orders.track', ['order_code' => $_GET['order_code']]);
        }
    }
    public function trackOrder(Request $request)
    {

        if (isset($_GET['paysky'])) {
            $paysky = true;
        } else {
            $paysky = false;
        }
        if (isset($_GET['fawry'])) {
            $fawry = true;
        } else {
            $fawry = false;
        }
        if ($request->has('order_code')) {
            $order = Order::where('code', $request->order_code)->first();
            $shipping_address['name'] = json_decode($order->shipping_address)->name;
            $shipping_address['email'] = json_decode($order->shipping_address)->email;
            $shipping_address['address'] = json_decode($order->shipping_address)->address;
            $shipping_address['phone'] = json_decode($order->shipping_address)->phone;
            if ($order != null) {
                return view('frontend.track_order', compact('order', 'paysky', 'fawry', 'shipping_address'));
            }
        } else {
            $shipping_address['name'] = null;
            $shipping_address['email'] = null;
            $shipping_address['address'] = null;
            $shipping_address['phone'] = null;
        }
        return view('frontend.track_order', compact('paysky', 'fawry', 'shipping_address'));
    }

    public function product(Request $request, $slug)
    {
        // return sendSMS('01153430338', env("APP_NAME"), 151515 . ' is your verification code for ' . env('APP_NAME'));
        $agent = new \Jenssegers\Agent\Agent;
        if (BusinessSetting::where('type', 'mobile_app')->first()->value == 1) {
            if ($agent->isMobile() == true || $agent->isTablet() == true) {
                if (is_numeric($slug)) {
                    return redirect(BusinessSetting::where('type', 'mobile_app_googleplay_link')->first()->value);
                }
                $open_app = true;
            } else {
                $open_app = false;
            }
        } else {
            $open_app = false;
        }

        // return response()->json($install_app);
        if (Product::where('slug_ar', $slug)->OrWhere('slug_en', $slug)->exists() || Product::where('id', $slug)->exists()) {
            $product = Product::where('slug_ar', $slug)->OrWhere('slug_en', $slug)->OrWhere('id', $slug)->first();
            // return $product;
            if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], '/search?category') !== false) {
                // return $_SERVER['HTTP_REFERER'];
                $url_components = parse_url($_SERVER['HTTP_REFERER']);
                parse_str($url_components['query'], $params);
                $category_level = Category::where('slug_ar', $params['category'])->OrWhere('slug_en', $params['category'])->first();
                if ($category_level->level == 2) {
                    $crumbArr['sub_sub_category_id'] = $category_level->id;
                    $crumbArr['sub_category_id'] = $category_level->parent_id;
                    $crumbArr['category_id'] = Category::find($category_level->parent_id)->parent_id;
                } elseif ($category_level->level == 1) {
                    $crumbArr['sub_sub_category_id'] = null;
                    $crumbArr['sub_category_id'] = $category_level->id;
                    $crumbArr['category_id'] = $category_level->parent_id;
                } else {
                    $crumbArr['sub_sub_category_id'] = null;
                    $crumbArr['sub_category_id'] = null;
                    $crumbArr['category_id'] = $category_level->id;
                }
            } else {
                $productSubSubCat = ProductSubSubCategory::where('product_id', $product->id)->first();
                if ($productSubSubCat) {
                    $category_level = Category::find($productSubSubCat->subsubcategory_id);
                    if ($category_level->level == 2) {
                        $crumbArr['sub_sub_category_id'] = $category_level->id;
                        $crumbArr['sub_category_id'] = $category_level->parent_id;
                        $crumbArr['category_id'] = Category::find($category_level->parent_id)->parent_id;
                    } elseif ($category_level->level == 1) {
                        $crumbArr['sub_sub_category_id'] = null;
                        $crumbArr['sub_category_id'] = $category_level->id;
                        $crumbArr['category_id'] = $category_level->parent_id;
                    } else {
                        $crumbArr['sub_sub_category_id'] = null;
                        $crumbArr['sub_category_id'] = null;
                        $crumbArr['category_id'] = $category_level->id;
                    }
                } else {
                    $crumbArr['sub_sub_category_id'] = null;
                    $crumbArr['sub_category_id'] = null;
                    $crumbArr['category_id'] = null;
                }
            }
            if ($product->{'slug_' . locale()} != $slug) {
                return  redirect()->route('product', $product->{'slug_' . locale()});
            }

            $detailedProduct  = Product::select([
                '*', 'name_' . locale() . ' as name', 'slug_' . locale() . ' as slug', 'meta_description_' . locale() . ' as meta_description', 'meta_title_' . locale() . ' as meta_title'
            ])->where('slug_' . locale(), $slug)->first();

            if ($detailedProduct != null && $detailedProduct->published) {
                updateCartSetup();
                if ($request->has('product_referral_code')) {
                    Cookie::queue('product_referral_code', $request->product_referral_code, 43200);
                    Cookie::queue('referred_product_id', $detailedProduct->id, 43200);
                }
                // return $detailedProduct;
                if ($detailedProduct->digital == 1) {
                    // return $request;
                    return view('frontend.digital_product_details', compact('detailedProduct', 'crumbArr', 'open_app'));
                } else {
                    // return $slug;

                    return view('frontend.product_details', compact('detailedProduct', 'crumbArr', 'open_app'));
                }
                // return view('frontend.product_details', compact('detailedProduct'));
            }
        } else {
            abort(404);
        }
    }

    public function shop($slug)
    {
        if (BusinessSetting::where('type', 'mobile_app')->first()->value == 1) {
            $agent = new \Jenssegers\Agent\Agent;
            if ($agent->isMobile() == true || $agent->isTablet() == true) {
                if (is_numeric($slug)) {
                    return redirect(BusinessSetting::where('type', 'mobile_app_googleplay_link')->first()->value);
                    if (Shop::where('id', $slug)->first() != null) {
                        return  redirect()->route('shop.visit', Shop::where('id', $slug)->first()->slug);
                    }
                }
                $open_app = true;
            } else {
                if (Shop::where('id', $slug)->first() != null) {
                    return  redirect()->route('shop.visit', Shop::where('id', $slug)->first()->slug);
                }
                $open_app = false;
            }
        } else {
            $open_app = false;
        }

        $shop  = Shop::where('slug', $slug)->first();
        if ($shop != null) {
            $seller = Seller::where('user_id', $shop->user_id)->first();
            if ($seller->verification_status != 0) {
                return view('frontend.seller_shop', compact('shop', 'open_app'));
            } else {
                return view('frontend.seller_shop_without_verification', compact('shop', 'seller', 'open_app'));
            }
        }
        abort(404);
    }

    public function filter_shop($slug, $type)
    {
        $agent = new \Jenssegers\Agent\Agent;
        if (BusinessSetting::where('type', 'mobile_app')->first()->value == 1) {
            if ($agent->isMobile() == true || $agent->isTablet() == true) {
                if (is_numeric($slug)) {
                    return redirect(BusinessSetting::where('type', 'mobile_app_googleplay_link')->first()->value);
                    if (Shop::where('id', $slug)->first() != null) {
                        return  redirect()->route('shop.visit', Shop::where('id', $slug)->first()->slug);
                    }
                }
                $open_app = true;
            } else {
                if (Shop::where('id', $slug)->first() != null) {
                    return  redirect()->route('shop.visit', Shop::where('id', $slug)->first()->slug);
                }
                $open_app = false;
            }
        } else {
            $open_app = false;
        }

        $shop  = Shop::where('slug', $slug)->first();
        if ($shop != null && $type != null) {
            return view('frontend.seller_shop', compact('shop', 'type', 'open_app'));
        }
        abort(404);
    }

    public function listing(Request $request)
    {
        // $products = filter_products(Product::orderBy('created_at', 'desc'))->paginate(12);
        // return view('frontend.product_listing', compact('products'));
        return $this->search($request);
    }

    public function listingByCategory(Request $request, $category_slug)
    {

        if (is_numeric($category_slug)) {
            $category = Category::where('id', $category_slug)->first();
        } else {
            $category = Category::where('slug_ar', $category_slug)->orWhere('slug_en', $category_slug)->first();
        }
        if ($category) {
            if (isset($category->slug_ar) && $category_slug == $category->slug_ar && locale() != 'ar') {
                return redirect()->route('products.category', $category->slug_en);
            }

            if (isset($category->slug_en) && $category_slug == $category->slug_en && locale() != 'en') {
                return redirect()->route('products.category', $category->slug_ar);
            }
            if ($category_slug == $category->id) {
                return redirect()->route('products.category', $category->{'slug_' . locale()});
            }
            if ($category != null) {
                return $this->search($request, $category->id);
            }
        }

        abort(404);
    }

    public function listingByBrand(Request $request, $brand_slug)
    {
        if (is_numeric($brand_slug)) {
            $brand = Brand::where('id', $brand_slug)->first();
        } else {
            $brand = Brand::where('slug_ar', $brand_slug)->orWhere('slug_en', $brand_slug)->first();
        }
        if ($brand_slug == $brand->slug_ar && locale() != 'ar') {
            return redirect()->route('products.brand', $brand->slug_en);
        }
        if ($brand_slug == $brand->slug_en && locale() != 'en') {
            return redirect()->route('products.brand', $brand->slug_ar);
        }
        if ($brand_slug == $brand->id) {
            return redirect()->route('products.brand', $brand->{'slug_' . locale()});
        }
        if ($brand != null) {
            return $this->search($request, null, $brand->id);
        }
        abort(404);
    }

    public function all_categories(Request $request)
    {
        $categories = Category::where('level', 0)->where('published', true)->select(['*', 'name_' . locale() . ' as name'])->get();
        // return $categories;
        return view('frontend.all_category', compact('categories'));
    }
    public function all_brands(Request $request)
    {
        $categories = Category::where('published', true)->select(['*', 'name_' . locale() . ' as name'])->get();
        return view('frontend.all_brand', compact('categories'));
    }

    public function show_product_upload_form(Request $request)
    {
        if (
            \App\Addon::where('unique_identifier', 'seller_subscription')->first() != null &&
            \App\Addon::where('unique_identifier', 'seller_subscription')->first()->activated
        ) {
            if (Auth::user()->seller->remaining_uploads > 0) {
                $categories = Category::where('parent_id', 0)->where('published', true)->select(['*', 'name_' . locale() . ' as name'])->get();
                return view('frontend.seller.product_upload', compact('categories'));
            } else {
                flash(translate('Upload limit has been reached. Please upgrade your package.'))->warning();
                return back();
            }
        }

        $categories = Category::where('parent_id', 0)->where('published', true)->select(['*', 'name_' . locale() . ' as name'])->get();
        return view('frontend.seller.product_upload', compact('categories'));
    }

    public function show_product_edit_form(Request $request, $id)
    {

        $categories = Category::where('level', 0)->where('published', true)->select(['*', 'name_' . locale() . ' as name'])->get();

        $product = Product::find(decrypt($id));
        $subsubcategory_id_multy_selected = $product->subsubcategoryMany->pluck('id')->all();
        $subsubcategory_id_multy = Category::where('published', true)->pluck('name_' . locale() . ' as name', 'id')->all();
        $product->category = null;
        $product->subcategory = null;
        $product->subsubcategory = null;
        if (!empty($subsubcategory_id_multy_selected)) {
            $category = Category::find($subsubcategory_id_multy_selected[0]);
            if ($category->level == 1) {
                $product->category = Category::find($category->parent_id);
                $product->subcategory = $category;
                $product->subsubcategory = null;
            } elseif ($category->level == 2) {
                $product->subcategory = Category::find($category->parent_id);
                $product->category = Category::find($product->subcategory->parent_id);
                $product->subsubcategory = $category;
            }
        } else {
            $product->subcategory = null;
            $product->category = null;
            $product->subsubcategory = null;
        }
        return view('frontend.seller.product_edit', compact('categories', 'product', 'subsubcategory_id_multy', 'subsubcategory_id_multy_selected'));
    }

    public function seller_product_list(Request $request)
    {
        $search = null;
        $products = Product::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc');
        if ($request->has('search')) {
            $search = $request->search;
            $products = $products->where('name_' . locale(), 'like', '%' . $search . '%');
        }
        $products = $products->paginate(10);
        return view('frontend.seller.products', compact('products', 'search'));
    }

    public function ajax_search(Request $request)
    {
        $keywords = array();
        $products = Product::where('published', 1)->where('tags_' . locale(), 'like', '%' . $request->search . '%')->get();
        foreach ($products as $key => $product) {
            foreach (explode(',', $product->tags) as $key => $tag) {
                if (stripos($tag, $request->search) !== false) {
                    if (sizeof($keywords) > 5) {
                        break;
                    } else {
                        if (!in_array(strtolower($tag), $keywords)) {
                            array_push($keywords, strtolower($tag));
                        }
                    }
                }
            }
        }

        $products = filter_products(Product::where('published', 1)->where('name_' . locale(), 'like', '%' . $request->search . '%'))->get()->take(3);

        $subsubcategories = SubSubCategory::where('name_' . locale(), 'like', '%' . $request->search . '%')->select(['*', 'name_' . locale() . ' as name'])->get()->take(3);

        $shops = Shop::whereIn('user_id', verified_sellers_id())->where('name_' . locale(), 'like', '%' . $request->search . '%')->get()->take(3);

        if (sizeof($keywords) > 0 || sizeof($subsubcategories) > 0 || sizeof($products) > 0 || sizeof($shops) > 0) {
            return view('frontend.partials.search_content', compact('products', 'subsubcategories', 'keywords', 'shops'));
        }
        return '0';
    }

    public function search(Request $request, $category_id = null, $brand_id = null)
    {

        if (BusinessSetting::where('type', 'mobile_app')->first()->value == 1) {
            $agent = new \Jenssegers\Agent\Agent;
            if ($agent->isMobile() == true || $agent->isTablet() == true) {
                if (is_numeric($request->brand)) {
                    return redirect(BusinessSetting::where('type', 'mobile_app_googleplay_link')->first()->value);
                    if (Brand::where('id', $request->brand)->first() != null) {
                        return  redirect()->route('products.brand', [Brand::where('id', $request->brand)->first()->{'slug_' . locale()}]);
                    }
                }
                if (is_numeric($request->category)) {
                    return redirect(BusinessSetting::where('type', 'mobile_app_googleplay_link')->first()->value);
                    if (Category::where('id', $category_id)->first() != null) {
                        return  redirect()->route('products.category', [Category::where('id', $category_id)->first()->{'slug_' . locale()}]);
                    }
                }
                $open_app = true;
            } else {
                $open_app = false;
            }
        } else {
            $open_app = false;
        }


        $query = $request->q;
        $sort_by = $request->sort_by;

        if ($category_id != null) {
            $category_front = Category::select('id', 'level', 'parent_id', 'title_' . locale() . ' AS title', 'description_' . locale() . ' AS description')->find($category_id);
            if ($category_front->level == 2) {
                $crumbArr['sub_sub_category_id'] = $category_front->id;
                $crumbArr['sub_category_id'] = $category_front->parent_id;
                $crumbArr['category_id'] = Category::find($category_front->parent_id)->parent_id;
            } elseif ($category_front->level == 1) {
                $crumbArr['sub_sub_category_id'] = null;
                $crumbArr['sub_category_id'] = $category_front->id;
                $crumbArr['category_id'] = $category_front->parent_id;
            } else {
                $crumbArr['sub_sub_category_id'] = null;
                $crumbArr['sub_category_id'] = null;
                $crumbArr['category_id'] = $category_front->id;
            }
        } else {
            $crumbArr['sub_sub_category_id'] = null;
            $crumbArr['sub_category_id'] = null;
            $crumbArr['category_id'] = null;
            $category_front = null;
        }
        $min_price = $request->min_price;
        $max_price = $request->max_price;
        $seller_id = $request->seller_id;

        $conditions = ['published' => 1];

        if ($brand_id != null) {
            $conditions = array_merge($conditions, ['brand_id' => $brand_id]);
        } elseif ($request->brand != null) {
            $brand_id = (Brand::where('slug_ar', $request->brand)->orWhere('slug_en', $request->brand)->first() != null) ? Brand::where('slug_ar', $request->brand)->orWhere('slug_en', $request->brand)->first()->id : null;
            $conditions = array_merge($conditions, ['brand_id' => $brand_id]);
        }


        if ($seller_id != null) {
            $conditions = array_merge($conditions, ['user_id' => Seller::findOrFail($seller_id)->user->id]);
        }
        if ($category_id != null) {
            // $this->category_id = $category_id;
            $this->category_ids = CategoryUtility::children_ids($category_id);
            $this->category_ids[] = $category_id;
            // return $this->category_ids;
            $products = Product::select(['products.*', 'products.name_' . locale() . ' as name', 'products.slug_' . locale() . ' as slug'])
                ->join('product_sub_sub_categories', function ($join) {
                    $join->on('products.id', '=', 'product_sub_sub_categories.product_id')
                        ->whereIn('product_sub_sub_categories.subsubcategory_id', $this->category_ids);
                })
                ->distinct()->where($conditions);
        } else {
            $products = Product::select(['*', 'name_' . locale() . ' as name', 'slug_' . locale() . ' as slug'])->where($conditions);
        }

        if ($min_price != null && $max_price != null) {
            $products = $products->where('unit_price', '>=', $min_price)->where('unit_price', '<=', $max_price);
        }
        $tag_query = null;
        if ($query != null) {
            // return $query;
            $searchController = new SearchController;
            $searchController->store($request);
            // return $query;
            // return locale();
            $tag_query = Tags::where('name_' . locale(), $query)->first();
            $products = $products->where('name_' . locale(), 'like', '%' . $query . '%')->orWhere('tags_' . locale(), 'like', '%' . $query . '%');
        }

        if ($sort_by != null) {
            switch ($sort_by) {
                case '1':
                    $products->orderBy('created_at', 'desc');
                    break;
                case '2':
                    $products->orderBy('created_at', 'asc');
                    break;
                case '3':
                    $products->orderBy('unit_price', 'asc');
                    break;
                case '4':
                    $products->orderBy('unit_price', 'desc');
                    break;
                default:
                    // code...
                    break;
            }
        }


        $non_paginate_products = filter_products($products)->get();
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


        $products = filter_products($products)->paginate(12)->appends(request()->query());
        // return $products;
        return view('frontend.product_listing', compact('open_app', 'tag_query', 'category_front', 'products', 'query', 'category_id', 'crumbArr', 'brand_id', 'sort_by', 'seller_id', 'min_price', 'max_price', 'attributes', 'selected_attributes', 'all_colors', 'selected_color'));
    }

    public function product_content(Request $request)
    {
        $connector  = $request->connector;
        $selector   = $request->selector;
        $select     = $request->select;
        $type       = $request->type;
        productDescCache($connector, $selector, $select, $type);
    }

    public function home_settings(Request $request)
    {

        $pages = Page::pluck('title_' . locale() . ' AS title', 'id');
        $appSetting = AppSettings::find(1);
        return view('home_settings.index', compact('pages', 'appSetting'));
    }

    public function top_10_settings(Request $request)
    {
        foreach (Category::where('published', true)->get() as $key => $category) {
            if (is_array($request->top_categories) && in_array($category->id, $request->top_categories)) {
                $category->top = 1;
                $category->save();
            } else {
                $category->top = 0;
                $category->save();
            }
        }

        foreach (Brand::all() as $key => $brand) {
            if (is_array($request->top_brands) && in_array($brand->id, $request->top_brands)) {
                $brand->top = 1;
                $brand->save();
            } else {
                $brand->top = 0;
                $brand->save();
            }
        }

        flash(translate('Top 10 categories and brands have been updated successfully'))->success();
        return redirect()->route('home_settings.index');
    }

    public function frontPageStore(Request $request)
    {
        $appSetting = AppSettings::find(1);
        $appSetting->seller_policy = $request->seller_policy;
        $appSetting->return_policy = $request->return_policy;
        $appSetting->support_policy = $request->support_policy;
        $appSetting->terms_conditions = $request->terms_conditions;
        $appSetting->privacy_policy = $request->privacy_policy;
        $appSetting->save();
        // return $appSetting;
        flash(translate('Front Pages Saved'))->success();
        return redirect()->route('home_settings.index');
    }

    public function variant_price(Request $request)
    {
        $product = Product::find($request->id);
        $str = '';
        $quantity = 0;

        if ($request->has('color')) {
            $data['color'] = $request['color'];
            $str = Color::where('code', $request['color'])->first()->name;
        }

        if (json_decode(Product::find($request->id)->choice_options) != null) {
            foreach (json_decode(Product::find($request->id)->choice_options) as $key => $choice) {
                if ($str != null) {
                    $str .= '-' . str_replace(' ', '', $request['attribute_id_' . $choice->attribute_id]);
                } else {
                    $str .= str_replace(' ', '', $request['attribute_id_' . $choice->attribute_id]);
                }
            }
        }



        if ($str != null && $product->variant_product) {
            $product_stock = $product->stocks->where('variant', $str)->first();
            $price = $product_stock->price;
            $quantity = $product_stock->qty;
        } else {
            $price = $product->unit_price;
            $quantity = $product->current_stock;
        }

        //discount calculation
        $flash_deals = \App\FlashDeal::where('status', 1)->get();
        $inFlashDeal = false;
        foreach ($flash_deals as $key => $flash_deal) {
            if ($flash_deal != null && $flash_deal->status == 1 && strtotime(date('d-m-Y')) >= $flash_deal->start_date && strtotime(date('d-m-Y')) <= $flash_deal->end_date && \App\FlashDealProduct::where('flash_deal_id', $flash_deal->id)->where('product_id', $product->id)->first() != null) {
                $flash_deal_product = \App\FlashDealProduct::where('flash_deal_id', $flash_deal->id)->where('product_id', $product->id)->first();
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
        return array('price' => single_price($price * $request->quantity), 'quantity' => $quantity, 'digital' => $product->digital);
    }

    public function sellerpolicy()
    {
        return view("frontend.policies.sellerpolicy");
    }

    public function returnpolicy()
    {
        return view("frontend.policies.returnpolicy");
    }

    public function supportpolicy()
    {
        return view("frontend.policies.supportpolicy");
    }

    public function terms()
    {
        return view("frontend.policies.terms");
    }

    public function privacypolicy()
    {
        return view("frontend.policies.privacypolicy");
    }

    public function get_pick_ip_points(Request $request)
    {
        $pick_up_points = PickupPoint::all();
        return view('frontend.partials.pick_up_points', compact('pick_up_points'));
    }

    public function get_category_items(Request $request)
    {
        //        $category = Category::findOrFail($request->id);
        $category = Category::where('id', $request->id)->select(['*', 'name_' . locale() . ' as name'])->first();
        if (empty($category)) {
            abort(404);
        }
        return view('frontend.partials.category_elements', compact('category'));
    }

    public function premium_package_index()
    {
        $customer_packages = CustomerPackage::all();
        return view('frontend.customer_packages_lists', compact('customer_packages'));
    }

    public function seller_digital_product_list(Request $request)
    {
        $products = Product::where('user_id', Auth::user()->id)->where('digital', 1)->orderBy('created_at', 'desc')->paginate(10);
        return view('frontend.seller.digitalproducts.products', compact('products'));
    }
    public function show_digital_product_upload_form(Request $request)
    {
        if (\App\Addon::where('unique_identifier', 'seller_subscription')->first() != null && \App\Addon::where('unique_identifier', 'seller_subscription')->first()->activated) {
            if (Auth::user()->seller->remaining_digital_uploads > 0) {
                $business_settings = BusinessSetting::where('type', 'digital_product_upload')->first();
                $categories = Category::where('digital', 1)->where('published', true)->select(['*', 'name_' . locale() . ' as name'])->get();
                return view('frontend.seller.digitalproducts.product_upload', compact('categories'));
            } else {
                flash(translate('Upload limit has been reached. Please upgrade your package.'))->warning();
                return back();
            }
        }

        $business_settings = BusinessSetting::where('type', 'digital_product_upload')->first();
        $categories = Category::where('digital', 1)->where('published', true)->get();
        return view('frontend.seller.digitalproducts.product_upload', compact('categories'));
    }

    public function show_digital_product_edit_form(Request $request, $id)
    {
        $categories = Category::where('digital', 1)->where('published', true)->select(['*', 'name_' . locale() . ' as name'])->get();
        $product = Product::find(decrypt($id));
        return view('frontend.seller.digitalproducts.product_edit', compact('categories', 'product'));
    }

    // Ajax call
    public function new_verify(Request $request)
    {
        $email = $request->email;
        if (isUnique($email) == '0') {
            $response['status'] = 2;
            $response['message'] = 'Email already exists!';
            return json_encode($response);
        }

        $response = $this->send_email_change_verification_mail($request, $email);
        return json_encode($response);
    }
    
    // Form request
    public function update_email(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|ends_with:gmail.com,hotmail.com,yahoo.com',
        ],[
            'email.ends_with' => 'please insert right email just gmail or hotmail or yahoo',
        ]);
        
        $email = $request->email;
        if (isUnique($email)) {
            // $this->send_email_change_verification_mail($request, $email);
            auth()->user()->update(['email' => $request->get('email')]);
            flash(translate('A verification mail has been sent to the mail you provided us with.'))->success();
            return back();
        }

        flash(translate('Email already exists!'))->warning();
        return back();
    }

    public function send_email_change_verification_mail($request, $email)
    {
        $response['status'] = 0;
        $response['message'] = 'Unknown';

        $verification_code = Str::random(32);

        $array['subject'] = 'Email Verification';
        $array['from'] = env('MAIL_USERNAME');
        $array['content'] = 'Verify your account';
        $array['link'] = route('email_change.callback') . '?new_email_verificiation_code=' . $verification_code . '&email=' . $email;
        $array['sender'] = Auth::user()->name;
        $array['details'] = "Email Second";

        $user = Auth::user();
        $user->new_email_verificiation_code = $verification_code;
        $user->save();

        try {
            Mail::to($email)->queue(new SecondEmailVerifyMailManager($array));

            $response['status'] = 1;
            $response['message'] = translate("Your verification mail has been Sent to your email.");
        } catch (\Exception $e) {
            // return $e->getMessage();
            $response['status'] = 0;
            $response['message'] = $e->getMessage();
        }

        return $response;
    }

    public function email_change_callback(Request $request)
    {
        if ($request->has('new_email_verificiation_code') && $request->has('email')) {
            $verification_code_of_url_param =  $request->input('new_email_verificiation_code');
            $user = User::where('new_email_verificiation_code', $verification_code_of_url_param)->first();

            if ($user != null) {

                $user->email = $request->input('email');
                $user->new_email_verificiation_code = null;
                $user->save();

                auth()->login($user, true);

                flash(translate('Email Changed successfully'))->success();
                return redirect()->route('dashboard');
            }
        }

        flash(translate('Email was not verified. Please resend your mail!'))->error();
        return redirect()->route('dashboard');
    }
}
