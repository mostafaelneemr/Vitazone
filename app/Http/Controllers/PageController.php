<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Page;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pages = Page::select([
            '*', 'title_' . locale() . ' as title', 'slug_' . locale() . ' as slug', 'content_' . locale() . ' as content', 'meta_title_' . locale() . ' as meta_title', 'meta_description_' . locale() . ' as meta_description', 'keywords_' . locale() . ' as keywords'
        ])->get();
        return view('pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title_en' => 'required',
            'title_ar' => 'required',
            'slug_en' => 'required|unique:pages,slug_en',
            'slug_ar' => 'required|unique:pages,slug_ar',
        ]);
        $page = new Page;
        $page->title_en = $request->title_en;
        $page->title_ar = $request->title_ar;
        $page->slug_en = str_replace(' ', '-', $request->slug_en);
        $page->slug_ar = str_replace(' ', '-', $request->slug_ar);
        $page->content_en = $request->content_en;
        $page->content_ar = $request->content_ar;
        $page->meta_title_en = $request->meta_title_en;
        $page->meta_title_ar = $request->meta_title_ar;
        $page->meta_description_en = $request->meta_description_en;
        $page->meta_description_ar = $request->meta_description_ar;
        $page->keywords_en = $request->keywords_en;
        $page->keywords_ar = $request->keywords_ar;
        $page->meta_image = $request->meta_image;
        $page->cover_photo = $request->cover_photo;
        $page->cover_photo_mobile = $request->cover_photo_mobile;
        $page->icon = $request->icon;
        if ($page->save()) {
            flash(translate('New page has been created successfully'))->success();
            return redirect()->route('pages.index');
        }
        flash(translate('Slug has been used already'))->warning();
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
        $page = Page::where('slug_' . locale(), $id)->first();
        if ($page != null) {
            return view('pages.edit', compact('page'));
        }
        abort(404);
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
        $request->validate([
            'title_en' => 'required',
            'title_ar' => 'required',
            'slug_en' => 'required|unique:pages,slug_en,' . $id,
            'slug_ar' => 'required|unique:pages,slug_ar,' . $id,
        ]);

        $page = Page::findOrFail($id);
        $page->title_en = $request->title_en;
        $page->title_ar = $request->title_ar;
        $page->slug_en = str_replace(' ', '-', $request->slug_en);
        $page->slug_ar = str_replace(' ', '-', $request->slug_ar);
        $page->content_en = $request->content_en;
        $page->content_ar = $request->content_ar;
        $page->meta_title_en = $request->meta_title_en;
        $page->meta_title_ar = $request->meta_title_ar;
        $page->meta_description_en = $request->meta_description_en;
        $page->meta_description_ar = $request->meta_description_ar;
        $page->keywords_en = $request->keywords_en;
        $page->keywords_ar = $request->keywords_ar;
        $page->icon = $request->icon;
        $page->meta_image = $request->meta_image;
        $page->cover_photo = $request->cover_photo;
        $page->cover_photo_mobile = $request->cover_photo_mobile;
        $page->save();
        flash(translate('New page has been created successfully'))->success();
        if ($request->button != 'save') {
            return redirect()->route('pages.index');
        } else {
            return redirect()->route('pages.edit', $page->{'slug_' . locale()});
        }

        flash(translate('Slug has been used already'))->warning();
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
        if (Page::destroy($id)) {
            flash(translate('Page has been deleted successfully'))->success();
            return redirect()->back();
        }
        return back();
    }

    public function show_custom_page($slug)
    {
        if (is_numeric($slug)) {
            $page = Page::where('id', $slug)->first();
        } else {
            $page = Page::where('slug_ar', $slug)->OrWhere('slug_en', $slug)->first();
        }
        if ($page != null) {
            if ($page->{'slug_' . locale()} != $slug) {
                return  redirect()->route('custom-pages.show_custom_page', $page->{'slug_' . locale()});
            }
            $page = Page::select(['*'])->where('slug_' . locale(), $slug)->first();
            if ($page != null) {
                return view('frontend.custom_page', compact('page'));
            }
            abort(404);
        }
        abort(404);
    }


    public function update_mobile_appear(Request $request)
    {
        $page = Page::findOrFail($request->id);
        $page->mobile_apear = $request->mobile_apear;
        if ($page->save()) {
            return 1;
        }
        return 0;
    }
}
