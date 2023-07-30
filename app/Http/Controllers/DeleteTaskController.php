<?php

namespace App\Http\Controllers;
use App\Models\DeleteTask;
use App\Models\DeleteTaskApp; 
use App\Models\DeleteTaskTerminalGroupLink;
use App\Models\DeleteTaskTerminalLink;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;


class DeleteTaskController extends Controller
{
    public function list(Request $request){

        try {
                $pageSize = ($request->pageSize)?$request->pageSize:10;
                $pageNum = ($request->pageNum)?$request->pageNum:1;

                $query = DeleteTask::select(
                    'tms_delete_task.id',
                    'tms_delete_task.name',
                    'tms_delete_task as deleteTime',
                    'tms_delete_task.status',
                    'tms_delete_task.version',
                    'tms_delete_task.created_by as createdBy',
                    'tms_delete_task.create_ts as createdTime',
                    'tms_delete_task.updated_by as lastUpdatedBy',
                    'tms_delete_task.update_ts as lastUpdatedTime'
                )
                ->whereNull('tms_delete_task.deleted_by');
                
               /*  if($request->terminalId){

                } */
                    
                $count = $query->get()->count();
            
                $results = $query->offset(($pageNum-1) * $pageSize) 
                ->limit($pageSize)->orderBy('tms_delete_task.create_ts', 'DESC')
                ->get()->makeHidden(['tms_delete_task.deleted_by','tms_delete_task.delete_ts']);
                
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
            'name' => 'required|max:50|unique:tms_download_task',
            'deleteTime' => 'date_format:Y-m-d H:i:s',
            'applications' =>  'required',
            'terminalGroupIds' => 'required_without:terminalIds',
            'terminalIds' => 'required_without:terminalGroupIds',
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

            $dt = new DeleteTask();
            $dt->version = 1; 
            $dt->name  =  $request->name;
            $dt->delete_time = $request->deleteTime;
            $dt->status = 0;
            $dt->old_status = 0;
            $dt->tenant_id = $request->header('Tenant-id');
            $dt->save();

            $dataSet = [];
            
            foreach ($request->applications as $app) {
                    $dataSet[] = [
                        'app_name'            => $app['appName'],
                        'package_name'    => $app['packageName'],
                        'app_version'         => $app['appVersion'],
                        'task_id' => $dt->id
                    ];
                    // $ob = new DeleteTaskApp();
                    // $ob->app_name = $app['appName'];
                    // $ob->package_name = $app['packageName'];
                    // $ob->app_version = $app['appVersion'];
                    // $ob->task_id = $dt->id;
                    // $ob->save();
                    
                }
                
            DeleteTaskApp::insert($dataSet);

            // if($request->terminalGroupIds){
            //     $dataTerminalGroup = [];
            //     foreach ($request->terminalGroupIds as $terminalGroupIds) {
            //         $dataTerminalGroup []= [
            //             'delete_task_id' => $dt->id,
            //             'group_id' => $terminalGroupIds
            //         ];
                    
            //     }
            //     DeleteTaskTerminalGroupLink::insert($dataTerminalGroup);
            // }
                    
            // if($request->terminalIds){
                
            //     $dataTerminal = [];
            //     foreach ($request->terminalIds as $terminalIds) {
            //         $dataTerminal[] =[
            //         'delete_task_id' => $dt->id,
            //         'terminal_id' => $terminalIds
            //         ];
                    
            //     }
            //     DeleteTaskTerminalLink::insert($dataTerminal);
            // }

            DB::commit();

            $a  =   [   
                "responseCode"=>"0000",
                "responseDesc"=>"OK",
                "generatedId" =>  $dt->id
                ];    
            return $this->headerResponse($a,$request);

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
            $t = DeleteTask::
            select(
                'id',
                'name',
                'delete_time as deleteTime',
                'status',
                'version',
                'created_by as createdBy',
                'create_ts as createdTime',
                'updated_by as lastUpdatedBy',
                'update_ts as lastUpdatedTime'                
                )
            -> 
            where('id', $request->id)->whereNull('deleted_by')->with(['applications' => function ($query) {
                $query->select('id', 'package_name as packageName','app_name as name','app_version as appVersion');
             
            }])
            ;
            if($t->get()->count()>0)
            {
                $t =  $t->get()
                ->makeHidden(['deleted_by', 'delete_ts']);
                $a=["responseCode"=>"0000",
                "responseDesc"=>"OK",
                 "data" => $t
                ];    
            return $this->headerResponse($a,$request);
            }
            else
            {
           
                $a=["responseCode"=>"0400",
                "responseDesc"=>"Data Not Found",
                 "data" => $t
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
