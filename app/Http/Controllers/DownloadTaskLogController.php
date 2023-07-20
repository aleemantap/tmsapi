<?php

namespace App\Http\Controllers;
use App\Models\DownloadTaskLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;


class DownloadTaskLogController extends Controller
{
    public function list(Request $request){

        try {
                $pageSize = $request->pageSize;
                $pageNum = $request->pageNum;
                $task_id = $request->task_id;
                $application_id = $request->application_id;
                
                $query = DownloadTaskLog::whereNotNull('id');

                 
                if($request->task_id != '')
                {
                    $query->where('task_id', $request->task_id);
                }
                if($request->application_id != '')
                {
                    $query->where('application_id', $request->application_id);
                }
               
                $count = $query->get()->count();
            
                $results = $query->offset(($pageNum-1) * $pageSize) 
                ->limit($pageSize)->orderBy('create_ts', 'DESC')
                ->get();
                
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
            'version' => 'required|numeric',
            'task_id' => 'required',
            'application_id' => 'required',
            'activity' =>  'required|numeric',
            'terminal_id' =>  'required',
            'last_broadcast_ts' => 'date_format:Y-m-d H:i:s|max:6',
            'old_activity' => 'numeric',
            'message' => 'max:255',
            
        ]);
 
        if ($validator->fails()) {
            return response()->json(['responseCode' => '5555', //gagal validasi
                                     'responseDesc' => $validator->errors()]
                                    );
        }
      
        try {

            $dt = new DowloadTaskLog();
            $dt->version = 1; 
            $dt->task_id  =  $request->task_id;
            $dt->application_id = $request->application_id;
            $dt->activity = $request->activity;
            $dt->terminal_id = $request->terminal_id;
            $dt->last_broadcast_ts = $request->last_broadcast_ts;
            $dt->old_activity = $request->old_activity;
            $dt->message = $request->message;
            
            if ($dt->save()) {
                return response()->json(['responseCode' => '0000', //sukses insert
                                          'responseDesc' => 'Download Task Log created successfully',
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
           
            'id' => 'required',
            'version' => 'required|numeric',
            'task_id' => 'required',
            'application_id' => 'required',
            'activity' =>  'required|numeric',
            'terminal_id' =>  'required',
            'last_broadcast_ts' => 'date_format:Y-m-d H:i:s|max:6',
            'old_activity' => 'numeric',
            'message' => 'max:255',
           
        ]);
 
        if ($validator->fails()) {
            return response()->json(['responseCode' => '5555', //gagal validasi
                                     'responseDesc' => $validator->errors()]
                                    );
        }

        try {

            $dt = DowloadTaskLog::where([
                ['id',$request->id],
                ['version',$request->version]
               
            ])->first();
            $dt->version = $request->version + 1;
            $dt->task_id  =  $request->task_id;
            $dt->application_id = $request->application_id;
            $dt->activity = $request->activity;
            $dt->terminal_id = $request->terminal_id;
            $dt->last_broadcast_ts = $request->last_broadcast_ts;
            $dt->old_activity = $request->old_activity;
            $dt->message = $request->message;
           

            if ($dt->save()) {
                return response()->json(['responseCode' => '0000', //sukses update
                                          'responseDesc' => 'Download Task Log  updated successfully',
                                        
                                        ]);
            }
        } catch (\Exception $e) {
            return response()->json([
            'responseCode' => '3333', 
            'responseDesc' => "Download Task Log Update Failure"
        ]);
        }
    }
    
    public function show(Request $request){
        try {
            $t = DownloadTaskLog::where('id', $request->id);
            if($t->get()->count()>0)
            {
                $t =  $t->get();
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
            $t= DownloadTaskLog::where('id','=',$request->id)
            ->where('version','=',$request->version);
             $cn = $t->get()->count();
             if( $cn > 0)
             {
                $update_t = $t->first();
                $current_date_time = \Carbon\Carbon::now()->toDateTimeString();
                $update_t->delete_ts = $current_date_time; 
                $update_t->deleted_by = "admin";//Auth::user()->id 
                if ($update_t->save()) {
                     return response()->json(['responseCode' => '0000', 'responseDesc' => 'Download Task Log deleted successfully']);
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
