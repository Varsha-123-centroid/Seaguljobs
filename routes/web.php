<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| common Routes
|--------------------------------------------------------------------------
|
| Here is where you can register common routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "common" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/
// Route::get('/', function () {
//     return view('dashboard');
// });


// /////////////////////////////////////Frondend//////////////////////////////////////////////////////////////////////

Route::get('/', [App\Http\Controllers\CommonUserController::class, 'home'])->name('common.home');


Route::get('/qr-code/{id}', [App\Http\Controllers\CommonUserController::class, 'scan_qrcode'])->name('common.scan_qrcode');


// /////////////////////////////////////Admin//////////////////////////////////////////////////////////////////////
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('role:Dashboard,p_view');


Route::post('/uploadimage-tinymce', [App\Http\Controllers\Admin\TempleController::class, 'uploadImageTinyMCE'])->name('uploadimage_tinymce');
// deity


//slider

Route::get('/slider', [App\Http\Controllers\Admin\SliderController::class, 'index'])->name('slider')->middleware('role:Slider,p_view');
Route::get('/add-slider', [App\Http\Controllers\Admin\SliderController::class, 'create'])->name('slider_add_form')->middleware('role:Slider,p_add');
Route::post('/insert-slider', [App\Http\Controllers\Admin\SliderController::class, 'store'])->name('insert_slider');
Route::get('/delete-slider/{id}', [App\Http\Controllers\Admin\SliderController::class, 'destroy'])->name('slider_delete')->middleware('role:Slider,p_delete');

// get deity by temple id

Route::get('/get-deity/{id}', [App\Http\Controllers\Admin\DeityController::class, 'get_deity'])->name('get_deity');

// get district by state id

Route::get('/get-district/{id}', [App\Http\Controllers\Admin\TempleController::class, 'get_district'])->name('get_district');

// users

Route::get('/user', [App\Http\Controllers\Admin\TempleusersController::class, 'index'])->name('user')->middleware('role:User,p_view');
Route::get('/add-user', [App\Http\Controllers\Admin\TempleusersController::class, 'create'])->name('user_add_form')->middleware('role:User,p_add');
Route::post('/insert-user', [App\Http\Controllers\Admin\TempleusersController::class, 'store'])->name('insert_user');
Route::get('/edit-user/{id}', [App\Http\Controllers\Admin\TempleusersController::class, 'edit'])->name('user_edit_form')->middleware('role:User,p_edit');
Route::post('/update-user/{id}', [App\Http\Controllers\Admin\TempleusersController::class, 'update'])->name('update_user');
Route::get('/delete-user/{id}', [App\Http\Controllers\Admin\TempleusersController::class, 'destroy'])->name('user_delete')->middleware('role:User,p_delete');



/// pages /////////////

Route::get('/jobskils', [App\Http\Controllers\Admin\TempleusersController::class, 'jobskils'])->name('jobskils');
Route::get('/jobskils_add', [App\Http\Controllers\Admin\TempleusersController::class, 'jobskils_add'])->name('jobskils_add');
Route::get('/jobskils_edit', [App\Http\Controllers\Admin\TempleusersController::class, 'jobskils_edit'])->name('jobskils_edit');

Route::get('/departments', [App\Http\Controllers\Admin\TempleusersController::class, 'departments'])->name('departments');
Route::get('/departments_add', [App\Http\Controllers\Admin\TempleusersController::class, 'departments_add'])->name('departments_add');
Route::get('/departments_edit', [App\Http\Controllers\Admin\TempleusersController::class, 'departments_edit'])->name('departments_edit');

Route::get('/designation', [App\Http\Controllers\Admin\TempleusersController::class, 'designation'])->name('designation');
Route::get('/designation_add', [App\Http\Controllers\Admin\TempleusersController::class, 'designation_add'])->name('designation_add');
Route::get('/designation_edit', [App\Http\Controllers\Admin\TempleusersController::class, 'designation_edit'])->name('designation_edit');

















// role and permission

Route::get('/role', [App\Http\Controllers\Admin\RolePermissionsController::class, 'index'])->name('role')->middleware('role:Role,p_view');
Route::get('/add-role', [App\Http\Controllers\Admin\RolePermissionsController::class, 'create'])->name('role_add_form')->middleware('role:Role,p_add');
Route::post('/insert-role', [App\Http\Controllers\Admin\RolePermissionsController::class, 'store'])->name('insert_role');
Route::get('/edit-role/{id}', [App\Http\Controllers\Admin\RolePermissionsController::class, 'edit'])->name('role_edit_form')->middleware('role:Role,p_edit');
Route::post('/update-role/{id}', [App\Http\Controllers\Admin\RolePermissionsController::class, 'update'])->name('update_role');
Route::get('/delete-role/{id}', [App\Http\Controllers\Admin\RolePermissionsController::class, 'destroy'])->name('role_delete')->middleware('role:Role,p_delete');
Route::view('/unauthorized', 'permission_declined')->name('unauthorized');
//pooja bookings

Route::get('/bookings', [App\Http\Controllers\Admin\BookingsController::class, 'index'])->name('bookings')->middleware('role:PoojaBookings,p_view');
Route::get('/get-bookings', [App\Http\Controllers\Admin\BookingsController::class, 'get_bookings'])->name('getBookings');
Route::get('/bookings-view/{id}', [App\Http\Controllers\Admin\BookingsController::class, 'bookings_view'])->name('bookings_view');

