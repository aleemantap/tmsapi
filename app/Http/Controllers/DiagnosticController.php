<?php

namespace App\Http\Controllers;
//use App\Models\Application;
use App\Models\HeartBeat;
use Illuminate\Http\Request;
//use Illuminate\Support\Facades\DB;
//use Illuminate\Support\Str;
//use Illuminate\Support\Facades\Validator;
//use Illuminate\Validation\Rule;
//use Illuminate\Support\Facades\Storage;

class DiagnosticController extends Controller
{
   
    public function lastHeartbeat(Request $request){
      
        try {
            $app = HeartBeat::
			where('tenant_id',$request->header('Tenant-id'))
			->whereNull('deleted_by');
			//->with(['deviceModel' => function ($query) {
              //      $query->select('id', 'model');
					
            //}]);

            if($request->sn != '')
            {
                $app->where('sn', $request->sn);
            }

            if($request->terminalId != '')
            {
                //$app->where('sn', $request->terminalId);
            }
			
            
            if($app->get()->count()>0)
            {
                $app =  $app->get();//->makeHidden(['deleted_by', 'delete_ts']);
                $jsonr =[
                    "sn" => $app[0]['id'],
                    "batteryTemp" => $app[0]['package_name'],
                    "batteryPercentage" =>  $app[0]['name'],
                    "latitude" => $app[0]['description'],
                    "longitude" => $app[0]['app_version'],
                    "cellName" => $app[0]['uninstallable'],
                    "cellType" => $app[0]['company_name'],
                    "cellStrength" => $app[0]['deviceModel'],
                    "updateTime" => $app[0]['version']
                    
                ];

                $a=["responseCode"=>"0000",
                    "responseDesc"=>"OK",
                     "data" => $jsonr
                    ];    
                return $this->headerResponse($a,$request);
            }
            else
            {
                $a=["responseCode"=>"0400",
                    "responseDesc"=>"Data Not Found",
                    "data" => null
                ];    
                return $this->headerResponse($a,$request);
            }
            
        }
        catch(\Exception $e)
        {
          
            $a  =   [   
                "responseCode"=>"3333",
                "responseDesc"=>$e->getMessage()
                ];    
            return $this->headerResponse($a,$request);
        }
    }
    
    public function lastDiagnostic(Request $request){
       
        try {
            $app = Application::
			
            where('tenant_id',$request->header('Tenant-id'))
			->whereNull('deleted_by');

            if($request->sn != '')
            {
                $app->where('sn', $request->sn);
            }

            if($request->terminalId != '')
            {
                $app->where('sn', $request->terminalId);
            }
			
            
            //->with(['deviceModel' => function ($query) {
              //      $query->select('id', 'model');
					
            //}]);
			
            
            if($app->get()->count()>0)
            {
                $app =  $app->get()->makeHidden(['deleted_by', 'delete_ts']);
                $urlIcon = \Storage::cloud()->temporaryUrl($app[0]['icon_url'],\Carbon\Carbon::now()->addMinutes(30));
                $jsonr =[
                    "id" => $app[0]['id'],
                    "packageName" => $app[0]['package_name'],
                    "name" =>  $app[0]['name'],
                    "description" => $app[0]['description'],
                    "appVersion" => $app[0]['app_version'],
                    "uninstallable" => $app[0]['uninstallable'],
                    "companyName" => $app[0]['company_name'],
                    "iconUrl"  => $urlIcon,
					"deviceModel" => $app[0]['deviceModel'],
                    "version" => $app[0]['version'],
                    "createdBy"=> $app[0]['created_by'],
                    "createdTime" => $app[0]['create_ts'],
                    "lastUpdatedBy"=> $app[0]['updated_by'],
                    "lastUpdatedTime"=> $app[0]['update_ts']
                ];

                $a=["responseCode"=>"0000",
                    "responseDesc"=>"OK",
                     "data" => $jsonr
                    ];    
                return $this->headerResponse($a,$request);
            }
            else
            {
                $a=["responseCode"=>"0400",
                    "responseDesc"=>"Data Not Found",
                    "data" => null
                ];    
                return $this->headerResponse($a,$request);
            }
            
        }
        catch(\Exception $e)
        {
          
            $a  =   [   
                "responseCode"=>"3333",
                "responseDesc"=>$e->getMessage()
                ];    
            return $this->headerResponse($a,$request);
        }
    }


    

    
}