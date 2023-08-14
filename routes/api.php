<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\MerchantTypeController;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\DeviceModelController;
use App\Http\Controllers\DeviceProfileController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\TerminalGroupController;
use App\Http\Controllers\TerminalController;
use App\Http\Controllers\DownloadTaskController;
use App\Http\Controllers\DeleteTaskController;
//use App\Http\Controllers\HeartBeatController;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('HeaderAccess')->group(function($router){
   
    $router->group(['prefix' => 'v1'], function () use ($router) {
        /*buat tes  aja  */
        $router->post('/file/add',[FileController::class, 'store']);
        $router->get('/file/get',[FileController::class, 'show']);
        $router->get('/file/export',[FileController::class, 'export']);
        $router->get('/file/download/{file}',[FileController::class,'download']);
        $router->get('/file/status-export/{file}',[FileController::class,'statusExport']);
        /** end tes */
    
        /* router country */
        $router->get('/country/list', [CountryController::class,'list']);
        $router->get('/country/get', [CountryController::class,'show']);
        $router->post('/country/add', [CountryController::class,'create']);
        $router->post('/country/update', [CountryController::class,'update']);
        $router->post('/country/delete', [CountryController::class,'delete']);
        /* router state */
        $router->get('/state/list', [StateController::class,'list']);
        $router->get('/state/get', [StateController::class,'show']);
        $router->post('/state/add', [StateController::class,'create']);
        $router->post('/state/update', [StateController::class,'update']);
        $router->post('/state/delete', [StateController::class,'delete']);
    
        /* router city */
        $router->get('/city/list', [CityController::class,'list']);
        $router->get('/city/get', [CityController::class,'show']);
        $router->post('/city/add', [CityController::class,'create']);
        $router->post('/city/update', [CityController::class,'update']);
        $router->post('/city/delete', [CityController::class,'delete']);
    
        /* router district */
        $router->get('/district/list', [DistrictController::class,'list']);
        $router->get('/district/get', [DistrictController::class,'show']);
        $router->post('/district/add', [DistrictController::class,'create']);
        $router->post('/district/update', [DistrictController::class,'update']);
        $router->post('/district/delete', [DistrictController::class,'delete']);
    
        /* router merchat_type */
        $router->get('/merchantType/list', [MerchantTypeController::class,'list']);
        $router->get('/merchantType/get', [MerchantTypeController::class,'show']);
        $router->post('/merchantType/add', [MerchantTypeController::class,'create']);
        $router->post('/merchantType/update', [MerchantTypeController::class,'update']);
        $router->post('/merchantType/delete', [MerchantTypeController::class,'delete']);
    
        /* router merchat */
        $router->get('/merchant/list', [MerchantController::class,'list']);
        $router->get('/merchant/get', [MerchantController::class,'show']);
        $router->post('/merchant/add', [MerchantController::class,'create']);
        $router->post('/merchant/update', [MerchantController::class,'update']);
        $router->post('/merchant/delete', [MerchantController::class,'delete']);
    
        /* router device model */
        $router->get('/deviceModel/list', [DeviceModelController::class,'list']);
        $router->get('/deviceModel/get', [DeviceModelController::class,'show']);
        $router->post('/deviceModel/add', [DeviceModelController::class,'create']);
        $router->post('/deviceModel/update', [DeviceModelController::class,'update']);
        $router->post('/deviceModel/delete', [DeviceModelController::class,'delete']);
    
        /* router device profile */
        $router->get('/profile/list', [DeviceProfileController::class,'list']);
        $router->get('/profile/get', [DeviceProfileController::class,'show']);
        $router->post('/profile/add', [DeviceProfileController::class,'create']);
        $router->post('/profile/update', [DeviceProfileController::class,'update']);
        $router->post('/profile/delete', [DeviceProfileController::class,'delete']);
    
    
        /* router application*/ 
        $router->get('/application/list', [ApplicationController::class,'list']);
        $router->get('/application/get', [ApplicationController::class,'show']);
        $router->post('/application/add', [ApplicationController::class,'create']);
        $router->post('/application/update', [ApplicationController::class,'update']);
        $router->post('/application/delete', [ApplicationController::class,'delete']);
        $router->get('/application/getApk', [ApplicationController::class,'getApk']);
    
    
        /* router terminal group*/ 
        $router->get('/terminalGroup/listTerminal', [TerminalGroupController::class,'listTerminal']);
        $router->get('/terminalGroup/list', [TerminalGroupController::class,'list']);
        $router->get('/terminalGroup/get', [TerminalGroupController::class,'show']);
        $router->post('/terminalGroup/add', [TerminalGroupController::class,'create']);
        $router->post('/terminalGroup/update', [TerminalGroupController::class,'update']);
        $router->post('/terminalGroup/delete', [TerminalGroupController::class,'delete']);
        $router->post('/terminalGroup/addTerminals', [TerminalGroupController::class,'addTerminals']);
        $router->post('/terminalGroup/deleteTerminals', [TerminalGroupController::class,'deleteTerminals']);
    
    
        /* router terminal*/ 
        $router->get('/terminal/list', [TerminalController::class,'list']);
        $router->get('/terminal/get', [TerminalController::class,'show']);
        $router->post('/terminal/add', [TerminalController::class,'create']);
        $router->post('/terminal/update', [TerminalController::class,'update']);
        $router->post('/terminal/delete', [TerminalController::class,'delete']);
        $router->post('/terminal/restart', [TerminalController::class,'restart']);
        $router->post('/terminal/lockUnlock', [TerminalController::class,'lockUnlock']);
    
        /* router download task*/ 
        $router->get('/downloadTask/list', [DownloadTaskController::class,'list']);
        $router->get('/downloadTask/listTerminal', [DownloadTaskController::class,'listTerminal']);
        $router->get('/downloadTask/listTerminalGroup', [DownloadTaskController::class,'listGroup']);
        $router->get('/downloadTask/get',  [DownloadTaskController::class,'show']);
        $router->post('/downloadTask/add',  [DownloadTaskController::class,'create']);
        $router->post('/downloadTask/update',  [DownloadTaskController::class,'update']);
        $router->post('/downloadTask/delete',  [DownloadTaskController::class,'delete']);
        $router->post('/downloadTask/cancel',  [DownloadTaskController::class,'cancel']);
        $router->post('/downloadTask/history',  [DownloadTaskController::class,'history']);
        $router->post('/downloadTask/terminalHistory',  [DownloadTaskController::class,'terminalHistory']);
    
        /* router delete task*/ 
        $router->get('/deleteTask/list', [DeleteTaskController::class,'list']);
        $router->get('/deleteTask/get', [DeleteTaskController::class,'show']);
        $router->post('/deleteTask/add', [DeleteTaskController::class,'create']);
        $router->post('/deleteTask/update', [DeleteTaskController::class,'update']);
        $router->post('/deleteTask/delete', [DeleteTaskController::class,'delete']);        
        $router->post('/deleteTask/listTerminalGroup', [DeleteTaskController::class,'listTerminalGroup']);
        $router->post('/deleteTask/listTerminal', [DeleteTaskController::class,'listTerminal']);
        $router->post('/deleteTask/history', [DeleteTaskController::class,'history']);
        $router->post('/deleteTask/terminalHistory', [DeleteTaskController::class,'terminalHistory']);
        $router->post('/deleteTask/cancel', [DeleteTaskController::class,'cancel']);

        /* router  Diagnostic */
        $router->get('/diagnostic/lastHeartbeat', [DiagnosticController::class,'lastHeartbeat']);
        $router->get('/diagnostic/lastDiagnostic', [DiagnosticController::class,'lastDiagnostic']);

        
        
        
    
    });

});