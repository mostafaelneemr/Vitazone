<?php

namespace App\Http\Controllers;

use App\City;
use App\Region;
use Illuminate\Http\Request;

class RegionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search = null;
        $regions = Region::select(['*','name_'.locale().' as name'])->with(['city' => function($query){
            $query->select('id','name_'.locale().' as name');
        }]);
        if ($request->has('search')) {
            $sort_search = $request->search;
            $regions = $regions->where('name_ar', 'like', '%' . $sort_search . '%')
                ->orWhere('name_en', 'like', '%' . $sort_search . '%');
        }
        $regions = $regions->paginate(15);
        // return $regions;
        return view('regions.index', compact('regions','sort_search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cities = City::select('name_'.locale() . ' AS name' , 'id')->get();
        // return $countries;
        return view('regions.create',compact('cities'));
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
            'name_ar' =>'required',
            'name_en'=>'required',
            'shipping_cost'=>'required',
            'code'=>'required',
            'city_id'=>'required'
         ]);
         $theRequest = $request->only(['name_en','name_ar','shipping_cost','code','city_id','shipping_cost_high','shipping_duration','shipping_duration_high']);
         if(Region::create($theRequest)){
             flash(translate('Region has been inserted successfully'))->success();
             return redirect()->route('regions.index');
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
        $region = Region::findOrFail(decrypt($id));
        $cities = City::select('name_'.locale() . ' AS name' , 'id')->get();
        return view('regions.edit', compact('region','cities'));
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
            'name_ar' =>'required',
            'name_en'=>'required',
            'shipping_cost'=>'required',
            'code'=>'required',
            'city_id'=>'required',
        ]);
        $region = Region::find($id);
        $theRequest = $request->only(['name_en','name_ar','shipping_cost','code','city_id','shipping_cost_high','shipping_duration','shipping_duration_high']);
        if($region->update($theRequest)){
            flash(translate('Region has been updated successfully'))->success();
            if ($request->button != 'save') {
                return redirect()->route('regions.index');
            } else {
                return redirect()->route('regions.edit', encrypt($region->id));
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
        if(Region::destroy($id)){
            flash(translate('Region has been deleted successfully'))->success();
            return redirect()->route('regions.index');
        }
        else{
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }

    public function updateStatus(Request $request){
        $region = Region::findOrFail($request->id);
        $region->status = $request->status;
        if($region->save()){
            return 1;
        }
        return 0;
    }
}
