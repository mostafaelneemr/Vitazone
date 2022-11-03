<?php

namespace App\Http\Controllers\Api;

use App\Addon;
use App\Address;
use App\Category;
use App\City;
use App\Color;
use App\Country;
use App\CustomerPackage;
use App\Http\Resources\CustomerPackageCollection;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Product;
use App\Models\OrderDetail;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\BusinessSetting;
use App\Models\Wallet;
use App\OrderDetail as AppOrderDetail;
use App\RefundRequest;
use App\RefundResone;
use App\Region;
use App\Review;
use App\Shop;
use App\User;
use App\Utility\CategoryUtility;
use Carbon\Carbon;
use DB;
use Dotenv\Regex\Success;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function processOrder(Request $request)
    {
        $shippingAddress['name'] = $request->shipping_adress_name;
        $shippingAddress['email'] = $request->shipping_adress_email;
        $shippingAddress['address'] = $request->shipping_adress_address;
        $shippingAddress['country'] = $request->shipping_adress_country;
        $shippingAddress['province'] = $request->shipping_adress_province;
        $shippingAddress['city'] = $request->shipping_adress_city;
        $shippingAddress['region'] = $request->shipping_adress_region;
        $shippingAddress['phone'] = $request->shipping_adress_phone;
        $shippingAddress['checkout_type'] = $request->shipping_adress_checkout_type;
        $shippingAddress['shipping_cost'] = $request->shipping_cost;
        $shippingAddress['shipping_date'] = $request->shipping_date;
        $cartItems = Cart::where('user_id', $request->user_id)->get();
        if ($request->wallet_discount > 0) {
            $userr = User::findOrFail($request->user_id);
            $userr->balance -= $request->wallet_discount;
            $userr->save();
        }
        // create an order
        $order = Order::create([
            'user_id' => $request->user_id,
            'shipping_address' => json_encode($shippingAddress),
            'payment_type' => $request->payment_type,
            'estimated_shipping_date' => $request->shipping_date,
            'payment_status' => $request->payment_status,
            'wallet_discount' => $request->wallet_discount,
            'grand_total' => $request->grand_total - $request->coupon_discount + $shippingAddress['shipping_cost'] - $request->wallet_discount,    //// 'grand_total' => $request->grand_total + $shipping,
            'coupon_discount' => $request->coupon_discount,
            'code' => date('Ymd-his'),
            'date' => strtotime('now')
        ]);

        foreach ($cartItems as $cartItem) {
            $product = Product::findOrFail($cartItem->product_id);
            if ($cartItem->variation) {
                $cartItemVariation = $cartItem->variation;
                $product_stocks = $product->stocks->where('variant', $cartItem->variation)->first();
                $product_stocks->qty -= $cartItem->quantity;
                $product_stocks->save();
            } else {
                $product->update([
                    'current_stock' => DB::raw('current_stock - ' . $cartItem->quantity)
                ]);
            }
            // save order details
            OrderDetail::create([
                'order_id' => $order->id,
                'seller_id' => $product->user_id,
                'product_id' => $product->id,
                'variation' => $cartItem->variation,
                'price' => $cartItem->price * $cartItem->quantity,
                'tax' => $cartItem->tax * $cartItem->quantity,
                'shipping_cost' => $shippingAddress['shipping_cost'],
                'quantity' => $cartItem->quantity,
                'payment_status' => $request->payment_status
            ]);
            $product->update([
                'num_of_sale' => DB::raw('num_of_sale + ' . $cartItem->quantity)
            ]);
        }
        // apply coupon usage
        if ($request->coupon_code != '') {
            CouponUsage::create([
                'user_id' => $request->user_id,
                'coupon_id' => Coupon::where('code', $request->coupon_code)->first()->id
            ]);
        }
        // calculate commission
        $commission_percentage = BusinessSetting::where('type', 'vendor_commission')->first()->value;
        foreach ($order->orderDetails as $orderDetail) {
            if ($orderDetail->product->user->user_type == 'seller') {
                $seller = $orderDetail->product->user->seller;
                $price = $orderDetail->price + $orderDetail->tax + $orderDetail->shipping_cost;
                $seller->admin_to_pay = ($request->payment_type == 'cash_on_delivery') ? $seller->admin_to_pay - ($price * $commission_percentage) / 100 : $seller->admin_to_pay + ($price * (100 - $commission_percentage)) / 100;
                $seller->save();
            }
        }
        // clear user's cart
        $user = User::findOrFail($request->user_id);
        $user->carts()->delete();
        return response()->json([
            'success' => true,
            'order_id' => $order->id,
            'message' => trans('messages.Your order has been placed successfully')
        ]);
    }

    public function store(Request $request)
    {
        return $this->processOrder($request);
    }

    public function UserBalance()
    {
        $balance = User::findOrFail(Auth::user()->id)->balance;
        $data['success'] = true;
        $data['balance'] = $balance;
        return response()->json($data);
    }

    public function walletHistory()
    {
        $history = Wallet::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->get();
        if (sizeof($history) > 0) {
            foreach ($history as $key => $value) {
                $history[$key]->payment_details = trans('messages.' . $history[$key]->payment_details);
            }
            $data['success'] = true;
            $data['data'] = $history;
        } else {
            $data['success'] = true;
            $data['data'] = [];
        }
        return response()->json($data);
    }

    public function payOrderWithWallet($order_id)
    {
        $user = Auth::user();
        $order = Order::findOrFail($order_id);
        $user->balance -= $order->grand_total;
        $user->save();
        $order->payment_type = 'wallet';
        $order->payment_status = 'paid';
        $order->save();
        foreach (AppOrderDetail::where('order_id', $order->id)->get() as $key => $value) {
            $value->payment_status = 'paid';
            $value->save();
        }
        $data['success'] = true;
        return response()->json($data);
    }
    public function getUserOrders()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)->select('id', 'code', 'payment_type', 'estimated_shipping_date AS shipping_date', 'grand_total', 'cancel_order',
         'viewed', 'delivery_viewed', 'payment_status_viewed', DB::raw('DATE_FORMAT(orders.created_at, "%d/%m/%Y") as date'))->orderBy('id', 'desc')->get();
        foreach ($orders as $key => $order) {
            if (sizeof($order->orderDetails) > 0) {
                unset($orders[$key]->orderDetails);
            } else {
                unset($orders[$key]);
            }
        }
        return response()->json(['data' => array_values($orders->toArray())]);
    }

    public function trackYourOrder($id)
    {
        $user = Auth::user();
        $this->lang = $_SERVER['HTTP_LANG'];
        $order = Order::where('id', $id)->select('id', 'user_id', 'guest_id', 'shipping_address', 'payment_type', 'estimated_shipping_date AS shipping_date', 'payment_merchent_ref', 'payment_refrence', 'manual_payment', 'manual_payment_data', 'payment_status', 'payment_details', 'grand_total', 'coupon_discount', 'code', 'viewed', 'delivery_viewed', 'payment_status_viewed', 'cancel_order', 'commission_calculated', DB::raw('DATE_FORMAT(orders.created_at, "%d/%m/%Y") as date'))->with(['orderDetails' => function ($q) {
            $q->select('id', 'order_id', 'seller_id', 'product_id', 'variation', 'price', 'tax', 'shipping_cost', 'quantity', 'seller_status', 'payment_status', 'delivery_status', 'shipping_type', 'product_referral_code','created_at')->with(['product' => function ($p) {
                $p->select('id', 'name_' . $this->lang . ' AS name', 'thumbnail_img', 'unit_price', 'added_by', 'user_id')->with(['user' => function ($u) {
                    $u->select('id', 'name', 'avatar_original');
                }]);
            }]);
        }])->first();
        $order->shipping_address = json_decode($order->shipping_address);
        $order->shipping_address->country = Country::find($order->shipping_address->country)->{'name_' . $this->lang};
        $order->shipping_address->city = City::find($order->shipping_address->city)->{'name_' . $this->lang};
        $order->shipping_address->region = (Region::where('id', $order->shipping_address->region)->first() != null) ? Region::where('id', $order->shipping_address->region)->first()->{'name_' . $this->lang} : 'Region deleted';
        $order->payment_status = trans('messages.' . $order->payment_status);
        $order->payment_type = trans('messages.' . $order->payment_type);
        $refund_request_addon = Addon::where('unique_identifier', 'refund_request')->first();
        $status = $order->orderDetails->first()->delivery_status;
        foreach ($order->orderDetails as $key => $orderDetail) {
            $order->orderDetails[$key]->can_refunded = false;
            if ($refund_request_addon != null && $refund_request_addon->activated == 1 && $order->cancel_order == 0) {
                $no_of_max_day = BusinessSetting::where('type', 'refund_request_time')->first()->value;
                $last_refund_date = $orderDetail->created_at->addDays($no_of_max_day);
                $today_date = Carbon::now();
                if ($orderDetail->product != null && $orderDetail->product->refundable != 0 && $orderDetail->refund_request == null && $today_date <= $last_refund_date && $status == 'delivered') {
                    $order->orderDetails[$key]->can_refunded = true;
                }
            }


            $order->orderDetails[$key]->payment_status = trans('messages.' . $order->orderDetails[$key]->payment_status);
            $order->orderDetails[$key]->shipping_type = trans('messages.' . $order->orderDetails[$key]->shipping_type);
            $order->orderDetails[$key]->is_reviewed = (sizeof(Review::where(['product_id' => $order->orderDetails[$key]->product_id, 'user_id' => $user->id])->get()) > 0) ? true : false;
            $order->orderDetails[$key]->is_refunded = (sizeof(RefundRequest::where('order_detail_id', $order->orderDetails[$key]->id)->get()) > 0) ? true : false;
            $order->orderDetails[$key]->product->thumbnail_img = api_asset($order->orderDetails[$key]->product->thumbnail_img);
        }
        if ($order) {
            return response()->json(['data' => $order]);
        } else {
            return response()->json(['message' => trans('messages.This order not exist')]);
        }
    }

    public function cancelOrder($order_id)
    {
        $order = Order::findOrFail($order_id);
        $order->cancel_order = 1;
        $order->save();

        $cleint = User::find($order->user_id);
        if ($order->payment_status == 'paid') {
            $cleint->balance += $order->grand_total;
            $cleint->save();
        }
        if ($cleint->device_token != null) {
            if ($cleint->lang = 'ar') {
                $notification_title = 'طلبك تم تغيير حالتة';
                $notification_text = 'طلبك ' . $order->code . ' تم تحديث حالتة ألي ' . 'تم الألغاء';
            } else {
                $notification_title = 'Order status has been updated ';
                $notification_text = 'Your order ' . $order->code . ' status has been updated to canceled';
            }
            $notification_body['reciever_id'] = $cleint->id;
            $notification_body['type'] = 'order';
            $notification_body['order_id'] = $order->id;
            $notification_body['title'] = $notification_title;
            $notification_body['text'] = $notification_text;
            $notification_body['click_action'] = 'MedicalApp';
            $notification_body['sound'] = true;
            $notification_body['icon'] = 'logo';
            $notification_body['android_channel_id'] = 'android_channel_id';
            $notification_body['high_priority'] = 'high_priority';
            $notification_body['show_in_foreground'] = true;
            sendNotification($notification_body, $cleint->device_token);
        }
        $data['success'] = true;
        $data['message'] = trans('messages.Order has been canceled');
        return response()->json($data);
    }

    public function getCouponDiscount(Request $request)
    {
        $coupon = Coupon::where('code', $request->code)->first();
        if ($coupon != null) {
            // return response()->json(strtotime(date('d-m-Y')));
            // return response()->json(date('d-m-Y',strtotime(1604959200)));
            // return response()->json(strtotime('+4 days'));
            if (strtotime(date('d-m-Y')) >= $coupon->start_date && strtotime(date('d-m-Y')) <= $coupon->end_date) {
                if (CouponUsage::where('user_id', Auth::user()->id)->where('coupon_id', $coupon->id)->first() == null) {
                    $coupon_details = json_decode($coupon->details);
                    if ($coupon->type == 'cart_base') {
                        $subtotal = 0;
                        $tax = 0;
                        $shipping = 0;
                        foreach (Cart::where('user_id', Auth::user()->id)->get() as $key => $cartItem) {
                            $subtotal += $cartItem->price * $cartItem->quantity;
                            $tax += $cartItem->tax * $cartItem->quantity;
                            $shipping = $cartItem->shipping;
                        }
                        $sum = $subtotal + $tax + $shipping;

                        if ($sum > $coupon_details->min_buy) {
                            if ($coupon->discount_type == 'percent') {
                                $coupon_discount = ($sum * $coupon->discount) / 100;
                                if ($coupon_discount > $coupon_details->max_discount) {
                                    $coupon_discount = $coupon_details->max_discount;
                                }
                            } elseif ($coupon->discount_type == 'amount') {
                                $coupon_discount = $coupon->discount;
                            }
                            $data['success'] = true;
                            $data['data']['coupon_id'] = $coupon->id;
                            $data['data']['coupon_discount'] = $coupon_discount;
                            $data['message'] = trans('messages.Coupon has been applied');
                            return response()->json($data);
                        } else {
                            $data['success'] = false;
                            $data['message'] = trans('messages.Price must be above than ') . $coupon_details->min_buy;
                            return response()->json($data);
                        }
                    } elseif ($coupon->type == 'product_base') {
                        $coupon_discount = 0;
                        foreach (Cart::where('user_id', Auth::user()->id)->get() as $key => $cartItem) {
                            foreach ($coupon_details as $key => $coupon_detail) {
                                if ($coupon_detail->product_id == $cartItem->product_id) {
                                    if ($coupon->discount_type == 'percent') {
                                        $coupon_discount += $cartItem->price * $coupon->discount / 100;
                                    } elseif ($coupon->discount_type == 'amount') {
                                        $coupon_discount += $coupon->discount;
                                    }
                                }
                            }
                        }
                        if ($coupon_discount > 0) {
                            $data['success'] = true;
                            $data['data']['coupon_id'] = $coupon->id;
                            $data['data']['coupon_discount'] = $coupon_discount;
                            $data['message'] = trans('messages.Coupon has been applied');
                            return response()->json($data);
                        } else {
                            $data['success'] = false;
                            $data['message'] = trans('messages.Not applicable to this product');
                            return response()->json($data);
                        }
                    } elseif ($coupon->type == 'category_base') {
                        $coupon_discount = 0;
                        foreach (Cart::where('user_id', Auth::user()->id)->get() as $key => $cartItem) {
                            $product = \App\Product::find($cartItem['product_id']);
                            $category_level = Category::find($coupon_details->category_id);
                            $catArr[] = $category_level->id;
                            if ($category_level->level == 0) {
                                $firstLevel_cat = CategoryUtility::get_immediate_children_ids($category_level->id);
                                foreach ($firstLevel_cat as $key => $firstLevel_cat_id) {
                                    $catArr[] = $firstLevel_cat_id;
                                    $secLevel_cat = CategoryUtility::get_immediate_children_ids($firstLevel_cat_id);
                                    foreach ($secLevel_cat as $key => $secLevel_cat_id) {
                                        $catArr[] = $secLevel_cat_id;
                                    }
                                }
                            } elseif ($category_level->level == 1) {
                                $firstLevel_cat = CategoryUtility::get_immediate_children_ids($category_level->id);
                                foreach ($firstLevel_cat as $key => $firstLevel_cat_id) {
                                    $catArr[] = $firstLevel_cat_id;
                                }
                            }
                            $product_catArr = array_column($product->subsubcategoryMany->toArray(), 'id');
                            $array_intersect = array_intersect($catArr, $product_catArr);
                            if (count($array_intersect) > 0) {
                                if ($coupon->discount_type == 'percent') {
                                    $coupon_discount += $cartItem['price'] * $coupon->discount / 100;
                                } elseif ($coupon->discount_type == 'amount') {
                                    $coupon_discount += $coupon->discount;
                                }
                            }
                        }

                        if ($coupon_discount > 0) {
                            $data['success'] = true;
                            $data['data']['coupon_id'] = $coupon->id;
                            $data['data']['coupon_discount'] = $coupon_discount;
                            $data['message'] = trans('messages.Coupon has been applied');
                            return response()->json($data);
                        } else {
                            $data['success'] = false;
                            $data['message'] = trans('messages.Not applicable to this category');
                            return response()->json($data);
                        }
                    } elseif ($coupon->type == 'user_base') {
                        $subtotal = 0;
                        $tax = 0;
                        $shipping = 0;
                        foreach (Cart::where('user_id', Auth::user()->id)->get() as $key => $cartItem) {
                            $subtotal += $cartItem->price * $cartItem->quantity;
                            $tax += $cartItem->tax * $cartItem->quantity;
                            $shipping = $cartItem->shipping;
                        }
                        $sum = $subtotal + $tax + $shipping;
                        $user_id = auth()->user()->id;
                        if ($coupon_details->user_id == $user_id) {
                            if ($coupon->discount_type == 'percent') {
                                $coupon_discount = ($sum * $coupon->discount) / 100;
                            } elseif ($coupon->discount_type == 'amount') {
                                $coupon_discount = $coupon->discount;
                            }
                            $data['success'] = true;
                            $data['data']['coupon_id'] = $coupon->id;
                            $data['data']['coupon_discount'] = $coupon_discount;
                            $data['message'] = trans('messages.Coupon has been applied');
                            return response()->json($data);
                        } else {
                            $data['success'] = false;
                            $data['message'] = trans('messages.This coupon not belongs to you.');
                            return response()->json($data);
                        }
                    } elseif ($coupon->type == 'vendor_base') {
                        $coupon_discount = 0;
                        $seller = \App\Seller::find($coupon_details->seller_id);
                        foreach (Cart::where('user_id', Auth::user()->id)->get() as $key => $cartItem) {
                            $product = \App\Product::find($cartItem->product_id);
                            if ($product->user_id == $seller->user_id) {

                                if ($coupon->discount_type == 'percent') {
                                    $coupon_discount += $cartItem->price * $coupon->discount / 100;
                                } elseif ($coupon->discount_type == 'amount') {
                                    $coupon_discount += $coupon->discount;
                                }
                            }
                        }
                        if ($coupon_discount > 0) {
                            $data['success'] = true;
                            $data['data']['coupon_id'] = $coupon->id;
                            $data['data']['coupon_discount'] = $coupon_discount;
                            $data['message'] = 'Coupon has been applied';
                            return response()->json($data);
                        } else {
                            $data['success'] = false;
                            $data['message'] = trans('messages.Not applicable to this vendor');
                            return response()->json($data);
                        }
                    }
                } else {
                    $data['success'] = false;
                    $data['message'] = trans('messages.You already used this coupon!');
                    return response()->json($data);
                }
            } else {
                $data['success'] = false;
                $data['message'] = trans('messages.Coupon expired!');
                return response()->json($data);
            }
        } else {
            $data['success'] = false;
            $data['message'] = trans('messages.Invalid coupon!');
            return response()->json($data);
        }
    }

    public function refundRequest(Request $request)
    {
        $order_detail = OrderDetail::where('id', $request->order_details_id)->get();
        if (sizeof($order_detail) > 0) {
            $refund = new RefundRequest();
            $refund->user_id = Auth::user()->id;
            $refund->order_id = $order_detail[0]->order_id;
            $refund->order_detail_id = $order_detail[0]->id;
            $refund->seller_id = $order_detail[0]->seller_id;
            $refund->seller_approval = 0;
            $refund->reason = $request->reason;
            $refund->resone_id = $request->resone_id;
            $refund->admin_approval = 0;
            $refund->admin_seen = 0;
            $refund->refund_amount = $order_detail[0]->price + $order_detail[0]->tax;
            $refund->refund_status = 0;
            if ($refund->save()) {
                $data['success'] = true;
            } else {
                $data['success'] = false;
            }
        } else {
            $data['success'] = false;
        }

        return response()->json($data);
    }

    public function getRefundResons()
    {
        $resons = RefundResone::select('id', 'resone_' . locale() . ' as resone')->get();
        if (sizeof($resons) > 0) {
            $data['success'] = true;
        } else {
            $data['success'] = false;
        }

        $data['data'] = $resons;
        return response()->json($data);
    }

    public function getPackages()
    {
        return new CustomerPackageCollection(CustomerPackage::all());
    }

    public function getClientPackage()
    {
        $packages = CustomerPackage::select('id', 'name_' . $_SERVER['HTTP_LANG'] . ' AS name', 'logo_' . $_SERVER['HTTP_LANG'] . ' AS logo', 'amount', 'product_upload')->where('id', Auth::user()->customer_package_id)->get();
        if (sizeof($packages) > 0) {
            $data['success'] = true;
            $data['data'] = $packages[0];
        } else {
            $data['success'] = false;
        }

        return response()->json($data);
    }

    public function purchaseFreePackage(Request $request)
    {
        $user = User::findOrFail(Auth::user()->id);
        if ($user->customer_package_id != $request->customer_package_id) {
            $user->customer_package_id = $request->customer_package_id;
            $customer_package = CustomerPackage::findOrFail($request->customer_package_id);
            $user->remaining_uploads += $customer_package->product_upload;
            $user->save();
            $data['success'] = true;
        } else {
            $data['success'] = true;
        }

        return response()->json($data);
    }

    public function getShippingCostDuration(Request $request)
    {
        $admin_products = array();
        $seller_products = array();
        $calculate_shipping = 0;
        $cart_total = 0;
        $regions = array();
        $user = Auth::user()->id;
        if ($request->shipping_adress_region == null) {
            $address_region = Address::where(['user_id' => $user, 'set_default' => 1])->first();
            if ($address_region != null) {
                $request->shipping_adress_region = $address_region->region;
            } else {
                $address_region = Address::where(['user_id' => $user])->first();
                if ($address_region != null) {
                    $request->shipping_adress_region = $address_region->region;
                } else {
                    $data['success'] = false;
                    $data['resone'] = 'no_address';
                }
            }
        }
        $cartItems = Cart::where('user_id', $user)->get();
        if (sizeof($cartItems) > 0) {
            if (BusinessSetting::where('type', 'shipping_type')->first()->value == 'flat_rate') {
                $threshold = BusinessSetting::where('type', 'free_shipping_treshold')->first()->value;
                if (BusinessSetting::where('type', 'free_shipping_by_city')->first()->value) {
                    $regions = explode(',', BusinessSetting::where('type', 'free_shipping_by_city')->first()->value);
                }
                $calculate_shipping = BusinessSetting::where('type', 'flat_rate_shipping_cost')->first()->value;
                if ((int)$threshold > 0) {
                    foreach ($cartItems as $key => $cartItem) {
                        $cart_total += $cartItem->price * $cartItem->quantity;
                    }
                    if ($cart_total >= (int)$threshold) {
                        $calculate_shipping = 0;
                    }
                }
                foreach ($regions as $key => $region) {
                    if ($region == $request->shipping_adress_region) {
                        $calculate_shipping = 0;
                    }
                }
            } elseif (BusinessSetting::where('type', 'shipping_type')->first()->value == 'seller_wise_shipping') {
                foreach ($cartItems as $key => $cartItem) {
                    $product = Product::find($cartItem->product_id);
                    if ($product->added_by == 'admin') {
                        array_push($admin_products, $cartItem->product_id);
                    } else {
                        $product_ids = array();
                        if (array_key_exists($product->user_id, $seller_products)) {
                            $product_ids = $seller_products[$product->user_id];
                        }
                        array_push($product_ids, $cartItem->product_id);
                        $seller_products[$product->user_id] = $product_ids;
                    }
                }
                if (!empty($admin_products)) {
                    $calculate_shipping = BusinessSetting::where('type', 'shipping_cost_admin')->first()->value;
                }
                if (!empty($seller_products)) {
                    foreach ($seller_products as $key => $seller_product) {
                        $calculate_shipping += Shop::where('user_id', $key)->first()->shipping_cost;
                    }
                }
            } elseif (get_setting('shipping_type') == 'area_wise_shipping') {
                $products_types = array_column(\App\Product::whereIn('id', array_column($cartItems->toArray(), 'product_id'))->select('id', 'light_heavy_shipping')->get()->toArray(), 'light_heavy_shipping');
                $region = Region::where('id', $request->shipping_adress_region)->first();
                if ($region != null) {
                    if (in_array('heavy', $products_types)) {
                        $calculate_shipping = $region->shipping_cost_high;
                    } else {
                        $calculate_shipping = $region->shipping_cost;
                    }
                }
            }
            $products_types = array_column(\App\Product::whereIn('id', array_column($cartItems->toArray(), 'product_id'))->select('id', 'light_heavy_shipping')->get()->toArray(), 'light_heavy_shipping');
            $region = Region::where('id', $request->shipping_adress_region)->first();
            if ($region != null) {
                if (in_array('heavy', $products_types)) {
                    $shipping_date = date('d/m/Y', strtotime('+' . $region->shipping_duration_high . ' days'));
                } else {
                    $shipping_date = date('d/m/Y', strtotime('+' . $region->shipping_duration . ' days'));
                }
            } else {
                $data['success'] = false;
                $data['resone'] = 'address_not_valid';
                return response()->json($data);
            }
            $data['success'] = true;
            $data['shipping_date'] = $shipping_date;
            $data['shipping_cost'] = doubleval($calculate_shipping);
        } else {
            $data['success'] = false;
            $data['resone'] = 'empty_cart';
        }

        return response()->json($data);
    }
}
