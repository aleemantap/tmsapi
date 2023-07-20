<?php

namespace App\Http\Controllers;
use App\Models\DeleteTaskLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;


class DeleteTaskLogController extends Controller
{
    public function list(Request $request){

        try {
                $pageSize = $request->pageSize;
                $pageNum = $request->pageNum;
                $task_id = $request->task_id;
                $version = $request->version; 
                $app_id = $request->app_id; 
                
                $query = DeleteTaskLog::whereNotNull('id');

                 
                if($request->app_id != '')
                {
                    $query->where('app_id', $request->app_id);
                }
                if($request->version != '')
                {
                    $query->where('version', $request->version);
                }
                if($request->task_id != '')
                {
                    $query->where('task_id', $request->task_id);
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
            'app_id' => 'required',
            'terminal_id' =>  'required|numeric',
            'activity' =>  'required|numeric',
            'last_broadcast_ts' => 'max:6',
            'old_activity' => 'numeric',
            'message' => 'max:255',
            
        ]);
 
        if ($validator->fails()) {
            return response()->json(['responseCode' => '5555', //gagal validasi
                                     'responseDesc' => $validator->errors()]
                                    );
        }
      
        try {

            $dt = new DeleteTaskLog();
            $dt->version = 1; 
            $dt->task_id  =  $request->task_id;
            $dt->app_id = $request->app_id;
            $dt->activity = $request->activity;
            $dt->terminal_id = $request->terminal_id;
            $dt->last_broadcast_ts = $request->last_broadcast_ts;
            $dt->old_activity = $request->old_activity;
            $dt->message = $request->message;
            
            if ($dt->save()) {
                return response()->json(['responseCode' => '0000', //sukses insert
                                          'responseDesc' => 'Delete Task Log created successfully',
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
            'app_id' => 'required',
            'terminal_id' =>  'required|numeric',
            'activity' =>  'required|numeric',
            'last_broadcast_ts' => 'max:6',
            'old_activity' => 'numeric',
            'message' => 'max:255',
           
        ]);
 
        if ($validator->fails()) {
            return response()->json(['responseCode' => '5555', //gagal validasi
                                     'responseDesc' => $validator->errors()]
                                    );
        }

        try {

            $dt = DeleteTaskLog::where([
                ['id',$request->id],
                ['version',$request->version]
               
            ])->first();
            $dt->version = $request->version + 1;
            $dt->task_id  =  $request->task_id;
            $dt->app_id = $request->app_id;
            $dt->activity = $request->activity;
            $dt->terminal_id = $request->terminal_id;
            $dt->last_broadcast_ts = $request->last_broadcast_ts;
            $dt->old_activity = $request->old_activity;
            $dt->message = $request->message;
           

            if ($dt->save()) {
                return response()->json(['responseCode' => '0000', //sukses update
                                          'responseDesc' => 'Delete Task Log  updated successfully',
                                        
                                        ]);
            }
        } catch (\Exception $e) {
            return response()->json([
            'responseCode' => '3333', 
            'responseDesc' => "Delete Task Log Update Failure"
        ]);
        }
    }
    
    public function show(Request $request){
        try {
            $t = DeleteTaskLog::where('id', $request->id);
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
            $t= DeleteTaskLog::where('id','=',$request->id)
            ->where('version','=',$request->version);
             $cn = $t->get()->count();
             if( $cn > 0)
             {
                $update_t = $t->first();
                $current_date_time = \Carbon\Carbon::now()->toDateTimeString();
                $update_t->delete_ts = $current_date_time; 
                $update_t->deleted_by = "admin";//Auth::user()->id 
                if ($update_t->save()) {
                     return response()->json(['responseCode' => '0000', 'responseDesc' => 'Delete Task Log deleted successfully']);
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
