<?php

namespace App\Http\Controllers;
use App\Models\DeviceProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;



class DeviceProfileController extends Controller
{
    public function list(Request $request){

        try {

                $pageSize = $request->pageSize;
                $pageNum = $request->pageNum;
                $name = $request->name;
                
                $query = DeviceProfile::whereNull('deleted_by');

                 
                if($request->name != '')
                {
                    $query->where('name', $request->name);
                }
               
                $count = $query->get()->count();
            
                $results = $query->offset(($pageNum-1) * $pageSize) 
                ->limit($pageSize)->orderBy('name', 'ASC')
                ->get(['id','name','version','created_by','create_ts','updated_by','update_ts','is_default']);
                
                if($count > 0)
                {
                    return response()->json(['responseCode' => '0000', 
                                        'responseDesc' => 'OK',
                                        'pageSize'  =>  $pageSize,
                                        'totalPage' => ceil($count/$pageSize),
                                        'total' => $count,
                                        'rows' => $results
                                    ]);
                }
                else
                {
                    return response()->json(['responseCode' => '0400', 
                                        'responseDesc' => 'Data Not Found',
                                        'rows' => $results
                                        
                                    ]);
                }
                
        } catch (\Exception $e) {
            return response()->json(['status' => '3333', 'message' => $e->getMessage()]);
        }
    }

   
    public function create(Request $request){
     
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:50|unique:tms_device_profile',
            'heartbeatInterval' => 'required|numeric',
            'diagnosticInterval' => 'required|numeric',
            'maskHomeButton'=>  'required|boolean',  
            'maskStatusBar'=>  'required|boolean', 
            'scheduleReboot' => 'required|boolean', 
            'relocationAlert' => 'boolean', 
            'scheduleRebootTime'  => ['required_if:scheduleReboot,true','date_format:H:i:s'], 
            'movingThreshold'  =>  ['required_if:relocationAlert,true','numeric'], 
            'adminPassword' =>  'max:50',
            'frontApp' =>  'max:255',
            
        ]);
 
        if ($validator->fails()) {
            $a  =   [   
                "responseCode"=>"5555",
                "responseDesc"=>$validator->errors()
                ];    
            return $this->headerResponse($a,$request);
        }

        DB::beginTransaction();
        try {

            $dp = new DeviceProfile();
            $dp->version = 1; 
            $dp->name = $request->name;
            $dp->heartbeat_interval = $request->heartbeatInterval;
            $dp->diagnostic_interval = $request->diagnosticInterval;
            $dp->mask_home_button = $request->maskHomeButton;
            $dp->mask_status_bar = $request->maskStatusBar;
            $dp->schedule_reboot = $request->scheduleReboot;
            if($request->scheduleReboot==true)
            {
                $dp->schedule_reboot_time = $request->scheduleRebootTime;
            }
            $dp->is_default = $request->default;
            $dp->relocation_alert = $request->relocationAlert;
            if($request->relocationAlert==true)
            {
                $dp->moving_threshold = $request->movingThreshold;
            }
            $dp->admin_password = $request->adminPassword;
            $dp->front_app = $request->frontApp;
            $dp->tenant_id = $request->header('Tenant-id');
        
            if ($dp->save()) {
                DB::commit();
                $a  =   [   
                    "responseCode"=>"0000",
                    "responseDesc"=>"OK",
                    "generatedId" =>  $dp->id
                    ];    
            return $this->headerResponse($a,$request);



            }
        } catch (\Exception $e) {
            DB::rollBack();
            $a  =   [
                "responseCode"=>"3333",
                "responseDesc"=>$e->getMessage()
                ];    
            return $this->failedInssertResponse($a,$request);
        }

    }

    public function update(Request $request){

        $check = DeviceProfile::where([
            ['id',$request->id],
            ['name',$request->name]
           
        ])->first();
        
        
        $device     = [
            'version' => 'required|numeric|max:32',
            'id' => 'required',
            'name' => 'required',
            'heartbeatInterval' => 'required|numeric',
            'diagnosticInterval' => 'required|numeric',
            'maskHomeButton'=>  'required|boolean',  
            'maskStatusBar'=>  'required|boolean', 
            'scheduleReboot' => 'required|boolean', 
            'relocationAlert' => 'boolean', 
            'scheduleRebootTime'  => ['required_if:scheduleReboot,true','date_format:H:i:s'], 
            'movingThreshold'  =>  ['required_if:relocationAlert,true','numeric'], 
            'adminPassword' =>  'max:50',
            'frontApp' =>  'max:255',
        ];


        if(!$check){
         
            $device['name'] = 'required|max:50|unique:tms_device_profile';
        }

        $validator = Validator::make($request->all(), $device);

        
        if ($validator->fails()) {
            $a  =   [   
                "responseCode"=>"5555",
                "responseDesc"=>$validator->errors()
                ];    
            return $this->headerResponse($a,$request);
        }

        DB::beginTransaction();
        try {

            $dp = DeviceProfile::where([
                ['id',$request->id],
                ['version',$request->version]
               
            ])->first();

            $dp->version = $request->version + 1;
            $dp->name = $request->name;
            $dp->heartbeat_interval = $request->heartbeatInterval;
            $dp->diagnostic_interval = $request->diagnosticInterval;
            $dp->mask_home_button = $request->maskHomeButton;
            $dp->mask_status_bar = $request->maskStatusBar;
            $dp->schedule_reboot = $request->scheduleReboot;
            if($request->scheduleReboot==true)
            {
                $dp->schedule_reboot_time = $request->scheduleRebootTime;
            }
            $dp->is_default = $request->default;
            $dp->relocation_alert = $request->relocationAlert;
            if($request->relocationAlert==true)
            {
                $dp->moving_threshold = $request->movingThreshold;
            }
            $dp->admin_password = $request->adminPassword;
            $dp->front_app = $request->frontApp;
            $dp->tenant_id = $request->header('Tenant-id');
        
            
            
            if ($dp->save()) {
                DB::commit();
                $a  =   [   
                    "responseCode"=>"0000",
                    "responseDesc"=>"OK"
                    ];    
                return $this->headerResponse($a,$request);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $a  =   [   
                "responseCode"=>"3333",
                "responseDesc"=>$e->getMessage()
                ];    
            return $this->headerResponse($a,$request);
        }
    }
    
    public function show(Request $request){
        try {
            $DeviceModel = DeviceProfile::where('id', $request->id)->whereNull('deleted_by');
            
            
            if($DeviceModel->get()->count()>0)
            {
                $DeviceModel =  $DeviceModel->get()->makeHidden(['deleted_by', 'delete_ts']);
                return response()->json([
                    'responseCode' => '0000', 
                    'responseDesc' => 'OK',
                    'data' => $DeviceModel
                    
                ]);
            }
            else
            {
           
                return response()->json([
                    'responseCode' => '0400', 
                    'responseDesc' => 'Data Not Found',
                    'data' => []                   
                ]);
            }
            
        }
        catch(\Exception $e)
        {
            return response()->json(['responseCode' => '3333', 'responseDesc' => $e->getMessage()]);
        }
    }


    public function delete(Request $request){
        try {
            $m = DeviceProfile::where('id','=',$request->id)
            ->where('version','=',$request->version);
             $cn = $m->get()->count();
             if( $cn > 0)
             {
                $updateMt = $m->first();
                $current_date_time = \Carbon\Carbon::now()->toDateTimeString();
                $updateMt->delete_ts = $current_date_time; 
                $updateMt->deleted_by = "admin";//Auth::user()->id 
                if ($updateMt->save()) {
                     return response()->json(['responseCode' => '0000', 'responseDesc' => 'Device Profile  deleted successfully']);
                 }
             }
             else
             {
                     return response()->json(['responseCode' => '0400', 'responseDesc' => 'Data Not Found']);
              }

            
        } catch (\Exception $e) {
            return response()->json(['responseCode' => '3333', 'responseDesc' => $e->getMessage()]);
        }
    }


    
}
