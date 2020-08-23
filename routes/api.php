<?php

use Illuminate\Http\Request;
use \App\Http\Controllers\API;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

##### Redirect route if request route is not found route####
Route::fallback(function(){
    return response()->json(['msg' => [__('messages.request_not_found')]],404);
});
Auth::routes(['verify' => true]);

##### Set middleware for all routes
//Route::middleware(['setLanguageResponse'])->group(function () {

    #### Authentication routes #####
    Route::prefix('auth')->group(function () {
        Route::post('/register','API\AuthController@register')->name('auth.register');
        Route::post('/login','API\AuthController@login')->name('auth.login');
        Route::post('password/reset', 'API\AuthController@reset')->name('auth.passwordReset');
    });

    ################################# Routes for logged user ################################
    Route::middleware(['auth:api','verified'])->group(function () {
        #### Authentication routes #####
        Route::prefix('auth')->group(function () {
            Route::post('/logout','API\AuthController@logout')->name('auth.logout');
            Route::post('/change-password', 'API\AuthController@changePassword')->name('auth.changePassword');
        });

        #### User routes #####
        Route::prefix('users')->group(function () {
            Route::post('/update-profile','API\UsersController@updateProfile')->name('users.updateProfile');
            Route::get('/profile','API\UsersController@profile')->name('users.profile');
        });

        #### Causes routes #####
        Route::resource('cause', 'API\CauseController');
        Route::prefix('cause')->group(function () {
            Route::post('/update', 'API\CauseController@update')->name('cause.update');
            Route::get('/{id}/offers','API\CauseController@offers')->name('cause.offers');
            Route::post('/accept/offer', 'API\CauseController@acceptOffer')->name('cause.acceptOffer');
            Route::post('/attachments/upload', 'API\CauseController@uploadAttachments')->name('cause.uploadAttachments');
        });

        #### Consultation routes #####
        Route::resource('consultation', 'API\ConsultationController');
        Route::prefix('consultation')->group(function () {
            Route::post('update', 'API\ConsultationController@update')->name('consultation.update');
            Route::post('/accept/offer', 'API\ConsultationController@acceptOffer')->name('consultation.acceptOffer');
        });

        #### Offices routes #####
        Route::prefix('office')->group(function () {
            Route::get('/list','API\OfficeController@list')->name('office.list');
        });

        #### Messages routes #####
        Route::prefix('messages')->group(function () {
            Route::post('/send','API\MessageController@sendMessage')->name('messages.sendMessage');
            Route::get('/list','MessageController@list')->name('messages.list');
            Route::get('/specific-list/{receiver_id}','MessageController@specificList')->name('messages.specificList');
            Route::get('/cause-consultations-list','MessageController@messagesLists')->name('messages.messagesLists');
        });

        #### Free Lawyer routes #####
        Route::resource('free-lawyer', 'API\FreeLawyerController');

        #### Complaints routes #####
        Route::resource('complaint', 'API\ComplaintController');
        Route::prefix('complaint')->group(function () {
            Route::post('/update', 'API\ComplaintController@update')->name('complaint.update');
            Route::post('/reply', 'API\ComplaintController@reply')->name('complaint.reply');
            Route::delete('/reply/delete/{id}', 'API\ComplaintController@deleteReply')->name('complaint.deleteReply');
        });


    });

    Route::group(['middleware' => 'auth:api'], function () {

        Route::get('freecauses/{id}', 'FreecausesController@show');
        Route::post('freecauses', 'FreecausesController@store');
        Route::put('freecauses/{id}', 'FreecausesController@update');
        Route::delete('freecauses/{id}', 'FreecausesController@delete');


    });

    Route::group(['middleware' => 'auth:api'], function () {

        Route::get('freeoffers', 'FreeoffersController@index');
        Route::get('freeoffers/{id}', 'FreeoffersController@show');
        Route::post('freeoffers', 'FreeoffersController@store');
        Route::put('freeoffers/{id}', 'FreeoffersController@update');
        Route::delete('freeoffers/{id}', 'FreeoffersController@delete');

    });




