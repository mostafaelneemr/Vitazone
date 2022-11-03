<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode as Middleware;

class CheckForMaintenanceMode extends Middleware
{
    /**
     * The URIs that should be reachable while maintenance mode is enabled.
     *
     * @var array
     */
    protected $except = [
        'en/admin*', 'eg/admin*', 'en/login', 'eg/login', 'en/logout', 'eg/logout', '/aiz-uploader*'
    ];
}
