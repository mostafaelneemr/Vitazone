<?php

namespace App\Http\Controllers;

use FontLib\Table\Type\loca;
use Illuminate\Http\Request;
use App\Brand;
use App\Product;
use GuzzleHttp\Client;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // return phoneWithoutCountryCode('1153430338');
        // return removePhoneZero('01153430338');

        $sort_search = null;
        $brands = Brand::select(['*', 'name_' . locale() . ' as name'])->orderBy('created_at', 'desc');
        if ($request->has('search')) {
            $sort_search = $request->search;
            $brands = $brands->where('name_ar', 'like', '%' . $sort_search . '%')
                ->orWhere('name_en', 'like', '%' . $sort_search . '%');
        }
        $brands = $brands->paginate(15);
        return view('brands.index', compact('brands', 'sort_search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('brands.create');
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
            'name_ar' => 'required',
            'name_en' => 'required',
            'meta_title_ar' => 'required',
            'meta_title_en' => 'required',
            'slug_en' => 'required',
            'slug_ar' => 'required',
            'meta_description_ar' => 'required',
            'meta_description_en' => 'required',
        ]);
        $theRequest = $request->only(['name_ar', 'name_en', 'logo', 'meta_title_ar', 'meta_title_en', 'meta_description_ar', 'meta_description_en']);
        if ($request->slug_en != null) {
            $theRequest['slug_en'] =  $request->slug_en;
        } else {
            $theRequest['slug_en'] = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name_en)) . '-' . Str::random(5);
        }

        if ($request->slug_ar != null) {
            $theRequest['slug_ar'] = $request->slug_ar;
        } else {
            $theRequest['slug_ar'] = str_replace(' ', '-', $request->name_ar);
        }

        if (Brand::create($theRequest)) {
            flash(translate('Brand has been inserted successfully'))->success();
            return redirect()->route('brands.index');
        } else {
            flash(translate('Something went wrong'))->error();
            return back();
        }
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
        $brand = Brand::findOrFail(decrypt($id));
        return view('brands.edit', compact('brand'));
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
        // return $request;

        $brand = Brand::findOrFail($id);
        $brand->name_ar = $request->name_ar;
        $brand->name_en = $request->name_en;
        $brand->logo = $request->logo;
        $brand->meta_title_ar = $request->meta_title_ar;
        $brand->meta_title_en = $request->meta_title_en;
        $brand->meta_description_ar = $request->meta_description_ar;
        $brand->meta_description_en = $request->meta_description_en;
        if ($request->slug_en != null) {
            $brand->slug_en =  $request->slug_en;
        } else {
            $brand->slug_en = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name_en)) . '-' . Str::random(5);
        }

        if ($request->slug_ar != null) {
            $brand->slug_ar = $request->slug_ar;
        } else {
            $brand->slug_ar = str_replace(' ', '-', $request->name_ar);
        }


        $brand->save();
        flash(translate('Brand has been updated successfully'))->success();
        if ($request->button != 'save') {
            return redirect()->route('brands.index');
        } else {
            return redirect()->route('brands.edit', encrypt($brand->id));
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
        $brand = Brand::findOrFail($id);
        Product::where('brand_id', $brand->id)->delete();
        if (Brand::destroy($id)) {
            if ($brand->logo != null) {
                //unlink($brand->logo);
            }
            flash(translate('Brand has been deleted successfully'))->success();
            return redirect()->route('brands.index');
        } else {
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }
}
