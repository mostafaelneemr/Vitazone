<?php

namespace App\Http\Controllers;

use App\AppStartPage;
use App\Models\AppSettings;
use Illuminate\Http\Request;

class AppStartPagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pages = AppStartPage::get();
        return view("startPages.index", compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (AppStartPage::all()->count() <= 2) {
            return view('startPages.create');
        } else {
            flash(translate('You can not add more than 3 pages'))->error();
            return back();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title_en' => 'required|max:50',
            'title_ar' => 'required|max:50',
            'sub_title_en' => 'required|max:255',
            'sub_title_ar' => 'required|max:255',
        ]);
        $link = new AppStartPage;
        $link->image = $request->image;
        $link->title_en = $request->title_en;
        $link->title_ar = $request->title_ar;
        $link->sub_title_en = $request->sub_title_en;
        $link->sub_title_ar = $request->sub_title_ar;
        if ($link->save()) {
            flash(translate('Page has been inserted successfully'))->success();
            return redirect()->route('startPages.index');
        }
        flash(translate('Something went wrong'))->error();
        return back();
    }

    public function updatePromotion(Request $request)
    {
        $input = $request->all();
        $input['promotion_appear'] = ($request->promotion_appear == 'on') ? true : false;
        AppSettings::find(1)->update($input);
        return back();
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
        $page = AppStartPage::findOrFail(decrypt($id));
        return view('startPages.edit', compact('page'));
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
        $this->validate($request, [
            'title_en' => 'required|max:50',
            'title_ar' => 'required|max:50',
            'sub_title_en' => 'required|max:255',
            'sub_title_ar' => 'required|max:255',
        ]);
        $link = AppStartPage::findOrFail($id);
        $link->image = $request->image;
        $link->title_en = $request->title_en;
        $link->title_ar = $request->title_ar;
        $link->sub_title_en = $request->sub_title_en;
        $link->sub_title_ar = $request->sub_title_ar;
        if ($link->save()) {
            flash(translate('Link has been updated successfully'))->success();
            if ($request->button != 'save') {
                return redirect()->route('startPages.index');
            } else {
                return redirect()->route('startPages.edit', encrypt($link->id));
            }
        }
        flash(translate('Something went wrong'))->error();
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (AppStartPage::destroy($id)) {
            flash(translate('Page has been deleted successfully'))->success();
            return redirect()->route('startPages.index');
        }

        flash(translate('Something went wrong'))->error();
        return back();
    }
}
