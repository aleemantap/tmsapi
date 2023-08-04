<?php

namespace App\Http\Controllers;
use App\Models\Terminal;
use App\Models\TerminalGroupLink;
use App\Models\DeviceModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;


class TerminalController extends Controller
{
    public function list(Request $request){

        try {
            $pageSize = ($request->pageSize)?$request->pageSize:10;
            $pageNum = ($request->pageNum)?$request->pageNum:1;
                $model_id = $request->modelId;
                $merchant_id = $request->merchantId;
                $sn = $request->sn;
                $profile_id = $request->profileId;
                $id = $request->terminalId;
                
                //$query1 = TerminalGroupLink::where('terminal_group_id',$request->terminalGroupId);
                $query = Terminal::select(
                    'tms_terminal.id',
                    'tms_terminal.sn',
                    'tms_device_model.model as modelName',
                    'tms_merchant.name as merchantName',
                    'tms_device_profile.name as profileName',
                    'tms_terminal.is_locked as locked',
                    'tms_terminal.version',
                    'tms_terminal.created_by as createdBy',
                    'tms_terminal.create_ts as createdTime',
                    'tms_terminal.updated_by as lastUpdatedBy',
                    'tms_terminal.update_ts as lastUpdatedTime'
                    )
                    ->join('tms_device_model', 'tms_terminal.model_id', '=', 'tms_device_model.id')
                    ->join('tms_merchant', 'tms_terminal.merchant_id', '=', 'tms_merchant.id')
                    ->join('tms_device_profile', 'tms_terminal.profile_id', '=', 'tms_device_profile.id')
                ->whereNull('tms_terminal.deleted_by');

                 
                if($request->modelId != '')
                {
                    $query->where('model_id', 'ILIKE', '%' . $request->modelId . '%');
                }
                if($request->merchantId != '')
                {
                    $query->where('merchant_id', 'ILIKE', '%' . $request->merchantId . '%');
                }
                if($request->sn != '')
                {
                    $query->where('sn', 'ILIKE', '%' . $request->sn . '%');
                }
                if($request->profileId != '')
                {
                    $query->where('profile_id', 'ILIKE', '%' . $request->profileId . '%');
                }
                if($request->terminalId != '')
                {
                    $query->where('id', 'ILIKE', '%' . $request->terminalId . '%');
                }
                
                
                if($request->terminalGroupId != '')
                {
                    $query->whereIn('tms_terminal.id', TerminalGroupLink::select('terminal_id')->where('terminal_group_id',$request->terminalGroupId));
                    
                }

                $count = $query->get()->count();
                //echo $query;
                
                $results = $query->offset(($pageNum-1) * $pageSize) 
                ->limit($pageSize)->orderBy('tms_terminal.create_ts', 'DESC')
                ->get()->makeHidden(['tms_terminal.deleted_by','tms_terminal.delete_ts']);
                
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
            'sn' => 'required|max:30',
            'modelId' => 'required|max:36',
            'merchantId' => 'required|max:36',
            'profileId' =>'required|max:36'

        ]);
 
        if ($validator->fails()) {
            $a  =   [   
                "responseCode"=>"5555",
                "responseDesc"=>$validator->errors(),
                
                ];    
            return $this->headerResponse($a,$request);
        }
        
