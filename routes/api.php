<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
//  cargamos nuestro middleware personalizado
use App\Http\Middleware\ApiAuthMiddleware;
use App\Http\Middleware\RoleMiddleware;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Rutas de la API

Route::post('/register', 'UserController@register');
Route::post('/login', 'UserController@login');
Route::put('/user/update', 'UserController@update');
Route::post('/user/upload', 'UserController@upload')->middleware(ApiAuthMiddleware::class);

//  construimos la ruta para acceder a nuestras imagenes
Route::get('/user/image/{imageName}', 'UserController@getImage');

Route::get('/user/detail/{id}', 'UserController@detail');
Route::delete('/user/delete/{id}', 'UserController@destroy');

//  rutas Dispatcher
// Route::Resource('/dispatcher', 'DispatcherController');

//  rutas Materiales
Route::Resource('/materials', 'MaterialController');
//  ruta para acceder a nuestras imagenes de materiales
Route::post('/materials/upload', 'MaterialController@upload');
Route::get('/materials/image/{imageName}', 'MaterialController@getImage');

//  rutas Proveedores
Route::Resource('/provider', 'ProviderController');

//  rutas Departamentos municipio
Route::Resource('/department', 'DepartmentController');

//  rutas oficinas municipio
Route::Resource('/offices', 'OfficeController');

//  rutas material invoice
Route::Resource('/materialinvoice', 'MaterialInvoiceController');

//  rutas ordenes dspacho
Route::Resource('/order', 'OrderController');

//  rutas material order
Route::Resource('/materialorder', 'MaterialOrderController');
Route::get('/materialorder/detail/{order}', 'MaterialOrderController@order');
