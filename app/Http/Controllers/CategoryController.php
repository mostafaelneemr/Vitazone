<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Coupon;
use App\HomeCategory;
use App\Product;
use App\Language;
use App\ProductSubSubCategory;
use App\Utility\CategoryUtility;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $coupon = Coupon::where('code', 'MAHMOUD2')->first();
        // $coupon_details = json_decode($coupon->details);
        // $product = \App\Product::find(129);
        // $category_level = Category::find($coupon_details->category_id);
        // $catArr[] = $category_level->id;
        // if ($category_level->level == 0) {
        //     $firstLevel_cat = CategoryUtility::get_immediate_children_ids($category_level->id);
        //     foreach ($firstLevel_cat as $key => $firstLevel_cat_id) {
        //         $catArr[] = $firstLevel_cat_id;
        //         $secLevel_cat = CategoryUtility::get_immediate_children_ids($firstLevel_cat_id);
        //         foreach ($secLevel_cat as $key => $secLevel_cat_id) {
        //             $catArr[] = $secLevel_cat_id;
        //         }
        //     }
        // } elseif ($category_level->level == 1) {
        //     $firstLevel_cat = CategoryUtility::get_immediate_children_ids($category_level->id);
        //     foreach ($firstLevel_cat as $key => $firstLevel_cat_id) {
        //         $catArr[] = $firstLevel_cat_id;
        //     }
        // }
        // $product_catArr = array_column($product->subsubcategoryMany->toArray(), 'id');
        // $array_intersect = array_intersect($catArr, $product_catArr);
        // if (count($array_intersect) > 0) {
        //     return response()->json(true);
        // } else {
        //     return response()->json(false);
        // }
        $sort_search = null;
        $categories = Category::select(['*', 'name_' . locale() . ' as name'])->orderBy('created_at', 'desc');
        if ($request->has('search')) {
            $sort_search = $request->search;
            $categories = $categories->where('name_ar', 'like', '%' . $sort_search . '%')
                ->orWhere('name_en', 'like', '%' . $sort_search . '%');
        }
        $categories = $categories->paginate(15);
        return view('categories.index', compact('categories', 'sort_search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tags = \App\Tags::select(['*', 'name_' . locale() . ' as name'])->orderBy('created_at', 'desc')->get();
        $categories = Category::where('parent_id', 0)
            ->with('childrenCategories')
            ->get();
        return view('categories.create', compact('tags', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $category = new Category;
        $category->name_en = $request->name_en;
        $category->name_ar = $request->name_ar;
        $category->title_ar = $request->title_ar;
        $category->title_en = $request->title_en;
        $category->digital = $request->digital;
        $category->banner = $request->banner;
        $category->icon = $request->icon;
        $category->meta_title_ar = $request->meta_title_ar;
        $category->meta_title_en = $request->meta_title_en;
        $category->description_ar = $request->description_ar;
        $category->description_en = $request->description_en;
        $category->meta_description_ar = $request->meta_description_ar;
        $category->meta_description_en = $request->meta_description_en;
        if ($request->parent_id != "0") {
            $category->parent_id = $request->parent_id;

            $parent = Category::find($request->parent_id);
            $category->level = $parent->level + 1;
        }
        if ($request->hash_tags != null) {
            $category->tag_ids = implode(',', $request->hash_tags);
        }
        // return $request->name_en;
        if ($request->slug_en != null) {
            $category->slug_en =  $request->slug_en;
        } else {
            $category->slug_en = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name_en)) . '-' . Str::random(5);
        }

        if ($request->slug_ar != null) {
            $category->slug_ar = $request->slug_ar;
        }else{
            $category->slug_ar = str_replace(' ', '-', $request->name_ar);
        }

        if ($request->commision_rate != null) {
            $category->commision_rate = $request->commision_rate;
        }

        $data = openJSONFile('en');
        $data[$request->name_en] = $request->name_en;
        saveJSONFile('en', $data);

        $category->save();
        flash(translate('Category has been inserted successfully'))->success();
        return redirect()->route('categories.index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::findOrFail(decrypt($id));
        $tags = \App\Tags::select(['*', 'name_' . locale() . ' as name'])->orderBy('created_at', 'desc')->get();
        $categories = Category::where('parent_id', 0)
            ->with('childrenCategories')
            ->whereNotIn('id', CategoryUtility::children_ids($category->id, true))->where('id', '!=', $category->id)
            ->orderBy('name_' . locale(), 'asc')
            ->get();
        return view('categories.edit', compact('categories', 'category', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $category = Category::findOrFail($id);
        $category->name_ar = $request->name_ar;
        $category->name_en = $request->name_en;
        $category->title_ar = $request->title_ar;
        $category->title_en = $request->title_en;
        $category->description_ar = $request->description_ar;
        $category->description_en = $request->description_en;
        $category->meta_title_ar = $request->meta_title_ar;
        $category->meta_title_en = $request->meta_title_en;
        $category->meta_description_ar = $request->meta_description_ar;
        $category->meta_description_en = $request->meta_description_en;

        if ($request->hash_tags != null) {
            $category->tag_ids = implode(',', $request->hash_tags);
        }

        if ($request->slug_en != null) {
            $category->slug_en =  $request->slug_en;
        } else {
            $category->slug_en = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name_en)) . '-' . Str::random(5);
        }

        if ($request->slug_ar != null) {
            $category->slug_ar = $request->slug_ar;
        }else{
            $category->slug_ar = str_replace(' ', '-', $request->name_ar);
        }

        $category->banner = $request->banner;
        $category->icon = $request->icon;
        $previous_level = $category->level;
        if ($request->parent_id != "0") {
            $category->parent_id = $request->parent_id;

            $parent = Category::find($request->parent_id);
            $category->level = $parent->level + 1;
        } else {
            $category->parent_id = 0;
            $category->level = 0;
        }
        if ($category->level > $previous_level) {
            CategoryUtility::move_level_down($category->id);
        } elseif ($category->level < $previous_level) {
            CategoryUtility::move_level_up($category->id);
        }
        if ($request->commision_rate != null) {
            $category->commision_rate = $request->commision_rate;
        }

        $category->digital = $request->digital;
        $category->save();
        
        flash(translate('Category has been updated successfully'))->success();
        if ($request->button != 'save') {
            return redirect()->route('categories.index');
        } else {
            return redirect()->route('categories.edit', encrypt($category->id));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        foreach ($category->subcategories as $key => $subcategory) {
            foreach ($subcategory->subsubcategories as $key => $subsubcategory) {
                $subsubcategory->delete();
            }
            $subcategory->delete();
        }

        ProductSubSubCategory::where('subsubcategory_id', $category->id)->delete();
        HomeCategory::where('category_id', $category->id)->delete();

        if (Category::destroy($id)) {
            foreach (Language::all() as $key => $language) {
                $data = openJSONFile($language->code);
                unset($data[$category->name]);
                saveJSONFile($language->code, $data);
            }

            CategoryUtility::delete_category($id);
            flash(translate('Category has been deleted successfully'))->success();
            return redirect()->route('categories.index');
        } else {
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }

    public function updateFeatured(Request $request)
    {
        $category = Category::findOrFail($request->id);
        $category->featured = $request->status;
        if ($category->save()) {
            return 1;
        }
        return 0;
    }

    public function updatePublished(Request $request)
    {
        $category = Category::findOrFail($request->id);
        $category->published = $request->status;
        if ($category->save()) {
            return 1;
        }
        return 0;
    }

    public function get_subcategories_by_category(Request $request)
    {
        // return $request;
        $subcategories = Category::where('parent_id', $request->category_id)->select(['*', 'name_' . locale() . ' as name'])->get();
        return $subcategories;
    }
}
