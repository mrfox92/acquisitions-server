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
Route::get('/user', 'UserController@index');
Route::put('/user/edit/{user}', 'UserController@update');
Route::post('/user/checktoken', 'UserController@checkToken');
Route::get('/user/search', 'UserController@search');
Route::post('/user/upload/{user}', 'UserController@upload')->middleware(ApiAuthMiddleware::class);
Route::put('/user/role/{user}', 'UserController@updateRoleUser');


//  construimos la ruta para acceder a nuestras imagenes
Route::get('/user/image/{imageName}', 'UserController@getImage');

Route::get('/user/detail/{id}', 'UserController@detail');
Route::delete('/user/delete/{id}', 'UserController@destroy');

//  rutas Dispatcher
Route::get('/dispatcher/material/list', 'DispatcherController@index');
Route::get('/dispatcher/material/orders', 'DispatcherController@getOrders');
Route::get('/dispatcher/material/deptos', 'DispatcherController@deptosList');
Route::get('/dispatcher/material/offices/{id}', 'DispatcherController@officesList');
Route::get('/dispatcher/material/detail/{id}', 'DispatcherController@show');
Route::get('/dispatcher/material/order/{id}', 'DispatcherController@order');
Route::get('/dispatcher/material/search/{search}', 'DispatcherController@getSearchResults');
Route::put('/dispatcher/material/order/{id}', 'DispatcherController@processingOrder');
Route::delete('/dispatcher/material/delete/{id}', 'DispatcherController@deleteItem');
Route::post('/dispatcher/order', 'DispatcherController@store');
// Route::Resource('/dispatcher', 'DispatcherController');

//  rutas Materiales
Route::Resource('/materials', 'MaterialController');
Route::get('/materials/material/list', 'MaterialController@getMaterials');
Route::get('/materials/search/material', 'MaterialController@search');
//  ruta para acceder a nuestras imagenes de materiales
Route::post('/materials/upload/{material}', 'MaterialController@upload')->middleware(ApiAuthMiddleware::class);
Route::get('/materials/image/{imageName}', 'MaterialController@getImage');

//  rutas Proveedores
Route::Resource('/provider', 'ProviderController');
//  busqueda proveedor
Route::get('/provider/search/provider', 'ProviderController@search');
//  acquisiciones - proveedores
Route::get('/provider/invoices/{id}', 'AcquisitionController@getInvoicesProvider');

//  rutas Departamentos municipio
Route::Resource('/department', 'DepartmentController');
Route::get('/department/search/department', 'DepartmentController@search');

//  rutas oficinas municipio
Route::Resource('/offices', 'OfficeController');
Route::get('/offices/departments/list', 'OfficeController@getDeptos');
Route::get('/offices/search/office', 'OfficeController@search');

//  rutas facturas
Route::resource('/invoices', 'InvoiceController');
Route::get('/invoices/providers/list', 'InvoiceController@getProviders');
Route::get('/invoices/provider/{provider}', 'InvoiceController@getInvoicesProvider');
Route::get('/invoices/search/invoice', 'InvoiceController@search');


//  rutas material invoice
Route::Resource('/materialinvoice', 'MaterialInvoiceController');


//  rutas adquisiciones
Route::Resource('/acquisition', 'AcquisitionController');
Route::get('/acquisition/order/detail/{id}', 'AcquisitionController@getOrderDetail');
Route::put('/acquisition/order/{id}', 'AcquisitionController@updateStatusOrder');
Route::get('/acquisition/materials/outofstock', 'AcquisitionController@getOutOfStock');


//  rutas ordenes dspacho
Route::Resource('/order', 'OrderController');
Route::get('/order/search/order', 'OrderController@search');

//  rutas material order
Route::Resource('/materialorder', 'MaterialOrderController');
Route::get('/materialorder/detail/{order}', 'MaterialOrderController@order');
