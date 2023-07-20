<?php

namespace App\Http\Controllers;
use App\Models\DeleteTaskApp;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;


class DeleteTaskAppController extends Controller
{
    public function list(Request $request){

        try {
                $pageSize = $request->pageSize;
                $pageNum = $request->pageNum;
                $app_name = $request->app_name;
                $package_name = $request->package_name;
                $app_version = $request->app_version;
              
                
                $query = DeleteTaskApp::whereNotNull('id');

                 
                if($request->app_name != '')
                {
                    $query->where('app_name', $request->app_name);
                }
                if($request->package_name != '')
                {
                    $query->where('package_name', $request->package_name);
                }
               
                $count = $query->get()->count();
            
                $results = $query->offset(($pageNum-1) * $pageSize) 
                ->limit($pageSize)->orderBy('app_name', 'ASC')
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
            'app_name' => 'required|max:255|unique:tms_delete_task_app',
            'package_name' => 'required|max:255',
            'app_version' =>  'required|max:255',
            'task_id' =>  'required'
        ]);
 
        if ($validator->fails()) {
            return response()->json(['responseCode' => '5555', //gagal validasi
                                     'responseDesc' => $validator->errors()]
                                    );
        }
      
        try {

            $dt = new DeleteTaskApp();
         
            $dt->app_name  =  $request->app_name;
            $dt->package_name = $request->package_name;
            $dt->app_version = $request->app_version;
            $dt->task_id = $request->task_id;
         
            if ($dt->save()) {
                return response()->json(['responseCode' => '0000', //sukses insert
                                          'responseDesc' => 'Delete Task App created successfully',
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
            'app_name' => 'required|max:255|unique:tms_delete_task_app',
            'package_name' => 'required|max:255',
            'app_version' =>  'required|max:255',
            'task_id' =>  'required'
        ]);
 
        if ($validator->fails()) {
            return response()->json(['responseCode' => '5555', //gagal validasi
                                     'responseDesc' => $validator->errors()]
                                    );
        }

        try {

            $dt = DeleteTaskApp::where([
                ['id',$request->id]
             
               
            ])->first();
            $dt->app_name  =  $request->app_name;
            $dt->package_name = $request->package_name;
            $dt->app_version = $request->app_version;
            $dt->task_id = $request->task_id;
           

            if ($dt->save()) {
                return response()->json(['responseCode' => '0000', //sukses update
                                          'responseDesc' => 'Delete Task App updated successfully',
                                        
                                        ]);
            }
        } catch (\Exception $e) {
            return response()->json([
            'responseCode' => '3333', 
            'responseDesc' => "Delete Task App Update Failure"
        ]);
        }
    }
    
    public function show(Request $request){
        try {
            $t = DeleteTaskApp::where('id', $request->id);
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
            $t= DeleteTaskApp::where('id','=',$request->id);
             $cn = $t->get()->count();
             if( $cn > 0)
             {
                $update_t = $t->first();
                $current_date_time = \Carbon\Carbon::now()->toDateTimeString();
                $update_t->delete_ts = $current_date_time; 
                $update_t->deleted_by = "admin";//Auth::user()->id 
                if ($update_t->save()) {
                     return response()->json(['responseCode' => '0000', 'responseDesc' => 'Download Task App deleted successfully']);
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
