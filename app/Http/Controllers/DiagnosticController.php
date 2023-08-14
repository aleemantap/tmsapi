<?php

namespace App\Http\Controllers;
use App\Models\LastHeartBeat;
use App\Models\ViewLastHeartBeat;
use App\Models\Terminal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DiagnosticController extends Controller
{
    

   
    // public function lastHeartbeatx(Request $request){
    //     $validator = Validator::make($request->all(), [
    //         'sn' => 'required_without:terminalId',
    //         'terminalId' => 'required_without:sn',
          
    //     ]);
 
    //     if ($validator->fails()) {
    //          $a  =   [   
    //             "responseCode"=>"5555",
    //             "responseDesc"=>$validator->errors()
    //             ];    
    //         return $this->headerResponse($a,$request);
    //     }

    //     try {
    //         //$app = ViewLastHeartBeat::query()
	// 		//->where('tenant_id',$request->header('Tenant-id'));
    //         //->with('lastHeartBeat');
	// 		//->with(['lastHeartBeat' => function ($query) {
    //           //      $query->select('*'); // update_time
	// 		//}]);

    //         //$app = ViewLastHeartBeat::select("*");
    //         $app  = DB::table('Views.tms_v_last_hear_beat');

    //         //->get();

    //         //->toArray();

    //         //if($request->sn != '')
    //         //{
    //            // $app->where('sn', $request->sn);
    //         //}

    //         //if($request->terminalId != '')
    //         //{
    //           //  $app->where('id', $request->terminalId);
    //         //}
           
			
            
    //         if($app->get()->count()>0)
    //         {
    //             $app =  $app->get();//->makeHidden(['deleted_by', 'delete_ts']);
    //             $jsonr =[
    //                 "sn" => $app[0]['sn'],
    //                 //"batteryTemp" => $app[0]['last_heart_beat'][0][''],
    //                 //"batteryPercentage" =>  $app[0]['name'],
    //                 //"latitude" => $app[0]['description'],
    //                 //"longitude" => $app[0]['app_version'],
    //                 //"cellName" => $app[0]['uninstallable'],
    //                 //"cellType" => $app[0]['company_name'],
    //                 //"cellStrength" => $app[0]['deviceModel'],
    //                 //"updateTime" => $app[0]['version']
                    
    //             ];

    //             $a=["responseCode"=>"0000",
    //                 "responseDesc"=>"OK",
    //                  "data" => $app
    //                 ];    
    //             return $this->headerResponse($a,$request);
    //         }
    //         else
    //         {
    //             $a=["responseCode"=>"0400",
    //                 "responseDesc"=>"Data Not Found",
    //                 "data" => null
    //             ];    
    //             return $this->headerResponse($a,$request);
    //         }
            
    //     }
    //     catch(\Exception $e)
    //     {
          
    //         $a  =   [   
    //             "responseCode"=>"3333",
    //             "responseDesc"=>$e->getMessage()
    //             ];    
    //         return $this->headerResponse($a,$request);
    //     }

    // }
    public function lastHeartbeat(Request $request){

        $validator = Validator::make($request->all(), [
            'sn' => 'required_without:terminalId',
            'terminalId' => 'required_without:sn',
          
        ]);
 
        if ($validator->fails()) {
             $a  =   [   
                "responseCode"=>"5555",
                "responseDesc"=>$validator->errors()
                ];    
            return $this->headerResponse($a,$request);
        }
       
        try {
            $app = Terminal::whereNull('deleted_by')
			->where('tenant_id',$request->header('Tenant-id'))
            ->with('lastHeartBeat');
			
            if($request->sn != '')
            {
                $app->where('sn', $request->sn);
            }

            if($request->terminalId != '')
            {
                $app->where('id', $request->terminalId);
            }
           
            
            if($app->get()->count()>0)
            {
                $app =  $app->get()->toArray();//->makeHidden(['deleted_by', 'delete_ts']);
                $app2 =[
                    "sn" => $app[0]['sn'],
                    "batteryTemp" => $app[0]['last_heart_beat'][0]['battery_temp'],
                    "batteryPercentage" =>  $app[0]['last_heart_beat'][0]['battery_percentage'],
                    "latitude" =>  $app[0]['last_heart_beat'][0]['latitude'],
                    "longitude" =>  $app[0]['last_heart_beat'][0]['longitude'],
                    "cellName" =>  $app[0]['last_heart_beat'][0]['cell_name'],
                    "cellStrength" =>  $app[0]['last_heart_beat'][0]['cell_strength'],
                    "cellType"=>  $app[0]['last_heart_beat'][0]['cell_type'],
                    "updateTime" =>  $app[0]['last_heart_beat'][0]['create_ts'],
                ];

                $a=["responseCode"=>"0000",
                    "responseDesc"=>"OK",
                     "data" => $app2
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
       /*

        SELECT t.sn,
    tdi.battery_temp,
    tdi.battery_percentage,
    tdi.latitude,
    tdi.longitude,
    tdi.meid,
    tdi.switching_times,
    tdi.swiping_card_times,
    tdi.dip_inserting_times,
    tdi.nfc_card_reading_times,
    tdi.front_camera_open_times,
    tdi.rear_camera_open_times,
    tdi.charge_times,
    tdi.total_memory,
    tdi.available_memory,
    tdi.total_flash_memory,
    tdi.available_flash_memory,
    tdi.total_mobile_data,
    tdi.current_boot_time,
    tdi.total_boot_time,
    tdi.total_length_printed,
    tdi.cell_name,
    tdi.cell_type,
    tdi.cell_strength,
    tdi.installed_apps_string,
    tdi.create_ts AS update_time,
    t.tenant_id
   FROM tms_terminal t
     JOIN tms_last_diagnostic_info tldi ON tldi.terminal_id = t.id
     JOIN tms_diagnostic_info tdi ON tldi.diagnostic_info_id = tdi.id
  WHERE t.delete_ts IS NULL

       */
        $validator = Validator::make($request->all(), [
            'sn' => 'required_without:terminalId',
            'terminalId' => 'required_without:sn',
          
        ]);
 
        if ($validator->fails()) {
             $a  =   [   
                "responseCode"=>"5555",
                "responseDesc"=>$validator->errors()
                ];    
            return $this->headerResponse($a,$request);
        }
       
        try {
            $app = Terminal::whereNull('deleted_by')
			->where('tenant_id',$request->header('Tenant-id'))
            ->with('lastHeartBeat');
			
            if($request->sn != '')
            {
                $app->where('sn', $request->sn);
            }

            if($request->terminalId != '')
            {
                $app->where('id', $request->terminalId);
            }
           
            
            if($app->get()->count()>0)
            {
                $app =  $app->get()->toArray();//->makeHidden(['deleted_by', 'delete_ts']);
                $app2 =[
                    "sn" => $app[0]['sn'],
                    "batteryTemp" => $app[0]['last_heart_beat'][0]['battery_temp'],
                    "batteryPercentage" =>  $app[0]['last_heart_beat'][0]['battery_percentage'],
                    "latitude" =>  $app[0]['last_heart_beat'][0]['latitude'],
                    "longitude" =>  $app[0]['last_heart_beat'][0]['longitude'],
                    //meid
                    //totalMemory
                    //availableMemory
                    //totalFlashMemory
                    //availableFlashMemory
                    //totalMobileData
                    //switchingTimes
                    //currentBootTime
                    //totalBootTime
                    //totalLengthPrinted
                    //swipingCardTimes
                    //dipInsertingTimes
                    "cellName" =>  $app[0]['last_heart_beat'][0]['cell_name'],
                    "cellStrength" =>  $app[0]['last_heart_beat'][0]['cell_strength'],
                    "cellType"=>  $app[0]['last_heart_beat'][0]['cell_type'],
                    "updateTime" =>  $app[0]['last_heart_beat'][0]['create_ts'],
                ];

                $a=["responseCode"=>"0000",
                    "responseDesc"=>"OK",
                     "data" => $app2 // ViewLastHeartBeat::get()
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