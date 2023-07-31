<?php

namespace App\Http\Controllers;
use App\Models\DeleteTask;
use App\Models\DeleteTaskApp; 
use App\Models\DeleteTaskTerminalGroupLink;
use App\Models\DeleteTaskTerminalLink;
use App\Models\TerminalGroup;
use App\Models\Application;
use App\Models\Terminal;
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
                
                $query = DeleteTask
                
                ::select(
                    'tms_delete_task.id',
                    'tms_delete_task.name as name',
                    'tms_delete_task.delete_time as deleteTime',
                    'tms_delete_task.status',
                    'tms_delete_task.version',
                    'tms_delete_task.created_by as createdBy',
                    'tms_delete_task.create_ts as createdTime',
                    'tms_delete_task.updated_by as lastUpdatedBy',
                    'tms_delete_task.update_ts as lastUpdatedTime'
                )
               
                ->where('tms_delete_task.tenant_id',$request->header('Tenant-id'))
                ->whereNull('tms_delete_task.deleted_by');

                if($request->name != ''){
                    $query -> where('tms_delete_task.name', 'ILIKE', '%' . $request->name . '%');
                }

                if($request->sn != ''){
                    
                    $q = DeleteTaskTerminalLink::select('delete_task_id')
                    ->whereIn('terminal_id',Terminal::select('id')
                    ->where('sn','ILIKE', '%' . $request->sn . '%'));
                    $query->whereIn('tms_delete_task.id',$q)
                    ->Join('tms_delete_task_terminal_link', 
                    'tms_delete_task.id', '=', 
                    'tms_delete_task_terminal_link.delete_task_id')
                    ->groupBy('tms_delete_task.id');
                }

                if($request->packageName != ''){
                    $rp = $request->packageName;
                  
                    $query->whereHas('deletetaskapp', function($q) use ($rp) {
                        $q->where('package_name', $rp);
                    });
                }

                if($request->appName != ''){
                 
                    $rp = $request->appName;
                  
                    $query->whereHas('deletetaskapp', function($q) use ($rp) {
                        $q->where('app_name', $rp);
                    });
                }

                if($request->terminalId != ''){
                    $rp = $request->terminalId;
                    $query->whereHas('deletetaskTerminalLink', function($q) use ($rp) {
                        $q->where('terminal_id', $rp);
                    });
                }

                if($request->terminalGroupId != ''){
                    $rp = $request->terminalGroupId;
                    $query->whereHas('deletetaskTerminalGroupLink', function($q) use ($rp) {
                        $q->where('group_id', $rp);
                    });
                }

                    
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
            'name' => 'required|max:50|unique:tms_delete_task',
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
                        'id' => Str::uuid()->toString(),
                        'app_name'            => $app['appName'],
                        'package_name'    => $app['packageName'],
                        'app_version'         => $app['appVersion'],
                        'task_id' => $dt->id
                        
                    ];
                  
                }
                
            DeleteTaskApp::insert($dataSet);
            //terminalGroupIds
            if($request->terminalGroupIds){
                $dataTerminalGroup = [];
                foreach ($request->terminalGroupIds as $terminalGroupIds) {
                   
                    $dataTerminalGroup []= [
                        'delete_task_id' => $dt->id,
                        'group_id' => $terminalGroupIds
                    ];
                    
                }
                DeleteTaskTerminalGroupLink::insert($dataTerminalGroup);
            }
                    
            if($request->terminalIds){
                
                $dataTerminal = [];
                foreach ($request->terminalIds as $terminalIds) {
                    $dataTerminal[] =[
                    'delete_task_id' => $dt->id,
                    'terminal_id' => $terminalIds
                    ];
                    
                } //
                DeleteTaskTerminalLink::insert($dataTerminal);
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

        $check = DeleteTask::where([
            ['id',$request->id],
            ['name',$request->name],
            ['tenant_id', $request->header('Tenant-id')]
        
        ])->first();

        
        $appa = [
            'name' => 'required',
            'deleteTime' => 'date_format:Y-m-d H:i:s',
            'applications' =>  'required',
            'terminalGroupIds' => 'required_without:terminalIds',
            'terminalIds' => 'required_without:terminalGroupIds',
          
        ];
        
        if(!empty($check)){
     
            $appa['name'] = 'required|max:50|unique:tms_delete_task';
           
        }
        $validator = Validator::make($request->all(),$appa);
 
        if ($validator->fails()) {
            $a  =   [   
                "responseCode"=>"5555",
                "responseDesc"=>$validator->errors()
                ];    
            return $this->headerResponse($a,$request);
        }
        DB::beginTransaction();
        try {

            $dt = DeleteTask::where([
                ['id',$request->id],
                ['version',$request->version],
                ['status',1],
                ['tenant_id', $request->header('Tenant-id')],
               
            ])->first();
            
            if(empty($dt)){

                $a=["responseCode"=>"0500",
                    "responseDesc"=>"Status data not for update",
                    "data" => $dt
                ];    
                return $this->headerResponse($a,$request);    
            }

            $dt->version = $request->version + 1;
            $dt->name  =  $request->name;
            $dt->delete_time = $request->deleteTime;
            //$dta->status = 0;
            //$dta->old_status = 0;
            $dt->tenant_id = $request->header('Tenant-id');
            $dt->save();

            
            $dataSet = [];
            foreach ($request->applications as $app) {
                    $dataSet[] = [
                        'id' => Str::uuid()->toString(),
                        'app_name'            => $app['appName'],
                        'package_name'    => $app['packageName'],
                        'app_version'         => $app['appVersion'],
                        'task_id' => $dt->id
                        
                    ];
                  
                }
            DeleteTaskApp::where('task_id',$request->id)->delete();
            DeleteTaskApp::insert($dataSet);

            if($request->terminalGroupIds){
                $dataTerminalGroup = [];
                foreach ($request->terminalGroupIds as $terminalGroupIds) {
                   
                    $dataTerminalGroup []= [
                        'delete_task_id' => $dt->id,
                        'group_id' => $terminalGroupIds
                    ];
                    
                }
                DeleteTaskTerminalGroupLink::where('delete_task_id',$request->id)->delete();
                DeleteTaskTerminalGroupLink::insert($dataTerminalGroup);
            }

            if($request->terminalIds){
                
                $dataTerminal = [];
                foreach ($request->terminalIds as $terminalIds) {
                    $dataTerminal[] =[
                    'delete_task_id' => $dt->id,
                    'terminal_id' => $terminalIds
                    ];
                    
                } //
                DeleteTaskTerminalLink::where('delete_task_id',$request->id)->delete();
                DeleteTaskTerminalLink::insert($dataTerminal);
            }


            DB::commit();
            $a  = [   
                "responseCode"=>"0000",
                "responseDesc"=>"OK"
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
    
    public function show(Request $request){
        try {
            $t = DeleteTask::
            select(
                [
                'id',
                'name',
                'delete_time as deleteTime',
                'status',
                'version',
                'created_by as createdBy',
                'create_ts as createdTime',
                'updated_by as lastUpdatedBy',
                'update_ts as lastUpdatedTime',
               
                           
                ])
             
             //->with(['applications' => function ($t) {
                //$t->select('id', 'package_name as packageName','app_name as name','app_version as appVersion')->pluck('id');
                //}])
            
            ->where('id', $request->id)->whereNull('deleted_by')->with('applications');
           
            
            if($t->get()->count()>0)
            {
                $t =  $t->get()
                ->makeHidden(['deleted_by', 'delete_ts']);

                $t = $t ->map(function ($item){
                    return collect([
                        'id' => $item->id,
                        'name' => $item->name,
                        'deleteTime' => $item->deleteTime,
                        'applications' => $item->applications->map(function ($details){
                            return [
                                'id' => $details->id,
                                'packageName' => $details->package_name,
                                'name' => $details->app_name,
                                'appVersion' =>  $details->app_version,
                            ];
                         }),
                        'status' => $item->status,
                        'version' => $item->version,
                        'createdBy' => $item->createdBy,
                        'createdTime' => $item->createdTime,
                        'lastUpdatedBy' =>  $item->lastUpdatedBy,
                        'lastUpdatedTime'  =>  $item->lastUpdatedTime,

                        
                    ]);
                });

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
            ->where('version','=',$request->version)
            ->where('tenant_id','=',$request->header('Tenant-id'));
             $cn = $t->get()->count();
             if( $cn > 0)
             {
                $update_t = $t->first();

                if($t->get()[0]['status'] == '2' )
                {
                    $a=["responseCode"=>"0500",
                        "responseDesc"=>"Status data not for update"
                    ];    
                     return $this->headerResponse($a,$request);   
                }
               
                $this->deleteAction($request, $update_t);
                if ($update_t->save()) {
                    DB::commit();
                      $a  =   [   
                        "responseCode"=>"0000",
                        "responseDesc"=>"OK"
                        ];    
                    return $this->headerResponse($a,$request);
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

    public function listTerminalGroup(Request $request)
    {
       
        try {
            $pageSize = ($request->pageSize)?$request->pageSize:10;
            $pageNum = ($request->pageNum)?$request->pageNum:1;

                $query = DeleteTask::select(
                    'tms_delete_task.id',
                    'tms_terminal.model_id as id',
                    'tms_delete_task.name'
                )
                ->where('tms_delete_task.id',$request->id)->whereNull('tms_delete_task.deleted_by')
                ->join('tms_delete_task_terminal_group_link', 'tms_delete_task.id', '=', 'tms_delete_task_terminal_group_link.delete_task_id')
                ->join('tms_terminal_group', 'tms_terminal_group.id', '=', 'tms_delete_task_terminal_group_link.group_id')
                ->join('tms_terminal_group_link', 'tms_terminal_group_link.terminal_group_id', '=', 'tms_terminal_group.id')
                ->join('tms_terminal', 'tms_terminal.id', '=', 'tms_terminal_group_link.terminal_id');

            
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

    public function listTerminal(Request $request){

        try {
            $pageSize = ($request->pageSize)?$request->pageSize:10;
            $pageNum = ($request->pageNum)?$request->pageNum:1;

                $query = DeleteTask::select(
                    'tms_delete_task.id',
                    'tms_terminal.model_id as id',
                    'tms_terminal.sn',
                )
                ->where('tms_delete_task.id',$request->id)->whereNull('tms_delete_task.deleted_by')
                ->join('tms_delete_task_terminal_link', 'tms_delete_task.id', '=', 'tms_delete_task_terminal_link.delete_task_id')
                ->join('tms_terminal', 'tms_terminal.id', '=', 'tms_delete_task_terminal_link.terminal_id')
                ;

            
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

    public function history(Request $request){

        try {
            $pageSize = ($request->pageSize)?$request->pageSize:10;
            $pageNum = ($request->pageNum)?$request->pageNum:1;

                $query = DeleteTask::select(
                    'tms_terminal.model_id as id',
                    'tms_delete_task.status as activity',
                    'tms_terminal.sn',
                    'tms_delete_task.update_ts as lastUpdateTime'
                )
                ->where('tms_delete_task.id',$request->id)
                ->where('tms_delete_task.tenant_id',$request->header('Tenant-id'))
                ->whereNull('tms_delete_task.deleted_by')
                ->join('tms_delete_task_terminal_link', 'tms_delete_task.id', '=', 'tms_delete_task_terminal_link.delete_task_id')
                ->join('tms_terminal', 'tms_terminal.id', '=', 'tms_delete_task_terminal_link.terminal_id');
                

                if($request->sn != ''){
                    $query->where('tms_terminal.sn', 'ILIKE', '%' . $request->sn . '%');
                }
            
                $count = $query->get()->count();
            
                $results = $query->offset(($pageNum-1) * $pageSize) 
                ->limit($pageSize)
                ->orderBy('tms_delete_task.create_ts', 'DESC')
                ->get()
                ->makeHidden(['tms_delete_task.deleted_by','tms_delete_task.delete_ts']);
                
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

    public function terminalHistory(Request $request){

        try {
            $pageSize = ($request->pageSize)?$request->pageSize:10;
            $pageNum = ($request->pageNum)?$request->pageNum:1;

                $query = DeleteTask::select(
                    'tms_terminal.model_id as id',
                    'tms_delete_task.status as activity',
                    'tms_terminal.sn',
                    'tms_delete_task.update_ts as lastUpdateTime'
                )
                ->where('tms_delete_task.id',$request->id)->where('tms_delete_task.tenant_id',$request->header('Tenant-id'))->whereNull('tms_delete_task.deleted_by')
                ->join('tms_delete_task_terminal_link', 'tms_delete_task.id', '=', 'tms_delete_task_terminal_link.delete_task_id')
                ->join('tms_terminal', 'tms_terminal.id', '=', 'tms_delete_task_terminal_link.terminal_id')
                ;

                if($request->terminalId != ''){
                    $query->where('tms_terminal.id', 'ILIKE', '%' . $request->terminalId . '%');
                }
            
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

    /**
     * Summary of cancel
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancel(Request $request){
        DB::beginTransaction();
        try {
            $t= DeleteTask::where('id','=',$request->id)
            ->where('version','=',$request->version)
            ->where('tenant_id',$request->header('Tenant-id'))
            ->whereIn('status',[1,2])
            ->whereNull('deleted_by');
             $cn = $t->get()->count();
             if( $cn > 0)
             {
                $update_t = $t->first();
                $update_t->version = $request->version + 1; 
                $update_t->status = 3; 
                
                $update_t->save();
                DeleteTaskApp::where('task_id', $request->id)->delete();
                DeleteTaskTerminalGroupLink::where('delete_task_id', $request->id)->delete();
                DeleteTaskTerminalLink::where('delete_task_id', $request->id)->delete();

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
                "responseDesc"=>"Status data not for cancel"
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


    
}
