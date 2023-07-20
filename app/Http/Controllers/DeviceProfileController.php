<?php

namespace App\Http\Controllers;
use App\Models\DeviceProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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
            'heartbeat_interval' => 'required|numeric',
            'diagnostic_interval' => 'required|numeric',
            'mask_home_button'=>  'required|boolean',  
            'mask_status_bar'=>  'required|boolean', 
            'schedule_reboot' => 'required|boolean', 
            'relocation_alert' => 'boolean', 
            'schedule_reboot_time'  => ['required_if:schedule_reboot,true','date_format:H:i:s'], 
            'moving_threshold'  =>  ['required_if:relocation_alert,true','numeric'], 
            'admin_password' =>  'max:50',
            'front_app' =>  'max:255',
            
        ]);
 
        if ($validator->fails()) {
            return response()->json(['responseCode' => '5555', //gagal validasi
                                     'responseDesc' => $validator->errors()]
                                    );
        }

        try {

            $dp = new DeviceProfile();
            $dp->version = 1; 
            $dp->name = $request->name;
            $dp->heartbeat_interval = $request->heartbeat_interval;
            $dp->diagnostic_interval = $request->diagnostic_interval;
            $dp->mask_home_button = $request->mask_home_button;
            $dp->mask_status_bar = $request->mask_status_bar;
            $dp->schedule_reboot = $request->schedule_reboot;
            if($request->schedule_reboot==true)
            {
                $dp->schedule_reboot_time = $request->schedule_reboot_time;
            }
            $dp->is_default = $request->is_default;
            $dp->relocation_alert = $request->relocation_alert;
            if($request->relocation_alert==true)
            {
                $dp->moving_threshold = $request->moving_threshold;
            }
            $dp->admin_password = $request->admin_password;
            $dp->front_app = $request->front_app;
            $dp->tenant_id = $request->tenant_id;
        
            if ($dp->save()) {
                return response()->json(['responseCode' => '0000', //sukses insert
                                          'responseDesc' => 'Device Profile created successfully',
                                          'generatedId' =>  $dp->id
                                        ]);
            }
        } catch (\Exception $e) {
            return response()->json(['responseCode' => '3333', //gagal exception 
                                     'responseDesc' => $e->getMessage()
                                    ]);
        }

    }

    public function update(Request $request){

        $validator = Validator::make($request->all(), [
            'version' => 'required|numeric|max:32',
            'id' => 'required',
            'name' => 'required|max:50|unique:tms_device_profile',
            'heartbeat_interval' => 'required|numeric',
            'diagnostic_interval' => 'required|numeric',
            'mask_home_button'=>  'required|boolean',  
            'mask_status_bar'=>  'required|boolean', 
            'schedule_reboot' => 'required|boolean', 
            'relocation_alert' => 'boolean', 
            'schedule_reboot_time'  => ['required_if:schedule_reboot,true','date_format:H:i:s'], 
            'moving_threshold'  =>  ['required_if:relocation_alert,true','numeric'], 
            'admin_password' =>  'max:50',
            'front_app' =>  'max:255',
        ]);
 
        if ($validator->fails()) {
            return response()->json(['responseCode' => '5555', //gagal validasi
                                     'responseDesc' => $validator->errors()]
                                    );
        }

        try {

            $dp = DeviceProfile::where([
                ['id',$request->id],
                ['version',$request->version]
               
            ])->first();

            $dp->version = $request->version + 1;
            $dp->name = $request->name;
            $dp->heartbeat_interval = $request->heartbeat_interval;
            $dp->diagnostic_interval = $request->diagnostic_interval;
            $dp->mask_home_button = $request->mask_home_button;
            $dp->mask_status_bar = $request->mask_status_bar;
            $dp->schedule_reboot = $request->schedule_reboot;
            if($request->schedule_reboot==true)
            {
                $dp->schedule_reboot_time = $request->schedule_reboot_time;
            }
            $dp->is_default = $request->is_default;
            $dp->relocation_alert = $request->relocation_alert;
            if($request->relocation_alert==true)
            {
                $dp->moving_threshold = $request->moving_threshold;
            }
            $dp->admin_password = $request->admin_password;
            $dp->front_app = $request->front_app;
            $dp->tenant_id = $request->tenant_id;
        
            
            
            if ($dp->save()) {
                return response()->json(['responseCode' => '0000', //sukses update
                                          'responseDesc' => 'Device Profile updated successfully',
                                        
                                        ]);
            }
        } catch (\Exception $e) {
            return response()->json([
            'responseCode' => '3333', 
            'responseDesc' => "Device Profile Update Failure"
        ]);
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
