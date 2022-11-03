<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Staff;
use App\Role;
use App\User;
use Hash;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $admins = array_column(User::where('user_type', 'admin')->get()->toArray(), 'id');
        $admins[] = Auth::user()->id;
        $staffs = Staff::where('user_id', '<>', Auth::user()->id)->whereNotIn('user_id', $admins)->paginate(15);
        return view('staffs.index', compact('staffs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all();
        return view('staffs.create', compact('roles'));
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
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|unique:users,phone',
            'password' => 'required',
            'role_id' => 'required'
        ]);
        if (User::where('email', $request->email)->first() == null) {
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->user_type = "staff";
            $user->password = Hash::make($request->password);
            if ($user->save()) {
                $staff = new Staff;
                $staff->user_id = $user->id;
                $staff->role_id = $request->role_id;
                if ($staff->save()) {
                    flash(translate('Staff has been inserted successfully'))->success();
                    return redirect()->route('staffs.index');
                }
            }
        }

        flash(translate('Email already used'))->error();
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
        $staff = Staff::findOrFail(decrypt($id));
        $roles = Role::all();
        return view('staffs.edit', compact('staff', 'roles'));
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
        $staff = Staff::findOrFail($id);
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $staff->user_id,
            'phone' => 'required|unique:users,phone,' . $staff->user_id,
            'password' => 'nullable',
            'role_id' => 'required'
        ]);
        $user = $staff->user;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        if (strlen($request->password) > 0) {
            $user->password = Hash::make($request->password);
        }
        if ($user->save()) {
            $staff->role_id = $request->role_id;
            if ($staff->save()) {
                flash(translate('Staff has been updated successfully'))->success();
                if ($request->button != 'save') {
                    return redirect()->route('staffs.index');
                } else {
                    return redirect()->route('staffs.edit', encrypt($staff->id));
                }
            }
        }

        flash(translate('Something went wrong'))->error();
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::destroy(Staff::findOrFail($id)->user->id);
        if (Staff::destroy($id)) {
            flash(translate('Staff has been deleted successfully'))->success();
            return redirect()->route('staffs.index');
        }

        flash(translate('Something went wrong'))->error();
        return back();
    }
}
