<?php

namespace App\Http\Controllers;

use App\MediaCenter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaCentersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $MediaCenter = MediaCenter::paginate(15);

        return view('MediaCenters.index', compact('MediaCenter'));
    }

    public function deleteFile(Request $request)
    {
        // return public_path() . '/uploads/' . decrypt($request->file); 
        if (file_exists(public_path() . '/uploads/' . decrypt($request->file))) {
            // return $request; 
            unlink(public_path() . '/uploads/' . decrypt($request->file));
            flash(translate('File has been deleted successfully'))->success();
            return redirect()->route('MediaCenters.dealWithFiles');
        } else {
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }

    public function editFile(Request $request)
    {
        $file = decrypt($request->file);
        return view('MediaCenters.editFile', compact('file'));
    }

    public function updateFile(Request $request)
    {

        // return $request;
        $name = explode('\\', $request->old_file);
        $name = $name[sizeof($name) - 1];
        $folder_path = $request->old_file;
        $folder_path = explode('\\', $folder_path);
        array_pop($folder_path);
        $folder_path = implode('\\', $folder_path);
        unlink(public_path() . '/uploads/' . $folder_path . '/' . $name);
        $request->file('File')->storeAs('uploads/' . $folder_path, $name);
        flash(translate('File has been deleted successfully'))->success();
        return redirect()->route('MediaCenters.dealWithFiles');
    }
    public function dealWithFiles(Request $request)
    {

        // return $request->filter;
        $filter = $request->filter;
        $page = (int) $request->input('page') ?: 1;
        $images = collect(\File::allFiles(public_path('uploads/' . $request->filter)));
        $onPage = 20;
        $slice = $images->slice(($page - 1) * $onPage, $onPage);
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator($slice, $images->count(), $onPage);
        $directories = Storage::allDirectories('uploads/');

        foreach ($directories as $key => $value) {

            $directories[$key] = str_replace('uploads/', '', $value);
        }
        // return $directories;
        // return $images[0];
        // return $paginator[0];
        // return public_path() . '\uploads/';
        foreach ($paginator as $key => $value) {
            if (strpos($value, public_path() . '\uploads/') !== false) {
                $paginator[$key] = str_replace(public_path() . '\uploads/', '', $value);
            } else {
                $paginator[$key] = str_replace(public_path() . '\uploads\\', '', $value);
            }
        }
        $paginator->setPath(route('MediaCenters.dealWithFiles', ['filter' => $request->filter]));
        // return $paginator;
        return view('MediaCenters.dealWithFiles', compact('paginator', 'directories', 'filter'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('MediaCenters.create');
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
            'alt_ar' => 'required',
            'alt_en' => 'required',
            'file_path' => 'nullable|image',
        ]);
        $theRequest = $request->only(['alt_ar', 'alt_en']);
        if ($request->hasFile('file_path')) {
            $theRequest['file_path'] = $request->file('file_path')->store('uploads/MediaCenters');
            $theRequest['type'] = $request->file('file_path')->getClientOriginalExtension();
        }
        if (MediaCenter::create($theRequest)) {
            flash(translate('MediaCenter has been inserted successfully'))->success();
            return redirect()->route('MediaCenters.dealWithFiles');
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
        $MediaCenter = MediaCenter::findOrFail(decrypt($id));
        return view('MediaCenters.edit', compact('MediaCenter'));
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
            'alt_ar' => 'required',
            'alt_en' => 'required',
            'file_path' => 'nullable|image',
        ]);
        $MediaCenter = MediaCenter::findOrFail($id);
        $theRequest = $request->only(['alt_ar', 'alt_en']);
        if ($request->hasFile('file_path')) {
            $theRequest['file_path'] = $request->file('file_path')->store('uploads/MediaCenters');
            $theRequest['type'] = $request->file('file_path')->getClientOriginalExtension();
        }
        if ($MediaCenter->update($theRequest)) {
            flash(translate('MediaCenter has been inserted successfully'))->success();
            return redirect()->route('MediaCenters.index');
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
        $MediaCenter = MediaCenter::findOrFail($id);
        if (MediaCenter::destroy($id)) {
            if ($MediaCenter->file_path != null) {
                unlink(public_path() . '/' . $MediaCenter->file_path);
            }
            flash(translate('MediaCenter has been deleted successfully'))->success();
            return redirect()->route('MediaCenters.index');
        } else {
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }
}
