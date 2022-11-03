<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\BlogCategory;
use App\Blog;
use FontLib\Table\Type\loca;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search = null;
        $blogs = Blog::orderBy('created_at', 'desc');

        if ($request->search != null) {
            $blogs = $blogs->where('title_' . locale(), 'like', '%' . $request->search . '%');
            $sort_search = $request->search;
        }

        $blogs = $blogs->paginate(15);

        return view('blog_system.blog.index', compact('blogs', 'sort_search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $blog_categories = BlogCategory::all();
        return view('blog_system.blog.create', compact('blog_categories'));
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
            'category_id' => 'required',
            'title_en' => 'required|max:255',
            'title_ar' => 'required|max:255',
        ]);

        $blog = new Blog;

        $blog->category_id = $request->category_id;
        $blog->title_en = $request->title_en;
        $blog->title_ar = $request->title_ar;
        $blog->banner = $request->banner;
        $blog->slug_ar = $request->slug_ar;
        $blog->slug_en = $request->slug_en;
        $blog->short_description_en = $request->short_description_en;
        $blog->short_description_ar = $request->short_description_ar;
        $blog->description_en = $request->description_en;
        $blog->description_ar = $request->description_ar;

        $blog->meta_title_en = $request->meta_title_en;
        $blog->meta_title_ar = $request->meta_title_ar;
        $blog->meta_img = $request->meta_img;
        $blog->meta_description_en = $request->meta_description_en;
        $blog->meta_description_ar = $request->meta_description_ar;
        $blog->meta_keywords_en = $request->meta_keywords_en;
        $blog->meta_keywords_ar = $request->meta_keywords_ar;

        $blog->save();

        flash(translate('Blog post has been created successfully'))->success();
        return redirect()->route('blog.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $blog = Blog::find($id);
        $blog_categories = BlogCategory::all();

        return view('blog_system.blog.edit', compact('blog', 'blog_categories'));
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
            'category_id' => 'required',
            'title_en' => 'required|max:255',
            'title_ar' => 'required|max:255',
        ]);

        $blog = Blog::find($id);

        $blog->category_id = $request->category_id;
        $blog->title_en = $request->title_en;
        $blog->title_ar = $request->title_ar;
        $blog->banner = $request->banner;
        $blog->slug_ar = $request->slug_ar;
        $blog->slug_en = $request->slug_en;
        $blog->short_description_en = $request->short_description_en;
        $blog->short_description_ar = $request->short_description_ar;
        $blog->description_en = $request->description_en;
        $blog->description_ar = $request->description_ar;

        $blog->meta_title_en = $request->meta_title_en;
        $blog->meta_title_ar = $request->meta_title_ar;
        $blog->meta_img = $request->meta_img;
        $blog->meta_description_en = $request->meta_description_en;
        $blog->meta_description_ar = $request->meta_description_ar;
        $blog->meta_keywords_en = $request->meta_keywords_en;
        $blog->meta_keywords_ar = $request->meta_keywords_ar;

        $blog->save();

        flash(translate('Blog post has been updated successfully'))->success();
        if ($request->button != 'save') {
            return redirect()->route('blog.index');
        } else {
            return redirect()->route('blog.edit', $blog->id);
        }
    }

    public function change_status(Request $request)
    {
        $blog = Blog::find($request->id);
        $blog->status = $request->status;

        $blog->save();
        return 1;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Blog::find($id)->delete();

        return redirect('admin/blogs');
    }


    public function all_blog()
    {
        $blogs = Blog::where('status', 1)->orderBy('created_at', 'desc')->paginate(12);
        return view("frontend.blog.listing", compact('blogs'));
    }

    public function blog_details($slug)
    {
        $blog = Blog::where('slug_' . locale(), $slug)->first();
        return view("frontend.blog.details", compact('blog'));
    }
}
