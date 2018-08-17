<?php

Route::group(
    [
        // @TODO middleware doesn't work
//        'middleware' => ['auth:api'],
        'namespace' => 'Api'
    ],
    function () {
        Route::get('hydrant/hydrants_for_point_by_radius', 'HydrantController@getHydrantsForPointByRadius');
        Route::apiResource('hydrant', 'HydrantController');
    }
);
