<?php

Route::group(['prefix' => 'auths', 'middleware' => []], function () {
    Route::get('/', 'Api\AuthController@index')->name('api.auths.index');
    Route::post('/', 'Api\AuthController@store')->name('api.auths.store');
    Route::get('/{auth}', 'Api\AuthController@show')->name('api.auths.read');
    Route::put('/{auth}', 'Api\AuthController@update')->name('api.auths.update');
    Route::delete('/{auth}', 'Api\AuthController@destroy')->name('api.auths.delete');
});