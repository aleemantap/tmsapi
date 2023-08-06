<?php

namespace App\Http\Controllers;
use App\Models\Application;
use App\Models\DownloadTask;
use App\Models\DownloadTaskApplicationLink;
use App\Models\DownloadTaskTerminalGroupLink;
use App\Models\DownloadTaskTerminalLink;
use App\Models\Terminal;
use App\Models\TerminalGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;


class DownloadTaskController extends Controller
{
    /**
     * Summary of listTerminal
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request){

        try {
            $pageSize = ($request->pageSize)?$request->pageSize:10;
            $pageNum = ($request->pageNum)?$request->pageNum:1;

                $query = DownloadTask::select(
                    'tms_download_task.id',
                    'tms_download_task.name',
                    'tms_download_task.status',
                    'tms_download_task.version',
                    'tms_download_task.created_by as createdBy',
                    'tms_download_task.create_ts as createdTime',
                    'tms_download_task.updated_by as lastUpdatedBy',
                    'tms_download_task.update_ts as lastUpdatedTime',
                    //'tms_download_task_terminal_link.terminal_id'
                )
                ->where('tms_download_task.tenant_id',$request->header('Tenant-id'))
                ->whereNull('tms_download_task.deleted_by');
                
                
               if($request->terminalId != ''){
                    $q = DownloadTaskTerminalLink::select('download_task_id')->where('terminal_id',$request->terminalId);
                    $query->whereIn('tms_download_task.id',$q)
                    ->Join('tms_download_task_terminal_link', 'tms_download_task.id', '=', 'tms_download_task_terminal_link.download_task_id')
                    ->groupBy('tms_download_task.id');
                }
               
               if($request->terminalGroupId != ''){
                    $q = DownloadTaskTerminalGroupLink::select('download_task_id')->where('group_id',$request->terminalGroupId);
                    $query->whereIn('tms_download_task.id',$q)
                    ->Join('tms_download_task_terminal_group_link', 'tms_download_task.id', '=', 'tms_download_task_terminal_group_link.download_task_id')
                    ->groupBy('tms_download_task.id');
                }
               
               if($request->applicationId != ''){
                    $q = DownloadTaskApplicationLink::select('download_task_id')->where('application_id',$request->applicationId);
                    $query->whereIn('tms_download_task.id',$q)
                    ->Join('tms_download_task_application_link', 'tms_download_task.id', '=', 'tms_download_task_application_link.download_task_id')
                    ->groupBy('tms_download_task.id');
                }
               
                if($request->sn != ''){
                    
                    $q = DownloadTaskTerminalLink::select('download_task_id')->whereIn('terminal_id',Terminal::select('id')->where('sn','ILIKE', '%' . $request->sn . '%'));
                    $query->whereIn('tms_download_task.id',$q)
                    ->Join('tms_download_task_terminal_link', 'tms_download_task.id', '=', 'tms_download_task_terminal_link.download_task_id')
                    ->groupBy('tms_download_task.id');
                }

                if($request->packageName != ''){
                    $q = DownloadTaskApplicationLink::select('download_task_id')->whereIn('application_id',Application::select('id')->where('package_name','ILIKE','%' . $request->packageName . '%'));
                    $query->whereIn('tms_download_task.id',$q)
                    ->Join('tms_download_task_application_link', 'tms_download_task.id', '=', 'tms_download_task_application_link.download_task_id')
                    ->groupBy('tms_download_task.id');
                }
                if($request->name != ''){
                    $query -> where('name', 'ILIKE', '%' . $request->name . '%');
                }

                $count = $query->get()->count();
            
                $results = $query->offset(($pageNum-1) * $pageSize) 
                ->limit($pageSize)->orderBy('tms_download_task.create_ts', 'DESC')
                ->get()->makeHidden(['tms_download_task.deleted_by','tms_download_task.delete_ts']);
                
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
                    'rows' => null
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
    public function listTerminal(Request $request){

        try {
            $pageSize = ($request->pageSize)?$request->pageSize:10;
            $pageNum = ($request->pageNum)?$request->pageNum:1;

                $query = DownloadTask::select(
                    'tms_download_task.id',
                    'tms_terminal.model_id as id',
                    'tms_terminal.sn',
                )
                ->where('tms_download_task.id',$request->id)->whereNull('tms_download_task.deleted_by')
                ->join('tms_download_task_terminal_link', 'tms_download_task.id', '=', 'tms_download_task_terminal_link.download_task_id')
                ->join('tms_terminal', 'tms_terminal.id', '=', 'tms_download_task_terminal_link.terminal_id')
                ;

            
                $count = $query->get()->count();
            
                $results = $query->offset(($pageNum-1) * $pageSize) 
                ->limit($pageSize)->orderBy('tms_download_task.create_ts', 'DESC')
                ->get()->makeHidden(['tms_download_task.deleted_by','tms_download_task.delete_ts']);
                
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
                    'rows' => null
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
    public function listGroup(Request $request){

        try {
            $pageSize = ($request->pageSize)?$request->pageSize:10;
            $pageNum = ($request->pageNum)?$request->pageNum:1;

                $query = DownloadTask::select(
                    'tms_download_task.id',
                    'tms_terminal.model_id as id',
                    'tms_download_task.name'
                )
                ->where('tms_download_task.id',$request->id)->whereNull('tms_download_task.deleted_by')
                ->join('tms_download_task_terminal_group_link', 'tms_download_task.id', '=', 'tms_download_task_terminal_group_link.download_task_id')
                ->join('tms_terminal_group', 'tms_terminal_group.id', '=', 'tms_download_task_terminal_group_link.group_id')
                ->join('tms_terminal_group_link', 'tms_terminal_group_link.terminal_group_id', '=', 'tms_terminal_group.id')
                ->join('tms_terminal', 'tms_terminal.id', '=', 'tms_terminal_group_link.terminal_id')
                ;

            
                $count = $query->get()->count();
            
                $results = $query->offset(($pageNum-1) * $pageSize) 
                ->limit($pageSize)->orderBy('tms_download_task.create_ts', 'DESC')
                ->get()->makeHidden(['tms_download_task.deleted_by','tms_download_task.delete_ts']);
                
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
                    'rows' => null
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
            'name' => 'required|max:50',
            'publishTimeType' => 'in:1,2',
            'publishTime' => ['required_if:publishTimeType,2','date_format:Y-m-d H:i:s'],
            'downloadTimeType' => 'required|in:1,2',
            'downloadTime' => ['required_if:publishTimeType,2','date_format:Y-m-d H:i:s'],
            'installationTimeType' =>  'required|in:1,2',
            'installationTime' =>  ['required_if:installationTimeType,2','date_format:Y-m-d H:i:s'],
            'installationNotification' => 'required|in:1,2',
            'applicationIds' => 'required',
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

            $dt = new DownloadTask();
            $dt->version = 1; 
            $dt->name  =  $request->name;
            $dt->publish_time_type = $request->publishTimeType;
            $dt->publish_time = $request->publishTime;
            $dt->installation_time_type = $request->installationTimeType;
            $dt->installation_time = $request->installationTime;
            $dt->installation_notification = $request->installationNotification;
            $dt->status = 0;
            $dt->download_time_type = $request->downloadTimeType;
            $dt->tenant_id = $request->header('Tenant-id');
            //$dt->download_url = $request->download_url;
            $this->saveActioin($request,$dt);
            $dt->save();
          
            
                $dataSet = [];
                foreach ($request->applicationIds as $applicationIds) {
                        $dataSet[] = [
                            'download_task_id'  => $dt->id,
                            'application_id'    => $applicationIds
                        ];
                    }
                    
                DownloadTaskApplicationLink::insert($dataSet);
                
          

            if($request->terminalGroupIds){
                $dataTerminalGroup = [];
                foreach ($request->terminalGroupIds as $terminalGroupIds) {
                    $dataTerminalGroup []= [
                        'download_task_id' => $dt->id,
                        'group_id' => $terminalGroupIds
                    ];
                    
                }
                DownloadTaskTerminalGroupLink::insert($dataTerminalGroup);
            }
                    
            if($request->terminalIds){
                
                $dataTerminal = [];
                foreach ($request->terminalIds as $terminalIds) {
                    $dataTerminal[] =[
                    'download_task_id' => $dt->id,
                    'terminal_id' => $terminalIds
                    ];
                    
                }
                DownloadTaskTerminalLink::insert($dataTerminal);
            }
                    
             
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

        $check = DownloadTask::where([
            ['id',$request->id],
            ['version',$request->version]
           
        ])->first();
        
        $DownloadTask = [
            'version' => 'required|numeric|max:32',
            'id' => 'required',
            'name' => 'required',
            'publishTimeType' => 'in:1,2',
            'publishTime' => ['required_if:publishTimeType,2','date_format:Y-m-d H:i:s'],
            'downloadTimeType' => 'required|in:1,2',
            'downloadTime' => ['required_if:publishTimeType,2','date_format:Y-m-d H:i:s'],
            'installationTimeType' =>  'required|in:1,2',
            'installationTime' => ['required_if:installationTimeType,2','date_format:Y-m-d H:i:s'],
            'installationNotification' => 'required|in:1,2',
            'applicationIds' => 'required',
            'terminalGroupIds' => 'required_without:terminalIds',
            'terminalIds' => 'required_without:terminalGroupIds',
           
        ];
        
        if(!$check){
         
            $DownloadTask['name'] = 'required|max:100';
        }

        $validator = Validator::make($request->all(), $DownloadTask);

        if ($validator->fails()) {
            $a  =   [   
                "responseCode"=>"5555",
                "responseDesc"=>$validator->errors()
                ];    
            return $this->headerResponse($a,$request);
        }

        DB::beginTransaction();
        try {

            $dt = DownloadTask::where([
                ['id',$request->id],
                ['version',$request->version],
                ['tenant_id',$request->header('Tenant-id')]
               
            ])->first();

            $dtc = DownloadTask::where([
                ['id',$request->id],
                ['version',$request->version],
                ['tenant_id',$request->header('Tenant-id')],
                ['status','!=',1],
               
            ]);
            
            $cnt = $dtc->get()->count();
            
            if($dtc->whereNull('deleted_by')->get()->count()==0){
                $a=["responseCode"=>"0400",
                    "responseDesc"=>"Data Not Found"
                    ];    
                return $this->headerResponse($a,$request);
                }
            

            if($cnt>0){
                $dt->version = $request->version + 1;
                $dt->publish_time_type = $request->publishTimeType;
                $dt->publish_time = $request->publishTime;
                $dt->download_time_type = $request->downloadTimeType;
                $dt->download_time = $request->downloadTime;
                $dt->installation_time_type = $request->installationTimeType;
                $dt->installation_time = $request->installationTime;
                $dt->installation_notification = $request->installationNotification;
                $this->updateActioin($request, $dt);
                $dt->save();
                

                DownloadTaskApplicationLink::where('download_task_id', $dt->id)->delete();

                $dataSet = [];
                foreach ($request->applicationIds as $applicationIds) {
                        $dataSet[] = [
                            'download_task_id'  => $dt->id,
                            'application_id'    => $applicationIds
                        ];
                    }
                    
                DownloadTaskApplicationLink::insert($dataSet);
                
          

            if($request->terminalGroupIds){
                DownloadTaskTerminalGroupLink::where('download_task_id', $dt->id)->delete();
                $dataTerminalGroup = [];
                foreach ($request->terminalGroupIds as $terminalGroupIds) {
                    $dataTerminalGroup []= [
                        'download_task_id' => $dt->id,
                        'group_id' => $terminalGroupIds
                    ];
                    
                }
                DownloadTaskTerminalGroupLink::insert($dataTerminalGroup);
            }
                    
            if($request->terminalIds){
                
                DownloadTaskTerminalLink::where('download_task_id', $dt->id)->delete();
                $dataTerminal = [];
                foreach ($request->terminalIds as $terminalIds) {
                    $dataTerminal[] =[
                    'download_task_id' => $dt->id,
                    'terminal_id' => $terminalIds
                    ];
                    
                }
                DownloadTaskTerminalLink::insert($dataTerminal);
            }

            DB::commit();
                $a  =   [   
                "responseCode"=>"0000",
                "responseDesc"=>"OK"
                ];    
            return $this->headerResponse($a,$request);
        

            }else{
                $a=["responseCode"=>"0500",
                "responseDesc"=>"Status data not for update"
                ];    
                return $this->headerResponse($a,$request);
            }
        } catch (\Exception $e) {
            $a  =   [   
                "responseCode"=>"3333",
                "responseDesc"=>$e->getMessage()
                ];    
            return $this->headerResponse($a,$request);
        }
    }
    
    /**
     * Summary of show
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required|max:36'
        ]);
 
        if ($validator->fails()) {
            $a  =   [   
                "responseCode"=>"5555",
                "responseDesc"=>$validator->errors()
                ];    
            return $this->headerResponse($a,$request);
        }
        
        try {
            $t = DownloadTask::
            
           select(
                'id',
                'name',
                'publish_time_type as publishTimeType',
                'publish_time as publishTime',
                'download_time_type as downloadTimeType',
                'download_time as downloadTime',
                'installation_time_type as installationTimeType',
                'installation_time as installationTime',
                'installation_notification as installationNotification',
                'status',
                'version',
                'created_by as createdBy',
                'create_ts as createdTime',
                'updated_by as lastUpdatedBy',
                'update_ts as lastUpdatedTime'                
                )
            -> 
            where('id', $request->id)->whereNull('deleted_by')->with(['applications' => function ($query) {
                $query->select('id', 'package_name as packageName','name','app_version as appVersion');
             
            }])
            ;
            if($t->get()->count()>0)
            {
                $t =  $t->get()
                /* ->with(['application' => function ($query) {
                    $query->select('id', 'package_name','name','app_version');
                 
                }]) */
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
                 "data" => null
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


