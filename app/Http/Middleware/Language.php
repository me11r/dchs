<?php

namespace App\Http\Middleware;

use App\Translation;
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
        if (!$request->ajax() && $request->isMethod('GET')) {
            if($request->segment(1) === 'kk'){
                app()->setLocale('kk');
                Session::put('language','kk');
            }
            else{
                app()->setLocale('ru');
                Session::put('language','ru');
            }
        }

        $locale = \Illuminate\Support\Facades\Session::get('language', 'ru');

        try {
            $translations = Translation::getByLocale($locale)->get(['key', 'value']);
        }
        catch (\Exception $exception) {
            $translations = json_encode([]);
        }

        view()->composer('*', function ($view) use ($locale, $translations) {
            $view->with([
                'language' => $locale,
                'translations' => $translations,
            ]);
        });

        return $next($request);
    }
}
