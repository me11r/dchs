<?php

use Illuminate\Support\Facades\Route;

Route::get('/test/fcm', 'TestController@fcm')->name('test.fcm');

Route::post('logout', 'Auth\LoginController@postLogout')->name('logout');
//Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['localeViewPath']], function () {
    Route::get('login', 'Auth\LoginController@getIndex')->name('login');
    Route::post('login', 'Auth\LoginController@postIndex')->name('post-login');
    Route::get('auth/password-reset', 'Auth\ResetPasswordController@getResetPassword')->name('password.request');
//});
Route::get('event', function () {
    event(new \App\Events\ReportUpdated());
});

Route::group(['middleware' => ['auth','check.blocked']], function () {
//    Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['localeViewPath']], function () {

        Route::group(['prefix' => 'ajax'], function () {
            Route::get('street/{area_id?}', 'AjaxController@findStreet')->where('area_id', '[0-9]+');
            Route::get('find_special_plan', 'AjaxController@findSpecialPlan');
            Route::get('find_special_plan_by_id', 'AjaxController@findSpecialPlanById');
            Route::get('find_operational_card_by_id', 'AjaxController@findOperationalCardById');
            Route::get('rights/list', 'AjaxController@getRightIds');
            Route::get('messenger-rights', 'AjaxController@getMessengerPermissions');
            Route::get('roadtrips', 'AjaxController@getRoadtripPlans');
            Route::post('roadtrips-submit-notify-retreat101', 'AjaxController@postSubmitNotifyRetreat');
            Route::get('service-plans', 'AjaxController@getServicePlans');
            Route::post('roadrip-notify-token', 'AjaxController@postRoadtripNotificationToken');
        });

        Route::group(['prefix' => 'admin', 'middleware' => ['role:admin']], function () {
            Route::get('polygons', 'PolygonsController@index')->name('polygons.index');

            Route::get('users', 'AdminController@getUsers')->name('admin-users');
            Route::get('users/edit/{user_id?}', 'AdminController@getUserEdit')->where(['user_id' => '[0-9]+'])->name('admin-users-edit');
            Route::post('users/edit/{user_id?}', 'AdminController@postUserEdit')->where(['user_id' => '[0-9]+'])->name('post-admin-users-edit');

            Route::get('users/lock/{user_id}', 'AdminController@getUserLock')->where(['user_id' => '[0-9]+'])->name('admin-users-lock');
            Route::post('users/lock/{user_id}', 'AdminController@postUserLock')->where(['user_id' => '[0-9]+'])->name('post-admin-users-lock');

            Route::get('users/impersonate/{user_id}', 'AdminController@getImpersonate')->where(['user_id' => '[0-9]+'])->name('admin-users-impersonate');
            Route::post('users/impersonate/{user_id}', 'AdminController@postImpersonate')->where(['user_id' => '[0-9]+'])->name('post-admin-users-impersonate');

            Route::get('users/passwd/{user_id}', 'AdminController@getPassword')->where(['user_id' => '[0-9]+'])->name('admin-users-password');
            Route::post('users/passwd/{user_id}', 'AdminController@postPassword')->where(['user_id' => '[0-9]+'])->name('post-admin-users-password');
            Route::delete('users/delete/{user_id}', 'AdminController@delete')->where(['user_id' => '[0-9]+'])->name('users.delete');

            Route::group(['prefix' => 'roles', 'as' => 'roles.', 'middleware' => ['right:CAN_MANAGE_USERS']], function (){
                Route::get('/', 'RoleController@index')->name('index');
                Route::get('create', 'RoleController@create')->name('create');
                Route::get('edit/{id}', 'RoleController@edit')->name('edit');
                Route::post('add-edit', 'RoleController@store')->name('add-edit');
                Route::post('delete/{id}', 'RoleController@delete')->name('delete');
            });

            Route::group(['prefix' => 'messenger-permissions', 'as' => 'messenger-permissions.'], function (){
                Route::get('/', 'MessengerRightController@edit')->name('edit');
                Route::post('store', 'MessengerRightController@store')->name('store');
            });

        });

        Route::get('/card/101/{card_type?}', 'CardController@get101')->name('card101');
        Route::get('/card/add101/{card_id?}/{card_type?}', 'CardController@getAdd101')->name('card101add')->where(['card_id' => '[0-9]+', 'card_type' => '[A-Za-z]+']);

        Route::group(['prefix' => 'card101-other-rides', 'as' => 'card101-other-rides'], function (){
            Route::get('/', 'OtherRides101Controller@index')->name('index')->middleware(['right:CARD101_ACCESS_OTHERS_RIDES']);
            Route::match(['get', 'post'], '/create', 'OtherRides101Controller@create')->name('create')->middleware(['right:CARD101_ACCESS_OTHERS_RIDES']);
            Route::match(['get', 'post'], '{id}/edit', 'OtherRides101Controller@edit')->name('edit')->middleware(['right:CARD101_ACCESS_OTHERS_RIDES']);
            Route::delete('delete/{id}', 'OtherRides101Controller@delete')->name('delete')->middleware(['right:CARD101_ACCESS_OTHERS_RIDES,CARD101_OTHERS_RIDES_CAN_DELETE']);
            Route::post('switch-delayed', 'OtherRides101Controller@switchDelayed')->middleware(['right:CARD101_ACCESS_OTHERS_RIDES,CARD101_OTHER_RIDES_CAN_SET_DELAYED']);
            Route::post('cancel-delayed', 'OtherRides101Controller@cancelDelayed')->middleware(['right:CARD101_ACCESS_OTHERS_RIDES,CARD101_OTHER_RIDES_CAN_SET_DELAYED']);
            Route::post('approve-delayed', 'OtherRides101Controller@approveDelayed')->middleware(['right:CARD101_ACCESS_OTHERS_RIDES,CARD101_OTHER_RIDES_CAN_SET_DELAYED']);
        });

        Route::group(['prefix' => 'card101-drill-rides', 'as' => 'card101-drill-rides'], function (){
            Route::get('/', 'DrillRides101Controller@index')->name('card101-drill-rides.index')->middleware(['right:CARD101_ACCESS_DRILL_RIDES']);
            Route::match(['get', 'post'], '/create', 'DrillRides101Controller@create')->name('create')->middleware(['right:CARD101_ACCESS_DRILL_RIDES']);
            Route::match(['get', 'post'], '{id}/edit', 'DrillRides101Controller@edit')->name('edit')->middleware(['right:CARD101_ACCESS_DRILL_RIDES']);
            Route::delete('delete/{id}', 'DrillRides101Controller@delete')->name('delete')->middleware(['right:CARD101_ACCESS_DRILL_RIDES,CARD101_DRILL_RIDES_CAN_DELETE']);
        });

        Route::post('/card/add101/{card_id?}/{card_type?}', 'CardController@postAdd101')->name('card101save')->where(['user_id' => '[0-9]+','card_type' => '[A-Za-z]+']);
        Route::post('/card/add101/{card_id}/switch-state', 'CardController@postSwitchStateCard')->name('card101save')->where(['user_id' => '[0-9]+']);
        Route::post('/card/101/delete', 'CardController@postDelete')->name('card101.delete');

        Route::group(['prefix' => 'norms-psp', 'as' => 'norms-psp.'], function (){
            Route::get('/', 'NormPspController@index')->name('index')->middleware(['right:CAN_ACCESS_NORMS_PSP']);
            Route::get('{id}/edit', 'NormPspController@edit')->name('edit')->middleware(['right:CAN_ACCESS_NORMS_PSP']);
            Route::delete('delete/{id}', 'NormPspController@delete')->name('delete')->middleware(['right:CAN_DELETE_NORMS_PSP']);
            Route::post('{id}/update', 'NormPspController@update')->name('update')->middleware(['right:CAN_EDIT_NORMS_PSP']);
            Route::match(['get','post'],'create', 'NormPspController@create')->name('create')->middleware(['right:CAN_CREATE_NORMS_PSP']);
        });

        Route::get('/card/mapscreen', 'CardController@getMapscreen')->name('card101.mapscreen');
        Route::get('/hydrants', 'CardController@hydrants')->name('hydrants')->middleware(['right:CAN_ACCESS_HYDRANT']);

        Route::resource('/card112', 'Card112Controller');

        Route::resource('/emergency-situation', 'EmergencySituationController');
        Route::get('emergency-situation/{id}/download', 'EmergencySituationController@download')->name('emergency-situation.download');

        Route::get('/import', 'ImportController@index')->name('import.index');
        Route::post('/import/special_plans', 'ImportController@specialPlans')->name('import.special_plans');
        Route::post('/import/hydrants', 'ImportController@hydrants')->name('import.hydrants');

        // Строевые записки
        Route::group(['prefix' => 'formation'], function () {
            Route::get('/', 'FormationController@getServicesList');
            Route::get('101', 'FormationController@get101');
            Route::match(['get', 'post'],'create', 'FormationController@create');
            Route::group(['prefix' => 'air-rescue'], function (){
                Route::get('/', 'FormationController@getAirRescue');
                Route::get('/create', 'FormationController@getAirRescueCreate');
                Route::get('/{id}/edit', 'FormationController@getAirRescueEdit');
                Route::post('/edit-create', 'FormationController@getAirRescueEditCreate');
                Route::post('/approve/{id}', 'FormationController@approveAirRescue');
                Route::delete('{id}', 'FormationController@deleteAirRescue');
            });
            Route::get('addToday', 'FormationController@getAddToday');
            Route::post('addToday', 'FormationController@postAddToday');
            Route::post('approve101/{id}', 'FormationController@postApproveReport101');
            Route::get('add101persons/{form_id}/{dept_id?}', 'FormationController@getAdd101Persons')
                ->where('form_id', '[0-9]+')
                ->where('dept_id', '[0-9]+')
                ->name('formation.101.add');
            Route::post('add101persons/{form_id}/{dept_id?}', 'FormationController@postAdd101Persons')
                ->where('form_id', '[0-9]+')
                ->where('dept_id', '[0-9]+');
            Route::get('add101tech/{form_id}/{dept_id?}', 'FormationController@getAdd101Tech')
                ->where('form_id', '[0-9]+')
                ->where('dept_id', '[0-9]+')
                ->name('formation.101.add');
            Route::post('add101tech/{form_id}/{dept_id?}', 'FormationController@postAdd101Tech')
                ->where('form_id', '[0-9]+')
                ->where('dept_id', '[0-9]+');
            Route::get('view101/{form_id}', 'FormationController@getView101')->where('form_id', '[0-9]+');
            Route::get('air-rescue/{form_id}', 'FormationController@getAirRescueView')->where('form_id', '[0-9]+');

            Route::get('mudflow', 'FormationController@getMudflow');
            Route::get('editmudflow/{id}', 'FormationController@getEditMudflow')->where('id', '[0-9]+');
            Route::post('editmudflow/{id}', 'FormationController@postEditMudflow')->where('id', '[0-9]+');

            Route::get('medical', 'FormationController@getMedical');
            Route::get('editmedical/{id}', 'FormationController@getEditMedical')->where('id', '[0-9]+');
            Route::post('editmedical/{id}', 'FormationController@postEditMedical')->where('id', '[0-9]+');

            Route::get('editsavers/{id}', 'FormationController@getEditSavers')->where('id', '[0-9]+')->middleware(['right:CAN_ACCESS_FORMATION_REPORT_ROSO_EDIT']);
            Route::post('editsavers/{id}', 'FormationController@postEditSavers')->where('id', '[0-9]+')->middleware(['right:CAN_ACCESS_FORMATION_REPORT_ROSO']);
            // ГУ РОСО спасоперации итд
            Route::group(['prefix' => 'savers'], function () {
                //спасоперации
                Route::get('events/{id}', 'FormationController@getSaversOperationsList')->where('id', '[0-9]+');
                Route::get('event/{parent_id}/{id?}', 'FormationController@getSaversOperation')
                    ->where('parent_id', '[0-9]+')
                    ->where('id', '[0-9]+');
                Route::post('event/{parent_id}/{id?}', 'FormationController@postSaversOperation')
                    ->where('parent_id', '[0-9]+')
                    ->where('id', '[0-9]+');
                // перемещения
                Route::get('migrations/{id}', 'FormationController@getSaversMigrationsList')->where('id', '[0-9]+');
                Route::get('migration/{parent_id}/{id?}', 'FormationController@getSaversMigration')
                    ->where('parent_id', '[0-9]+')
                    ->where('id', '[0-9]+');
                Route::post('migration/{parent_id}/{id?}', 'FormationController@postSaversMigration')
                    ->where('parent_id', '[0-9]+')
                    ->where('id', '[0-9]+');
                // силы и средства
                Route::get('resources/{id}', 'FormationController@getSaversResourcesList')->where('id', '[0-9]+');
                Route::get('resource/{parent_id}/{id?}', 'FormationController@getSaversResources')
                    ->where('parent_id', '[0-9]+')
                    ->where('id', '[0-9]+');
                Route::post('resource/{parent_id}/{id?}', 'FormationController@postSaversResources')
                    ->where('parent_id', '[0-9]+')
                    ->where('id', '[0-9]+');

                Route::get('/', 'FormationController@getSavers');
            });
        });

        Route::group(['middleware' => 'rights.formation.record'], function () {
                Route::get('formation-record/{organisation}', 'FormationRecordController@singleIndex')->name('formation-record.single-index');
                Route::get('formation-record/{id}/total-edit', 'FormationRecordController@totalEdit')->name('formation-record.total-edit');
                Route::post('formation-record/{id}/total-update', 'FormationRecordController@totalUpdate')->name('formation-record.total-update');
                Route::match(['get', 'post'],'formation-record/staff/{date}/{ods}', 'FormationRecordController@staffCreateEdit')->name('formation-record.staff_CreateEdit');
                Route::match(['get', 'post'],'formation-record/staff-checkpoint/{date}/{ods}', 'FormationRecordController@staffCheckpointCreateEdit')->name('formation-record.staff-checkpoint');
                Route::match(['get', 'post'],'formation-record/district-managers/{date}', 'FormationRecordController@districtManagersCreateEdit')->name('formation-record.districtManagers_CreateEdit');
                Route::match(['get', 'post'],'formation-record/duty-persons-services/{date}', 'FormationRecordController@dutyPersonsServicesCreateEdit')->name('formation-record.dutyPersonsServicesCreateEdit_CreateEdit');
                Route::resource('formation-record', 'FormationRecordController');
                Route::post('formation-record/approve/{id}', 'FormationRecordController@approve');
                Route::get('formation-record/total-edit/word', 'FormationRecordController@saveTotalAsDocx');

                Route::match(['get', 'post'],'formation-record/{organisation}/create', 'FormationRecordController@create');

        });

        Route::group(['prefix' => 'roadtrip'], function () {
            Route::get('/', 'RoadtripController@getIndex');
            Route::post('/accept/{id}', 'RoadtripController@postAccept')->where('id', '[0-9]+');
            Route::post('/force-out/{id}', 'RoadtripController@postForceOut')->where('id', '[0-9]+');
            Route::get('/view/{plan_id}', 'RoadtripController@getView')
                ->name('roadtrip.plan.view')
                ->where('plan_id', '[0-9]+');
            Route::get('/view-other/{plan_id}', 'RoadtripController@getViewOther')
                ->name('roadtrip.plan.view.other')
                ->where('plan_id', '[0-9]+');
            Route::get('/print/{id}', 'RoadtripController@getPrint')
                ->where('id', '[0-9]+')
                ->name('roadtrip.plan.print');
            Route::post('dispatch', 'RoadtripController@postDispatch');
            Route::post('arrived', 'RoadtripController@postArrived');
            Route::post('return', 'RoadtripController@postReturn');
            Route::post('/save/{plan_id}', 'RoadtripController@postPlan')
                ->where('plan_id', '[0-9]+');

            Route::get('/send/{dept_id}/{ticket_id}/{departments?}', 'RoadtripController@getSend')
                ->where('dept_id', '[0-9]+')
                ->where('ticket_id', '[0-9]+')
                ->where('departments', '[0-9]+');

            Route::post('/retreat/{dept_id}/{ticket_id}/{departments?}', 'RoadtripController@postRetreat')
                ->where('dept_id', '[0-9]+')
                ->where('ticket_id', '[0-9]+')
                ->where('departments', '[0-9]+');

            Route::post('/retreat-hq', 'RoadtripController@postRetreatHq');

            Route::get('/send-all/{ticket_id}', 'RoadtripController@postSendAll');
            Route::post('other/send-all/{ticket_id}', 'RoadtripController@postSendAllOther');
            Route::post('other/send/{dept_id}/{ticket_id}/{departments?}', 'RoadtripController@postSendOther');
            Route::post('recommend', 'RoadtripController@postRecommend');

            Route::get('/additional/{id}', 'RoadtripController@getAdditional')
                ->name('roadtrip.additional')
                ->where('id', '[0-9]+');

        });

        Route::group(['prefix' => 'service-plans'], function (){
            Route::get('/', 'ServicePlanController@getList');
            Route::post('send', 'ServicePlanController@postSend');
            Route::post('check', 'ServicePlanController@postCheck');
            Route::post('accept/{id}/{service}', 'ServicePlanController@postAccept')->where('id', '[0-9]+');
            Route::post('arrive/{id}/{service}', 'ServicePlanController@postArrive')->where('id', '[0-9]+');
            Route::post('return/{id}/{service}', 'ServicePlanController@postReturn')->where('id', '[0-9]+');
            Route::get('{service}', 'ServicePlanController@getIndex')->where('service', '[0-9]+');
            Route::get('{service}/{id}/show', 'ServicePlanController@getShow')->where('service', '[0-9]+');
            Route::get('print/{id}', 'ServicePlanController@getPrint')
                ->where('id', '[0-9]+')
                ->name('service-plans.print');
        });

        Route::get('/dictionaries', 'DictionaryController@getIndex');

        Route::group(['prefix' => 'dictionaries-entity', 'as' => 'dictionaries-entity.', 'middleware' => ['right:CAN_ACCESS_DICTIONARY_ENTITY']], function () {
            Route::get('{id}/edit', 'DictionaryEntityController@edit');
            Route::post('create-edit', 'DictionaryEntityController@createEdit');
            Route::get('create', 'DictionaryEntityController@create');
        });

        Route::get('/dictionaries/{name}', 'DictionaryController@getIndexByName');
        Route::get('/dictionaries/{name}/create', 'DictionaryController@getEditByName');
        Route::get('/dictionaries/{name}/{id}/edit', 'DictionaryController@getEditByName')->where('dict_id', '[0-9]+');
        Route::post('/dictionaries/{name}/create-edit', 'DictionaryController@postEditCreateByName');

        Route::get('/dictionaries/list/{dict_id}', 'DictionaryController@getList')->where('dict_id', '[0-9]+');
        Route::get('/dictionaries/edit/{dict_id}/{row_id?}', 'DictionaryController@getEdit')
            ->where('dict_id', '[0-9]+')
            ->where('row_id', '[0-9]+');
        Route::post('/dictionaries/edit/{dict_id}/{row_id?}', 'DictionaryController@postEdit')
            ->where('dict_id', '[0-9]+')
            ->where('row_id', '[0-9]+');
        Route::delete('/dictionaries/delete/{dict_id}/{row_id}', 'DictionaryController@delete')
            ->where('dict_id', '[0-9]+')
            ->where('row_id', '[0-9]+')
            ->name('dictionaries.row.delete');

        Route::delete('/dictionaries/delete/{name}/{row_id}', 'DictionaryController@deleteByName')
            ->where('row_id', '[0-9]+')
            ->name('dictionaries.row.delete_by_name');


        Route::get('/pdf/dailyReport', 'ReportController@getDaily')->name('dailyReport');
        Route::get('/pdf/get-operational-plan/{id}', 'ReportController@getOperationalPlan')->name('operational-plan')->where('id', '[0-9]+');;
        Route::get('/pdf/get-operational-card/{id}', 'ReportController@getOperationalCard')->name('operational-card')->where('id', '[0-9]+');;
        Route::get('/pdf/operational-report', 'ReportController@getOperational')->name('operational-report');
        Route::get('/pdf/report101/{type}', 'ReportController@getReport101')->name('report101');
        Route::get('/xls/report101/forces', 'ReportController@exportForcesXls')->name('report101.forces.xls');
        Route::get('/xls/report101/emergency', 'ReportController@exportEmergency101Xls')->name('report101.emergency.xls');
        Route::get('/xls/report112/emergency', 'ReportController@exportEmergency112Xls')->name('report112.emergency.xls');
        Route::get('/xls/card101/chronology/{card_id}', 'ReportController@exportCard101ChronologyXls')->name('card101.chronology.xls');
        Route::resource('/chats', 'ChatController');
        Route::resource('/messages', 'MessageController');
        Route::resource('/nicknames', 'NicknameController');
        Route::resource('/information', 'InformationController');

        Route::get('/fire-department-checks/{date}', 'FireDepartmentCheckController@editByDay')
            ->name('fire-department-checks.edit-by-date')->where(['date' => '[0-9]{4}-[0-9]{2}-[0-9]{2}']);

        Route::post('/fire-department-checks/{date}', 'FireDepartmentCheckController@updateByDay')
            ->name('fire-department-checks.update-by-date')->where(['date' => '[0-9]{4}-[0-9]{2}-[0-9]{2}']);

        Route::resource('/fire-department-checks', 'FireDepartmentCheckController');

        Route::get('/mudflowProtection/export/xls/{date}', 'MudflowProtectionController@exportExcel');

        Route::group(['prefix' => 'mudflow-protection'], function () {
            Route::get('/', 'MudflowProtectionController@list')->middleware(['right:CAN_VIEW_MUDFLOW_PROTECTION']);
            Route::get('{date}', 'MudflowProtectionController@indexByDate')->middleware(['right:CAN_VIEW_MUDFLOW_PROTECTION']);
            Route::get('{date}/{id}/edit', 'MudflowProtectionController@edit')->middleware(['right:CAN_VIEW_MUDFLOW_PROTECTION']);
            Route::get('{date}/{id}/create', 'MudflowProtectionController@create')->middleware(['right:CAN_EDIT_MUDFLOW_PROTECTION']);
            Route::post('{date}/{id}/update', 'MudflowProtectionController@update')->middleware(['right:CAN_EDIT_MUDFLOW_PROTECTION']);
            Route::post('{date}/{id}/store', 'MudflowProtectionController@store')->middleware(['right:CAN_EDIT_MUDFLOW_PROTECTION']);
            Route::post('{date}/{id}/delete', 'MudflowProtectionController@delete')->middleware(['right:CAN_EDIT_MUDFLOW_PROTECTION']);
        });

        Route::resource('/weather', 'WeatherController')->middleware(['right:KAZGIDROMET_FILLING']);
        Route::resource('/quakes', 'QuakeController');
        Route::get('/quakes/export/xls', 'QuakeController@exportExcel');
        Route::resource('/vehicles', 'VehicleController');
        Route::resource('/staff', 'StaffController');
        Route::resource('/schedules', 'ScheduleController');
        #Route::resource('/morainic-lakes', 'MorainicLakeController');
        Route::resource('/morainic-lakes-summaries', 'MorainicLakeSummaryController');
        Route::get('/morainic-lakes-summaries/export/xls/{date}', 'MorainicLakeSummaryController@exportXls');
        Route::resource('/notification-groups', 'NotificationGroupsController');
        Route::get('/morainic-lakes-reports/{date}', 'MorainicLakeReportController@index');
        Route::post('/morainic-lakes-reports/{date}/update', 'MorainicLakeReportController@update');
        Route::resource('/salvage', 'SalvageController');

        Route::group(['prefix' => 'reports/101', 'as' => 'reports.'], function () {
            Route::get('/staff', 'ReportController@getReport101Staff')->name('staff');
            Route::post('/staff', 'ReportController@postReport101Staff')->name('staff_post');
            Route::post('/staff-managers-ods', 'ReportController@postReportStaffManagersOSDStaff')->name('staff_post_ods');
            Route::get('/vehicles', 'ReportController@getReport101Vehicles')->name('vehicles');
            Route::post('/vehicles', 'ReportController@postReport101Vehicles')->name('vehicles_post');

            Route::get('/forces-resources', 'ReportController@getForces')->name('forces');

            Route::get('/emergency', 'ReportController@getReport101Emergency')->name('emergency101_get');
            Route::post('/emergency', 'ReportController@postReport101Emergency')->name('emergency101_post');
        });

        Route::group(['prefix' => 'reports/112', 'as' => 'reports112.'], function () {
            Route::get('/', 'Analytics112Controller@index')->name('index');
            Route::get('/staff', 'ReportController@getReport112Staff')->name('staff');
            Route::post('/staff', 'ReportController@postReport112Staff')->name('staff_post');
            Route::get('/vehicles', 'ReportController@getReport112Vehicles')->name('vehicles');
            Route::post('/vehicles', 'ReportController@postReport112Vehicles')->name('vehicles_post');

            Route::get('/emergency', 'ReportController@getReport112Emergency')->name('emergency112_get');
            Route::post('/emergency', 'ReportController@postReport112Emergency')->name('emergency112_post');

            Route::get('/avalanches', 'ReportController@getReportAvalanches')->name('avalanches_get');
            Route::get('/elevators', 'ReportController@getReportElevators')->name('elevators_get');
            Route::get('/disease', 'ReportController@getReportDisease')->name('disease_get');

            Route::get('/branches', 'ReportController@getReport112Branches')
                ->name('emergency112_get_branches');
            Route::match(['get','post'],'/branches_export', 'ReportController@getReport112BranchesExport')
                ->name('emergency112_get_branches_export');
        });

        Route::group(['prefix' => 'reports/siren-speeches', 'as' => 'reports-siren-speeches.'], function () {
            Route::get('/', 'SirenSpeechTechController@index')->name('index')->middleware(['right:SIREN_SPEECH_TECH_SHOW']);
            Route::get('/create', 'SirenSpeechTechController@create')->name('create')->middleware(['right:SIREN_SPEECH_TECH_CREATE']);
            Route::get('/edit/{id}', 'SirenSpeechTechController@edit')->name('edit')->middleware(['right:SIREN_SPEECH_TECH_SHOW']);
            Route::put('/update/{id}', 'SirenSpeechTechController@update')->name('update')->middleware(['right:SIREN_SPEECH_TECH_EDIT']);
            Route::post('/store', 'SirenSpeechTechController@store')->name('store')->middleware(['right:SIREN_SPEECH_TECH_CREATE']);
            Route::delete('/delete/{id}', 'SirenSpeechTechController@delete')->name('delete')->middleware(['right:SIREN_SPEECH_TECH_DELETE']);
        });

        Route::group(['prefix' => 'reports/call-infos', 'as' => 'reports-call-infos.'], function () {
            Route::get('/', 'CallInfoController@index')->name('index')->middleware(['right:CALL_INFO_SHOW']);
            Route::get('/create', 'CallInfoController@create')->name('create')->middleware(['right:CALL_INFO_CREATE']);
            Route::get('/edit/{id}', 'CallInfoController@edit')->name('edit')->middleware(['right:CALL_INFO_SHOW']);
            Route::put('/update/{id}', 'CallInfoController@update')->name('update')->middleware(['right:CALL_INFO_EDIT']);
            Route::post('/store', 'CallInfoController@store')->name('store')->middleware(['right:CALL_INFO_CREATE']);
            Route::delete('/delete/{id}', 'CallInfoController@delete')->name('delete')->middleware(['right:CALL_INFO_DELETE']);
            Route::get('/show', 'CallInfoController@show')->name('show');//->middleware(['right:CALL_INFO_DELETE']);
            Route::get('/download/{format}', 'CallInfoController@download')->name('download');//->middleware(['right:CALL_INFO_DELETE']);
        });

        Route::get('reports/112-emergency-report','ReportController@getReport112EmergencyType')->middleware(['right:CAN_ACCESS_REPORT_112_EMERGENCY_REPORT']);
        Route::get('reports/112-emergency-report/export/{type}','ReportController@exportReport112Emergency')->middleware(['right:CAN_ACCESS_REPORT_112_EMERGENCY_REPORT']);

        Route::get('reports/other-rides-report','ReportController@getReportOtherRides')->middleware(['right:CAN_ACCESS_REPORT_OTHER_RIDES']);
        Route::get('reports/other-rides-report/export/{type}','ReportController@exportReportOtherRides')->middleware(['right:CAN_ACCESS_REPORT_OTHER_RIDES']);

        Route::get('reports/forces-resources','ReportController@getReportForcesResources')->middleware(['right:CAN_ACCESS_REPORT_FORCES_RESOURCES']);
        Route::get('reports/forces-resources/export/{type}','ReportController@exportReportForcesResources')->middleware(['right:CAN_ACCESS_REPORT_FORCES_RESOURCES']);

        Route::get('reports/drill-rides-report','ReportController@getReportDrillRides')->middleware(['right:CAN_ACCESS_REPORT_DRILL_RIDES']);
        Route::get('reports/drill-rides-report/export/{type}','ReportController@exportReportDrillRides')->middleware(['right:CAN_ACCESS_REPORT_DRILL_RIDES']);

        Route::get('reports/emergency-rescue-gu/','ReportController@getReportEmergencyRescueGu')->middleware(['right:CAN_ACCESS_REPORT_EMERGENCY_RESCUE_GU']);
        Route::get('reports/emergency-rescue-gu/export/{type}','ReportController@exportReportEmergencyRescueGu')->middleware(['right:CAN_ACCESS_REPORT_EMERGENCY_RESCUE_GU']);

        Route::get('reports/object-classifications/','ReportController@getReportObjectClassification')->middleware(['right:CAN_ACCESS_REPORT_OBJECT_CLASSIFICATION']);
        Route::get('reports/object-classifications/export/{type}','ReportController@exportReportObjectClassification')->middleware(['right:CAN_ACCESS_REPORT_OBJECT_CLASSIFICATION']);

        Route::get('reports/water-consumption/','ReportController@getReportWaterConsumption');//->middleware(['right:CAN_ACCESS_REPORT_OBJECT_CLASSIFICATION']);
        Route::get('reports/water-consumption/export/{type}','ReportController@exportReportWaterConsumption');//->middleware(['right:CAN_ACCESS_REPORT_OBJECT_CLASSIFICATION']);
        Route::get('reports/quakes','ReportController@getReportQuakes');

        Route::get('reports/daily-reports/{type}','ReportController@daily_reports');

        Route::get('reports/analytics-spiasr', 'AnalyticsSpiasrController@index');

        Route::get('reports/queued-reports', 'ReportController@queuedReports')->name('reports.queued-reports');
        Route::get('reports/queued-reports/view', 'ReportController@queuedReportsView')->name('reports.queued-reports-view');

        /** Суточные отчеты в формате Ворд */
        Route::group(['prefix' => 'reports'], function(){
            Route::get('daily101/{format}', 'ReportController@getDaily101Formatted')->where('format', '(word)');
            Route::get('daily112/{format}', 'ReportController@getDaily112Formatted')->where('format', '(word)');
        });

        /** Мессенджер */
        Route::group(['namespace' => 'Api\\Messenger', 'prefix' => 'api/messenger', 'middleware' => ['auth']], function() {
            Route::get('users/list', 'MessengerController@getUserList');
            Route::post('message/send', 'MessengerController@postMessage');
            Route::get('messages/unread', 'MessengerController@getAnyUnread');
            Route::get('messages/list/{user_id}', 'MessengerController@getMessages')->where('user_id', '[0-9]+');

            Route::get('messages/list/unread/{user_id}', 'MessengerController@getUnreadCount')->where('user_id', '[0-9]+');
        });

        Route::group(['namespace' => 'Api\\Upload', 'prefix' => 'api/upload', 'middleware' => ['auth']], function() {
            Route::post('file', 'FileUploadController@postUpload')
                ->name('storage.file.upload');
            Route::get('file/info/{file_id}', 'FileUploadController@getFileInfo')
                ->where('file_id', '[0-9]+')
                ->name('storage.file.info');
            Route::get('file/download/{file_id}', 'FileUploadController@getFile')
                ->where('file_id', '[0-9]+')
                ->name('storage.file.download');
            Route::get('queued-report/file/download/{report_id}', 'FileUploadController@getQueuedReportFile')
                ->where('report_id', '[0-9]+')
                ->name('storage.queued-report.file.download');
        });

        Route::group(['prefix' => 'reports/analytics101', 'as' => 'reports.analytics101.'], function (){
            Route::get('/', 'AnalyticsController@index')->name('index'); //->middleware(['right:ANALYTICS101_SHOW']);
            Route::get('{id}/edit', 'AnalyticsController@edit')->name('edit')->middleware(['right:ANALYTICS101_EDIT']);
            Route::post('update/{id}', 'AnalyticsController@update')->name('update')->middleware(['right:ANALYTICS101_EDIT']);
            Route::delete('delete/{id}', 'AnalyticsController@delete')->name('delete')->middleware(['right:ANALYTICS101_DELETE']);
            Route::get('word/{id}', 'AnalyticsController@word')->name('word');//->middleware(['right:ANALYTICS101_EDIT']);
        });

        Route::group(['prefix' => 'reports/analytics112', 'as' => 'reports.analytics112.'], function (){
            Route::get('/', 'Analytics112Controller@index')->name('index'); //->middleware(['right:ANALYTICS101_SHOW']);
            Route::get('{id}/edit', 'Analytics112Controller@edit')->name('edit')->middleware(['right:ANALYTICS101_EDIT']);
            Route::post('update/{id}', 'Analytics112Controller@update')->name('update')->middleware(['right:ANALYTICS101_EDIT']);
            Route::delete('delete/{id}', 'Analytics112Controller@delete')->name('delete')->middleware(['right:ANALYTICS101_DELETE']);
            Route::get('download/{date}/{type?}', 'Analytics112Controller@download')->name('download');//->middleware(['right:ANALYTICS101_EDIT']);
        });

        Route::group(['prefix' => 'reports/alert-system-checks', 'as' => 'reports.alert-system-checks.'], function (){
            Route::get('/', 'AlertSystemCheckController@index')->name('index')->middleware(['right:ALERT_SYSTEM_CHECK_SHOW']);
            Route::get('{id}/edit', 'AlertSystemCheckController@edit')->name('edit')->middleware(['right:ALERT_SYSTEM_CHECK_EDIT']);
            Route::patch('update/{id}', 'AlertSystemCheckController@update')->name('update')->middleware(['right:ALERT_SYSTEM_CHECK_EDIT']);
            Route::get('download', 'AlertSystemCheckController@download')->name('download')->middleware(['right:ALERT_SYSTEM_DOWNLOAD']);
        });

        Route::get('check-popup-notifications', 'AjaxController@checkPopupNotifications');
        Route::post('increment-map-request', 'AjaxController@incrementMapRequest');


        // api
        Route::group(['middleware' => ['auth'], 'prefix' => 'auth-api', 'namespace' => 'Api'], function() {
            Route::get('queued-reports/user-has-not-finished-report', 'QueuedReportsController@userHasNotFinishedReport');
            Route::post('queued-reports/send-to-queue', 'QueuedReportsController@sendToQueue');
            Route::get('queued-reports/show-full/{id}', 'QueuedReportsController@showFull');
            Route::apiResource('queued-reports', 'QueuedReportsController');
        });

        Route::group(['prefix' => 'translates'], function () {
            Route::get('/', 'TranslateController@getIndex');
            Route::get('view/{groupKey?}', 'TranslateController@getView')->where('groupKey', '.*');
            Route::get('/{groupKey?}', 'TranslateController@getIndex')->where('groupKey', '.*');
            Route::post('/add/{groupKey}', 'TranslateController@postAdd')->where('groupKey', '.*');
            Route::post('/edit/{groupKey}', 'TranslateController@postEdit')->where('groupKey', '.*');
            Route::post('/groups/add', 'TranslateController@postAddGroup');
            Route::post('/delete/{groupKey}/{translationKey}', 'TranslateController@postDelete')->where('groupKey', '.*');
            Route::post('/import', 'TranslateController@postImport');
            Route::post('/find', 'TranslateController@postFind');
            Route::post('/locales/add', 'TranslateController@postAddLocale');
            Route::post('/locales/remove', 'TranslateController@postRemoveLocale');
            Route::post('/publish/{groupKey}', 'TranslateController@postPublish')->where('groupKey', '.*');
        });

        Route::get('/', 'HomeController@getIndex')->name('home');

//    });
});
