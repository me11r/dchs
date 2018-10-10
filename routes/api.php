<?php

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

        Route::group(['prefix' => 'air-rescue'], function (){
            Route::get('get-staff' ,'AircraftController@getStaff');
            Route::get('get-tech' ,'AircraftController@getTech');
        });
    }
);
