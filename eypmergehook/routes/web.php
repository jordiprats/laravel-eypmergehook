<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/github/webhook', 'MergeController@status')->name('mergehook.status');
#Route::post('/github/webhook', 'MergeController@mergeHook')->name('mergehook');
Route::post('/github/webhook', array(
                                      'uses' => 'MergeController@mergeHook',
                                      'middleware' => ['githubvalidator']
                                    ))->name('mergehook');

Route::get('/', function () {
    return view('welcome');
});
