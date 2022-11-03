<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Role;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notAllowedRoleIds[] = Auth::user()->staff->role->id;
        $notAllowedRoleIds[] = 3;
        // return $notAllowedRoleIds;
        $roles = Role::whereNotIn('id', $notAllowedRoleIds)->paginate(15);
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('roles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->has('permissions')) {
            $role = new Role;
            $role->name = $request->name;
            $role->permissions = json_encode($request->permissions);
            if ($role->save()) {
                flash(translate('Role has been inserted successfully'))->success();
                return redirect()->route('roles.index');
            }
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
        // return $id;
        $role = Role::findOrFail(decrypt($id));
        return view('roles.edit', compact('role'));
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
        $role = Role::findOrFail($id);
        // return $request;
        if ($request->has('permissions')) {
            $role->name = $request->name;
            $role->permissions = json_encode($request->permissions);
            if ($role->save()) {
                flash(translate('Role has been updated successfully'))->success();
                if ($request->button != 'save') {
                    return redirect()->route('roles.index');
                } else {
                    return redirect()->route('roles.edit', encrypt($role->id));
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
        if (Role::destroy($id)) {
            flash(translate('Role has been deleted successfully'))->success();
            return redirect()->route('roles.index');
        } else {
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }
}
