<?php

namespace App\Http\Controllers;

use App\City;
use Illuminate\Http\Request;
use App\Country;
use App\Provinces;
use App\Region;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search = null;
        $countries = Country::select(['*', 'name_' . locale() . ' as name']);
        if ($request->has('search')) {
            $sort_search = $request->search;
            $countries = $countries->where('name_ar', 'like', '%' . $sort_search . '%')
                ->orWhere('name_en', 'like', '%' . $sort_search . '%');
        }
        $countries = $countries->paginate(15);
        return view('countries.index', compact('countries','sort_search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('countries.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name_ar' => 'required',
            'name_en' => 'required',
            'code' => 'required'
        ]);
        $theRequest = $request->only(['name_en', 'name_ar', 'code']);
        if (Country::create($theRequest)) {
            flash(translate('Country has been inserted successfully'))->success();
            return redirect()->route('countries.index');
        } else {
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
        $country = Country::findOrFail(decrypt($id));
        return view('countries.edit', compact('country'));
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
        $this->validate($request, [
            'name_ar' => 'required',
            'name_en' => 'required',
            'code' => 'required'
        ]);
        $county = Country::find($id);
        $theRequest = $request->only(['name_en', 'name_ar', 'code']);
        if ($county->update($theRequest)) {
            flash(translate('Country has been updated successfully'))->success();
            if ($request->button != 'save') {
                return redirect()->route('countries.index');
            } else {
                return redirect()->route('countries.edit', encrypt($county->id));
            }
        } else {
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
        if (Country::destroy($id)) {
            flash(translate('Category has been deleted successfully'))->success();
            return redirect()->route('countries.index');
        } else {
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }

    public function updateStatus(Request $request)
    {
        $country = Country::findOrFail($request->id);
        $country->status = $request->status;
        if ($country->save()) {
            return 1;
        }
        return 0;
    }
    
    public function get_cities(Request $request)
    {
        // $request->id = 62;
        $cities = City::where('province_id', $request->id)->where('status', 1)->select('id', 'name_' . locale() . ' AS name')->get();
        return $cities;
    }
    public function get_provinces(Request $request)
    {
        // $request->id = 62;
        $provinces = Provinces::where('country_id', $request->id)->where('status', 1)->select('id', 'name_' . locale() . ' AS name')->get();
        return $provinces;
    }
    public function get_regions(Request $request)
    {
        $regions = Region::where('city_id', $request->id)->where('status', 1)->select('id', 'name_' . locale() . ' AS name')->get();
        return $regions;
    }
}
