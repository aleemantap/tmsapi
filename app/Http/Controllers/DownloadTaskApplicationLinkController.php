<?php

namespace App\Http\Controllers;
use App\Models\DownloadTaskApplicationLink;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;


class TerminalController extends Controller
{
    public function list(Request $request){

        try {
                $pageSize = $request->pageSize;
                $pageNum = $request->pageNum;
                $download_task_id = $request->download_task_id;
                $application_id = $request->application_id;
               
                
                $query = DownloadTaskApplicationLink::whereNotNull('download_task_id');

                 
                if($request->download_task_id != '')
                {
                    $query->where('download_task_id', $request->download_task_id);
                }
                if($request->application_id != '')
                {
                    $query->where('application_id', $request->application_id);
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
            'download_task_id' => 'required',
            'application_id' => 'required',
        
        ]);
 
        if ($validator->fails()) {
            return response()->json(['responseCode' => '5555', //gagal validasi
                                     'responseDesc' => $validator->errors()]
                                    );
        }
      
        try {

            $dt = new DownloadTaskApplicationLink();
            $dt->download_task_id = $request->download_task_id;
            $dt->application_id = $request->application_id;
           
            if ($dt->save()) {
                return response()->json(['responseCode' => '0000', //sukses insert
                                          'responseDesc' => 'Download Task Application Link  created successfully',
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
            'application_id' => 'required',
        ]);
 
        if ($validator->fails()) {
            return response()->json(['responseCode' => '5555', //gagal validasi
                                     'responseDesc' => $validator->errors()]
                                    );
        }

        try {

            $dt = DownloadTaskApplicationLink::where([
                ['download_task_id',$request->download_task_id],
                ['application_id',$request->application_id]
               
            ])->first();

         
            $dt->download_task_id = $request->download_task_id;
            $dt->application_id = $request->application_id;

            if ($t->save()) {
                return response()->json(['responseCode' => '0000', //sukses update
                                          'responseDesc' => 'Download Task Application Link  updated successfully',
                                        
                                        ]);
            }
        } catch (\Exception $e) {
            return response()->json([
            'responseCode' => '3333', 
            'responseDesc' => "Download Task Application Link Update Failure"
        ]);
        }
    }
    
    public function show(Request $request){
        try {
            $t = DownloadTaskApplicationLink::where('download_task_id', $request->download_task_id)->where('application_id', $request->application_id);
            
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
            $t = DownloadTaskApplicationLink::where('download_task_id', $request->download_task_id)->where('application_id', $request->application_id);
            $cn = $t->get()->count();
             if( $cn > 0)
             {
                $update_t = $t->first();
                $current_date_time = \Carbon\Carbon::now()->toDateTimeString();
                $update_t->delete_ts = $current_date_time; 
                $update_t->deleted_by = "admin";//Auth::user()->id 
                if ($update_t->save()) {
                     return response()->json(['responseCode' => '0000', 'responseDesc' => 'Download Task Application Link deleted successfully']);
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

    public function restart(Request $request){

    }

    public function lockUnlock(Request $request){

    }


    
}
