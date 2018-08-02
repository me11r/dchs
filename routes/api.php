<?php

Route::group(
    [
        // @TODO middleware doesn't work
//        'middleware' => ['auth:api'],
        'namespace' => 'Api'
    ],
    function () {
        Route::apiResource('hydrant', 'HydrantController');
    }
);
