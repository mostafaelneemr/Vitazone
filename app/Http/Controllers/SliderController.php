<?php

namespace App\Http\Controllers;

use App\Models\GeneralSetting;
use Illuminate\Http\Request;
use App\Slider;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home_settings.index');
        return redirect()->route('home_settings.index');
        $sliders = Slider::all();
        return view('sliders.index', compact('sliders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('sliders.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $slider = new Slider;
        $slider->link_en = $request->url_en;
        $slider->link_ar = $request->url_ar;
        $slider->mobile_link = $request->mobile_link;
        $slider->photo_mobile_en = $request->photo_mobile_en;
        $slider->photo_mobile_ar = $request->photo_mobile_ar;
        $slider->photo_web_en = $request->photo_web_en;
        $slider->photo_web_ar = $request->photo_web_ar;
        $slider->save();
        flash(translate('Slider has been inserted successfully'))->success();
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
        $slider = Slider::findOrFail($id);
        // return $slider;
        return view('sliders.edit', compact('slider'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function updateAll(Request $request, $id)
    {
        $banner = Slider::find($id);
        $banner->photo_web_ar = $request->photo_web_ar;
        $banner->photo_web_en = $request->photo_web_en;
        $banner->photo_mobile_en = $request->photo_mobile_en;
        $banner->photo_mobile_ar = $request->photo_mobile_ar;
        $banner->link_en = $request->link_en;
        $banner->link_ar = $request->link_ar;
        $banner->mobile_link = $request->mobile_link;
        $banner->save();
        flash(translate('Banner has been updated successfully'))->success();
        return redirect()->route('home_settings.index');
    }

    public function update(Request $request, $id)
    {
        $slider = Slider::find($id);
        $slider->published = $request->status;
        if ($slider->save()) {
            return '1';
        } else {
            return '0';
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $slider = Slider::findOrFail($id);
        if (Slider::destroy($id)) {
            //unlink($slider->photo);
            flash(translate('Slider has been deleted successfully'))->success();
        } else {
            flash(translate('Something went wrong'))->error();
        }
        return redirect()->route('home_settings.index');
    }
}