        DB::beginTransaction();
        try {

            $t = new Terminal();
            $t->version = 1; 
            $t->imei = $request->sn;
            $t->model_id = $request->modelId;
            $t->merchant_id = $request->merchantId;
            $t->tenant_id = $request->header('Tenant-id');
            $t->sn = $request->sn;
            $t->profile_id = $request->profileId;
            $t->create_ts = \Carbon\Carbon::now()->toDateTimeString();
            
            //$t->is_locked = $request->is_locked;
            //$t->locked_reason = $request->locked_reason;
            
            $t->save();

            if($request->terminalGroupIds){

                $dataSet = [];
                    foreach ($request->terminalGroupIds as $terminalGroup) {
                        $dataSet[] = [
                            'terminal_id'  => $t->id,
                            'terminal_group_id'    => $terminalGroup
                        ];
                    }

               TerminalGroupLink::insert($dataSet);
                
            }

            DB::commit();
            $a  =   [   
                "responseCode"=>"0000",
                "responseDesc"=>"OK",
                "generatedId" =>  $t->id
                ];    
        return $this->headerResponse($a,$request);


        } catch (\Exception $e) {
            $a  =   [
                "responseCode"=>"3333",
                "responseDesc"=>$e->getMessage()
                ];    
            return $this->failedInssertResponse($a,$request);
        }

    }

    public function update(Request $request){

        $check = Terminal::where([
            ['id',$request->id],
            ['sn',$request->sn]
           
        ])->first();
        

        $terminal = [
            'version' => 'required|numeric|max:32',
            'id' => 'required',
            'sn' => 'required',
            'modelId' => 'required|max:36',
            'merchantId' => 'required|max:36',
            'profileId' =>'required|max:36'
        ];

        if(!$check){
         
            $terminal['sn'] = 'required|max:30|unique:tms_terminal';
        }
        $validator = Validator::make($request->all(), $terminal);

        if ($validator->fails()) {
            $a  =   [   
                "responseCode"=>"5555",
                "responseDesc"=>$validator->errors()
                ];    
            return $this->headerResponse($a,$request);
        }

        DB::beginTransaction();
        try {

            $t = Terminal::where([
                ['id',$request->id],
                ['version',$request->version],
                ['tenant_id',$request->header('Tenant-id')]
               
            ])->first();
            $t->version = $request->version + 1;
            $t->imei = $request->sn;
            $t->model_id = $request->modelId;
            $t->merchant_id = $request->merchantId;
            $t->sn = $request->sn;
            $t->profile_id = $request->profileId;
            $t->update_ts = \Carbon\Carbon::now()->toDateTimeString();
            
           
            $t->save();

            if($request->terminalGroupIds){

                TerminalGroupLink::where('terminal_id', $t->id)->delete();
                $dataSet = [];
                    foreach ($request->terminalGroupIds as $terminalGroup) {
                        $dataSet[] = [
                            'terminal_group_id'  => $terminalGroup,
                            'terminal_id'    => $t->id
                        ];
                    }
                    
               TerminalGroupLink::insert($dataSet);
                
            }

           DB::commit();
           $a  =   [   
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
            return $this->headerResponse($a,$request);
        }
    }
    
    public function show(Request $request){
        try {
            $t = Terminal::select(
                'id',
                'sn',
                'model_id',
                'merchant_id',
                'profile_id',
                DB::raw('(CASE 
                WHEN is_locked = 1 THEN True
                ELSE False
                END) AS locked'),
                'locked_by as lockedBy',
                'locked_time as lockedTime',
                'version',
                'created_by as createdBy',
                'create_ts as createdTime',
                'updated_by as lastUpdatedBy',
                'update_ts as lastUpdatedTime'

            )
           


            ->where('id', $request->id)->whereNull('deleted_by');
            
            
            if($t->get()->count()>0)
            {
                $t =  $t->with(['model' => function ($query) {
                    $query->select('id', 'model');
                 }, 'Merchant' => function($query){
                    $query->select('id', 'name');
                 }, 'profile' => function($query){
                    $query->select('id', 'name');
                }])->get()->makeHidden(['deleted_by', 'delete_ts','model_id','profile_id','merchant_id']);
                //$t =  $t->get()->makeHidden(['deleted_by', 'delete_ts']);
                
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
        DB::beginTransaction();
        try {
            $t= Terminal::where('id','=',$request->id)
            ->where('version','=',$request->version)
            ->where('tenant_id', $request->header('Tenant-id'));
             $cn = $t->get()->count();
             if( $cn > 0)
             {
                $update_t = $t->first();
                $this->deleteAction($request, $update_t);
                //TerminalGroupLink::where('terminal_id', $request->id)->delete();

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
                $a  =   [   
                    "responseCode"=>"0400",
                    "responseDesc"=>"Data No Found"
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

    public function restart(Request $request){

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
            $t= Terminal::where('id','=',$request->id)
            ->where('tenant_id', $request->header('Tenant-id'));
             $cn = $t->get()->count();
             if( $cn > 0)
             {
               
                // Command Here "" ///

                    
                    $a  =   [   
                        "responseCode"=>"0000",
                        "responseDesc"=>"OK"
                        ];    
                    return $this->headerResponse($a,$request);
                
             }
             else
             {
                $a  =   [   
                    "responseCode"=>"0400",
                    "responseDesc"=>"Data No Found"
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

    public function lockUnlock(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required|max:36',
            'version' => 'required|numeric',
            'action' => 'required|in:LOCK,UNLOCK',
            'lockReason' => 'required|max:255' 
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
            $t= Terminal::where('id','=',$request->id)
            ->where('version','=',$request->version)
            ->where('tenant_id', $request->header('Tenant-id'));
             $cn = $t->get()->count();
             if( $cn > 0)
             {
                $locked_t = $t->first();
                $current_date_time = \Carbon\Carbon::now()->toDateTimeString();
                $locked_t->locked_by = $request->header('X-Consumer-Username');
                $locked_t->version = $request->version + 1;
                $locked_t->is_locked = ($request->action=="LOCK")?1:0;
                $locked_t->locked_reason = $request->lockReason;
                $locked_t->locked_time = $current_date_time; 

                //TerminalGroupLink::where('terminal_id', $request->id)->delete();

                if ($locked_t->save()) {
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
                $a  =   [   
                    "responseCode"=>"0400",
                    "responseDesc"=>"Data No Found"
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
