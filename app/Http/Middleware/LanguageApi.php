<?php

namespace App\Http\Middleware;

use Closure;
use App;
use Session;
use Config;
use Illuminate\Support\Facades\App as FacadesApp;

class LanguageApi
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
        $locale = 'en';
        if (isset($_SERVER['HTTP_LANG'])) {
            $locale = $_SERVER['HTTP_LANG'];
        }
        app()->setLocale($locale);
        
        return $next($request);
    }
}
