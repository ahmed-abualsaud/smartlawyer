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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['verify' => true]);
Route::get('/set-offers','HomeController@setOffers')->name('setOffers');

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => LaravelLocalization::setLocale(),
    'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath','verified' ]], function(){

    # Auth Routes
    Route::group(['middleware' => ['auth']], function () {

        Route::get('freecauses/{pages}/{i}', 'FreecausesController@index')->name('freecauses');
        Route::get('/fetch-freecauses','FreecausesController@fetchFreecauses')->name('freecauses.fetchFreecauses');
        Route::get('/freecauses/details/{id}','FreecausesController@freecausesDetails')->name('freecauses.details');
        Route::get('/delete-freecauses/{id}','FreecausesController@deleteFreecauses')->name('freecauses.delete');
        Route::get('/freecauses-fetch-attachments/{id}','FreecausesController@fetchAttachments')->name('freecauses.fetchAttachments');
        Route::delete('/freecauses-delete-attachment','FreecausesController@deleteAttachment')->name('freecauses.deleteAttachment');
        Route::get('/freecauses-download-attachment/{id}','FreecausesController@downloadAttachment')->name('freecauses.downloadAttachment');
        Route::get('/freecauses-attachments/{id}','FreecausesController@attachments')->name('freecauses.attachments');
        Route::get('/freecauses-offers/{id}','FreecausesController@offers')->name('freecauses.offers');
        Route::get('/freecauses-add-offer/{id}','FreecausesController@addOffer')->name('freecauses.addOffer');
        Route::post('/freecauses-store-offer','FreecausesController@storeOffer')->name('freecauses.storeOffer');
        Route::get('/freecauses-fetch-offers/{id}','FreecausesController@fetchOffers')->name('freecauses.fetchOffers');
        Route::delete('/freecauses-delete-offer','FreecausesController@deleteOffer')->name('freecauses.deleteOffer');

        Route::get('/showcases','UserController@ShowToAdmin')->name('showCasesToAdmin');
        Route::get('/showcases/{id}', 'UserController@getCaseToAdmin')->name('getCaseToAdmin');


        ######### General Routes ###########
        Route::get('/dashboard','HomeController@index')->name('dashboard');
        Route::view('/profile','profile')->name('profile');
        Route::post('/update-profile','UserController@updateProfile')->name('user.updateProfile');

        ########### Admin Routes ############
        Route::group(['middleware' => 'ManagementPermission'], function() {
            ######## Offices Routes ########
            Route::group(['prefix' => 'offices'], function() {
                Route::get('/','OfficeController@index')->name('offices');
                Route::get('/fetch-offices','OfficeController@fetchOffices')->name('offices.fetchOffices');
                Route::post('/change-status','OfficeController@changeStatus')->name('offices.changeStatus');
            });

            ######## Users Routes ########
            Route::group(['prefix' => 'users'], function() {
                Route::get('/','UserController@index')->name('users');
                Route::get('/fetch-users','UserController@fetchUsers')->name('users.fetchUsers');
                Route::post('/change-status','UserController@changeStatus')->name('users.changeStatus');
                Route::view('/add-employee','employees.add')->name('users.addForm');
                Route::post('/store-employee','UserController@storeEmployee')->name('users.storeEmployee');
                Route::get('/update-employee/{id}','UserController@updateForm')->name('causes.updateForm');
                Route::post('/update-employee','UserController@updateEmployee')->name('users.updateEmployee');
                Route::get('/log/{id}','UserController@log')->name('users.log');
                Route::get('/fetch-log/{id}','UserController@fetchLog')->name('users.fetchLog');
            });



            ######## Causes Routes ########
            ######## Causes Routes ########
            Route::group(['prefix' => 'causes'], function() {
                Route::get('/','CauseController@index')->name('causes');
                Route::get('/fetch-causes','CauseController@fetchCauses')->name('causes.fetchCauses');
                Route::get('/offers/{id}','CauseController@offers')->name('causes.offers');
                Route::get('/fetch-offers/{id}','CauseController@fetchOffers')->name('causes.fetchOffers');
                Route::get('/attachments/{id}','CauseController@attachments')->name('causes.attachments');
                Route::get('/fetch-attachments/{id}','CauseController@fetchAttachments')->name('causes.fetchAttachments');
                Route::get('/download-attachment/{id}','CauseController@downloadAttachment')->name('causes.downloadAttachment');
                Route::delete('/delete-attachment','CauseController@deleteAttachment')->name('causes.deleteAttachment');
                Route::delete('/delete','CauseController@delete')->name('causes.delete');
                Route::get('/add-offer/{id}','CauseController@addOffer')->name('causes.addOffer');
                Route::post('/store-offer','CauseController@storeOffer')->name('causes.storeOffer');
                Route::delete('/delete-offer','CauseController@deleteOffer')->name('causes.deleteOffer');
                Route::get('/add-new-stage/{id}','CauseController@addNewStage')->name('causes.addNewStage');
                Route::post('/store-new-stage','CauseController@storeNewStage')->name('causes.storeNewStage');

            });

            ######## Consultations Routes ########
            Route::group(['prefix' => 'consultations'], function() {
                Route::get('/','ConsultationController@index')->name('consultations');
                Route::get('/fetch-consultations','ConsultationController@fetchConsultations')->name('consultations.fetchConsultations');
                Route::get('/offers/{id}','ConsultationController@offers')->name('consultations.offers');
                Route::get('/fetch-offers/{id}','ConsultationController@fetchOffers')->name('consultations.fetchOffers');
                Route::delete('/delete','ConsultationController@delete')->name('consultations.delete');
                Route::get('/add-offer/{id}','ConsultationController@addOffer')->name('consultations.addOffer');
                Route::post('/store-offer','ConsultationController@storeOffer')->name('consultations.storeOffer');
                Route::delete('/delete-offer','ConsultationController@deleteOffer')->name('consultations.deleteOffer');
                Route::get('/details/{id}','ConsultationController@details')->name('consultations.details');

            });

            ######## Complaints Routes ########
            Route::group(['prefix' => 'complaints'], function() {
                Route::get('/','ComplaintController@index')->name('complaints');
                Route::get('/fetch-consultations','ComplaintController@fetchComplaints')->name('complaints.fetchComplaints');
                Route::delete('/delete','ComplaintController@delete')->name('complaints.delete');
                Route::get('/add-reply/{id}','ComplaintController@addReply')->name('complaints.addReply');
                Route::post('/store-reply','ComplaintController@storeReply')->name('complaints.storeReply');
                Route::get('/replies/{id}','ComplaintController@replies')->name('complaints.replies');
                Route::get('/fetch-replies/{id}','ComplaintController@fetchReplies')->name('complaints.fetchReplies');
                Route::delete('/delete-reply','ComplaintController@deleteReply')->name('complaints.deleteReply');

            });

            ######## Free Lawyer Routes ########
            Route::group(['prefix' => 'free-lawyer'], function() {
                Route::get('/','FreeLawyerController@index')->name('free_lawyer');
                Route::get('/fetch-questions','FreeLawyerController@fetchQuestions')->name('free_lawyer.fetchQuestions');
                Route::delete('/delete','FreeLawyerController@delete')->name('free_lawyer.delete');
                Route::get('/add-reply/{id}','FreeLawyerController@addReply')->name('free_lawyer.addReply');
                Route::post('/store-reply','FreeLawyerController@storeReply')->name('free_lawyer.storeReply');
                Route::delete('/delete-reply','FreeLawyerController@deleteReply')->name('free_lawyer.deleteReply');
            });

        });

        ########### Message Routes ############
        Route::group(['prefix' => 'messages'], function() {
            Route::get('/inbox/{tpe}/{id}','MessageController@index')->name('messages');
            Route::get('/fetch-messages/{type}/{id}','MessageController@fetchMessages')->name('messages.fetchMessages');
            Route::get('/send-message/{type?}/{id}','MessageController@sendMessageForm')->name('messages.sendMessageForm');
            Route::post('/send-message','MessageController@sendMessage')->name('messages.sendMessage');
        });

        ########### Settings Routes ############
        Route::group(['prefix' => 'settings'], function() {
            Route::get('/','SettingsController@index')->name('settings');
            Route::get('/fetch-settings','SettingsController@fetchSettings')->name('settings.fetchSettings');
            Route::post('/store-setting','SettingsController@updateSetting')->name('settings.updateSetting');
            Route::post('/updateCase', 'SettingsController@updateCaseCount')->name('settings.updateCase');
        });


    });

});


Route::group(['prefix'=>'payment', 'middleware'=>'auth'], function () {

    Route::get('/index/{params}', 'PaymentProviderController@index');
    Route::post('/pay/{id}', 'PaymentProviderController@pay')->name('payment.pay');
    Route::post('/verify','PaymentProviderController@verify')->name('payment.verify');
    Route::get('/response/{id}','PaymentProviderController@paymentResponse')->name('payment.response');
    Route::get('/show-payments', 'PaymentProviderController@showPayment')->name('showPaymentsToAdmin');
    Route::get('/user-payments/{id}', 'PaymentProviderController@perUserPayments')->name('listPaymentsPerUser');
    Route::get('/list-payments', 'PaymentProviderController@listPayments')->name('showPaymentsToLawyer');
    Route::post('/get-page', 'PaymentProviderController@getPage');
});
Route::group([], function () {
    Route::post('/payment/payAPI', 'PaymentProviderController@toPayPageAPI');
});
