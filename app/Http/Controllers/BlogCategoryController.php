<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\BlogCategory;
use FontLib\Table\Type\loca;

class BlogCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search = null;
        $categories = BlogCategory::orderBy('category_name_' . locale(), 'asc');

        if ($request->has('search')) {
            $sort_search = $request->search;
            $categories = $categories->where('category_name_' . locale(), 'like', '%' . $sort_search . '%');
        }

        $categories = $categories->paginate(15);
        return view('blog_system.category.index', compact('categories', 'sort_search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $all_categories = BlogCategory::all();
        return view('blog_system.category.create', compact('all_categories'));
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
            'category_name_ar' => 'required|max:255',
            'category_name_en' => 'required|max:255',
        ]);

        $category = new BlogCategory;

        $category->category_name_ar = $request->category_name_ar;
        $category->category_name_en = $request->category_name_en;
        $category->slug_en = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->category_name_en));
        $category->slug_ar = str_replace(' ', '-', $request->category_name_ar);

        $category->save();


        flash(translate('Blog category has been created successfully'))->success();
        return redirect()->route('blog-category.index');
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
        $cateogry = BlogCategory::find($id);
        $all_categories = BlogCategory::all();

        return view('blog_system.category.edit',  compact('cateogry', 'all_categories'));
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
            'category_name_ar' => 'required|max:255',
            'category_name_en' => 'required|max:255',
        ]);

        $category = BlogCategory::find($id);

        $category->category_name_ar = $request->category_name_ar;
        $category->category_name_en = $request->category_name_en;
        $category->slug_en = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->category_name_en));
        $category->slug_ar = str_replace(' ', '-', $request->category_name_ar);

        $category->save();


        flash(translate('Blog category has been updated successfully'))->success();
        if ($request->button != 'save') {
            return redirect()->route('blog-category.index');
        } else {
            return redirect()->route('blog-category.edit', $category->id);
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
        BlogCategory::find($id)->delete();

        return redirect('admin/blog-category');
    }
}
