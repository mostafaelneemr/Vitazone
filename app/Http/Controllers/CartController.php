<?php

namespace App\Http\Controllers;

use App\Address;
use Illuminate\Http\Request;
use App\Product;
use App\SubSubCategory;
use App\Category;
use Session;
use App\Color;
use App\Phone;
use Cookie;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index(Request $request)
    {

        $categories = Category::where('published', true)->select(['*', 'name_' . locale() . ' as name'])->get();

        if (Auth::check()) {
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
        } else {
            $default_phone = false;
        }

        return view('frontend.view_cart', compact('categories', 'default_phone'));
    }

    public function showCartModal(Request $request)
    {
        $product = Product::find($request->id);
        return view('frontend.partials.addToCart', compact('product'));
    }

    public function updateNavCart(Request $request)
    {
        return view('frontend.partials.cart');
    }

    public function addToCart(Request $request)
    {
        $product = Product::find($request->id);

        $data = array();
        $data['id'] = $product->id;
        $str = '';
        $tax = 0;

        if ($product->digital != 1 && $request->quantity < $product->min_qty) {
            return view('frontend.partials.minQtyNotSatisfied', [
                'min_qty' => $product->min_qty
            ]);
        }


        //check the color enabled or disabled for the product
        if ($request->has('color')) {
            $data['color'] = $request['color'];
            $str = Color::where('code', $request['color'])->first()->name;
        }

        if ($product->digital != 1) {
            //Gets all the choice values of customer choice option and generate a string like Black-S-Cotton
            foreach (json_decode(Product::find($request->id)->choice_options) as $key => $choice) {
                if ($str != null) {
                    $str .= '-' . str_replace(' ', '', $request['attribute_id_' . $choice->attribute_id]);
                } else {
                    $str .= str_replace(' ', '', $request['attribute_id_' . $choice->attribute_id]);
                }
            }
        }

        $data['variant'] = $str;

        if ($str != null && $product->variant_product) {
            $product_stock = $product->stocks->where('variant', $str)->first();
            $price = $product_stock->price;
            $quantity = $product_stock->qty;

            if ($quantity >= $request['quantity']) {
                // $variations->$str->qty -= $request['quantity'];
                // $product->variations = json_encode($variations);
                // $product->save();
            } else {
                return view('frontend.partials.outOfStockCart');
            }
        } else {
            $price = $product->unit_price;
        }

        //discount calculation based on flash deal and regular discount
        //calculation of taxes
        $flash_deals = \App\FlashDeal::where('status', 1)->get();
        $inFlashDeal = false;
        foreach ($flash_deals as $flash_deal) {
            if ($flash_deal != null && $flash_deal->status == 1  && strtotime(date('d-m-Y')) >= $flash_deal->start_date && strtotime(date('d-m-Y')) <= $flash_deal->end_date && \App\FlashDealProduct::where('flash_deal_id', $flash_deal->id)->where('product_id', $product->id)->first() != null) {
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
            $tax = ($price * $product->tax) / 100;
        } elseif ($product->tax_type == 'amount') {
            $tax = $product->tax;
        }

        $data['quantity'] = $request['quantity'];
        $data['price'] = $price;
        $data['tax'] = $tax;
        $data['shipping'] = 0;
        $data['product_referral_code'] = null;
        $data['digital'] = $product->digital;

        if ($request['quantity'] == null) {
            $data['quantity'] = 1;
        }

        if (Cookie::has('referred_product_id') && Cookie::get('referred_product_id') == $product->id) {
            $data['product_referral_code'] = Cookie::get('product_referral_code');
        }

        if ($request->session()->has('cart')) {
            $foundInCart = false;
            $cart = collect();

            foreach ($request->session()->get('cart') as $key => $cartItem) {
                if ($cartItem['id'] == $request->id) {
                    if ($cartItem['variant'] == $str) {
                        $foundInCart = true;
                        $cartItem['quantity'] += $request['quantity'];
                    }
                }
                $cart->push($cartItem);
            }

            if (!$foundInCart) {
                $cart->push($data);
            }
            $request->session()->put('cart', $cart);

            $cart = $cart->map(function ($object, $key) use ($request) {
                $object['shipping'] = getShippingCost($key);
                return $object;
            });

            $request->session()->put('cart', $cart);
        } else {
            $cart = collect([$data]);
            $request->session()->put('cart', $cart);
        }

        return view('frontend.partials.addedToCart', compact('product', 'data'));
    }

    //removes from Cart
    public function removeFromCart(Request $request)
    {
        if ($request->session()->has('cart')) {
            $cart = $request->session()->get('cart', collect([]));
            $cart->forget($request->key);
            $request->session()->put('cart', $cart);
            $cart = $cart->map(function ($object, $key) use ($request) {
                $object['shipping'] = getShippingCost($key);
                return $object;
            });

            $request->session()->put('cart', $cart);
        }

        return view('frontend.partials.cart_details');
    }

    public function verifyAdressPhone(Request $request)
    {
        for ($i = 0; $i <= 10; $i++) {
            $smsCod = rand(1, 9999);
            $newCode = str_split($smsCod, 1);
            if (!in_array(0, $newCode) && sizeof($newCode) == 4) {
                break;
            }
        }

        $checkphone_exist = Phone::where(['phone' => $request->phone, 'user_id' => Auth::user()->id])->get();
        if (sizeof($checkphone_exist) > 0) {
            if ($checkphone_exist[0]->status == 'has_attempts') {
                if ($checkphone_exist[0]->attempts_num >= 3) {
                    $checkphone_exist[0]->status = 'blocked';
                    $checkphone_exist[0]->save();
                    $data['success'] = false;
                    $data['status'] = 'blocked';
                    $data['message'] = 'This phone number was bloked .';
                    return response()->json($data);
                } else {
                    sendOtp($smsCod, $request->phone);
                    $checkphone_exist[0]->attempts_num += 1;
                    $checkphone_exist[0]->v_code = $smsCod;
                    $checkphone_exist[0]->save();
                    $data['success'] = true;
                    $data['status'] = 'code_send';
                    $data['message'] = 'Verification Code was sent to phone.';
                    return response()->json($data);
                }
            } elseif ($checkphone_exist[0]->status == 'blocked') {
                $data['success'] = false;
                $data['status'] = 'blocked';
                $data['message'] = 'This phone number was bloked .';
                return response()->json($data);
            } elseif ($checkphone_exist[0]->status == 'actived') {
                $data['success'] = false;
                $data['status'] = 'active';
                $data['message'] = 'This phone number already Actived';
                return response()->json($data);
            }
        } else {
            sendOtp($smsCod, $request->phone);
            $save_phone = Phone::create([
                'user_id' => Auth::user()->id,
                'phone' => $request->phone,
                'v_code' => $smsCod,
                'attempts_num' => 1,
            ]);
            $data['success'] = true;
            $data['status'] = 'code_send';
            $data['message'] = 'Verification Code was sent to phone.';
            return response()->json($data);
        }
    }
    public function verifyAdressPhoneAndGetResult(Request $request)
    {
        $checkphone_exist = Phone::where(['phone' => $request->phone, 'v_code' => $request->code])->get();
        if (sizeof($checkphone_exist) > 0) {
            $checkphone_exist[0]->status = 'actived';
            $checkphone_exist[0]->save();
            return response()->json(['success' => true]);
        } else {
            $phone = Phone::where('phone', $request->phone)->get();
            if (sizeof($phone) > 0) {
                if ($phone[0]->attempts_num > 2) {
                    $phone[0]->status = 'blocked';
                    $phone[0]->save();
                    $status = 'blocked';
                } else {
                    $phone[0]->attempts_num = $phone[0]->attempts_num + 1;
                    $phone[0]->save();
                    $status = 'not_bloked';
                }
            } else {
                $phoneArr['phone'] = $request->phone;
                Phone::create($phoneArr);
            }

            return response()->json(['success' => false, 'status' => $status]);
        }
    }
    //updated the quantity for a cart item
    public function updateQuantity(Request $request)
    {
        $cart = $request->session()->get('cart', collect([]));
        $cart = $cart->map(function ($object, $key) use ($request) {
            if ($key == $request->key) {
                $product = \App\Product::find($object['id']);
                if ($object['variant'] != null && $product->variant_product) {
                    $product_stock = $product->stocks->where('variant', $object['variant'])->first();
                    $quantity = $product_stock->qty;
                    if ($quantity >= $request->quantity) {
                        if ($request->quantity >= $product->min_qty) {
                            $object['quantity'] = $request->quantity;
                        }
                    }
                } elseif ($request->quantity >= $product->min_qty) {
                    $object['quantity'] = $request->quantity;
                }
            }
            return $object;
        });

        $request->session()->put('cart', $cart);

        $cart = $cart->map(function ($object, $key) use ($request) {
            $object['shipping'] = getShippingCost($key);
            return $object;
        });

        $request->session()->put('cart', $cart);

        return view('frontend.partials.cart_details');
    }
}
