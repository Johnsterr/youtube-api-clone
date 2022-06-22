<?php

Route::group(['prefix' => 'auths', 'middleware' => []], function () {
    Route::get('/', 'AuthController@index')->name('auths.index');
    Route::get('/create', 'AuthController@create')->name('auths.create');
    Route::post('/', 'AuthController@store')->name('auths.store');
    Route::get('/{auth}', 'AuthController@show')->name('auths.read');
    Route::get('/edit/{auth}', 'AuthController@edit')->name('auths.edit');
    Route::put('/{auth}', 'AuthController@update')->name('auths.update');
    Route::delete('/{auth}', 'AuthController@destroy')->name('auths.delete');
});