<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use File;
use App\Language;

class LanguageController extends Controller
{
    public function changeLanguage(Request $request)
    {
        if (isset($request->lang)) {
            $locale = $request->lang;
        } else {
            $locale = locale();
        }
        if ($locale == 'ar') {
            $locale = 'eg';
        }
        $request->session()->put('locale', $locale);
        $language = Language::where('code', $locale)->first();
        $segments = str_replace(url('/'), '', url()->previous());
        $segments = array_filter(explode('/', $segments));
        array_shift($segments);
        array_unshift($segments, $locale);
        flash(translate('Language changed to ') . $language->{'name_' . locale()})->success();

        return redirect()->to(implode('/', $segments));
    }

    public function index(Request $request)
    {
        $languages = Language::select(['*', 'name_' . locale() . ' as name'])->paginate(15);
        return view('business_settings.languages.index', compact('languages'));
    }

    public function create(Request $request)
    {
        return view('business_settings.languages.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name_ar' => 'required|max:255',
            'name_en' => 'required|max:255',
            'code' => 'required|max:100',
        ]);
        $language = new Language;
        $language->name_en = $request->name_en;
        $language->name_ar = $request->name_ar;
        $language->code = $request->code;
        if ($language->save()) {
            saveJSONFile($language->code, openJSONFile('en'));
            flash(translate('Language has been inserted successfully'))->success();
            return redirect()->route('languages.index');
        } else {
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }

    public function show($id)
    {
        $language = Language::findOrFail(decrypt($id));
        return view('business_settings.languages.language_view', compact('language'));
    }

    public function edit($id)
    {
        $language = Language::findOrFail(decrypt($id));
        return view('business_settings.languages.edit', compact('language'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name_ar' => 'required|max:255',
            'name_en' => 'required|max:255',
            'code' => 'required|max:100',
        ]);
        $language = Language::findOrFail($id);
        $language->name_en = $request->name_en;
        $language->name_ar = $request->name_ar;
        $language->code = $request->code;
        if ($language->save()) {
            flash(translate('Language has been updated successfully'))->success();
            if ($request->button != 'save') {
                return redirect()->route('languages.index');
            } else {
                return redirect()->route('languages.edit', encrypt($language->id));
            }
        } else {
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }

    public function key_value_store(Request $request)
    {
        $language = Language::findOrFail($request->id);
        // return $request;
        $data = openJSONFile($language->code);
        foreach ($request->key as $key => $key) {
            $data[$key] = $request->key[$key];
        }

        saveJSONFile($language->code, $data);
        flash(translate('Key-Value updated  for ') . $language->{'name_' . locale()})->success();
        return back();
    }

    public function update_rtl_status(Request $request)
    {
        $language = Language::findOrFail($request->id);
        $language->rtl = $request->status;
        if ($language->save()) {
            flash(translate('RTL status updated successfully'))->success();
            return 1;
        }
        return 0;
    }

    public function destroy($id)
    {
        if (Language::destroy($id)) {
            flash(translate('Language has been deleted successfully'))->success();
            return redirect()->route('languages.index');
        } else {
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }
}
