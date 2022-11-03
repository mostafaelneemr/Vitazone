<?php

namespace App\Http\Controllers;

use App\Exports\PhonesExport;
use App\Exports\UsersExport;
use App\Phone;
use App\PhoneExport;
use Illuminate\Http\Request;
use Excel;

class PhonesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (isset($_GET['phone']) && $_GET['phone'] != null) {
            $phone = $_GET['phone'];
            $phones = Phone::where('phone', 'like', "%{$phone}%")->with('user')->paginate(18);
            // return $phones;
        } else {
            $phones = Phone::with('user')->paginate(18);
        }
        // return $phones;
        return view('phones.index', ['phones' => $phones]);
    }

    public function export()
    {
        return Excel::download(new PhoneExport, 'phones.xlsx');
    }

    public function update_status(Request $request)
    {
        $phone = Phone::findOrFail($request->phone_id);
        $phone->status = $request->status;
        $phone->attempts_num = 0;
        if ($phone->save()) {
            return 1;
        }
        return 0;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
