<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Link;

class LinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $links = Link::select(['*','name_'.locale().' as name'])->get();
        return view("links.index", compact('links'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Link::all()->count() <= 9){
            return view('links.create');
        }
        else{
            flash(translate('You can not add more than 10 links'))->error();
            return back();
        }
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
            'name_ar'=>'required|max:50',
            'name_en'=>'required|max:50',
            'link_en'=>'required|max:255',
            'link_ar'=>'required|max:255',
        ]);
        $link = new Link;
        $link->name_ar = $request->name_ar;
        $link->name_en = $request->name_en;
        $link->url_en = $request->link_en;
        $link->url_ar = $request->link_ar;
        if($link->save()){
            flash(translate('Link has been inserted successfully'))->success();
            return redirect()->route('links.index');
        }
        flash(translate('Something went wrong'))->error();
        return back();
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
        $link = Link::findOrFail(decrypt($id));
        return view('links.edit', compact('link'));
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
            'name_ar'=>'required|max:50',
            'name_en'=>'required|max:50',
            'link_en'=>'required|max:255',
            'link_ar'=>'required|max:255',
        ]);
        $link = Link::findOrFail($id);
        $link->name_ar = $request->name_ar;
        $link->name_en = $request->name_en;
        $link->url_en = $request->link_en;
        $link->url_ar = $request->link_ar;
        if($link->save()){
            flash(translate('Link has been updated successfully'))->success();
            if ($request->button != 'save') {
                return redirect()->route('links.index');
            } else {
                return redirect()->route('links.edit', encrypt($link->id));
            }
        }
        flash(translate('Something went wrong'))->error();
        return back();
    }
    public function update_links_about(Request $request)
    {
        $link = Link::findOrFail($request->id);
        $link->links_about = $request->links_about;
        if ($link->save()) {
            return 1;
        }
        return 0;
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $link = Link::findOrFail($id);
        if(Link::destroy($id)){
            flash(translate('Link has been deleted successfully'))->success();
            return redirect()->route('links.index');
        }

        flash(translate('Something went wrong'))->error();
        return back();
    }
}
