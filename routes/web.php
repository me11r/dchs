<?php
Route::post('logout', 'Auth\LoginController@postLogout')->name('logout');
Route::get('login', 'Auth\LoginController@getIndex')->name('login');
Route::post('login', 'Auth\LoginController@postIndex')->name('post-login');
Route::get('auth/password-reset', 'Auth\ResetPasswordController@getResetPassword')->name('password.request');

Route::group(['middleware' => 'auth'], function () {
    Route::group(['prefix' => 'ajax'], function () {
        Route::post('city/save-edit', 'AjaxController@postCityEdit');
        Route::get('office-codes', 'AjaxController@getOfficeCodes');
        Route::get('managers', 'AjaxController@getManagers');
        Route::post('get-cities', 'AjaxController@getCities')->name('getCities');
        Route::post('ticket-details', 'AjaxController@getTicketDetails');
        Route::get('route-list', 'AjaxController@getRouteList');
        Route::get('getTicketsNotInLoading', 'AjaxController@getTicketsNotInLoading');
        Route::get('getShipmentsForRoute/{routeId}', 'AjaxController@getShipmentsForRoute')->where(['routeId' => '[0-9]+']);
        Route::post('addShipmentForRoute/{routeId}', 'AjaxController@postaddShipmentForRoute')->where(['routeId' => '[0-9]+']);
        Route::get('getTicketsForShipment/{shipmentId}', 'AjaxController@getTicketsForShipment')->where(['shipmentId' => '[0-9]+']);
        Route::post('clearTicketFromShipment/{ticketId}', 'AjaxController@getClearTicketFromShipment')->where(['ticketId' => '[0-9]+']);
        Route::post('setTicketForShipment/{ticketId}/{shipmentId}', 'AjaxController@postSetTicketForShipment')->where(['ticketId' => '[0-9]+', 'shipmentId' => '[0-9]+']);
    });

    Route::group(['prefix' => 'admin'], function () {
        Route::get('users', 'AdminController@getUsers')->name('admin-users');

        Route::get('cities', 'AdminController@getCities')->name('admin-edit-cities');

        Route::get('users/office/{office_id}', 'AdminController@getUsersOffice')->where(['office_id' => '[0-9]+'])->name('admin-users-in-office');

        Route::get('users/edit/{user_id?}', 'AdminController@getUserEdit')->where(['user_id' => '[0-9]+'])->name('admin-users-edit');
        Route::post('users/edit/{user_id?}', 'AdminController@postUserEdit')->where(['user_id' => '[0-9]+'])->name('post-admin-users-edit');

        Route::get('users/lock/{user_id}', 'AdminController@getUserLock')->where(['user_id' => '[0-9]+'])->name('admin-users-lock');
        Route::post('users/lock/{user_id}', 'AdminController@postUserLock')->where(['user_id' => '[0-9]+'])->name('post-admin-users-lock');

        Route::get('users/impersonate/{user_id}', 'AdminController@getImpersonate')->where(['user_id' => '[0-9]+'])->name('admin-users-impersonate');
        Route::post('users/impersonate/{user_id}', 'AdminController@postImpersonate')->where(['user_id' => '[0-9]+'])->name('post-admin-users-impersonate');

        Route::get('users/passwd/{user_id}', 'AdminController@getPassword')->where(['user_id' => '[0-9]+'])->name('admin-users-password');
        Route::post('users/passwd/{user_id}', 'AdminController@postPassword')->where(['user_id' => '[0-9]+'])->name('post-admin-users-password');

        Route::get('offices/{trashed?}', 'AdminController@getOffices')->where(['trashed' => '(trashed)'])->name('admin-offices');
        Route::get('offices/edit/{office_id?}', 'AdminController@getOffice')->where(['office_id' => '[0-9]+'])->name('admin-office-edit');
        Route::post('offices/edit/{office_id?}', 'AdminController@postOffice')->where(['office_id' => '[0-9]+'])->name('post-admin-office-edit');
        Route::get('offices/delete/{office_id}', 'AdminController@getOfficeDelete')->where(['office_id' => '[0-9]+'])->name('admin-office-delete');
        Route::post('offices/delete/{office_id}', 'AdminController@postOfficeDelete')->where(['office_id' => '[0-9]+'])->name('post-admin-office-delete');

        Route::get('drivers', 'DriversController@getIndex')->name('admin-drivers');
        Route::get('drivers/edit/{driver?}', 'DriversController@getEdit')->where(['driver' => '[0-9]+'])->name('admin-driver-edit');
        Route::get('drivers/delete/{driver?}', 'DriversController@getDelete')->where(['driver' => '[0-9]+'])->name('admin-driver-delete');
        Route::post('drivers/delete/{driver?}', 'DriversController@postDelete')->where(['driver' => '[0-9]+'])->name('post-admin-driver-edit');
        Route::post('drivers/edit/{driver?}', 'DriversController@postUpdate')->where(['driver' => '[0-9]+'])->name('post-admin-driver-edit');

        Route::get('transports', 'TransportsController@getIndex')->name('admin-transports');
        Route::get('transports/edit/{transport?}', 'TransportsController@getEdit')->where(['transport' => '[0-9]+'])->name('admin-transport-edit');
        Route::post('transports/edit/{transport?}', 'TransportsController@postUpdate')->where(['transport' => '[0-9]+'])->name('post-admin-transport-edit');
        Route::get('transports/delete/{transport?}', 'TransportsController@getDelete')->where(['transport' => '[0-9]+'])->name('admin-transport-delete');
        Route::post('transports/delete/{transport?}', 'TransportsController@postDelete')->where(['transport' => '[0-9]+'])->name('post-admin-transport-edit');

        Route::get('routes', 'CarRoutesController@getIndex')->name('admin-routes');
        Route::get('routes/edit/{route?}', 'CarRoutesController@getEdit')->where(['route' => '[0-9]+'])->name('admin-route-edit');
        Route::post('routes/edit/{route?}', 'CarRoutesController@postEdit')->where(['route' => '[0-9]+'])->name('post-admin-route-edit');
        Route::get('routes/delete/{route?}', 'CarRoutesController@getDelete')->where(['route' => '[0-9]+'])->name('admin-route-delete');
        Route::post('routes/delete/{route?}', 'CarRoutesController@postDelete')->where(['route' => '[0-9]+'])->name('post-admin-route-edit');

    });

    Route::get('/', 'HomeController@getIndex')->name('home');

});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
