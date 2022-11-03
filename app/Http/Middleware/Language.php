<?php

namespace App\Http\Middleware;

use Closure;
use App;
use Session;
use Config;

class Language
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // dd($request);
        // if(Session::has('locale')){
        //     $locale = Session::get('locale');
        // }elseif(env('DEFAULT_LANGUAGE') != null){
        //     $locale = env('DEFAULT_LANGUAGE');
        // }
        // else{
        //     $locale = 'en';
        // }

        // App::setLocale($locale);
        // $request->session()->put('locale', $locale);
        // if (env('DEFAULT_LANGUAGE') != null && !Session::has('locale')) {
        //     $locale = env('DEFAULT_LANGUAGE');
        //     App::setLocale($locale);
        //     $request->session()->put('locale', $locale);
        // }
        return $next($request);
    }
}
