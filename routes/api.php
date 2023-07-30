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
use App\Http\Controllers\DownloadTaskApplicationLinkController;
use App\Http\Controllers\DownloadTaskLogController;
use App\Http\Controllers\DownloadTaskTerminalGroupLinkController;
use App\Http\Controllers\DownloadTaskTerminalLinkController;
use App\Http\Controllers\HeartBeatController;
use App\Http\Controllers\TerminalGroupLinkController;
use App\Http\Controllers\DeleteTaskController;
use App\Http\Controllers\DeleteTaskAppController;
use App\Http\Controllers\DeleteTaskLogController;
use App\Http\Controllers\DeleteTaskTerminalGroupLinkController;
use App\Http\Controllers\DeleteTaskTerminalLinkController;
use App\Http\Controllers\DiagnosticInfoController;




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
        $router->post('/application/getFileMinio', [ApplicationController::class,'getMinio']);
    
    
        /* router terminal group*/ 
        $router->get('/terminalGroup/listTerminal', [TerminalGroupController::class,'list']);
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
    
        /* router download task  application link*/ 
        $router->get('/downloadtaskapplicationlink/list', [DownloadTaskApplicationLinkController::class,'list']);
        $router->get('/downloadtaskapplicationlink/get', [DownloadTaskApplicationLinkController::class,'show']);
        $router->post('/downloadtaskapplicationlink/add', [DownloadTaskApplicationLinkController::class,'create']);
        $router->post('/downloadtaskapplicationlink/update', [DownloadTaskApplicationLinkController::class,'update']);
      
        /* router downloadtasklog*/ 
        $router->get('/downloadtasklog/list', [DownloadTaskLogController::class,'list']);
        $router->get('/downloadtasklog/get', [DownloadTaskLogController::class,'show']);
        $router->post('/downloadtasklog/add', [DownloadTaskLogController::class,'create']);
        $router->post('/downloadtasklog/update', [DownloadTaskLogController::class,'update']);
    
        /* router download task  terminal group link*/ 
        $router->get('/downloadtaskterminalgrouplink/list', [DownloadTaskTerminalGroupLinkController::class,'list']);
        $router->get('/downloadtaskterminalgrouplink/get', [DownloadTaskTerminalGroupLinkController::class,'show']);
        $router->post('/downloadtaskterminalgrouplink/add', [DownloadTaskTerminalGroupLinkController::class,'create']);
        $router->post('/downloadtaskterminalgrouplink/update', [DownloadTaskTerminalGroupLinkController::class,'update']);
    
        /* router download task  terminal  link*/ 
        $router->get('/downloadtaskterminallink/list', [DownloadTaskTerminalLinkController::class,'list']);
        $router->get('/downloadtaskterminallink/get', [DownloadTaskTerminalLinkController::class,'show']);
        $router->post('/downloadtaskterminallink/add', [DownloadTaskTerminalLinkController::class,'create']);
        $router->post('/downloadtaskterminallink/update', [DownloadTaskTerminalLinkController::class,'update']);
    
        /* router hear beat*/ 
        $router->get('/heartbeat/list', [HeartBeatController::class,'list']);
        $router->get('/heartbeat/get', [HeartBeatController::class,'show']);
        $router->post('/heartbeat/add', [HeartBeatController::class,'create']);
        $router->post('/heartbeat/update', [HeartBeatController::class,'update']);
    
    
        /*router terminal group link */ 
        $router->get('/terminalgrouplink/list', [TerminalGroupLinkController::class,'list']);
        $router->get('/terminalgrouplink/get', [TerminalGroupLinkController::class,'show']);
        $router->post('/terminalgrouplink/add', [TerminalGroupLinkController::class,'create']);
        $router->post('/terminalgrouplink/update', [TerminalGroupLinkController::class,'update']);
    
        
        /* router delete task*/ 
        $router->get('/deletetask/list', [DeleteTaskController::class,'list']);
        $router->get('/deletetask/get', [DeleteTaskController::class,'show']);
        $router->post('/deletetask/add', [DeleteTaskController::class,'create']);
        $router->post('/deletetask/update', [DeleteTaskController::class,'update']);
    
        /* router delete task app*/ 
        $router->get('/deletetaskapp/list', [DeleteTaskAppController::class,'list']);
        $router->get('/deletetaskapp/get', [DeleteTaskAppController::class,'show']);
        $router->post('/deletetaskapp/add', [DeleteTaskAppController::class,'create']);
        $router->post('/deletetaskapp/update', [DeleteTaskAppController::class,'update']);
    
        /* router delete tas klog*/ 
        $router->get('/deletetasklog/list', [DeleteTaskLogController::class,'list']);
        $router->get('/deletetasklog/get', [DeleteTaskLogController::class,'show']);
        $router->post('/deletetasklog/add', [DeleteTaskLogController::class,'create']);
        $router->post('/deletetasklog/update', [DeleteTaskLogController::class,'update']);
    
         /* router delete task  terminal group link*/ 
        $router->get('/deletetaskterminalgrouplink/list', [DeleteTaskTerminalGroupLinkController::class,'list']);
        $router->get('/deletetaskterminalgrouplink/get', [DeleteTaskTerminalGroupLinkController::class,'show']);
        $router->post('/deletetaskterminalgrouplink/add', [DeleteTaskTerminalGroupLinkController::class,'create']);
        $router->post('/deletetaskterminalgrouplink/update', [DeleteTaskTerminalGroupLinkController::class,'update']);
        
        /* router delete task  terminal  link*/ 
        $router->get('/deletetaskterminallink/list', [DeleteTaskTerminalLinkController::class,'list']);
        $router->get('/deletetaskterminallink/get', [DeleteTaskTerminalLinkController::class,'show']);
        $router->post('/deletetaskterminallink/add', [DeleteTaskTerminalLinkController::class,'create']);
        $router->post('/deletetaskterminallink/update', [DeleteTaskTerminalLinkController::class,'update']);
    
         /* router diagnostic info*/ 
         $router->get('/diagnosticinfo/list', [DiagnosticInfoController::class,'list']);
         $router->get('/diagnosticinfo/get', [DiagnosticInfoController::class,'show']);
         $router->post('/diagnosticinfo/add', [DiagnosticInfoController::class,'create']);
         $router->post('/diagnosticinfo/update', [DiagnosticInfoController::class,'update']);
     
     
     
    
    });

});