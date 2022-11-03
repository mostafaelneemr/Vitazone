<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Currency;

class CurrencyController extends Controller
{

    public function changeCurrency(Request $request)
    {
    	$request->session()->put('currency_code', $request->currency_code);
        $currency = Currency::select(['*','name_'.locale().' as name','symbol_'.locale().' as symbol'])->where('code', $request->currency_code)->first();
    	flash(translate('Currency changed to ').$currency->name)->success();
    }

    public function currency(Request $request)
    {
        $sort_search =null;
        $currencies = Currency::orderBy('created_at', 'desc');
        if ($request->has('search')){
            $sort_search = $request->search;
            $currencies = $currencies->where('name_' . locale(), 'like', '%'.$sort_search.'%');
        }
        $currencies = $currencies->select(['*','name_'.locale().' as name','symbol_'.locale().' as symbol'])->paginate(10);
        // $currencies = Currency::select(['*','name_'.locale().' as name','symbol_'.locale().' as symbol'])->get();
        $active_currencies = Currency::select(['*','name_'.locale().' as name'])->where('status', 1)->get();
        return view('business_settings.currency', compact('currencies', 'active_currencies'));
    }

    // public function updateCurrency(Request $request)
    // {
    //     $currency = Currency::findOrFail($request->id);
    //     $currency->exchange_rate = $request->exchange_rate;
    //     $currency->status = $request->status;
    //     if($currency->save()){
    //         flash(translate('Currency updated successfully'))->success();
    //         return '1';
    //     }
    //     flash(translate('Something went wrong'))->error();
    //     return '0';
    // }

    public function updateYourCurrency(Request $request)
    {
        $this->validate($request,[
            'name_en'=>'required|max:255',
            'name_ar'=>'required|max:255',
            'symbol_en'=>'required',
            'symbol_ar'=>'required',
            'exchange_rate'=>'required',
            'code'=>'required',
        ]);
        $currency = Currency::findOrFail($request->id);
        $currency->name_en = $request->name_en;
        $currency->name_ar = $request->name_ar;
        $currency->symbol_en = $request->symbol_en;
        $currency->symbol_ar = $request->symbol_ar;
        $currency->code = $request->code;
        $currency->exchange_rate = $request->exchange_rate;
        $currency->status = $currency->status;
        if($currency->save()){
            flash(translate('Currency updated successfully'))->success();
            return redirect()->route('currency.index');
        }
        else {
            flash(translate('Something went wrong'))->error();
            return redirect()->route('currency.index');
        }
    }

    public function create()
    {
        return view('partials.currency_create');
    }

    public function edit(Request $request)
    {
        $currency = Currency::findOrFail($request->id);
        return view('partials.currency_edit', compact('currency'));
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name_en'=>'required|max:255',
            'name_ar'=>'required|max:255',
            'symbol_en'=>'required',
            'symbol_ar'=>'required',
            'exchange_rate'=>'required',
            'code'=>'required',
        ]);

        $currency = new Currency;
        $currency->name_en = $request->name_en;
        $currency->name_ar = $request->name_ar;
        $currency->symbol_en = $request->symbol_en;
        $currency->symbol_ar = $request->symbol_ar;
        $currency->code = $request->code;
        $currency->exchange_rate = $request->exchange_rate;
        $currency->status = '0';
        if($currency->save()){
            flash(translate('Currency updated successfully'))->success();
            return redirect()->route('currency.index');
        }
        else {
            flash(translate('Something went wrong'))->error();
            return redirect()->route('currency.index');
        }
    }

    public function update_status(Request $request)
    {
        $currency = Currency::findOrFail($request->id);
        $currency->status = $request->status;
        if($currency->save()){
            return 1;
        }
        return 0;
    }
}