//darshan bookings

Route::get('/darshan-bookings', [App\Http\Controllers\Admin\BookingsController::class, 'darshan_bookings'])->name('admin.darshan_bookings')->middleware('role:DarshanBookings,p_view');
Route::get('/darshan-booking/{id}', [App\Http\Controllers\Admin\BookingsController::class, 'darshan_booking'])->name('admin.darshan_booking');
// exel

Route::get('/export-exel/{fromDate}/{toDate}', [App\Http\Controllers\Admin\BookingsController::class, 'export_exel'])->name('export_exel');
Route::post('/export-dharsan', [App\Http\Controllers\Admin\BookingsController::class, 'export_dharsan'])->name('export_dharsan');


// deity

Route::get('/manage-festivals', [App\Http\Controllers\Admin\FestivalController::class, 'index'])->name('festivals')->middleware('role:Festival,p_view');
Route::get('/add-festivals', [App\Http\Controllers\Admin\FestivalController::class, 'create'])->name('festivals_add_form')->middleware('role:Festival,p_add');
Route::post('/insert-festivals', [App\Http\Controllers\Admin\FestivalController::class, 'store'])->name('insert_festivals');
Route::get('/edit-festivals/{id}', [App\Http\Controllers\Admin\FestivalController::class, 'edit'])->name('festivals_edit_form')->middleware('role:Festival,p_edit');
Route::post('/update-festivals/{id}', [App\Http\Controllers\Admin\FestivalController::class, 'update'])->name('update_festivals');

// subdeity

Route::get('/subdeity', [App\Http\Controllers\Admin\SubDeityController::class, 'index'])->name('subdeity')->middleware('role:Deity,p_view');
Route::get('/add-subdeity', [App\Http\Controllers\Admin\SubDeityController::class, 'create'])->name('subdeity_add_form')->middleware('role:Deity,p_add');
Route::post('/insert-subdeity', [App\Http\Controllers\Admin\SubDeityController::class, 'store'])->name('insert_subdeity');
Route::get('/edit-subdeity/{id}', [App\Http\Controllers\Admin\SubDeityController::class, 'edit'])->name('subdeity_edit_form')->middleware('role:Deity,p_edit');
Route::post('/update-subdeity/{id}', [App\Http\Controllers\Admin\SubDeityController::class, 'update'])->name('update_subdeity');
Route::get('/delete-subdeity/{id}', [App\Http\Controllers\Admin\SubDeityController::class, 'destroy'])->name('subdeity_delete')->middleware('role:Deity,p_delete');

//Ambotti Thamburan

Route::get('/delete-photo', [App\Http\Controllers\Admin\AmbottiThampuranController::class, 'index'])->name('ambotti_thampuran')->middleware('role:Thambran,p_view');
Route::post('/update-Ambotti-Thamburan/{id}', [App\Http\Controllers\Admin\AmbottiThampuranController::class, 'update'])->name('update_ambotti_thampuran');
Route::post('/insert-Ambotti-Thamburan', [App\Http\Controllers\Admin\AmbottiThampuranController::class, 'InsertPhotos'])->name('InsertPhotos');
Route::post('/delete-Thamburan-photo', [App\Http\Controllers\Admin\AmbottiThampuranController::class, 'deletePhoto'])->name('ambotti_thampuran.delete');

// enquiry

Route::get('/enquiry', [App\Http\Controllers\Admin\EnquiryController::class, 'index'])->name('enquiry')->middleware('role:Enquiry,p_view');
Route::get('/enquiry-detail/{id}', [App\Http\Controllers\Admin\EnquiryController::class, 'view'])->name('enquiry_detail');
Route::get('/delete-enquiry/{id}', [App\Http\Controllers\Admin\EnquiryController::class, 'destroy'])->name('delete_enquiry')->middleware('role:Enquiry,p_delete');


// cahnge password

Route::get('/change-password', [App\Http\Controllers\HomeController::class, 'change_password'])->name('change_password');
Route::post('/update-password', [App\Http\Controllers\HomeController::class, 'update_password'])->name('update_password');

//Clear Cache facade value:
Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    Artisan::call('optimize');
    Artisan::call('route:cache');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('config:cache');
    return '<h1>Cache facade value cleared</h1>';
});

//Reoptimized class loader:
Route::get('/optimize', function() {
    $exitCode = Artisan::call('optimize');
    return '<h1>Reoptimized class loader</h1>';
});

//Route cache:
Route::get('/route-cache', function() {
    $exitCode = Artisan::call('route:cache');
    return '<h1>Routes cached</h1>';
});

//Clear Route cache:
Route::get('/route-clear', function() {
    $exitCode = Artisan::call('route:clear');
    return '<h1>Route cache cleared</h1>';
});

//Clear View cache:
Route::get('/view-clear', function() {
    $exitCode = Artisan::call('view:clear');
    return '<h1>View cache cleared</h1>';
});

//Clear Config cache:
Route::get('/config-cache', function() {
    $exitCode = Artisan::call('config:cache');
    return '<h1>Clear Config cleared</h1>';
});