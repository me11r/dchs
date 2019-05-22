<?php

use \Illuminate\Support\Facades\Route;

Route::group(
    [
        // @TODO middleware doesn't work
        // @TODO middleware works but with Laravel Passport
        //'middleware' => ['auth:api'],
        'namespace' => 'Api'
    ],
    function () {
        Route::get('hydrant/hydrants_for_point_by_radius', 'HydrantController@getHydrantsForPointByRadius');
        Route::get('101/get-tech', 'FormationTechController@index');
        Route::get('101/get-staff', 'FormationStaffController@staff_page');
        Route::get('112-formation/get-staff', 'FormationController@staff_page');
        Route::get('district-managers/get-staff', 'FormationController@staff_page_district_managers');
        Route::post('101/sync-formation-od-persons', 'FormationStaffController@syncFormationOdPersons');

        Route::apiResource('hydrant', 'HydrantController');
        Route::apiResource('polygon', 'PolygonController');

        Route::post('101card/save-other-records', 'CardController@createOtherRecord101card');
        Route::post('101card/save-chronology', 'CardController@createChronologyRecord101card');
        Route::post('101card/save-chronology-from-fd', 'CardController@createChronologyRecord101cardFromFd');
        Route::post('101card/copy-chronology-from-fd', 'CardController@copyChronologyRecord101cardFromFd');
        Route::post('101card/save-info-from-fd', 'CardController@createInfo101cardFromFd');
        Route::post('101card/update-chronology', 'CardController@updateChronologyRecord101card');
//        Route::post('101card/save-on-way', 'CardController@createOnWayRecord101card');
//        Route::post('101card/save-arrived', 'CardController@createArrivedRecord101card');
        Route::post('101card/delete-chronology', 'CardController@deleteChronologyRecord101card');
        Route::post('101card/delete-chronology-from-fd ', 'CardController@deleteChronologyFromFdRecord101card');
//        Route::post('101card/delete-on-way', 'CardController@deleteOnWayRecord101card');
        Route::post('101card/delete-arrived', 'CardController@deleteArrivedRecord101card');
        Route::post('101card/promote-to-action', 'CardController@postPromoteToAction');
        Route::get('101card/get_ticket101', 'CardController@getTicket101');
        Route::post('101card/send_notifications', 'CardController@sendNotifications');
        Route::post('101card/send-hq-ride', 'CardController@sendHqRide');
        Route::post('101card/update-fire-department-result', 'CardController@postUpdateFireDepartmentResult');
        Route::post('101card/update-fire-department-result-distance', 'CardController@postUpdateFireDepartmentResultDistance');
        Route::group(['namespace' => 'Open', 'prefix' => 'open'], function (){
            Route::post('fcm/register', 'FcmController@register');
            Route::get('fcm/send_test', 'FcmController@sendTest');
            Route::get('fcm/info/{infoId}', 'FcmController@displayInfo')->where('infoId', '[0-9]+');
            Route::post('fcm/mark_message_as_delivered', 'FcmController@markMessageAsDelivered');
        });

        Route::get('card112/get_card112', 'Card112Controller@getCard112');
        Route::post('card112/send_notifications', 'Card112Controller@sendNotifications');

        Route::group(['prefix' => 'notification'], function (){
            Route::post('ticket101send', 'NotificationController@ticket101Send');
            Route::post('ticket112send', 'NotificationController@ticket112Send');
        });

        Route::group(['prefix' => 'air-rescue'], function (){
            Route::get('get-staff' ,'AircraftController@getStaff');
            Route::get('get-tech' ,'AircraftController@getTech');
        });

        Route::group(['prefix' => 'card101'], function (){
            Route::post('check-roadtrip' ,'CardController@checkRoadtrip');

            Route::post('special-plans' ,'CardController@getSpecialPlans');
            Route::post('operational-cards' ,'CardController@getOperationalCards');
        });
    }
);
