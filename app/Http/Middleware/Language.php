<?php

namespace App\Http\Middleware;

use app\Http\Helpers\Helper;
use Closure;
use Illuminate\Support\Facades\Session;

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
        if($request->segment(1) === 'kk'){
            app()->setLocale('kk');
            Session::put('language','kk');
        }
        else{
            app()->setLocale('ru');
            Session::put('language','ru');
        }

        return $next($request);
    }
}
