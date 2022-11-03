<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\HomeCategory;
use App\Product;
use App\Language;
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Activity;
class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $activities = Activity::with(['subject','causer'])
            ->select([
                'id',
                'log_name',
                'description',
                'subject_id',
                'subject_type',
                'causer_id',
                'causer_type',
                'created_at',
                'updated_at'
            ])->latest();
        $activities = $activities->paginate(15);
        return view('activities.index', compact('activities'));
    }
    public function show($id){
        $result = Activity::findOrFail($id);
        return view('activities.show', compact('result'));
    }

    public function clear()
    {
        Activity::truncate();
        return back();
    }
}
