<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SeoSetting;

class SEOController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $seosetting = SeoSetting::first();
        return view('seo_settings.index', compact("seosetting"));
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
        $this->validate($request, [
            'author_en' => 'required|max:255',
            'author_ar' => 'required|max:255',
            'revisit' => 'required',
            'sitemap' => 'required',
            'description_en' => 'required',
            'description_ar' => 'required',
        ]);
        $seosetting = SeoSetting::first();
        $keyword_en = array();
        if ($request->keyword_en[0] != null) {
            foreach (json_decode($request->keyword_en[0]) as $key => $keyword) {
                array_push($keyword_en, $keyword->value);
            }
        }
        $seosetting->keyword_en = implode(',', $keyword_en);
        $keyword_ar = array();
        if ($request->keyword_ar[0] != null) {
            foreach (json_decode($request->keyword_ar[0]) as $key => $keyword) {
                array_push($keyword_ar, $keyword->value);
            }
        }
        $seosetting->keyword_ar = implode(',', $keyword_ar);
        $seosetting->title_en = $request->title_en;
        $seosetting->title_ar = $request->title_ar;
        $seosetting->author_en = $request->author_en;
        $seosetting->author_ar = $request->author_ar;
        $seosetting->revisit = $request->revisit;
        $seosetting->sitemap_link = $request->sitemap;
        $seosetting->description_en = $request->description_en;
        $seosetting->description_ar = $request->description_ar;


        $seosetting->products_meta_title_ar = $request->products_meta_title_ar;
        $seosetting->products_meta_title_en = $request->products_meta_title_en;
        $seosetting->products_meta_description_ar = $request->products_meta_description_ar;
        $seosetting->products_meta_description_en = $request->products_meta_description_en;

        $seosetting->categories_meta_title_ar = $request->categories_meta_title_ar;
        $seosetting->categories_meta_title_en = $request->categories_meta_title_en;
        $seosetting->categories_meta_description_ar = $request->categories_meta_description_ar;
        $seosetting->categories_meta_description_en = $request->categories_meta_description_en;

        $seosetting->brands_meta_title_ar = $request->brands_meta_title_ar;
        $seosetting->brands_meta_title_en = $request->brands_meta_title_en;
        $seosetting->brands_meta_description_ar = $request->brands_meta_description_ar;
        $seosetting->brands_meta_description_en = $request->brands_meta_description_en;

        $seosetting->customer_products_meta_title_ar = $request->customer_products_meta_title_ar;
        $seosetting->customer_products_meta_title_en = $request->customer_products_meta_title_en;
        $seosetting->customer_products_meta_description_ar = $request->customer_products_meta_description_ar;
        $seosetting->customer_products_meta_description_en = $request->customer_products_meta_description_en;

        $seosetting->customer_products_meta_title_concat_ar = $request->customer_products_meta_title_concat_ar;
        $seosetting->customer_products_meta_title_concat_en = $request->customer_products_meta_title_concat_en;
        $seosetting->customer_products_meta_description_concat_ar = $request->customer_products_meta_description_concat_ar;
        $seosetting->customer_products_meta_description_concat_en = $request->customer_products_meta_description_concat_en;

        $seosetting->blog_meta_title_en = $request->blog_meta_title_en;
        $seosetting->blog_meta_title_ar = $request->blog_meta_title_ar;
        $seosetting->blog_meta_description_en = $request->blog_meta_description_en;
        $seosetting->blog_meta_description_ar = $request->blog_meta_description_ar;

        if ($seosetting->save()) {
            flash(translate('SEO Setting has been updated successfully'))->success();
            return redirect()->route('seosetting.index');
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
