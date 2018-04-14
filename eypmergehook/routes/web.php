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

Route::prefix('/settings')->group(function () {
  Route::get('/profile', 'UserController@edit')->name('user.edit');
  Route::post('/profile', 'UserController@edit')->name('user.edit');
  Route::put('/profile.update', 'UserController@update')->name('user.update');
  Route::post('/profile.update', 'UserController@update')->name('user.update');
  Route::prefix('/controllers')->group(function () {
    Route::resource('/orgs', 'OrganizationController');
    Route::resource('/repos', 'RepoController');
    Route::resource('/platforms', 'PlatformController');
    Route::resource('/reporeleases', 'RepoReleaseController');
  });
});
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/login/{provider}',          'Auth\SocialAccountController@redirectToProvider');
Route::get('/login/{provider}/callback', 'Auth\SocialAccountController@handleProviderCallback');

Route::prefix('/{nickname}')->group(function () {
  Route::prefix('/platform-{platform}')->group(function () {
    Route::get('/', 'PlatformController@getUserPlatform')->name('show.eyp.user.platform');
  });
  Route::prefix('/repo-{repo}')->group(function () {
    Route::get('/', 'RepoController@getUserRepo')->name('show.eyp.user.repo');
  });
  Route::get('/', 'UserController@getUserInfo')->name('show.eyp.user');
});
