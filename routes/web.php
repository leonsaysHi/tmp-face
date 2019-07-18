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

Auth::routes();
Route::get('logout', 'Auth\LoginController@logout');

Route::get('/', function () {
    return redirect('/home');
});

Route::middleware('auth')->group(function () {
    //API VIEWER
    // Route::get("apiviewer", "APITestController");
    // Route::get('set-tester', 'APITestController@setTester');
    // Route::get('masquerade', 'APITestController@masquerade');
    // Route::get('end-masquerade', 'APITestController@endMasquerade');
});

Route::get('styleguide', 'StyleguideViewController')->name('styleguide');
Route::get('home', 'HomeViewController')->name('home');
Route::get('payment', 'PaymentViewController')->name('payment');

Route::middleware('auth')->group(function () {
    // Route::get('dashboard', 'DashboardViewController')->name('dashboard');
    // Route::get('search', 'SearchViewController')->name('search');
    // Route::get('search/saved', 'SearchSavedViewController')->name('search/saved');
    // Route::get('search/history', 'SearchHistoryViewController')->name('search/history');
    // Route::get('resource-links', 'ResourceLinksViewController')->name('resource-links');
    // Route::get('register-project', 'RegisterProjectViewController')->name('register-project');
    // Route::get('modify-project/{projectId}', 'RegisterProjectViewController')->name('modify-project');
    // Route::get('profile', 'ProfileViewController')->name('profile');
    // Route::get('project-details/{projectId}', 'ProjectDetailsViewController');
    // Route::get('uncache/{cacheName}', 'APITestController@unCache');
    // Route::get('get-cached-data', 'APITestController@getCachedData');
    // Route::get('session-timed-out', 'SessionTimedOutViewController');
});

Route::prefix('api')->group(function () {
    Route::middleware('auth')->group(function () {
        // Route::get('salesforce/query', 'SalesForceController@query');
        // Route::post('salesforce/create', 'SalesForceController@create');
        // Route::patch('salesforce/update', 'SalesForceController@update');
        // Route::post('salesforce/delete', 'SalesForceController@delete');
        // Route::post('get-project-list', 'ProjectListController');
        // Route::post('search', 'ProjectSearchController');
        // Route::get('get-user', 'GetUserController');
        // Route::post('set-user', 'SetUserController');
        // Route::post('search-user', 'SearchUserController');
        // Route::get('get-preferences/{platform?}', 'UserPreferencesController@getPreferences');
        // Route::post('set-preferences/{platform?}', 'UserPreferencesController@setPreferences');
        // Route::get('get-fx-rates', 'GetFXRateController');
        // Route::get('get-links', 'GetLinkController');
        // Route::post('create-update-project', 'CreateUpdateProjectController');
        // Route::get('get-project/{projectId}', 'GetProjectController');
        // Route::post('get-lov', 'GetLovController');
        // Route::get('search/history/all/{recordCount?}', 'SearchController@historyIndex');
        // Route::get('search/history/{id}', 'SearchController@show');
        // Route::post('search/saved', 'SearchController@savedStore');
        // Route::get('search/saved/all', 'SearchController@savedIndex');
        // Route::get('search/saved/{id}', 'SearchController@show');
        // Route::post('search/saved/{id}', 'SearchController@savedDestroy');
        // Route::post('search-sap', 'SearchSAPController');
        // Route::post('attach-sap', 'AttachSAPController');
        // Route::post('detach-sap', 'DetachSAPController');
        // Route::post('get-cep', 'GetCEPController');
        // Route::post('detach-cep', 'DetachCEPController');
        // Route::post('create-cep', 'CreateCEPController');
        // Route::post('clone-project', 'CloneProjectController');
        // Route::post('get-project-list-detail', 'GetProjectListDetailController');
        // Route::post('survey/set-result', 'SetSurveyResultController');
        // Route::get('survey/get-question', 'GetQuestionController');
        // Route::get('get-user-type', 'UserController');
        // Route::get('get-env', 'GetEnvController');
    });
    Route::middleware('auth')->group(function () {
        // Route::post('upload', 'SalesForceController@upload')->name('upload');
        // Route::get('download/{id}', 'SalesForceController@download')->name('download');
        // Route::get('delete-file/{id}', 'SalesForceController@deleteFile')->name('delete-file');
    });
});


Route::middleware(['auth', "admin_only"])->group(function () {
    // Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
});

Route::prefix('api')->namespace('Api')->group(function () {
    // Route::middleware(['auth', "admin_only"])->group(function () {
    // });
});
