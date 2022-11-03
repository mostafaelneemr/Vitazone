<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Tags;

class TagsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search = null;
        $tags = Tags::select(['*', 'name_' . locale() . ' as name'])->orderBy('created_at', 'desc');
        if ($request->has('search')) {
            $sort_search = $request->search;
            $tags = $tags->where('name_ar', 'like', '%' . $sort_search . '%')
                ->orWhere('name_en', 'like', '%' . $sort_search . '%');
        }
        $tags = $tags->paginate(15);
        return view('tags.index', compact('tags', 'sort_search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tags.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tag = new Tags;
        $tag->name_en = $request->name_en;
        $tag->name_ar = $request->name_ar;
        $tag->name_sa = $request->name_ar;

        $tag->meta_title_ar = $request->meta_title_ar;
        $tag->meta_title_en = $request->meta_title_en;
        $tag->meta_description_ar = $request->meta_description_ar;
        $tag->meta_description_en = $request->meta_description_en;
        $tag->save();

        return redirect()->route('tags.index');
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
        $tag = Tags::findOrFail(decrypt($id));
        return view('tags.edit', compact('tag'));
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
        $tag = Tags::findOrFail($id);
        $tag->name_en = $request->name_en;
        $tag->name_ar = $request->name_ar;
        $tag->name_sa = $request->name_ar;
        $tag->meta_title_ar = $request->meta_title_ar;
        $tag->meta_title_en = $request->meta_title_en;
        $tag->meta_description_ar = $request->meta_description_ar;
        $tag->meta_description_en = $request->meta_description_en;
        $tag->save();
        flash(translate('Tag has been updated successfully'))->success();
        if ($request->button != 'save') {
            return redirect()->route('tags.index');
        } else {
            return redirect()->route('tags.edit', encrypt($tag->id));
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
        $tag = Tags::findOrFail($id);
        $tag->delete();

        flash(translate('Tag has been deleted successfully'))->success();
        return redirect()->route('tags.index');
    }

    public function addnew(Request $request)
    {
        $tag = new Tags;
        $tag->name_en = $request->name_en;
        $tag->name_sa = $request->name_sa;
        $tag->save();

        return response()->json(['message' => 'Added Successfully'], 200);
    }

    public function getTags()
    {
        $tags = Tags::select(['*', 'name_' . locale() . ' as name'])->orderBy('created_at', 'desc')->get();
        return $tags;
    }
}
