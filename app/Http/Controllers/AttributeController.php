<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Attribute;
use CoreComponentRepository;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        CoreComponentRepository::instantiateShopRepository();
        $attributes = Attribute::all(['*','name_'.locale().' as name']);
        return view('attribute.index', compact('attributes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         return view('attribute.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate( $request, [
            'name_ar' => 'required|max:255',
            'name_en' => 'required|max:255',
        ] );
        $attribute = new Attribute;
        $attribute->name_en = $request->name_en;
        $attribute->name_ar = $request->name_ar;
        if($attribute->save()){
            flash(translate('Attribute has been inserted successfully'))->success();
            return redirect()->route('attributes.index');
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
        $attribute = Attribute::select(['*','name_'.locale().' as name'])->findOrFail(decrypt($id));
        return view('attribute.edit', compact('attribute'));
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
        $this->validate( $request, [
            'name_ar' => 'required|max:255',
            'name_en' => 'required|max:255',
        ] );
        $attribute = Attribute::findOrFail($id);
        $attribute->name_en = $request->name_en;
        $attribute->name_ar = $request->name_ar;
        if($attribute->save()){
            flash(translate('Attribute has been updated successfully'))->success();
            if ($request->button != 'save') {
                return redirect()->route('attributes.index');
            } else {
                return redirect()->route('attributes.edit', encrypt($attribute->id));
            }
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
        $attribute = Attribute::findOrFail($id);
        if(Attribute::destroy($id)){
            flash(translate('Attribute has been deleted successfully'))->success();
            return redirect()->route('attributes.index');
        }
        else{
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }
}
