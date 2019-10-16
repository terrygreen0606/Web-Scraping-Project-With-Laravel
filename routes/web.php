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


Route::get('/', 'pageController@home')->name('pages.home');


/***** Begin Authentication routes *****/
Route::group(['middleware' => ['unAuthenticated']], function () {

    Route::get('/register', 'RegisterController@index')->name('form.register');
    Route::post('/register', 'RegisterController@register')->name('user.register');

    Route::get('/login', 'LoginController@index')->name('form.login');
    Route::post('/login', 'LoginController@login')->name('user.login');

    Route::get('/forgot-password', 'PasswordController@index')->name('form.forgotPassword');
    Route::post('/forgot-password/send-token', 'PasswordController@sendToken')->name('user.forgotPassword');
    Route::get('/forgot-password/check-mail', 'PasswordController@showEmailSent')->name('user.forgotPasswordTokenSent');
    Route::get('/reset-password/{token}', 'PasswordController@resetPasswordForm')->name('form.resetPassword');
    Route::post('/reset-password', 'PasswordController@setNewPassword')->name('user.resetPassword');

});


Route::group(['middleware' => ['authenticated']], function () {

    Route::get('email-unverified', 'EmailVerificationController@emailUnverified')->name('emailUnverified');
    Route::get('verify-email/send-token', 'EmailVerificationController@sendToken')->name('user.verifyEmailSendToken');
    Route::get('verify-email/{token}', 'EmailVerificationController@verify')->name('user.verifyEmail');

    Route::get('/logout', 'LogoutController@index')->name('logout');
    Route::post('/logout', 'LogoutController@index')->name('ajax-logout');

});
/***** End Authentication routes *****/


/***** Begin routes that user must be authenticated *****/
Route::group(['middleware' => ['authenticated', 'emailVerified']], function () {

    /***** Begin profile *****/
    Route::get('/profile', 'ProfileController@index')->name('form.profile');
    Route::post('/profile', 'ProfileController@update')->name('user.profile');
    /***** End profile *****/


    /***** Begin change password *****/
    Route::get('/change-password', 'PasswordController@changePasswordForm')->name('form.changePassword');
    Route::post('/change-password', 'PasswordController@changePassword')->name('user.changePassword');
    /***** End change password *****/


    /***** Begin change the profile avatar *****/
    Route::get('/change-avatar', 'AvatarController@index')->name('form.changeAvatar');
    Route::post('/change-avatar', 'AvatarController@changeAvatar')->name('do.changeAvatar');
    /***** End change the profile avatar *****/


    /***** Begin manage categories *****/
    Route::get('/category', 'CategoryController@index')->name('categoryList');
    Route::post('/add-category', 'CategoryController@addCategory')->name('addCategory');
    Route::post('/update-category', 'CategoryController@updateCategory')->name('updateCategory');
    Route::post('/remove-category', 'CategoryController@deleteCategory')->name('deleteCategory');
    /***** End manage categories *****/


    /***** Begin manage images *****/
    Route::get('/upload-image', 'ImageController@showUploadForm')->name('form.uploadImage');
    Route::post('/upload-image', 'ImageController@uploadImage')->name('do.uploadImage');

    Route::get('/images', 'ImageController@showImageList')->name('form.imageList');
    Route::post('/images', 'ImageController@getImages')->name('do.imageList');
    Route::post('/remove-image', 'ImageController@removeImage')->name('do.removeImage');

    Route::get('/update-image', 'ImageController@showUploadForm')->name('form.updateImage');
    Route::post('/update-image', 'ImageController@uploadImage')->name('do.updateImage');
    /***** End manage images *****/


    /***** Begin search anchors *****/
    Route::get('/search-anchors', 'anchorController@showSearchForm')->name('form.searchAnchors');
    Route::post('/search-anchors', 'anchorController@search')->name('do.searchAnchors');
    Route::post('/search-anchors-plain-text', 'anchorController@processPlainText')->name('do.searchAnchorsPlainText');
    Route::post('/search-anchors-upload-file', 'anchorController@processUploadedFile')->name('do.searchAnchorsUploadFile');
    Route::post('/search-anchors-store-csv-file', 'anchorController@storSCVFile')->name('do.searchAnchorsStoreCSVFile');
    /***** End search anchors *****/


    /***** Begin add clients *****/
    Route::get('/clients', 'ClientController@index')->name('form.clients');
    Route::post('/add-client', 'ClientController@addNewClient')->name('do.addClient');
    Route::post('/remove-client', 'ClientController@removeClient')->name('do.removeClient');
    Route::post('/update-client', 'ClientController@updateClient')->name('do.updateClient');
    /***** End clients *****/

    /***** Begin providers *****/
    Route::get('/providers', 'ProviderController@index')->name('form.providers');
    Route::post('/add-provider', 'ProviderController@addNewProvider')->name('do.addProvider');
    Route::post('/remove-provider', 'ProviderController@removeProvider')->name('do.removeProvider');
    Route::post('/update-provider', 'ProviderController@updateProvider')->name('do.updateProvider');
    /***** End providers *****/

    /****** Begin tier 1 link  ******/
    Route::get('/tier1', 'Tier1Controller@index')->name('form.tier1link');
    Route::post('/add-tier1',  'Tier1Controller@addTier1')->name('do.addTier1');
    Route::post('/remove-tier1', 'Tier1Controller@removeTier1')->name('do.removeTier1');
    Route::post('/update-tier1', 'Tier1Controller@updateTier1')->name('do.updateTier1');
    /****** End tier 1 link  ******/

    /****** Begin tier 2 link  ******/
    Route::get('/tier2', 'Tier2Controller@index')->name('form.tier2link');
    Route::post('/add-tier2', 'Tier2Controller@addTier2')->name('do.addTier2');
    Route::post('/remove-tier2', 'Tier2Controller@removeTier2')->name('do.removeTier2');
    Route::post('/update-tier2', 'Tier2Controller@updateTier2')->name('do.updateTier2');
    /****** End tier 2 link  ******/


    Route::post('/setSidebar', 'userController@updateSidebarStatus');
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard.index');

});
/***** End routes that user must be authenticated *****/




/***** Begin test routes *****/
Route::get('/cron/ohi', 'CronjobController@addLinksToOHI');



Route::get('/test/create-ohi-batch', 'TestController@CreateOHIBatch');
Route::get('/test/add-new-links', 'TestController@OHIBatchAddLinks');
/***** End test routes *****/
