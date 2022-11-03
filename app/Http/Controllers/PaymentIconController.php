<?php

namespace App\Http\Controllers;

use App\PaymentIcon;
use Illuminate\Http\Request;

class PaymentIconController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $icons = PaymentIcon::all();
        return view('icons.index', compact('icons'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('icons.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $icon = new PaymentIcon;
        $icon->icon = $request->icon;
        $icon->title_ar = $request->title_ar;
        $icon->title_en = $request->title_en;
        $icon->link = $request->link;
        $icon->save();
        flash(translate('Icon has been inserted successfully'))->success();
        return redirect()->route('home_settings.index');
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
        $icon = PaymentIcon::findOrFail($id);
        return view('icons.edit', compact('icon'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $icon = PaymentIcon::find($id);
        $icon->icon = $request->icon;
        $icon->title_ar = $request->title_ar;
        $icon->title_en = $request->title_en;
        $icon->link = $request->link;
        $icon->save();
        flash(translate('Icon has been updated successfully'))->success();
        return redirect()->route('home_settings.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (PaymentIcon::destroy($id)) {
            flash(translate('Payment Icon has been deleted successfully'))->success();
        } else {
            flash(translate('Something went wrong'))->error();
        }
        return redirect()->route('home_settings.index');
    }
}
