<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\SubSubCategory;
use App\Brand;
use App\Product;
use App\Language;
use Illuminate\Support\Str;

class SubSubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search =null;
        $subsubcategories = SubSubCategory::select(['*','name_'.locale().' as name'])->orderBy('created_at', 'desc');
        if ($request->has('search')){
            $sort_search = $request->search;
            $subsubcategories = $subsubcategories->
            where('name_ar', 'like', '%'.$sort_search.'%')
            ->where('name_en', 'like', '%'.$sort_search.'%');
        }
        $subsubcategories = $subsubcategories->paginate(15);
        return view('subsubcategories.index', compact('subsubcategories', 'sort_search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::select(['*','name_'.locale().' as name'])->get();
        $tags = \App\Tags::select(['*','name_'.locale().' as name'])->orderBy('created_at', 'desc')->get();
        $brands = Brand::select(['*','name_'.locale().' as name'])->get();
        return view('subsubcategories.create', compact('categories', 'brands', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name_ar'=>'required',
            'name_en'=>'required',
            'sub_category_id'=>'required',
            'icon' => 'nullable|image',
        ]);
//        $subsubcategory = new SubSubCategory;
//        $subsubcategory->name = $request->name;
//        $subsubcategory->sub_category_id = $request->sub_category_id;
//        $subsubcategory->meta_title = $request->meta_title;
//        $subsubcategory->meta_description = $request->meta_description;
        $theRequest = $request->only(['name_ar','name_en','sub_category_id','meta_title_ar','meta_title_en','meta_description_ar','meta_description_en']);

        if($request->hash_tags != null){
            $theRequest['tag_ids'] = implode(',',$request->hash_tags);
        }

        if ($request->slug != null) {
            $theRequest['slug'] = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug));
        }
        else {
            $theRequest['slug'] = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name_en)).'-'.Str::random(5);
        }

        $data = openJSONFile('en');
        $data[$request->name_en] = $request->name_en;
        saveJSONFile('en', $data);
        if ($request->hasFile( 'icon' )) {
            $theRequest['icon'] = $request->file( 'icon' )->store( 'uploads/sub_sub_categories/icon' );
        }
        if(SubSubCategory::create($theRequest)){
            flash(translate('SubSubCategory has been inserted successfully'))->success();
            return redirect()->route('subsubcategories.index');
        }
        else{
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
        $subsubcategory = SubSubCategory::findOrFail(decrypt($id));
        $categories = Category::select(['*','name_'.locale().' as name'])->get();
        $tags = \App\Tags::select(['*','name_'.locale().' as name'])->orderBy('created_at', 'desc')->get();
        $brands = Brand::select(['*','name_'.locale().' as name'])->get();
        return view('subsubcategories.edit', compact('subsubcategory', 'categories', 'brands', 'tags'));
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
        $this->validate($request,[
            'name_ar'=>'required',
            'name_en'=>'required',
            'sub_category_id'=>'required',
            'icon' => 'nullable|image',
        ]);
        $subsubcategory = SubSubCategory::findOrFail($id);
        $theRequest = $request->only(['name_ar','name_en','sub_category_id','meta_title_ar','meta_title_en','meta_description_ar','meta_description_en']);

        if($request->hash_tags != null){
            $theRequest['tag_ids'] = implode(',',$request->hash_tags);
        }

        if ($request->slug != null) {
            $theRequest['slug'] = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug));
        }
        else {
            $theRequest['slug'] = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name_en)).'-'.Str::random(5);
        }
        if ($request->hasFile( 'icon' )) {
            $theRequest['icon'] = $request->file( 'icon' )->store( 'uploads/sub_sub_categories/icon' );
        }
        if($subsubcategory->update($theRequest)){
            flash(translate('SubSubCategory has been updated successfully'))->success();
            return redirect()->route('subsubcategories.index');
        }
        else{
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
        $subsubcategory = SubSubCategory::findOrFail($id);
        Product::where('subsubcategory_id', $subsubcategory->id)->delete();
        if(SubSubCategory::destroy($id)){
            foreach (Language::all() as $key => $language) {
                $data = openJSONFile($language->code);
                unset($data[$subsubcategory->name]);
                saveJSONFile($language->code, $data);
            }
            flash(translate('SubSubCategory has been deleted successfully'))->success();
            return redirect()->route('subsubcategories.index');
        }
        else{
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }

    // public function get_subsubcategories_by_subcategory(Request $request)
    // {
    //     $subsubcategories = SubSubCategory::where('sub_category_id', $request->subcategory_id)->select(['*','name_'.locale().' as name'])->get();
    //     return $subsubcategories;
    // }

    // public function get_brands_by_subsubcategory(Request $request)
    // {
    //     $brand_ids = json_decode(SubSubCategory::findOrFail($request->subsubcategory_id)->brands);
    //     $brands = array();
    //     foreach ($brand_ids as $key => $brand_id) {
    //         array_push($brands, Brand::findOrFail($brand_id));
    //     }
    //     return $brands;
    // }

    // public function get_attributes_by_subsubcategory(Request $request)
    // {
    //     $attribute_ids = json_decode(SubSubCategory::findOrFail($request->subsubcategory_id)->attributes);
    //     $attributes = array();
    //     foreach ($attribute_ids as $key => $attribute_id) {
    //         if(\App\Attribute::find($attribute_id) != null){
    //             array_push($attributes, \App\Attribute::findOrFail($attribute_id));
    //         }
    //     }
    //     return $attributes;
    // }
}
