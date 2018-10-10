<?php

use \Illuminate\Support\Facades\Route;

Route::group(
    [
        // @TODO middleware doesn't work
        // @TODO middleware works but with Laravel Passport
//        'middleware' => ['auth:api'],
        'namespace' => 'Api'
    ],
    function () {
        Route::get('hydrant/hydrants_for_point_by_radius', 'HydrantController@getHydrantsForPointByRadius');
        Route::get('101/get-tech', 'FormationTechController@index');
        Route::get('101/get-staff', 'FormationStaffController@index');
        Route::apiResource('hydrant', 'HydrantController');
        Route::post('101card/save-other-records', 'CardController@createOtherRecord101card');
        Route::group(['namespace' => 'Open', 'prefix' => 'open'], function (){
            Route::post('fcm/register', 'FcmController@register');
            Route::get('fcm/send_test', 'FcmController@sendTest');
        });
    }
);
