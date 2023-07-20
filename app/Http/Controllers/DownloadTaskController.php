<?php

namespace App\Http\Controllers;
use App\Models\DownloadTask;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;


class DownloadTaskController extends Controller
{
    public function list(Request $request){

        try {
                $pageSize = $request->pageSize;
                $pageNum = $request->pageNum;
                $name = $request->name;
                $status = $request->status;
                
                $query = DownloadTask::whereNull('deleted_by');

                 
                if($request->name != '')
                {
                    $query->where('name', $request->name);
                }
                if($request->status != '')
                {
                    $query->where('status', $request->status);
                }
               
                $count = $query->get()->count();
            
                $results = $query->offset(($pageNum-1) * $pageSize) 
                ->limit($pageSize)->orderBy('create_ts', 'DESC')
                ->get()->makeHidden(['deleted_by','delete_ts']);
                
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
            'name' => 'required|max:100|unique:tms_download_task',
            'publish_time_type' => 'required|numeric',
            'publish_time' => 'max:6',
            'installation_time_type' =>  'required|numeric',
            'installation_time' =>  'max:6',
            'installation_notification' => 'required|numeric',
            'status' => 'required|numeric',
            'old_status' => 'numeric',
            'tenant_id' => 'required',
            'download_url' => 'required|max:255',
        ]);
 
        if ($validator->fails()) {
            return response()->json(['responseCode' => '5555', //gagal validasi
                                     'responseDesc' => $validator->errors()]
                                    );
        }
      
        try {

            $dt = new DowloadTask();
            $dt->version = 1; 
            $dt->name  =  $request->name;
            $dt->publish_time_type = $request->publish_time_type;
            $dt->publish_time = $request->publish_time;
            $dt->installation_time_type = $request->installation_time_type;
            $dt->installation_time = $request->installation_time;
            $dt->installation_notification = $request->installation_notification;
            $dt->status = $request->status;
            $dt->old_status = $request->old_status;
            $dt->tenant_id = $request->tenant_id;
            $dt->download_url = $request->download_url;
            
            if ($dt->save()) {
                return response()->json(['responseCode' => '0000', //sukses insert
                                          'responseDesc' => 'Download Task created successfully',
                                          'generatedId' =>  $t->id
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
            'name' => 'required|max:100|unique:tms_download_task',
            'publish_time_type' => 'required|numeric',
            'publish_time' => 'max:6',
            'installation_time_type' =>  'required|numeric',
            'installation_time' =>  'max:6',
            'installation_notification' => 'required|numeric',
            'status' => 'required|numeric',
            'old_status' => 'numeric',
            'tenant_id' => 'required',
            'download_url' => 'required|max:255',
           
        ]);
 
        if ($validator->fails()) {
            return response()->json(['responseCode' => '5555', //gagal validasi
                                     'responseDesc' => $validator->errors()]
                                    );
        }

        try {

            $dt = DownloadTask::where([
                ['id',$request->id],
                ['version',$request->version]
               
            ])->first();
            $dt->version = $request->version + 1;
            $dt->publish_time_type = $request->publish_time_type;
            $dt->publish_time = $request->publish_time;
            $dt->installation_time_type = $request->installation_time_type;
            $dt->installation_time = $request->installation_time;
            $dt->installation_notification = $request->installation_notification;
            $dt->status = $request->status;
            $dt->old_status = $request->old_status;
            $dt->tenant_id = $request->tenant_id;
            $dt->download_url = $request->download_url;
           

            if ($dt->save()) {
                return response()->json(['responseCode' => '0000', //sukses update
                                          'responseDesc' => 'Download Task  updated successfully',
                                        
                                        ]);
            }
        } catch (\Exception $e) {
            return response()->json([
            'responseCode' => '3333', 
            'responseDesc' => "Download Task Update Failure"
        ]);
        }
    }
    
    public function show(Request $request){
        try {
            $t = DownloadTask::where('id', $request->id)->whereNull('deleted_by');
            if($t->get()->count()>0)
            {
                $t =  $t->get()->makeHidden(['deleted_by', 'delete_ts']);
                return response()->json([
                    'responseCode' => '0000', 
                    'responseDesc' => 'OK',
                    'data' => $t
                    
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
            $t= DownloadTask::where('id','=',$request->id)
            ->where('version','=',$request->version);
             $cn = $t->get()->count();
             if( $cn > 0)
             {
                $update_t = $t->first();
                $current_date_time = \Carbon\Carbon::now()->toDateTimeString();
                $update_t->delete_ts = $current_date_time; 
                $update_t->deleted_by = "admin";//Auth::user()->id 
                if ($update_t->save()) {
                     return response()->json(['responseCode' => '0000', 'responseDesc' => 'Download Task deleted successfully']);
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
