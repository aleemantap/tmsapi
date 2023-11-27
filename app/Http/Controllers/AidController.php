<?php

namespace App\Http\Controllers;
use App\Models\Aid;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class AidController extends Controller
{
    public function list(Request $request){

        try {

                $pageSize = ($request->pageSize)?$request->pageSize:10;
                $pageNum = ($request->pageNum)?$request->pageNum:1;
                $query = 
                Aid:: 
                select('id','name','version','txn_type as txnType','aid_version as aidVersion','created_by as createdBy','create_ts as createdTime', 'updated_by as lastUpdatedBy','update_ts as lastUpdatedTime')
                ->whereNull('deleted_by');                
                
                
                if($request->name != '')
                {
                    $query->where('name', 'ILIKE', '%' . $request->name . '%');
                }

                $count = $query->get()->count();
            
                $results = $query->offset(($pageNum-1) * $pageSize) 
                ->limit($pageSize)->orderBy('name', 'ASC')->get();
                if( $count  > 0)
                {
                $a=['responseCode' => '0000', 
                'responseDesc' => "OK",
                'pageSize'  =>  $pageSize,
                'totalPage' => ceil($count/$pageSize),
                'total' => $count,
                'rows' => $results
                    ];    
                return $this->listResponse($a,$request);
                }else{
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


    public function add(Request $request){

        
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100',
            'txnType' => 'required|max:8',
            'aid' => 'required|max:32',
            'aidVersion' => 'required|max:4', 
            'tacDefault' => 'required|max:10',
            'tacDenial' => 'required|max:10',
            'tacOnline' => 'required|max:10',
            'threshold' => 'required|max:12',
            'targetPercentage' => 'required|max:4',
            'maxTargetPercentage' => 'required|max:4',
            'ddol' => 'required',
            'tdol' => 'required',
            'floorLimit' => 'required|max:8',
            'appSelect' => 'required|max:2',
            'aidPriority' => 'required|max:2',
            'trxType9C' => 'required|max:2',
            'categoryCode' => 'required|max:2',
            'clKernelToUse' => 'max:4',
            'clOptions' => 'max:8',
            'clTrxLimit' => 'max:12',
            'clCVMLimit' => 'max:12',
            'clFloorLimit' => 'max:12',
            'remark' => 'max:100',
            'emvConfTerminalCapability' => 'max:1024',
            'additionalTerminalCapability' => 'max:1024',
            'dataTtq' => 'max:8'
            
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

            $aid = new Aid();
            $aid->version = 1; 
            $aid->name = $request->name;
            $aid->txn_type = $request->txnType;
            $aid->aid = $request->aid;
            $aid->aid_version = $request->aidVersion;
            $aid->tac_default = $request->tacDefault;
            $aid->tac_denial = $request->tacDenial;
            $aid->tac_online = $request->tacOnline;
            $aid->threshold = $request->threshold;
            $aid->target_percentage = $request->targetPercentage;
            $aid->max_target_percentage = $request->maxTargetPercentage;
            $aid->ddol = $request->ddol;
            $aid->tdol = $request->tdol;
            $aid->floor_limit = $request->floorLimit;
            $aid->app_select = $request->appSelect;
            $aid->aid_priority = $request->aidPriority;
            $aid->trx_type9c = $request->trxType9C;
            $aid->category_code = $request->categoryCode;
            $aid->cl_kernel_to_use = $request->clKernelToUse;
            $aid->cl_options = $request->clOptions;
            $aid->cl_trx_limit = $request->clTrxLimit;
            $aid->cl_cvm_limit = $request->clCVMLimit;
            $aid->cl_floor_limit = $request->clFloorLimit;
            $aid->remark = $request->remark;
            $aid->emv_conf_term_capability = $request->emvConfTerminalCapability;
            $aid->additional_term_capability = $request->additionalTerminalCapability;
            $aid->data_ttq = $request->dataTtq;


            $this->saveAction($request, $aid);

            if ($aid->save()) {
                DB::commit();
                $a  =   [   
                    "responseCode"=>"0000",
                    "responseDesc"=>"OK",
                    "generatedId" =>  $aid->id
                    ];    
            return $this->headerResponse($a,$request);
            }
           
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

       

        $check = Aid::where([
            ['id',$request->id],
            ['version',$request->version],
            ['name',$request->name]
        ])->get();

        
        $appa = [
            //'name' => 'required|max:100',
            'txnType' => 'required|max:8',
            'aid' => 'required|max:32',
            'aidVersion' => 'required|max:4', 
            'tacDefault' => 'required|max:10',
            'tacDenial' => 'required|max:10',
            'tacOnline' => 'required|max:10',
            'threshold' => 'required|max:12',
            'targetPercentage' => 'required|max:4',
            'maxTargetPercentage' => 'required|max:4',
            'ddol' => 'required',
            'tdol' => 'required',
            'floorLimit' => 'required|max:8',
            'appSelect' => 'required|max:2',
            'aidPriority' => 'required|max:2',
            'trxType9C' => 'required|max:2',
            'categoryCode' => 'required|max:2',
            'clKernelToUse' => 'max:4',
            'clOptions' => 'max:8',
            'clTrxLimit' => 'max:12',
            'clCVMLimit' => 'max:12',
            'clFloorLimit' => 'max:12',
            'remark' => 'max:100',
            'emvConfTerminalCapability' => 'max:1024',
            'additionalTerminalCapability' => 'max:1024',
            'dataTtq' => 'max:8'

          
        ];
        
        if($check->count() == 0){
     
            $appa['name'] = 'required|max:100';
           
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

            $aid = Aid::where([
                ['id',$request->id],
                ['version',$request->version]
                
            ])
            ->whereNull('deleted_by')
            ->first();

            if(empty($aid)){
                $a=["responseCode"=>"0400",
                "responseDesc"=>"Data Not Found"
                ];    
            return $this->headerResponse($a,$request);
            }
            
            $aid->version = $request->version + 1;
            $aid->name = $request->name;
            $aid->txn_type = $request->txnType;
            $aid->aid = $request->aid;
            $aid->aid_version = $request->aidVersion;
            $aid->tac_default = $request->tacDefault;
            $aid->tac_denial = $request->tacDenial;
            $aid->tac_online = $request->tacOnline;
            $aid->threshold = $request->threshold;
            $aid->target_percentage = $request->targetPercentage;
            $aid->max_target_percentage = $request->maxTargetPercentage;
            $aid->ddol = $request->ddol;
            $aid->tdol = $request->tdol;
            $aid->floor_limit = $request->floorLimit;
            $aid->app_select = $request->appSelect;
            $aid->aid_priority = $request->aidPriority;
            $aid->trx_type9c = $request->trxType9C;
            $aid->category_code = $request->categoryCode;
            $aid->cl_kernel_to_use = $request->clKernelToUse;
            $aid->cl_options = $request->clOptions;
            $aid->cl_trx_limit = $request->clTrxLimit;
            $aid->cl_cvm_limit = $request->clCVMLimit;
            $aid->cl_floor_limit = $request->clFloorLimit;
            $aid->remark = $request->remark;
            $aid->emv_conf_term_capability = $request->emvConfTerminalCapability;
            $aid->additional_term_capability = $request->additionalTerminalCapability;
            $aid->data_ttq = $request->dataTtq;
            

            $this->updateAction($request, $aid);
            
            if ($aid->save()) {
                DB::commit();
                $a  =   [   
                    "responseCode"=>"0000",
                    "responseDesc"=>"OK"
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
    
    public function get(Request $request){
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
            $aid = Aid::select(
                    'id',
                    'name',
                    'txn_type as txnType',
                    'aid',
                    'aid_version as aidVersion',
                    'tac_default as tacDefault',
                    'tac_denial as tacDenial',
                    'tac_online as tacOnline',
                    'threshold',
                    'target_percentage as targetPercentage',
                    'max_target_percentage as maxTargetPercentage',
                    'ddol',
                    'tdol',
                    'floor_limit as floorLimit',
                    'app_select as appSelect',
                    'aid_priority as aidPriority',
                    'trx_type9c as trxType9C',
                    'category_code as categoryCode',
                    'cl_kernel_to_use as clKernelToUse',
                    'cl_options as clOptions',
                    'cl_trx_limit as clTrxLimit',
                    'cl_cvm_limit as clCVMLimit',
                    'cl_floor_limit as clFloorLimit',
                    'remark',
                    'emv_conf_term_capability as emvConfTerminalCapability',
                    'additional_term_capability as additionalTerminalCapability',
                    'data_ttq as dataTtq',
                    'version',
                    'created_by as createdBy',
                    'create_ts as createdTime',
                    'updated_by as lastUpdatedBy',
                    'update_ts as lastUpdatedTime'
                    )
            ->where('id', 'ILIKE', '%' . $request->id . '%')
            ->whereNull('deleted_by')
            ->get();
            if($aid->count()>0)
            {
                $a=["responseCode"=>"0000",
                    "responseDesc"=>"OK",
                     "data" => $aid
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

    public function delete(Request $request){
        DB::beginTransaction();
        try {
            $m = Aid::where('id','=',$request->id)
            ->whereNull('deleted_by')
            ->where('version','=',$request->version);
            
             $cn = $m->get()->count();
             if( $cn > 0)
             {
            
                $re = $this->deleteAction($request,$m);
               
                if ($re) {
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
