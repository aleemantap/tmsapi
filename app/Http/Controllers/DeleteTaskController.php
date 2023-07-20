<?php

namespace App\Http\Controllers;
use App\Models\DeleteTask;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;


class DeleteTaskController extends Controller
{
    public function list(Request $request){

        try {
                $pageSize = $request->pageSize;
                $pageNum = $request->pageNum;
                $name = $request->name;
                $status = $request->status;
                
                $query = DeleteTask::whereNull('deleted_by');

                 
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
            'name' => 'required|max:100|unique:tms_delete_task',
            'status' => 'required|numeric',
            'delete_time' => 'max:6',
            'old_status' =>  'numeric',
            'tenant_id' =>  'required',
        ]);
 
        if ($validator->fails()) {
            return response()->json(['responseCode' => '5555', //gagal validasi
                                     'responseDesc' => $validator->errors()]
                                    );
        }
      
        try {

            $dt = new DeleteTask();
            $dt->version = 1; 
            $dt->name  =  $request->name;
            $dt->status = $request->status;
            $dt->delete_time = $request->delete_time;
            $dt->old_status = $request->old_status;
            $dt->tenant_id = $request->tenant_id;
            
            if ($dt->save()) {
                return response()->json(['responseCode' => '0000', //sukses insert
                                          'responseDesc' => 'Delete Task created successfully',
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
            'name' => 'required|max:100|unique:tms_delete_task',
            'status' => 'required|numeric',
            'delete_time' => 'max:6',
            'old_status' =>  'numeric',
            'tenant_id' =>  'required',
           
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
            $dt->name  =  $request->name;
            $dt->status = $request->status;
            $dt->delete_time = $request->delete_time;
            $dt->old_status = $request->old_status;
            $dt->tenant_id = $request->tenant_id;
           

            if ($dt->save()) {
                return response()->json(['responseCode' => '0000', //sukses update
                                          'responseDesc' => 'Delete Task  updated successfully',
                                        
                                        ]);
            }
        } catch (\Exception $e) {
            return response()->json([
            'responseCode' => '3333', 
            'responseDesc' => "Delete Task Update Failure"
        ]);
        }
    }
    
    public function show(Request $request){
        try {
            $t = DeleteTask::where('id', $request->id)->whereNull('deleted_by');
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
            $t= DeleteTask::where('id','=',$request->id)
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
