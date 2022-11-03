<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SubCategory;
use App\SubSubCategory;
use App\Category;
use App\Product;
use App\Language;
use Illuminate\Support\Str;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search =null;
        $subcategories = SubCategory::select(['*','name_'.locale().' as name'])->orderBy('created_at', 'desc');
        if ($request->has('search')){
            $sort_search = $request->search;
            $subcategories = $subcategories->where('name_ar', 'like', '%'.$sort_search.'%')
            ->orWhere('name_en', 'like', '%'.$sort_search.'%');
        }
        $subcategories = $subcategories->paginate(15);
        return view('subcategories.index', compact('subcategories', 'sort_search'));
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
        return view('subcategories.create', compact('categories', 'tags'));
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
            'category_id'=>'required',
            'icon' => 'nullable|image',
//            'meta_title_ar'=>'required',
//            'meta_title_en'=>'required',
//            'meta_description_ar'=>'required',
//            'meta_description_en'=>'required',
        ]);

        $theRequest = $request->only(['name_ar','name_en','category_id','meta_title_ar','meta_title_en','meta_description_ar','meta_description_en']);
        
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
            $theRequest['icon'] = $request->file( 'icon' )->store( 'uploads/sub_categories/icon' );
        }
        if(SubCategory::create($theRequest)){
            flash(translate('Subcategory has been inserted successfully'))->success();
            return redirect()->route('subcategories.index');
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
        $subcategory = SubCategory::findOrFail(decrypt($id));
        $categories = Category::select(['*','name_'.locale().' as name'])->get();
        $tags = \App\Tags::select(['*','name_'.locale().' as name'])->orderBy('created_at', 'desc')->get();
        return view('subcategories.edit', compact('categories', 'subcategory', 'tags'));
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
            'category_id'=>'required',
            'icon' => 'nullable|image',
//            'meta_title_ar'=>'required',
//            'meta_title_en'=>'required',
//            'meta_description_ar'=>'required',
//            'meta_description_en'=>'required',
        ]);
        $subcategory = SubCategory::findOrFail($id);

        $theRequest = $request->only(['name_ar','name_en','logo','meta_title_ar','meta_title_en','meta_description_ar','meta_description_en']);

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
            $theRequest['icon'] = $request->file( 'icon' )->store( 'uploads/sub_categories/icon' );
        }
        if($subcategory->update($theRequest)){
            flash(translate('Subcategory has been updated successfully'))->success();
            return redirect()->route('subcategories.index');
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
        $subcategory = SubCategory::findOrFail($id);
        foreach ($subcategory->subsubcategories as $key => $subsubcategory) {
            $subsubcategory->delete();
        }
        Product::where('subcategory_id', $subcategory->id)->delete();
        if(SubCategory::destroy($id)){
            foreach (Language::all() as $key => $language) {
                $data = openJSONFile($language->code);
                unset($data[$subcategory->name]);
                saveJSONFile($language->code, $data);
            }
            flash(translate('Subcategory has been deleted successfully'))->success();
            return redirect()->route('subcategories.index');
        }
        else{
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }


    
}
