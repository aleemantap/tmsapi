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
                $app =  $app->get();
               

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
    
    
}