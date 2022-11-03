<?php

namespace App\Http\Controllers;

use App\RefundResone;
use Illuminate\Http\Request;

class RefundResonesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search = null;
        $refundResones = RefundResone::orderBy('created_at', 'desc');
        if ($request->has('search')) {
            $sort_search = $request->search;
            $refundResones = $refundResones->where('resone_ar', 'like', '%' . $sort_search . '%')
                ->orWhere('resone_en', 'like', '%' . $sort_search . '%');
        }
        $refundResones = $refundResones->paginate(15);
        return view('refundResones.index', compact('refundResones', 'sort_search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('refundResones.create');
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
            'resone_ar' => 'required',
            'resone_en' => 'required'
        ]);
        $theRequest = $request->only(['resone_ar', 'resone_en']);
        if (RefundResone::create($theRequest)) {
            flash(translate('refund Resones has been inserted successfully'))->success();
            return redirect()->route('refundResones.index');
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
        $RefundResone = RefundResone::findOrFail(decrypt($id));
        return view('refundResones.edit', compact('RefundResone'));
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
            'resone_ar' => 'required',
            'resone_en' => 'required'
        ]);
        $RefundResone = RefundResone::findOrFail($id);
        $theRequest = $request->only(['resone_ar', 'resone_en']);
        if ($RefundResone->update($theRequest)) {
            flash(translate('Refund Resone has been updated successfully'))->success();
            return redirect()->route('refundResones.index');
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
        if (RefundResone::destroy($id)) {
            flash(translate('Refund Resone has been deleted successfully'))->success();
            return redirect()->route('refundResones.index');
        } else {
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }
}
