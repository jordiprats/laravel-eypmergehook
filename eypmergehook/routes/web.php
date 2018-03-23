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
Route::post('/github/webhook', array(
                                      'uses' => 'MergeController@mergeHook',
                                      'middleware' => ['githubvalidator']
                                    ))->name('mergehook');

Route::get('/bitbucket/webhook', 'MergeController@status')->name('mergehook.status.bitbucket');
Route::post('/bitbucket/webhook', array(
                                      'uses' => 'MergeController@mergeHook',
                                      'middleware' => ['bitbucketvalidator']
                                    ))->name('mergehook.bitbucket');


Route::get('/', function () {
    return view('welcome');
});

// Auth::routes();
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/settings/profile', 'UserController@edit')->name('user.edit');
Route::post('/settings/profile', 'UserController@edit')->name('user.edit');
Route::put('/settings/profile.update', 'UserController@update')->name('user.update');
Route::post('/settings/profile.update', 'UserController@update')->name('user.update');

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/login/{provider}',          'Auth\SocialAccountController@redirectToProvider');
Route::get('/login/{provider}/callback', 'Auth\SocialAccountController@handleProviderCallback');

Route::resource('/platforms', 'PlatformController');

Route::prefix('/{user}')->group(function () {
  Route::prefix('/{platform}')->group(function () {
    Route::get('/', 'PlatformController@getUserPlatform')->name('show.eyp.user.platform');
  });
  Route::get('/', 'UserController@userPlatforms')->name('show.eyp.user');
});
