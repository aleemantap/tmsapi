<?php

namespace App\Http\Controllers;
use App\Models\DownloadTaskTerminalGroupLink;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;


class DownloadTaskTerminalGroupController extends Controller
{
    public function list(Request $request){

        try {
            $pageSize = ($request->pageSize)?$request->pageSize:10;
            $pageNum = ($request->pageNum)?$request->pageNum:1;
                $download_task_id = $request->download_task_id;
                $group_id = $request->group_id;
               
                
                $query = DownloadTaskTerminalGroupLink::whereNotNull('download_task_id');

                 
                if($request->download_task_id != '')
                {
                    $query->where('download_task_id', $request->download_task_id);
                }
                if($request->group_id != '')
                {
                    $query->where('group_id', $request->group_id);
                }
               
                $count = $query->get()->count();
            
                $results = $query->offset(($pageNum-1) * $pageSize) 
                ->limit($pageSize)
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
            'download_task_id' => 'required',
            'group_id' => 'required',
        
        ]);
 
        if ($validator->fails()) {
            return response()->json(['responseCode' => '5555', //gagal validasi
                                     'responseDesc' => $validator->errors()]
                                    );
        }
      
        try {

            $dt = new DownloadTaskTerminalGroupLink();
            $dt->download_task_id = $request->download_task_id;
            $dt->group_id = $request->group_id;
           
            if ($dt->save()) {
                return response()->json(['responseCode' => '0000', //sukses insert
                                          'responseDesc' => 'Download Task Terminal Group Link  created successfully',
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
            'download_task_id' => 'required',
            'group_id' => 'required',
        ]);
 
        if ($validator->fails()) {
            return response()->json(['responseCode' => '5555', //gagal validasi
                                     'responseDesc' => $validator->errors()]
                                    );
        }

        try {

            $dt = DownloadTaskTerminalGroupLink::where([
                ['download_task_id',$request->download_task_id],
                ['group_id',$request->group_id]
               
            ])->first();

         
            $dt->download_task_id = $request->download_task_id;
            $dt->group_id = $request->group_id;

            if ($dt->save()) {
                return response()->json(['responseCode' => '0000', //sukses update
                                          'responseDesc' => 'Download Task Terminal Group Link  updated successfully',
                                        
                                        ]);
            }
        } catch (\Exception $e) {
            return response()->json([
            'responseCode' => '3333', 
            'responseDesc' => "Download Task Terminal Group Link  Update Failure"
        ]);
        }
    }
    
    public function show(Request $request){
        try {
            $t = DownloadTaskTerminalGroupLink::where('download_task_id', $request->download_task_id)->where('group_id', $request->group_id);
            
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
            $t = DownloadTaskTerminalGroupLink::where('download_task_id', $request->download_task_id)->where('application_id', $request->group_id);
            $cn = $t->get()->count();
             if( $cn > 0)
             {
                $update_t = $t->first();
                $current_date_time = \Carbon\Carbon::now()->toDateTimeString();
                $update_t->delete_ts = $current_date_time; 
                $update_t->deleted_by = "admin";//Auth::user()->id 
                if ($update_t->save()) {
                     return response()->json(['responseCode' => '0000', 'responseDesc' => 'Download Task Terminal Group Link deleted successfully']);
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
