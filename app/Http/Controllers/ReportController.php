<?php

namespace App\Http\Controllers;

use App\Category;
use App\Models\Search;
use Illuminate\Http\Request;
use App\Product;
use App\Seller;
use App\User;

class ReportController extends Controller
{
    public function stock_report(Request $request)
    {
        if ($request->has('category_id')) {
            $products = Product::where('category_id', $request->category_id)->paginate(15);
        } else {
            $products = Product::paginate(15);
        }
        $categories =   Category::where('published',true)->select(['id', 'name_' . locale() . ' as name'])->get();
        return view('reports.stock_report', compact('products', 'categories'));
    }

    public function in_house_sale_report(Request $request)
    {
        if ($request->has('category_id')) {
            $products = Product::where('category_id', $request->category_id)->orderBy('num_of_sale', 'desc')->paginate(15);
        } else {
            $products = Product::orderBy('num_of_sale', 'desc')->paginate(15);
        }
        $categories = Category::where('published',true)->select(['id', 'name_' . locale() . ' as name'])->get();
        return view('reports.in_house_sale_report', compact('products', 'categories'));
    }

    public function seller_report(Request $request)
    {
        if ($request->has('verification_status')) {
            $sellers = Seller::where('verification_status', $request->verification_status)->paginate(15);
        } else {
            $sellers = Seller::paginate(15);
        }
        return view('reports.seller_report', compact('sellers'));
    }

    public function seller_sale_report(Request $request)
    {
        if ($request->has('verification_status')) {
            $sellers = Seller::where('verification_status', $request->verification_status)->paginate(15);
        } else {
            $sellers = Seller::paginate(15);
        }
        return view('reports.seller_sale_report', compact('sellers'));
    }

    public function wish_report(Request $request)
    {
        if ($request->has('category_id')) {
            $products = Product::where('category_id', $request->category_id)->paginate(15);
        } else {
            $products = Product::paginate(15);
        }
        $categories =   Category::where('published',true)->select(['id', 'name_' . locale() . ' as name'])->get();
        return view('reports.wish_report', compact('products', 'categories'));
    }

    public function user_search_report(Request $request)
    {
        $searches = Search::orderBy('count', 'desc')->paginate(10);
        return view('reports.user_search_report', compact('searches'));
    }

    public function user_search_report_clear()
    {
        Search::truncate();
        return back();
    }
}
