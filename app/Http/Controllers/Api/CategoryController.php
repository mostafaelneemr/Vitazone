<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CategoryCollection;
use App\Models\BusinessSetting;
use App\Category;
use App\Http\Resources\ProductCollection;
use App\Models\Product;

class CategoryController extends Controller
{

    public function index()
    {
        //        return  Category::with('subCategories.subSubCategories')->get(['*','name_'.locale().' as name']);
        return new CategoryCollection(Category::where(['published' => 1, 'level' => 0])->with(['subCategories' => function ($query) {
            $query->select(['id', 'name_' . locale() . ' as name', 'icon', 'parent_id', 'slug_' . locale() . ' as slug']);
        }, 'subCategories.subSubCategories' => function ($q) {
            $q->select(['id', 'name_' . locale() . ' as name', 'icon', 'parent_id', 'slug_' . locale() . ' as slug']);
        }])->get(['*', 'name_' . locale() . ' as name']));
    }

    public function featured()
    {
        return new CategoryCollection(Category::where(['published' => 1, 'featured' => 1])->select(['*', 'name_' . locale() . ' as name'])->get());
    }

    public function home()
    {
        $homepageCategories = BusinessSetting::where('type', 'category_homepage')->first();
        $homepageCategories = json_decode($homepageCategories->value);
        $categories = json_decode($homepageCategories->category);
        return new CategoryCollection(Category::find($categories));
    }

    public function CategoryTags($tag_ids)
    {
        $tags = array();
        $hastags = explode(',', $tag_ids);
        foreach ($hastags as $key => $value) {
            $tag = Tags::select(['*', 'name_' . locale() . ' as name'])->find($value);
            $tags[$key] = $tag->name;
        }

        return response()->json([
            'tags' => $tags,
        ]);
    }

    public function getMainCategories()
    {
        $categories = Category::where(['published' => 1, 'level' => 0])->select('id', 'name_' .  locale() . ' AS name')->paginate(10);
        $data['success'] = true;
        $data['data'] = $categories;
        return response()->json($data);
    }

    public function subCategoriesTop($category_id)
    {
        $subCat = Category::where(['published' => 1, 'parent_id' => $category_id])->select('id', 'name_' .  locale() . ' AS name')->get();
        $data['success'] = true;
        $data['data'] = $subCat;
        return response()->json($data);
    }

    public function SubWithSubSub($category_id)
    {
        $subCats = Category::where(['published' => 1, 'parent_id' => $category_id])->select('id', 'name_' .  locale() . ' AS name', 'icon')->get();
        // return $subCats;
        foreach ($subCats as $key => $subCat) {
            $subSub = Category::where(['published' => 1, 'parent_id' => $subCat->id])->select('id', 'name_' .  locale() . ' AS name', 'icon')->take(9)->get();
            $subCats[$key]['icon'] = api_asset($subCat['icon']);
            foreach ($subSub as $key2 => $value) {
                $subSub[$key2]['icon'] = api_asset($value['icon']);
            }
            // return $subSub;

            $subSubCount = Category::where(['published' => 1, 'parent_id' => $subCat->id])->count();
            // return $subSubCount;
            if ($subSubCount > sizeof($subSub)) {
                $newSubSub['viewAll'] = true;
            } else {
                $newSubSub['viewAll'] = false;
            }
            $newSubSub['data'] = $subSub;

            $subCats[$key]['subSub'] = $newSubSub;
        }
        // return $subCats;
        $data['success'] = true;
        $data['data'] = $subCats;
        return response()->json($data);
    }

    public function allSubSubCategories($sub_category_id)
    {
        $subSub = Category::where(['published' => 1, 'parent_id' => $sub_category_id])
        ->select('id', 'name_' .  locale() . ' AS name', 'icon')->paginate(15);
        return $subSub;
        foreach ($subSub as $key => $value) {
            $subSub[$key]['icon'] = api_asset($value['icon']);
        }
        $data['success'] = true;
        $data['data'] = $subSub;
        return response()->json($data);
    }

    public function getProductsBySubSub($sub_sub_category_id)
    {
        $this->subsubcategory_id = $sub_sub_category_id;
        $products = Product::select(['products.*'])
            ->join('product_sub_sub_categories', function ($join) {
                $join->on('products.id', '=', 'product_sub_sub_categories.product_id')
                    ->where('product_sub_sub_categories.subsubcategory_id', '=', $this->subsubcategory_id);
            })->where('published', 1)
            ->latest()->paginate(10);
        return new ProductCollection($products);
    }
}
