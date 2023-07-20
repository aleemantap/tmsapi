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
    $router->get('/merchanttype/list', [MerchantTypeController::class,'list']);
    $router->get('/merchanttype/get', [MerchantTypeController::class,'show']);
    $router->post('/merchanttype/add', [MerchantTypeController::class,'create']);
    $router->post('/merchanttype/update', [MerchantTypeController::class,'update']);
    $router->post('/merchanttype/delete', [MerchantTypeController::class,'delete']);

    /* router merchat */
    $router->get('/merchant/list', [MerchantController::class,'list']);
    $router->get('/merchant/get', [MerchantController::class,'show']);
    $router->post('/merchant/add', [MerchantController::class,'create']);
    $router->post('/merchant/update', [MerchantController::class,'update']);
    $router->post('/merchant/delete', [MerchantController::class,'delete']);

    /* router device model */
    $router->get('/devicemodel/list', [DeviceModelController::class,'list']);
    $router->get('/devicemodel/get', [DeviceModelController::class,'show']);
    $router->post('/devicemodel/add', [DeviceModelController::class,'create']);
    $router->post('/devicemodel/update', [DeviceModelController::class,'update']);
    $router->post('/devicemodel/delete', [DeviceModelController::class,'delete']);

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
    $router->get('/terminalgroup/list', 'TerminalGroupController@list');
    $router->get('/terminalgroup/get', 'TerminalGroupController@show');
    $router->post('/terminalgroup/add', 'TerminalGroupController@create');
    $router->post('/terminalgroup/update', 'TerminalGroupController@update');
    $router->post('/terminalgroup/delete', 'TerminalGroupController@delete');


    /* router terminal*/ 
    $router->get('/terminal/list', 'TerminalController@list');
    $router->get('/terminal/get', 'TerminalController@show');
    $router->post('/terminal/add', 'TerminalController@create');
    $router->post('/terminal/update', 'TerminalController@update');
    $router->post('/terminal/restart', 'TerminalController@restart');
    $router->post('/terminal/lockUnlock', 'TerminalController@lockUnlock');

    /* router download task*/ 
    $router->get('/downloadtask/list', 'DownloadTaskController@list');
    $router->get('/downloadtask/get', 'DownloadTaskController@show');
    $router->post('/downloadtask/add', 'DownloadTaskController@create');
    $router->post('/downloadtask/update', 'DownloadTaskController@update');

    /* router download task  application link*/ 
    $router->get('/downloadtaskapplicationlink/list', 'DownloadTaskApplicationLinkController@list');
    $router->get('/downloadtaskapplicationlink/get', 'DownloadTaskApplicationLinkController@show');
    $router->post('/downloadtaskapplicationlink/add', 'DownloadTaskApplicationLinkController@create');
    $router->post('/downloadtaskapplicationlink/update', 'DownloadTaskApplicationLinkController@update');
  
    /* router downloadtasklog*/ 
    $router->get('/downloadtasklog/list', 'DownloadTaskLogController@list');
    $router->get('/downloadtasklog/get', 'DownloadTaskLogController@show');
    $router->post('/downloadtasklog/add', 'DownloadTaskLogController@create');
    $router->post('/downloadtasklog/update', 'DownloadTaskLogController@update');

    /* router download task  terminal group link*/ 
    $router->get('/downloadtaskterminalgrouplink/list', 'DownloadTaskTerminalGroupLinkController@list');
    $router->get('/downloadtaskterminalgrouplink/get', 'DownloadTaskTerminalGroupLinkController@show');
    $router->post('/downloadtaskterminalgrouplink/add', 'DownloadTaskTerminalGroupLinkController@create');
    $router->post('/downloadtaskterminalgrouplink/update', 'DownloadTaskTerminalGroupLinkController@update');

    /* router download task  terminal  link*/ 
    $router->get('/downloadtaskterminallink/list', 'DownloadTaskTerminalLinkController@list');
    $router->get('/downloadtaskterminallink/get', 'DownloadTaskTerminalLinkController@show');
    $router->post('/downloadtaskterminallink/add', 'DownloadTaskTerminalLinkController@create');
    $router->post('/downloadtaskterminallink/update', 'DownloadTaskTerminalLinkController@update');

    /* router hear beat*/ 
    $router->get('/heartbeat/list', 'HeartBeatController@list');
    $router->get('/heartbeat/get', 'HeartBeatController@show');
    $router->post('/heartbeat/add', 'HeartBeatController@create');
    $router->post('/heartbeat/update', 'HeartBeatController@update');


    /*router terminal group link*/ 
    $router->get('/terminalgrouplink/list', 'TerminalGroupLinkController@list');
    $router->get('/terminalgrouplink/get', 'TerminalGroupLinkController@show');
    $router->post('/terminalgrouplink/add', 'TerminalGroupLinkController@create');
    $router->post('/terminalgrouplink/update', 'TerminalGroupLinkController@update');

    
    /* router delete task*/ 
    $router->get('/deletetask/list', 'DeleteTaskController@list');
    $router->get('/deletetask/get', 'DeleteTaskController@show');
    $router->post('/deletetask/add', 'DeleteTaskController@create');
    $router->post('/deletetask/update', 'DeleteTaskController@update');

    /* router delete task app*/ 
    $router->get('/deletetaskapp/list', 'DeleteTaskAppController@list');
    $router->get('/deletetaskapp/get', 'DeleteTaskAppController@show');
    $router->post('/deletetaskapp/add', 'DeleteTaskAppController@create');
    $router->post('/deletetaskapp/update', 'DeleteTaskAppController@update');

    /* router delete tas klog*/ 
    $router->get('/deletetasklog/list', 'DeleteTaskLogController@list');
    $router->get('/deletetasklog/get', 'DeleteTaskLogController@show');
    $router->post('/deletetasklog/add', 'DeleteTaskLogController@create');
    $router->post('/deletetasklog/update', 'DeleteTaskLogController@update');

     /* router delete task  terminal group link*/ 
    $router->get('/deletetaskterminalgrouplink/list', 'DeleteTaskTerminalGroupLinkController@list');
    $router->get('/deletetaskterminalgrouplink/get', 'DeleteTaskTerminalGroupLinkController@show');
    $router->post('/deletetaskterminalgrouplink/add', 'DeleteTaskTerminalGroupLinkController@create');
    $router->post('/deletetaskterminalgrouplink/update', 'DeleteTaskTerminalGroupLinkController@update');
    
    /* router delete task  terminal  link*/ 
    $router->get('/deletetaskterminallink/list', 'DeleteTaskTerminalLinkController@list');
    $router->get('/deletetaskterminallink/get', 'DeleteTaskTerminalLinkController@show');
    $router->post('/deletetaskterminallink/add', 'DeleteTaskTerminalLinkController@create');
    $router->post('/deletetaskterminallink/update', 'DeleteTaskTerminalLinkController@update');

     /* router diagnostic info*/ 
     $router->get('/diagnosticinfo/list', 'DiagnosticInfoController@list');
     $router->get('/diagnosticinfo/get', 'DiagnosticInfoController@show');
     $router->post('/diagnosticinfo/add', 'DiagnosticInfoController@create');
     $router->post('/diagnosticinfo/update', 'DiagnosticInfoController@update');
 
 
 

});
