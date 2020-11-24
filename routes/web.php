<?php

use Illuminate\Support\Facades\Route;



Auth::routes();


Route::get('/', 'GuestController@index') -> name('home');

Route::get('/show/{id}', 'GuestController@show') -> name('apt-show');

Route::get('/create', 'LoggedController@create')->name('apt-create');

Route::post('/create/store', 'LoggedController@store' ) -> name('apt-store');

Route::get('/edit/{id}', 'LoggedController@edit')->name('apt-edit');

Route::post('/update/{id}', 'LoggedController@update' ) -> name('apt-update');

Route::get('/delete/{id}', 'LoggedController@delete') -> name('apt-delete');

Route::get('/profile/{id}', 'LoggedController@profile') -> name('profile');

Route::get('/messages/{id}', 'LoggedController@messages') -> name('messages');

Route::post('/send/message/{id}', 'GuestController@storemsg' ) -> name('apt-storemsg');

Route::get('/api/to/search', 'GuestController@toSearch') -> name('to-search');

Route::get('/api/search', 'ApiController@search');

Route::get('/promotion/{id}', 'LoggedController@promotion')->name('apt-promotion');

Route::get('/setVisibility', 'ApiController@setVisibility');

Route::get('/stats/{id}', 'LoggedController@stats') -> name('apt-stats');

Route::get('/getStats', 'ApiController@getStats');

Route::post('/sponsorship/{id}', 'PaymentController@sponsorshipPayment') -> name('apt-sponsorship');

Route::get('/allRead', 'LoggedController@allRead');