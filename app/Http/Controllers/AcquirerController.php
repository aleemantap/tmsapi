<?php

namespace App\Http\Controllers;
use App\Models\Acquirer;
use App\Models\Tlesetting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;


class AcquirerController extends Controller
{
    public function list(Request $request){

        try {

                $pageSize = ($request->pageSize)?$request->pageSize:10;
                $pageNum = ($request->pageNum)?$request->pageNum:1;
                $query = 
                Acquirer:: 
                select('id','name','version','acquirer_type as type','description','host_id as hostId','acquirer_id as acquirerId','created_by as createdBy','create_ts as createdTime', 'updated_by as lastUpdatedBy','update_ts as lastUpdatedTime')
                ->whereNull('deleted_by');                
                
                
                if($request->terminalExtId != '')
                {
                    $query->where('terminal_id', 'ILIKE', '%' . $request->terminalExtId . '%');
                }

                if($request->name != '')
                {
                    $query->where('name', 'ILIKE', '%' . $request->name . '%');
                }

                if($request->type != '')
                {
                    $query->where('acquirer_type', 'ILIKE', '%' . $request->type . '%');
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

        $ruleBool = [Rule::in(['true', 'false','TRUE','FALSE','True','False','1','0'])];
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100',
            'type' => 'required|max:50',
            'description' => 'max:255',
            'hostId' => 'max:3', 
            'settlementHostId' => 'max:3',
            'numberOfPrint' => 'numeric',
            'respTimeout' => 'numeric',
            'acquirerId' => 'max:2',
            'hostDestAddr' => 'max:100',
            'hostDestPort' => 'max:6',
            'tleAcquirer' => $ruleBool,
            'tleSettingId' =>  'required_if:tleAcquirer,1,True,TRUE,true',
            'masterKeyLocation' => 'max:4',
            //masterKey
            //workingKey
            'batchNumber' => 'max:6',
            'merchantId' => 'max:15',
            'terminalId' => 'max:8',
            'showPrintExpDate' => $ruleBool,
            'checkCardExpDate' => $ruleBool,
            'creditSettlement' => $ruleBool,
            'debitSettlement' => $ruleBool
            //terminalExtId

            
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

            $ta = new Acquirer();
            $ta->version = 1; 
            $ta->name = $request->name;
            $ta->acquirer_type = $request->type;
            $ta->description = $request->description;
            $ta->host_id = $request->hostId; 
            $ta->settlement_host_id = $request->settlementHostId;
            $ta->number_of_print = $request->numberOfPrint;
            $ta->resp_timeout = $request->respTimeout;
            $ta->acquirer_id = $request->acquirerId;
            $ta->host_destination_addr = $request->hostDestAddr;
            $ta->host_destination_port = $request->hostDestPort;
            $ta->tle_acquirer = $request->tleAcquirer;
            $ta->tle_setting_id = $request->tleSettingId;
            $ta->master_key_location = $request->masterKeyLocation;
            $ta->master_key = $request->masterKey;
            $ta->working_key = $request->workingKey;
            $ta->batch_number = $request->batchNumber;
            $ta->mid = $request->merchantId;
            $ta->tid = $request->terminalId;
            $ta->show_print_exp_date = $request->showPrintExpDate;
            $ta->check_card_exp_date = $request->checkCardExpDate;
            $ta->credit_settlement = $request->creditSettlement;
            $ta->debit_settlement = $request->debitSettlement;
            $ta->terminal_id = $request->terminalExtId;

            $this->saveAction($request, $ta);

            if ($ta->save()) {
                DB::commit();
                $a  =   [   
                    "responseCode"=>"0000",
                    "responseDesc"=>"OK",
                    "generatedId" =>  $ta->id
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

        $ruleBool = [Rule::in(['true', 'false','TRUE','FALSE','True','False','1','0'])];
      

        $check = Acquirer::where([
            ['id',$request->id],
            ['version',$request->version],
            ['name',$request->name],
            ['acquirer_type',$request->type]
            
        ])->get();

        
        $appa = [
            // /'type' => 'required|max:50',
            'description' => 'max:255',
            'hostId' => 'max:3', 
            'settlementHostId' => 'max:3',
            'numberOfPrint' => 'numeric',
            'respTimeout' => 'numeric',
            'acquirerId' => 'max:2',
            'hostDestAddr' => 'max:100',
            'hostDestPort' => 'max:6',
            'tleAcquirer' => $ruleBool,
            'tleSettingId' =>  'required_if:tleAcquirer,1,True,TRUE,true',
            'masterKeyLocation' => 'max:4',
            //masterKey
            //workingKey
            'batchNumber' => 'max:6',
            'merchantId' => 'max:15',
            'terminalId' => 'max:8',
            'showPrintExpDate' => $ruleBool,
            'checkCardExpDate' => $ruleBool,
            'creditSettlement' => $ruleBool,
            'debitSettlement' => $ruleBool,
            //terminalExtId

          
        ];
        
        if($check->count() == 0){
     
            $appa['name'] = 'required|max:100';
            $appa['type'] = 'required|max:50';
           
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

            $ta = Acquirer::where([
                ['id',$request->id],
                ['version',$request->version],
                ['acquirer_type',$request->type]
                
            ])
            ->whereNull('deleted_by')
            ->first();

            if(empty($ta)){
                $a=["responseCode"=>"0400",
                "responseDesc"=>"Data Not Found"
                ];    
            return $this->headerResponse($a,$request);
            }
            
            $ta->version = $request->version + 1;
            $ta->name = $request->name;
            $ta->acquirer_type = $request->type;
            $ta->description = $request->description;
            $ta->host_id = $request->hostId; 
            $ta->settlement_host_id = $request->settlementHostId;
            $ta->number_of_print = $request->numberOfPrint;
            $ta->resp_timeout = $request->respTimeout;
            $ta->acquirer_id = $request->acquirerId;
            $ta->host_destination_addr = $request->hostDestAddr;
            $ta->host_destination_port = $request->hostDestPort;
            $ta->tle_acquirer = $request->tleAcquirer;
            $ta->tle_setting_id = $request->tleSettingId;
            $ta->master_key_location = $request->masterKeyLocation;
            $ta->master_key = $request->masterKeyLocation;
            $ta->working_key = $request->workingKey;
            $ta->batch_number = $request->batchNumber;
            $ta->mid = $request->merchantId;
            $ta->tid = $request->terminalId;
            $ta->show_print_exp_date = $request->showPrintExpDate;
            $ta->check_card_exp_date = $request->checkCardExpDate;
            $ta->credit_settlement = $request->creditSettlement;
            $ta->debit_settlement = $request->debitSettlement;
            $ta->terminal_id = $request->terminalExtId;


            $this->updateAction($request, $ta);
            
            if ($ta->save()) {
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
            $ta = Acquirer::select(
                    'id',
                    'name',
                    'acquirer_type as type',
                    'description',
                    'host_id as hostId',
                    'settlement_host_id as settlementHostId',
                    'number_of_print as numberOfPrint',
                    'resp_timeout as respTimeout',
                    'acquirer_id as acquirerId',
                    'host_destination_addr as hostDestAddr',
                    'host_destination_port as hostDestPort',
                    'tle_acquirer as tleAcquirer',
                    'master_key_location as masterKeyLocation',
                    'master_key as masterKey',
                    'working_key as workingKey',
                    'batch_number as batchNumber',
                    'mid as merchantId',
                    'tid as terminalId',
                    'show_print_exp_date as showPrintExpDate',
                    'check_card_exp_date as checkCardExpDate',
                    'credit_settlement as creditSettlement',
                    'debit_settlement as debitSettlement',
                    'tle_setting_id as tle_setting_id', 
                    'version',
                    'created_by as createdBy',
                    'create_ts as createdTime',
                    'updated_by as lastUpdatedBy',
                    'update_ts as lastUpdatedTime'
                    )
            ->where('id', 'ILIKE', '%' . $request->id . '%')
            ->whereNull('deleted_by')
            // ->with(['tle_setting' => function ($query) {
            //              $query->select('id','tle_id as tleId','tle_eft_sec as tleEftSec','acquirer_id as acquirerId','ltmk_aid as ltmkAid','vendor_id as vendorId','tle_ver as tleVer');
                        
            // }])
            ->get();//->makeHidden('tle_setting_id');
            if($ta->count()>0)
            {
                
               

                $tas = $ta->map(function ($item) {
                    //
                    $g = Tlesetting::find($item->tle_setting_id);
                    $item['tleSetting'] = array('id'=>$g->id,
                                            'tleId'=>$g->tle_id,
                                            'tleEftSec'=>$g->tle_eft_sec,
                                            'acquirerId'=>$g->acquirer_id,
                                            'ltmkAid'=>$g->ltmk_aid,
                                            'vendorId'=>$g->vendor_id,
                                            'tleVer'=>$g->tle_ver,
                                        );
                    return $item;
                });

                unset($tas[0]['tle_setting_id']);

                $a=["responseCode"=>"0000",
                    "responseDesc"=>"OK",
                     "data" => $ta
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
            $m = Acquirer::where('id','=',$request->id)
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