    /**
     * Summary of delete
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request){
        DB::beginTransaction();
        try {
            $t= DownloadTask::where('id','=',$request->id)
            ->where('version','=',$request->version)
            ->where('tenant_id',$request->header('Tenant-id'))
            ->where('status','=',2);
             $cn = $t->get()->count();

             if($t->whereNull('deleted_by')->get()->count()==0){
                $a=["responseCode"=>"0400",
                "responseDesc"=>"Data Not Found"
                ];    
                return $this->headerResponse($a,$request);  
             }

             if( $cn > 0)
             {
                
                $this->deleteAction($request,$t);

                DownloadTaskApplicationLink::where('download_task_id', $request->id)->delete();
                DownloadTaskTerminalGroupLink::where('download_task_id', $request->id)->delete();
                DownloadTaskTerminalLink::where('download_task_id', $request->id)->delete();

                DB::commit();
                $a  =   [   
                    "responseCode"=>"0000",
                    "responseDesc"=>"OK"
                    ];    
                return $this->headerResponse($a,$request);


            }
             else
            {
                $a=["responseCode"=>"0500",
                "responseDesc"=>"Status data not for delete"
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

    /**
     * Summary of cancel
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancel(Request $request){
        DB::beginTransaction();
        try {
            $t= DownloadTask::where('id','=',$request->id)
            ->where('version','=',$request->version)
            ->where('tenant_id',$request->header('Tenant-id'))
            ->whereIn('status',[1,2]);
            
             $cn = $t->get()->count();

             if($t->whereNull('deleted_by')->get()->count()==0){
                $a=["responseCode"=>"0400",
                "responseDesc"=>"Data Not Found"
                ];    
                return $this->headerResponse($a,$request);  
             }
             if( $cn > 0)
             {
                $update_t = $t->first();
                $update_t->version = $request->version + 1; 
                $update_t->status = 3; 
                //$update_t->update_ts = \Carbon\Carbon::now()->toDateTimeString();
                $this->updateAction($update_t);
                $update_t->save();
                DownloadTaskApplicationLink::where('download_task_id', $request->id)->delete();
                DownloadTaskTerminalGroupLink::where('download_task_id', $request->id)->delete();
                DownloadTaskTerminalLink::where('download_task_id', $request->id)->delete();

                DB::commit();
                $a  =   [   
                    "responseCode"=>"0000",
                    "responseDesc"=>"OK"
                    ];    
                return $this->headerResponse($a,$request);


            }
             else
            {
                $a=["responseCode"=>"0500",
                "responseDesc"=>"Status data not for delete"
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
    public function history(Request $request){

        try {
            $pageSize = ($request->pageSize)?$request->pageSize:10;
            $pageNum = ($request->pageNum)?$request->pageNum:1;

                $query = DownloadTask::select(
                    'tms_terminal.model_id as id',
                    'tms_download_task.status as activity',
                    'tms_terminal.sn',
                    'tms_download_task.update_ts as lastUpdateTime'
                )
                ->where('tms_download_task.id',$request->id)->where('tms_download_task.tenant_id',$request->header('Tenant-id'))->whereNull('tms_download_task.deleted_by')
                ->join('tms_download_task_terminal_link', 'tms_download_task.id', '=', 'tms_download_task_terminal_link.download_task_id')
                ->join('tms_terminal', 'tms_terminal.id', '=', 'tms_download_task_terminal_link.terminal_id')
                ;

                if($request->sn != ''){
                    $query->where('tms_terminal.sn', 'ILIKE', '%' . $request->sn . '%');
                }
            
                $count = $query->get()->count();
            
                $results = $query->offset(($pageNum-1) * $pageSize) 
                ->limit($pageSize)->orderBy('tms_download_task.create_ts', 'DESC')
                ->get()->makeHidden(['tms_download_task.deleted_by','tms_download_task.delete_ts']);
                
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
                    'rows' => null
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

    /**
     * Summary of terminalHistory
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function terminalHistory(Request $request){

        try {
            $pageSize = ($request->pageSize)?$request->pageSize:10;
            $pageNum = ($request->pageNum)?$request->pageNum:1;

                $query = DownloadTask::select(
                    'tms_terminal.model_id as id',
                    'tms_download_task.status as activity',
                    'tms_terminal.sn',
                    'tms_download_task.update_ts as lastUpdateTime'
                )
                ->where('tms_download_task.id',$request->id)->where('tms_download_task.tenant_id',$request->header('Tenant-id'))->whereNull('tms_download_task.deleted_by')
                ->join('tms_download_task_terminal_link', 'tms_download_task.id', '=', 'tms_download_task_terminal_link.download_task_id')
                ->join('tms_terminal', 'tms_terminal.id', '=', 'tms_download_task_terminal_link.terminal_id')
                ;

                if($request->terminalId != ''){
                    $query->where('tms_terminal.id', 'ILIKE', '%' . $request->terminalId . '%');
                }
            
                $count = $query->get()->count();
            
                $results = $query->offset(($pageNum-1) * $pageSize) 
                ->limit($pageSize)->orderBy('tms_download_task.create_ts', 'DESC')
                ->get()->makeHidden(['tms_download_task.deleted_by','tms_download_task.delete_ts']);
                
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
                    'rows' => null
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
}