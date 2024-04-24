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
use App\Http\Controllers\DiagnosticController;
use App\Http\Controllers\TenantController;
use App\http\Controllers\AidController;
use App\http\Controllers\CapkController;
use App\http\Controllers\PublicKeyController;
use App\http\Controllers\TleSettingController;
use App\http\Controllers\TerminalExtController;
use App\http\Controllers\CardController;
use App\http\Controllers\AcquirerController;
use App\http\Controllers\IssuerController;
use App\http\Controllers\ResponseCodeController;
use App\http\Controllers\TemplateController;




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
        //$router->get('/terminal/getBySn', [TerminalController::class,'getBySn']);
    
        /* router download task*/ 
        $router->get('/downloadTask/list', [DownloadTaskController::class,'list']);
        $router->get('/downloadTask/listTerminal', [DownloadTaskController::class,'listTerminal']);
        $router->get('/downloadTask/listTerminalGroup', [DownloadTaskController::class,'listGroup']);
        $router->get('/downloadTask/get',  [DownloadTaskController::class,'show']);
        $router->post('/downloadTask/add',  [DownloadTaskController::class,'create']);
        $router->post('/downloadTask/update',  [DownloadTaskController::class,'update']);
        $router->post('/downloadTask/delete',  [DownloadTaskController::class,'delete']);
        $router->post('/downloadTask/cancel',  [DownloadTaskController::class,'cancel']);
        $router->get('/downloadTask/history',  [DownloadTaskController::class,'history']);
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
        $router->get('/diagnostic/lastDiagnosticExport', [DiagnosticController::class,'lastDiagnosticExport']);
        $router->get('/diagnostic/lastHeartbeatExport', [DiagnosticController::class,'lastHeartbeatExport']);

        
        /* router tenant */
        // $router->get('/tenant/list', [TenantController::class,'list']);
        // $router->get('/tenant/get', [TenantController::class,'show']);
        // $router->post('/tenant/add', [TenantController::class,'create']);
        // $router->post('/tenant/update', [TenantController::class,'update']);
        // $router->post('/tenant/delete', [TenantController::class,'delete']);

        /* router aid*/ 
        $router->get('/aid/list', [AidController::class,'list']);
        $router->post('/aid/add', [AidController::class,'add']);
        $router->post('/aid/update', [AidController::class,'update']);
        $router->post('/aid/delete', [AidController::class,'delete']);
        $router->get('/aid/get', [AidController::class,'get']);

        /* router capk*/ 
        $router->get('/capk/list', [CapkController::class,'list']);
        $router->post('/capk/add', [CapkController::class,'add']);
        $router->post('/capk/update', [CapkController::class,'update']);
        $router->post('/capk/delete', [CapkController::class,'delete']);
        $router->get('/capk/get', [CapkController::class,'get']);

        /* router publicKey*/ 
        $router->get('/publicKey/list', [PublicKeyController::class,'list']);
        $router->post('/publicKey/add', [PublicKeyController::class,'add']);
        $router->post('/publicKey/update', [PublicKeyController::class,'update']);
        $router->post('/publicKey/delete', [PublicKeyController::class,'delete']);
        $router->get('/publicKey/get', [PublicKeyController::class,'get']);

        /* router TleSetting*/ 
        $router->get('/tleSetting/list', [TleSettingController::class,'list']);
        $router->post('/tleSetting/add', [TleSettingController::class,'add']);
        $router->post('/tleSetting/update', [TleSettingController::class,'update']);
        $router->post('/tleSetting/delete', [TleSettingController::class,'delete']);
        $router->get('/tleSetting/get', [TleSettingController::class,'get']);

        /* router terminalExt*/ 
        $router->get('/terminalExt/list', [TerminalExtController::class,'list']); //L
        $router->post('/terminalExt/add', [TerminalExtController::class,'add']);
        $router->post('/terminalExt/update', [TerminalExtController::class,'update']);
        $router->post('/terminalExt/delete', [TerminalExtController::class,'delete']); //L
        $router->get('/terminalExt/get', [TerminalExtController::class,'get']); //L
       
        /* router acquirer*/ 
        $router->post('/acquirer/add', [AcquirerController::class,'add']);
        $router->post('/acquirer/update', [AcquirerController::class,'update']);
        $router->post('/acquirer/delete', [AcquirerController::class,'delete']);
        $router->get('/acquirer/list', [AcquirerController::class,'list']);
        $router->get('/acquirer/get', [AcquirerController::class,'show']);

        /* router issuer*/ 
        $router->post('/issuer/add', [IssuerController::class,'add']);
        $router->post('/issuer/update', [IssuerController::class,'update']);
        $router->post('/issuer/delete', [IssuerController::class,'delete']);
        $router->get('/issuer/list', [IssuerController::class,'list']);
        $router->get('/issuer/get', [IssuerController::class,'show']);
        $router->post('/issuer/linkUnlink', [IssuerController::class,'linkUnlink']); //L
        

        /* router card*/ 
        $router->post('/card/add', [CardController::class,'add']);
        $router->post('/card/update', [CardController::class,'update']);
        $router->post('/card/delete', [CardController::class,'delete']);
        $router->get('/card/list', [CardController::class,'list']);
        $router->get('/card/get', [CardController::class,'show']);
        $router->post('/card/linkUnlink', [CardController::class,'linkUnlink']); //L


        /* router template*/ 
        $router->post('/template/add', [TemplateController::class,'add']);
        $router->post('/template/update', [TemplateController::class,'update']);
        $router->post('/template/delete', [TemplateController::class,'delete']);
        $router->get('/template/list', [TemplateController::class,'list']);
        $router->get('/template/get', [TemplateController::class,'show']);
        $router->get('/template/listAcquirer', [TemplateController::class,'listAcquirer']);
        

         /* router response Code*/ 
        $router->post('/responseCode/add', [ResponseCodeController::class,'add']);
        $router->post('/responseCode/update', [ResponseCodeController::class,'update']);
        $router->post('/responseCode/delete', [ResponseCodeController::class,'delete']);
        $router->get('/responseCode/list', [ResponseCodeController::class,'list']);
        $router->get('/responseCode/get', [ResponseCodeController::class,'show']);
        
    
    });
    


});

/* router tenant (not in HeaderAccess Scope) 
accessing only as administrator user management in front end.
Modified by Ali
 */
Route::get('/v1/tenant/list', [TenantController::class,'list']);
Route::get('/v1/tenant/get', [TenantController::class,'show']);
Route::post('/v1/tenant/add', [TenantController::class,'create']);
Route::post('/v1/tenant/update', [TenantController::class,'update']);
Route::post('/v1/tenant/delete', [TenantController::class,'delete']);