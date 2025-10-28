<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect(route('home'));
});

Auth::routes();

Route::get('/registrarUsuario', [App\Http\Controllers\UserController::class, 'index'])->name('reg_usuario');
Route::post('/registrarUsuario', [App\Http\Controllers\UserController::class, 'register'])->name('save_usuario');
Route::get('/actualizarUsuario', [App\Http\Controllers\UserController::class, 'update'])->name('update_usuario');
Route::get('/getUsuarioInfo', [App\Http\Controllers\UserController::class, 'getUsuarioInfo'])->name('getUsuarioInfo');
Route::post('/actualizaUsuario', [App\Http\Controllers\UserController::class, 'upgrade'])->name('upgrade_usuario');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'indexJson'])->name('home');

Route::get('/getClients', [App\Http\Controllers\HomeController::class, 'getClients'])->name('getClients');
Route::get('/getProducts', [App\Http\Controllers\HomeController::class, 'getProducts'])->name('getProducts');

Route::get('/location-settings', [App\Http\Controllers\HomeController::class, 'indexLocation'])->name('indexLocation');
Route::get('/location-settings/getLocations', [App\Http\Controllers\HomeController::class, 'getLocations'])->name('getLocations');
Route::get('/location-settings/register', [App\Http\Controllers\HomeController::class, 'createLocation'])->name('createLocation');
Route::post('/location-settings/register', [App\Http\Controllers\HomeController::class, 'storeLocation'])->name('location.store');
Route::get('/location-settings/edit/{id}', [App\Http\Controllers\HomeController::class, 'editLocation'])->name('editLocation');
Route::post('/location-settings/edit/{id}', [App\Http\Controllers\HomeController::class, 'updateLocation'])->name('location.update');
Route::post('/location-settings/delete/{id}', [App\Http\Controllers\HomeController::class, 'deleteLocation'])->name('deleteLocation');
// Route::post('/location-setting/register', [App\Http\Controllers\HomeController::class, 'indexLocation'])->name('indexLocation');

Route::get('/location', [App\Http\Controllers\LocationController::class, 'index'])->name('location');
Route::get('/location/getClients', [App\Http\Controllers\LocationController::class, 'getClients'])->name('getClientLocation');
Route::get('/location/getProducts', [App\Http\Controllers\LocationController::class, 'getProducts'])->name('getClientProdsLocation');

// login ADAL
/*
Route::get('login', [App\Http\Controllers\LoginController::class, 'login'])->name('login');

Route::get('login/microsoft/callback', [App\Http\Controllers\LoginController::class, 'handleCallback']);

Route::post('logout-adal', function(Request $request){
     Auth::logout();
     Session::flush();
     \Cookie::forget('XSRF-TOKEN');
     return redirect(route('logout-microsoft'));
})->name('logout');

Route::get('logout', [App\Http\Controllers\LoginController::class, 'logout'])->name('logout-microsoft');
*/
URL::forceScheme('https');
