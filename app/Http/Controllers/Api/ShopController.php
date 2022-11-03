<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ProductCollection;
use App\Http\Resources\ShopCollection;
use App\Models\Product;
use App\Models\Shop;
use App\Seller;

class ShopController extends Controller
{
    public function index()
    {
        $activated_sellers = array_column(Seller::where('verification_status', 1)->select('user_id')->get()->toArray(), 'user_id');
        return new ShopCollection(Shop::whereIn('user_id', $activated_sellers)->get());
    }
    public function oneShop($id)
    {
        # code...
    }
    public function info($id)
    {
        return new ShopCollection(Shop::where('id', $id)->get());
    }

    public function shopOfUser($id)
    {
        return new ShopCollection(Shop::where('user_id', $id)->get());
    }

    public function allProducts($id)
    {
        $shop = Shop::findOrFail($id);
        return new ProductCollection(Product::where(['user_id' => $shop->user_id, 'published' => 1])->latest()->paginate(10));
    }

    public function topSellingProducts($id)
    {
        $shop = Shop::findOrFail($id);
        return new ProductCollection(Product::where(['user_id' => $shop->user_id, 'published' => 1])->orderBy('num_of_sale', 'desc')->limit(4)->get());
    }

    public function featuredProducts($id)
    {
        $shop = Shop::findOrFail($id);
        return new ProductCollection(Product::where(['user_id' => $shop->user_id, 'featured'  => 1, 'published' => 1])->latest()->get());
    }

    public function newProducts($id)
    {
        $shop = Shop::findOrFail($id);
        return new ProductCollection(Product::where(['user_id' => $shop->user_id, 'published' => 1])->orderBy('created_at', 'desc')->limit(10)->get());
    }

    public function brands($id)
    {
    }
}
