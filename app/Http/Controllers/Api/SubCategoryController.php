<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CategoryCollection;
use App\Models\Category;

class SubCategoryController extends Controller
{
    public function index($id)
    {
        return new CategoryCollection(Category::where('parent_id', $id)->get());
    }


    public function SubCategoryTags($tag_ids)
    {
        $tags = array();
        $hastags = explode(',', $tag_ids);
        foreach ($hastags as $key => $value) {
            $tag = Tags::select(['*','name_'.locale().' as name'])->find($value);
            $tags[$key] = $tag->name;
        }

        return response()->json([
            'tags' => $tags,
        ]);
    }
}
