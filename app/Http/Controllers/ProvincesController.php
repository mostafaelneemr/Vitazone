<?php

namespace App\Http\Controllers;

use App\Country;
use App\Provinces;
use Illuminate\Http\Request;

class ProvincesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search = null;
        $provinces = Provinces::select(['*', 'name_' . locale() . ' as name'])->with(['country' => function ($query) {
            $query->select('id', 'name_' . locale() . ' as name');
        }]);
        if ($request->has('search')) {
            $sort_search = $request->search;
            $provinces = $provinces->where('name_ar', 'like', '%' . $sort_search . '%')
                ->orWhere('name_en', 'like', '%' . $sort_search . '%');
        }
        $provinces = $provinces->paginate(15);
        return view('provinces.index', compact('provinces', 'sort_search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::select('name_' . locale() . ' AS name', 'id')->get();
        // return $countries;
        return view('provinces.create', compact('countries'));
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
            'code' => 'required',
            'country_id' => 'required',
        ]);
        $theRequest = $request->only(['name_en', 'name_ar', 'code', 'country_id']);
        if (Provinces::create($theRequest)) {
            flash(translate('City has been inserted successfully'))->success();
            return redirect()->route('provinces.index');
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
        $province = Provinces::findOrFail(decrypt($id));
        $countries = Country::select('name_' . locale() . ' AS name', 'id')->get();
        return view('provinces.edit', compact('province', 'countries'));
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
            'code' => 'required',
            'country_id' => 'required',
        ]);
        $province = Provinces::find($id);
        $theRequest = $request->only(['name_en', 'name_ar', 'code', 'country_id']);
        if ($province->update($theRequest)) {
            flash(translate('Province has been updated successfully'))->success();
            if ($request->button != 'save') {
                return redirect()->route('provinces.index');
            } else {
                return redirect()->route('provinces.edit', encrypt($province->id));
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
        if (Provinces::destroy($id)) {
            flash(translate('Province has been deleted successfully'))->success();
            return redirect()->route('provinces.index');
        } else {
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }

    public function updateStatus(Request $request)
    {
        $province = Provinces::findOrFail($request->id);
        $province->status = $request->status;
        if ($province->save()) {
            return 1;
        }
        return 0;
    }
}
