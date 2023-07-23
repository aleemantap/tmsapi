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

            $pageSize = ($request->pageSize)?$request->pageSize:10;
            $pageNum = ($request->pageNum)?$request->pageNum:1;
                
                $query = DeviceProfile::select('id', 'name', 'is_default as default','version','created_by as createdBy','create_ts as createdTime','updated_by as lastUpdateBy','update_ts as lastUpdateBy')
                ->where('tenant_id','=', $request->header('Tenant-id'))
                ->whereNull('deleted_by');

                 
                if($request->name != '')
                {
                    $query->where('name', 'ILIKE', '%' . $request->name .'%');
                }
               
                $count = $query->get()->count();
            
                $results = $query->offset(($pageNum-1) * $pageSize) 
                ->limit($pageSize)->orderBy('name', 'ASC')
                ->get(['id','name','version','created_by','create_ts','updated_by','update_ts','is_default']);
                
                if($count > 0)
                {
                    $a=['responseCode' => '0000', 
                        'responseDesc' => "OK",
                        'pageSize'  =>  $pageSize,
                        'totalPage' => ceil($count/$pageSize),
                        'total' => $count,
                        'rows' => $results
                        ];    
                        return $this->listResponse($a,$request);
                }
                else
                {
                    $a=["responseCode"=>"0400",
                    "responseDesc"=>"Data Not Found",
                    'rows' => $results
                    ];    
                return $this->headerResponse($a,$request);
                }
                
        } catch (\Exception $e) {
            $a=["responseCode"=>"3333",
            "responseDesc"=>$e->getMessage()
            ];    
            return $this->headerResponse($a,$request);
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
                ['version',$request->version],
                ['tenant_id', $request->header('Tenant-id')]
               
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
            }else{
                $a=["responseCode"=>"0400",
                "responseDesc"=>"Data Not Found"
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
            $DeviceProfile = DeviceProfile::
            select('id',
            'name',
            'heartbeat_interval as heartbeatInterval',
            'diagnostic_interval as diagnosticInterval',
            'mask_home_button as maskHomeButton',
            'mask_status_bar as maskStatusBar',
            'schedule_reboot as scheduleReboot',
            'schedule_reboot_time as scheduleRebootTime',
            'schedule_reboot_time as scheduleRebootTime',
            'is_default as default',
            'relocation_alert as relocationAlert',
            'moving_threshold as movingThreshold',
            'admin_password as adminPassword',
            'front_app as frontApp',
            'version as version',
            'created_by as createdBy',
            'create_ts as createdTime',
            'updated_by as lastUpdateBy',
            'update_ts as lastUpdateTime'
            )
            ->where('id', $request->id)
            ->where('tenant_id',$request->header('Tenant-id'))
            ->whereNull('deleted_by');
            
            
            if($DeviceProfile->get()->count()>0)
            {
                $DeviceProfile =  $DeviceProfile->get()->makeHidden(['deleted_by', 'delete_ts']);
                $a=["responseCode"=>"0000",
                    "responseDesc"=>"OK",
                     "data" => $DeviceProfile
                    ];    
                return $this->headerResponse($a,$request);
            }
            else
            {
           
                $a=["responseCode"=>"0400",
                "responseDesc"=>"Data Not Found",
                 "data" => $DeviceProfile
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


    public function delete(Request $request){
        DB::beginTransaction();
        try {
            $m = DeviceProfile::where('id','=',$request->id)
            ->where('version','=',$request->version)->where('tenant_id', '=', $request->header('Tenant-id'));
             $cn = $m->get()->count();
             if( $cn > 0)
             {
                $updateMt = $m->first();
                $current_date_time = \Carbon\Carbon::now()->toDateTimeString();
                $updateMt->delete_ts = $current_date_time; 
                $updateMt->deleted_by = "admin";//Auth::user()->id 
                if ($updateMt->save()) {
                    DB::commit();
                    $a  =   [   
                        "responseCode"=>"0000",
                        "responseDesc"=>"OK"
                        ];    
                    return $this->headerResponse($a,$request);
                 }
             }
             else
             {
                $a  =   [   
                    "responseCode"=>"0400",
                    "responseDesc"=>"Data No Found"
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


    
}