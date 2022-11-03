<?php

namespace App\Http\Middleware;

use Closure;

class AfterMiddleware
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
    	$business_setting = \App\BusinessSetting::where('type', "compress_html")->first();

        $response = $next($request);
		
        if($business_setting->value == 1){

        	if($response)
		    {

		        $buffer = $response->getContent();
		 

		        $search = array(
			        '/\>[^\S ]+/s',     // strip whitespaces after tags, except space
			        '/[^\S ]+\</s',     // strip whitespaces before tags, except space

			    );

			    $replace = array(
			        '>',
			        '<',
			    );

			    $buffer = preg_replace($search, $replace, $buffer);


				# $buffer = preg_replace(array('/<!--(.*)-->/Uis',"/[[:blank:]]+/"),array('',' '),str_replace(array("\n","\r","\t"),'',$buffer));
        		// $response->setContent($buffer);
		        // print_r($response);
		        // exit();
		    }

        }

        return $response;
    }
}
