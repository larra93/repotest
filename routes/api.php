<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController; 
use App\Http\Controllers\UserController; 
use App\Http\Controllers\RoleController; 
use App\Http\Controllers\CompanyController; 
use App\Http\Controllers\ContractController; 
use App\Http\Controllers\DailysController; 
use App\Http\Controllers\FieldController; 
use App\Http\Controllers\DailyStructureController;
use App\Http\Controllers\ValueController;
use App\Http\Controllers\ValuesRowController;





/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout']);

Route::get('/user', [LoginController::class, 'getUser']);

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', function(Request $request) {
        return [
            'user' => $request->user(),
            'currentToken' => $request->bearerToken()
        ];
    });
    Route::post('user/logout', [UserController::class, 'logout']);
});

Route::post('user/register', [UserController::class, 'store']);
Route::post('user/login', [UserController::class, 'auth']);

//corregir el sanctum en front end y tirar esto a rutas protegidas aca en backend
Route::resource('users', UserController::class)->names('users');
Route::resource('roles', RoleController::class)->names('roles');
Route::get('/getUsers', [UserController::class, 'getUsers']);

Route::resource('/companies', CompanyController::class);

Route::resource('/contracts', ContractController::class);
Route::get('/contracts/{id}/dailySheet', [ContractController::class, 'getStructureVigentes']);
Route::get('/Dailys/{id}/dailyStructure', [ContractController::class, 'getEstructureDaily']);
Route::get('/Dailys/{id}/dailyStructurev2', [ContractController::class, 'getEstructureDailyv2']);


Route::post('/fields/create/{id}', [FieldController::class, 'store']);
Route::post('/fields/update/{id}', [FieldController::class, 'update']);

Route::resource('/Dailys', DailysController::class);

Route::post('/dailyStructure/create/{id}/{iguales}', [DailyStructureController::class, 'store']);
Route::put('/updateValues', [ValuesRowController::class, 'updateValues']);
Route::delete('/values', [ValuesRowController::class, 'deleteValues']);

Route::post('/valuesRow',  [ValuesRowController::class, 'store']);
Route::post('/copyValuesRow', [ValuesRowController::class, 'copyValuesRow']);
