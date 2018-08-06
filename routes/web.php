<?php
Route::post('logout', 'Auth\LoginController@postLogout')->name('logout');
Route::get('login', 'Auth\LoginController@getIndex')->name('login');
Route::post('login', 'Auth\LoginController@postIndex')->name('post-login');
Route::get('auth/password-reset', 'Auth\ResetPasswordController@getResetPassword')->name('password.request');

Route::group(['middleware' => 'auth'], function () {
    Route::group(['prefix' => 'ajax'], function () {
        Route::get('street/{area_id?}', 'AjaxController@findStreet')->where('area_id', '[0-9]+');
        Route::get('find_special_plan', 'AjaxController@findSpecialPlan');
    });

    Route::group(['prefix' => 'admin'], function () {
        Route::get('users', 'AdminController@getUsers')->name('admin-users');
        Route::get('users/edit/{user_id?}', 'AdminController@getUserEdit')->where(['user_id' => '[0-9]+'])->name('admin-users-edit');
        Route::post('users/edit/{user_id?}', 'AdminController@postUserEdit')->where(['user_id' => '[0-9]+'])->name('post-admin-users-edit');

        Route::get('users/lock/{user_id}', 'AdminController@getUserLock')->where(['user_id' => '[0-9]+'])->name('admin-users-lock');
        Route::post('users/lock/{user_id}', 'AdminController@postUserLock')->where(['user_id' => '[0-9]+'])->name('post-admin-users-lock');

        Route::get('users/impersonate/{user_id}', 'AdminController@getImpersonate')->where(['user_id' => '[0-9]+'])->name('admin-users-impersonate');
        Route::post('users/impersonate/{user_id}', 'AdminController@postImpersonate')->where(['user_id' => '[0-9]+'])->name('post-admin-users-impersonate');

        Route::get('users/passwd/{user_id}', 'AdminController@getPassword')->where(['user_id' => '[0-9]+'])->name('admin-users-password');
        Route::post('users/passwd/{user_id}', 'AdminController@postPassword')->where(['user_id' => '[0-9]+'])->name('post-admin-users-password');

    });

    Route::get('/card/101', 'CardController@get101')->name('card101');
    Route::get('/card/add101/{card_id?}', 'CardController@getAdd101')->name('card101add')->where(['card_id' => '[0-9]+']);
    Route::post('/card/add101/{card_id?}', 'CardController@postAdd101')->name('card101save')->where(['user_id' => '[0-9]+']);
    Route::resource('/card112', 'Card112Controller');
    Route::get('/hydrant', 'HydrantController@index')->name('hydrant.index');

    Route::get('/formation/101', 'FormationController@get101');
    Route::get('/formation/addToday', 'FormationController@getAddToday');
    Route::post('/formation/addToday', 'FormationController@postAddToday');
    Route::get('/formation/add101persons/{form_id}/{dept_id?}', 'FormationController@getAdd101Persons')
        ->where('form_id', '[0-9]+')
        ->where('dept_id', '[0-9]+')
        ->name('formation.101.add');
    Route::post('/formation/add101persons/{form_id}/{dept_id?}', 'FormationController@postAdd101Persons')
        ->where('form_id', '[0-9]+')
        ->where('dept_id', '[0-9]+');
    Route::get('/formation/add101tech/{form_id}/{dept_id?}', 'FormationController@getAdd101Tech')
        ->where('form_id', '[0-9]+')
        ->where('dept_id', '[0-9]+')
        ->name('formation.101.add');
    Route::post('/formation/add101tech/{form_id}/{dept_id?}', 'FormationController@postAdd101Tech')
        ->where('form_id', '[0-9]+')
        ->where('dept_id', '[0-9]+');
    Route::get('/formation/view101/{form_id}', 'FormationController@getView101')->where('form_id', '[0-9]+');

    Route::group(['prefix' => 'roadtrip'], function () {
        Route::get('/', 'RoadtripController@getIndex');
        Route::get('/view/{plan_id}', 'RoadtripController@getView')
            ->name('roadtrip.plan.view')
            ->where('plan_id', '[0-9]+');
        Route::post('/save/{plan_id}', 'RoadtripController@postPlan')
            ->where('plan_id', '[0-9]+');
        Route::get('/send/{dept_id}/{ticket_id}', 'RoadtripController@getSend')
            ->where('dept_id', '[0-9]+')
            ->where('ticket_id', '[0-9]+');
    });

    Route::get('/dictionaries', 'DictionaryController@getIndex');
    Route::get('/dictionaries/list/{dict_id}', 'DictionaryController@getList')->where('dict_id', '[0-9]+');
    Route::get('/dictionaries/edit/{dict_id}/{row_id?}', 'DictionaryController@getEdit')
        ->where('dict_id', '[0-9]+')
        ->where('row_id', '[0-9]+');
    Route::post('/dictionaries/edit/{dict_id}/{row_id?}', 'DictionaryController@postEdit')
        ->where('dict_id', '[0-9]+')
        ->where('row_id', '[0-9]+');

    Route::get('/pdf/dailyReport', 'ReportController@getDaily')->name('dailyReport');
    Route::resource('/chats', 'ChatController');
    Route::resource('/messages', 'MessageController');
    Route::resource('/nicknames', 'NicknameController');
    Route::resource('/information', 'InformationController');
    Route::resource('/mudflowProtection', 'MudflowProtectionController');
    Route::resource('/weather', 'WeatherController');
    Route::resource('/quakes', 'QuakeController');


    Route::get('/', 'HomeController@getIndex')->name('home');
});
