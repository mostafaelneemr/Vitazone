<?php

namespace App\Http\Controllers;

use App\City;
use App\Models\Country;
use App\Provinces;
use Illuminate\Http\Request;

class CitiesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search = null;
        $cities = City::select(['*','name_'.locale().' as name'])->with(['province' => function($query){
            $query->select('id','name_'.locale().' as name');
        }]);
        if ($request->has('search')) {
            $sort_search = $request->search;
            $cities = $cities->where('name_ar', 'like', '%' . $sort_search . '%')
                ->orWhere('name_en', 'like', '%' . $sort_search . '%');
        }
        $cities = $cities->paginate(15);
        return view('cities.index', compact('cities','sort_search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $provinces = Provinces::select('name_'.locale() . ' AS name' , 'id')->get();
        // return $countries;
        return view('cities.create',compact('provinces'));
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
            'code'=>'required',
            'province_id'=>'required',
         ]);
         $theRequest = $request->only(['name_en','name_ar','code','province_id']);
         if(City::create($theRequest)){
             flash(translate('City has been inserted successfully'))->success();
             return redirect()->route('cities.index');
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
        $city = City::findOrFail(decrypt($id));
        $provinces = Provinces::select('name_'.locale() . ' AS name' , 'id')->get();
        return view('cities.edit', compact('city','provinces'));
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
            'code'=>'required',
            'province_id'=>'required',
        ]);
        $city = City::find($id);
        $theRequest = $request->only(['name_en','name_ar','code','province_id']);
        if($city->update($theRequest)){
            flash(translate('Country has been updated successfully'))->success();
            if ($request->button != 'save') {
                return redirect()->route('cities.index');
            } else {
                return redirect()->route('cities.edit', encrypt($city->id));
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
        if(City::destroy($id)){
            flash(translate('City has been deleted successfully'))->success();
            return redirect()->route('cities.index');
        }
        else{
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }

    public function updateStatus(Request $request){
        $city = City::findOrFail($request->id);
        $city->status = $request->status;
        if($city->save()){
            return 1;
        }
        return 0;
    }
}
