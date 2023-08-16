<?php

namespace App\Http\Controllers;
use App\Models\LastHeartBeat;
use App\Models\LastHeartBeatView;
use App\Models\LastDiagnosticInfoView;
use App\Models\Terminal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DiagnosticController extends Controller
{
    

   
    
    public function lastHeartbeat(Request $request){
        
        $validator = Validator::make($request->all(), [
            'sn' => 'required_without:terminalId|max:30',
            'terminalId' => 'required_without:sn|max:36'

        ]);
 
        if ($validator->fails()) {
            $a  =   [   
                "responseCode"=>"5555",
                "responseDesc"=>$validator->errors(),
                
                ];    
            return $this->headerResponse($a,$request);
        }

        try {
            $app = LastHeartBeatView::
            select(
                'tms_v_last_heart_beat.sn',
                'battery_temp as batteryTemp',
                'battery_percentage as batteryPercentage',
                'latitude',
                'longitude',
                'cell_name as cellName',
                'cell_type as cellType',
                'cell_strength as cellStrength',
                'update_time as updateTime',
            )
            ->where('tms_v_last_heart_beat.tenant_id',$request->header('Tenant-id'));
            
			
            if($request->sn != '')
            {
                $app->where('tms_v_last_heart_beat.sn', $request->sn);
            }

            if($request->terminalId != '')
            {
                $app->join('tms_terminal','tms_terminal.sn','=','tms_v_last_heart_beat.sn')
                ->where('tms_terminal.id', $request->terminalId);
            }
			
            
            if($app->get()->count()>0)
            {
                $app =  $app->get();//->makeHidden(['deleted_by', 'delete_ts']);
               

                $a=["responseCode"=>"0000",
                    "responseDesc"=>"OK",
                     "data" => $app
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
        $validator = Validator::make($request->all(), [
            'sn' => 'required_without:terminalId|max:30',
            'terminalId' => 'required_without:sn|max:36'

        ]);
 
        if ($validator->fails()) {
            $a  =   [   
                "responseCode"=>"5555",
                "responseDesc"=>$validator->errors(),
                
                ];    
            return $this->headerResponse($a,$request);
        }


        try {
            $app = LastDiagnosticInfoView::
            select(
                'tms_v_last_diagnostic_info.sn',
                'battery_temp as batteryTemp',
                'battery_percentage as batteryPercentage',
                'latitude',
                'longitude',
                'meid',
                'total_memory as totalMemory',
                'available_memory as availableMemory',
                'total_flash_memory as totalFlashMemory',
                'available_flash_memory as availableFlashMemory',
                'total_mobile_data as totalMobileData',
                'switching_times as switchingTimes',
                'current_boot_time as currentBootTime',
                'total_boot_time as totalBootTime',
                'total_length_printed as totalLengthPrinted',
                'swiping_card_times as swipingCardTimes',
                'dip_inserting_times as dipInsertingTimes',
                'nfc_card_reading_times as nfcCardReadingTimes',
                'front_camera_open_times as frontCameraOpenTimes',
                'rear_camera_open_times as rearCameraOpenTimes',
                'charge_times as chargeTimes',
                'total_memory as totalMemory',
                'available_memory as availableMemory',
                'total_flash_memory as totalFlashMemory',
                'available_flash_memory as availableFlashMemory',
                'total_mobile_data as totalMobileData',
                'current_boot_time as currentBootTime',
                'total_boot_time as totalBootTime',
                'total_length_printed as totalLengthPrinted',
                'cell_name as cellName',
                'cell_type as cellType',
                'cell_strength as cellStrength',
                'update_time as updateTime',
                'installed_apps_string as installedAppsString',
                
            )
            ->where('tms_v_last_diagnostic_info.tenant_id',$request->header('Tenant-id'));
            
			
            if($request->sn != '')
            {
                $app->where('tms_v_last_diagnostic_info.sn', $request->sn);
            }

            if($request->terminalId != '')
            {
                $app->join('tms_terminal','tms_terminal.sn','=','tms_v_last_diagnostic_info.sn')
                ->where('tms_terminal.id', $request->terminalId);
            }
			
            
            if($app->get()->count()>0)
            {
                

                $appAll =  $app->get()->toArray();
              
                $app2 = collect($appAll)->map(function ($data) {

                    $d = [];
                    $d['sn']              = $data['sn']; 
                    $d['batteryTemp'] = $data['batteryTemp']; 
                    $d['batteryPercentage'] = $data['batteryPercentage']; 
                    $d['latitude'] = $data['latitude']; 
                    $d['meid'] = $data['meid'];
                    $d['totalMemory'] = $data['totalMemory'];
                    $d['totalFlashMemory'] =  $data['totalFlashMemory'];
                    $d['availableFlashMemory'] =  $data['availableFlashMemory'];
                    $d['totalMobileData'] =  $data['totalMobileData']; 
                    $d['switchingTimes'] =  $data['switchingTimes'];
                    $d['currentBootTime'] =  $data['currentBootTime'];
                    $d['totalBootTime'] =  $data['totalBootTime'];
                    $d['totalLengthPrinted'] =  $data['totalLengthPrinted'];
                    $d['swipingCardTimes'] =  $data['swipingCardTimes'];
                    $d['dipInsertingTimes'] =  $data['dipInsertingTimes'];
                    $d['nfcCardReadingTimes'] =  $data['nfcCardReadingTimes'];
                    $d['frontCameraOpenTimes'] =  $data['frontCameraOpenTimes'];
                    $d['rearCameraOpenTimes'] =  $data['rearCameraOpenTimes'];
                    $d['chargeTimes'] =  $data['chargeTimes'];
                    $d['totalMemory'] =  $data['totalMemory'];
                    $d['availableMemory'] =  $data['availableMemory'];
                    $d['totalFlashMemory'] =  $data['totalFlashMemory'];
                    $d['availableFlashMemory'] =  $data['availableFlashMemory'];
                    $d['totalMobileData'] =  $data['totalMobileData'];
                    $d['currentBootTime'] =  $data['currentBootTime'];
                    $d['totalBootTime'] =  $data['totalBootTime'];
                    $d['totalLengthPrinted'] =  $data['totalLengthPrinted'];
                    $d['cellName'] =  $data['cellName'];
                    $d['cellType'] =  $data['cellType'];
                    $d['cellStrength'] =  $data['cellStrength'];
                    $d['updateTime'] =  $data['updateTime'];
                  
                    $child = json_decode($data['installedAppsString']);
                    $d['installedApps'] =  collect($child)->map(function ($dt) {
                                                $a = [];
                                                $a['appName']  = $dt->app_name;
                                                $a['packageName']  = $dt->package_name;
                                                $a['appVersion']  = $dt->app_version;
                                                return $a;
                                            }); 
                    return $d;
                
                });
               

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
    
    
}