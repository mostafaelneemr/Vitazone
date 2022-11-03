<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\GeneralSetting;
use ImageOptimizer;
use App\Http\Controllers\BusinessSettingsController;

class GeneralSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $generalsetting = GeneralSetting::first();
        return view("general_settings.index", compact("generalsetting"));
    }

    public function logo()
    {
        $generalsetting = GeneralSetting::first();
        return view("general_settings.logo", compact("generalsetting"));
    }

    //updates the logo and favicons of the system
    public function storeLogo(Request $request)
    {
        $generalsetting = GeneralSetting::first();

        $generalsetting->watermark_en = $request->watermark_en;
        $generalsetting->x_direction = $request->x_direction;
        $generalsetting->y_direction = $request->y_direction;
        $generalsetting->watermark_ar = $request->watermark_ar;
        $generalsetting->logo_en = $request->logo_en;
        $generalsetting->logo_ar = $request->logo_ar;
        $generalsetting->footer_logo_en = $request->footer_logo_en;
        $generalsetting->footer_logo_ar = $request->footer_logo_ar;
        $generalsetting->admin_logo_en = $request->admin_logo_en;
        $generalsetting->admin_logo_ar = $request->admin_logo_ar;
        $generalsetting->favicon_en = $request->favicon_en;
        $generalsetting->favicon_ar = $request->favicon_ar;
        $generalsetting->admin_login_background_en = $request->admin_login_background_en;
        $generalsetting->admin_login_background_ar = $request->admin_login_background_ar;
        $generalsetting->admin_login_sidebar_en = $request->admin_login_sidebar_en;
        $generalsetting->admin_login_sidebar_ar = $request->admin_login_sidebar_ar;

        if ($generalsetting->save()) {
            flash(translate('Logo settings has been updated successfully'))->success();
            return redirect()->route('generalsettings.logo');
        } else {
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }

    public function color()
    {
        $generalsetting = GeneralSetting::first();
        return view("general_settings.color", compact("generalsetting"));
    }

    //updates system ui color
    public function storeColor(Request $request)
    {
        $generalsetting = GeneralSetting::first();
        $generalsetting->frontend_color = $request->frontend_color;

        if ($generalsetting->save()) {
            flash(translate('Color settings has been updated successfully'))->success();
            return redirect()->route('generalsettings.color');
        } else {
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        $val = $this->validate($request, [
            'site_name_en' => 'required',
            'site_name_ar' => 'required',
            'address_en' => 'required',
            'address_ar' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'description_en' => 'required',
            'description_ar' => 'required',
            'facebook' => 'required',
            'instagram' => 'required',
            'twitter' => 'required',
            'youtube' => 'required',
            'google_plus' => 'required',
        ]);
        $generalsetting = GeneralSetting::first();
        $generalsetting->site_name_en = $request->site_name_en;
        $generalsetting->site_name_ar = $request->site_name_ar;
        $generalsetting->address_en = $request->address_en;
        $generalsetting->address_ar = $request->address_ar;
        $generalsetting->invoice_instructions_en = $request->invoice_instructions_en;
        $generalsetting->invoice_instructions_ar = $request->invoice_instructions_ar;
        $generalsetting->phone = $request->phone;
        $generalsetting->email = $request->email;
        $generalsetting->description_en = $request->description_en;
        $generalsetting->description_ar = $request->description_ar;
        $generalsetting->facebook = $request->facebook;
        $generalsetting->instagram = $request->instagram;
        $generalsetting->twitter = $request->twitter;
        $generalsetting->youtube = $request->youtube;
        $generalsetting->google_plus = $request->google_plus;

        if ($generalsetting->save()) {
            $businessSettingsController = new BusinessSettingsController;
            $businessSettingsController->overWriteEnvFile('APP_NAME', $request->name);
            $businessSettingsController->overWriteEnvFile('APP_TIMEZONE', $request->timezone);

            flash(translate('GeneralSetting has been updated successfully'))->success();
            return redirect()->route('generalsettings.index');
        } else {
            flash(translate('Something went wrong'))->error();
            return back();
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
        //
    }
}
