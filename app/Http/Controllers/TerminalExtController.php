<?php

namespace App\Http\Controllers;

use App\Models\TerminalExt;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//use Illuminate\Contracts\Validation\Rule;
use Illuminate\Validation\Rule;


class TerminalExtController extends Controller
{
    public function list(Request $request){

        try {

                $pageSize = ($request->pageSize)?$request->pageSize:10;
                $pageNum = ($request->pageNum)?$request->pageNum:1;
                $query = 
                TerminalExt:: 
                select('id',
                        //'sn',
                        'terminal_id as tid',
                        'merchant_id as mid',
                        'merchant_name1 as merchantName1',
                        'merchant_name2 as merchantName2',
                        'merchant_name3 as merchantName3',
                        'call_center1 as callCenter1',
                        'call_center2 as callCenter2',
                        'version',
                        'created_by as createdBy',
                        'create_ts as createdTime',
                        'updated_by as lastUpdatedBy',
                        'update_ts as lastUpdatedTime')
                ->whereNull('deleted_by'); 

                
                if($request->merchantName != '')
                {
                    //$query->where('tle_id', 'ILIKE', '%' . $request->merchantName . '%');
                    //$query->join('tmsext_terminal_ext.merchant_id','=','merchant.id');
                    $rp = $request->merchantName;
                    $query->whereHas('merchant', function($q) use ($rp) {
                        $q->where('name', 'ILIKE', '%' . $rp . '%');
                    });
                }

                /*
                
                if($request->terminalId != '')
                {
                    $query->where('terminal_id', 'ILIKE', '%' . $request->terminalId . '%');
                }

                if($request->tid != '')
                {
                    $query->where('tle_id', 'ILIKE', '%' . $request->tid . '%');
                }

                if($request->mid != '')
                {
                    $query->where('tle_id', 'ILIKE', '%' . $request->mid . '%');
                }


                if($request->sn != '')
                {
                    $query->where('tle_id', 'ILIKE', '%' . $request->sn . '%');
                }*/

                $count = $query->get()->count();
            
                $results = $query->offset(($pageNum-1) * $pageSize) 
                ->limit($pageSize)->orderBy('create_ts', 'DESC')->get();
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
            'tid' => 'required|max:8',
            'mid' => 'required|max:15',
            'merchantName1'  => 'required|max:30',
            'merchantName2'  => 'max:30',
            'merchantName3'   => 'max:30',
            'merchantPassword' => 'max:8',
            'adminPassword' => 'max:8',
            'callCenter1' => 'max:255',
            'callCenter2' => 'max:255',
            'settlementMaxTrxCount' => 'numeric',
            'settlementWarningTrxCount' => 'numeric',
            'settlementPassword' => 'max:8',
            'voidPassword' => 'max:8',
            'brizziDiscountPercentage' => 'max:10',
            'brizziDiscountAmount' => 'max:30',
            'fallbackEnabled' =>  $ruleBool,
            'featureSale'  =>  $ruleBool,
            'featureInstallment'  =>  $ruleBool,
            'featureCardVer'  =>  $ruleBool,
            'featureSaleRedemption'  =>  $ruleBool,
            'featureManualKeyIn'  =>  $ruleBool,
            'featureSaleCompletion'  =>  $ruleBool,
            'featureSaleTip'  =>  $ruleBool,
            'featureSaleFareNonFare'  =>  $ruleBool,
            'featureQris' =>  $ruleBool,
            'featureContactless'  =>  $ruleBool,
            'reprintOnlineRetry' =>'numeric',
            'qrisCountDown' =>'numeric',
            'randomPinKeypad' => $ruleBool,
            'beepPinKeypad' => $ruleBool,
            'nextLogon' => 'numeric',
            'autoLogon' =>  $ruleBool,
            'pushLogon' => 'numeric',
            'hostReport' => $ruleBool,
            'hostLogging' => $ruleBool,
            'importDefault' => $ruleBool,
            'settlementMaxTrxCount' => 'numeric'
           
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

            $c = new TerminalExt();
            $c->version = 1; 
            $c->terminal_id = $request->tid;
            $c->merchant_id = $request->mid;
            $c->merchant_name1  = $request->merchantName1;
            $c->merchant_name2  = $request->merchantName3;
            $c->merchant_name3  = $request->merchantName3;
            $c->merchant_password  = $request->merchantPassword;
            $c->admin_password  = $request->adminPassword;
            $c->call_center1  = $request->callCenter1;
            $c->call_center2  = $request->callCenter2;
            $c->settle_max_trx_count  = $request->settlementMaxTrxCount;
            $c->settle_warning_trx_count  = $request->settlementWarningTrxCount;
            $c->settlement_password  = $request->settlementPassword;
            $c->void_password  = $request->voidPassword;
            $c->brizzi_discount_percentage  = $request->brizziDiscountPercentage;
            $c->brizzi_discount_amount  = $request->brizziDiscountAmount;
            $c->fallback_enabled  = $request->fallbackEnabled;
            $c->feature_sale  =   $request->featureSale;
            $c->feature_installment = $request->featureInstallment;
            $c->feature_card_verification  =  $request->featureCardVer;
            $c->feature_sale_redemption   = $request->featureSaleRedemption;
            $c->feature_manual_key_in  =  $request->featureManualKeyIn; 
            
            $c->feature_sale_completion  =  $request->featureSaleCompletion;
            $c->feature_sale_tip  =  $request->featureSaleTip;
            $c->feature_sale_fare_non_fare  =  $request->featureSaleFareNonFare;
            $c->feature_qris  =  $request->featureQris;
            $c->feature_contactless  =  $request->featureContactless;
            $c->reprint_online_retry = $request->reprintOnlineRetry;
            $c->qris_count_down = $request->qrisCountDown;
            $c->random_pin_keypad = $request->randomPinKeypad;
            $c->beep_pin_keypad = $request->beepPinKeypad;
            $c->next_logon = $request->nextLogon;
            $c->auto_logon =  $request->autoLogon;
            $c->push_logon = $request->pushLogon;
            $c->host_report = $request->hostReport;
            $c->host_logging = $request->hostLogging;
            $c->import_default = $request->importDefault;

            $this->saveAction($request, $c);

            if ($c->save()) {
                DB::commit();
                $a  =   [   
                    "responseCode"=>"0000",
                    "responseDesc"=>"OK",
                    "generatedId" =>  $c->id
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
  
        $check = TerminalExt::where([
            ['id',$request->id],
            ['version',$request->version]
           
        ])->get();

        $ruleBool = [Rule::in(['true', 'false','TRUE','FALSE','True','False','1','0'])];
        $appa = [
            'tid' => 'required|max:8',
            'mid' => 'required|max:15',
            'merchantName1'  => 'required|max:30',
            'merchantName2'  => 'max:30',
            'merchantName3'   => 'max:30',
            'merchantPassword' => 'max:8',
            'adminPassword' => 'max:8',
            'callCenter1' => 'max:255',
            'callCenter2' => 'max:255',
            'settlementMaxTrxCount' => 'numeric',
            'settlementWarningTrxCount' => 'numeric',
            'settlementPassword' => 'max:8',
            'voidPassword' => 'max:8',
            'brizziDiscountPercentage' => 'max:10',
            'brizziDiscountAmount' => 'max:30',
            'fallbackEnabled' =>  $ruleBool,
            'featureSale'  =>  $ruleBool,
            'featureInstallment'  =>  $ruleBool,
            'featureCardVer'  =>  $ruleBool,
            'featureSaleRedemption'  =>  $ruleBool,
            'featureManualKeyIn'  =>  $ruleBool,
            'featureSaleCompletion'  =>  $ruleBool,
            'featureSaleTip'  =>  $ruleBool,
            'featureSaleFareNonFare'  =>  $ruleBool,
            'featureQris' =>  $ruleBool,
            'featureContactless'  =>  $ruleBool,
            'reprintOnlineRetry' =>'numeric',
            'qrisCountDown' =>'numeric',
            'randomPinKeypad' => $ruleBool,
            'beepPinKeypad' => $ruleBool,
            'nextLogon' => 'numeric',
            'autoLogon' =>  $ruleBool,
            'pushLogon' => 'numeric',
            'hostReport' => $ruleBool,
            'hostLogging' => $ruleBool,
            'importDefault' => $ruleBool,
            'settlementMaxTrxCount' => 'numeric'
          
        ];
        
        // if($check->count() == 0){
     
        //     $appa['name'] = 'required';
           
           
        // }
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

            $c = TerminalExt::where([
                ['id',$request->id],
                ['version',$request->version]
                
            ])
            ->whereNull('deleted_by')
            ->first();

            if(empty($c)){
                $a=["responseCode"=>"0400",
                "responseDesc"=>"Data Not Found"
                ];    
            return $this->headerResponse($a,$request);
            }
            
            $c->version = $request->version + 1;
             $c->terminal_id = $request->tid;
            $c->merchant_id = $request->mid;
            $c->merchant_name1  = $request->merchantName1;
            $c->merchant_name2  = $request->merchantName3;
            $c->merchant_name3  = $request->merchantName3;
            $c->merchant_password  = $request->merchantPassword;
            $c->admin_password  = $request->adminPassword;
            $c->call_center1  = $request->callCenter1;
            $c->call_center2  = $request->callCenter2;
            $c->settle_max_trx_count  = $request->settlementMaxTrxCount;
            $c->settle_warning_trx_count  = $request->settlementWarningTrxCount;
            $c->settlement_password  = $request->settlementPassword;
            $c->void_password  = $request->voidPassword;
            $c->brizzi_discount_percentage  = $request->brizziDiscountPercentage;
            $c->brizzi_discount_amount  = $request->brizziDiscountAmount;
            $c->fallback_enabled  = $request->fallbackEnabled;
            $c->feature_sale  =   $request->featureSale;
            $c->feature_installment = $request->featureInstallment;
            $c->feature_card_verification  =  $request->featureCardVer;
            $c->feature_sale_redemption   = $request->featureSaleRedemption;
            $c->feature_manual_key_in  =  $request->featureManualKeyIn; 
            
            $c->feature_sale_completion  =  $request->featureSaleCompletion;
            $c->feature_sale_tip  =  $request->featureSaleTip;
            $c->feature_sale_fare_non_fare  =  $request->featureSaleFareNonFare;
            $c->feature_qris  =  $request->featureQris;
            $c->feature_contactless  =  $request->featureContactless;
            $c->reprint_online_retry = $request->reprintOnlineRetry;
            $c->qris_count_down = $request->qrisCountDown;
            $c->random_pin_keypad = $request->randomPinKeypad;
            $c->beep_pin_keypad = $request->beepPinKeypad;
            $c->next_logon = $request->nextLogon;
            $c->auto_logon =  $request->autoLogon;
            $c->push_logon = $request->pushLogon;
            $c->host_report = $request->hostReport;
            $c->host_logging = $request->hostLogging;
            $c->import_default = $request->importDefault;
            
            
          
            $this->updateAction($request, $c);
            
            if ($c->save()) {
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
    
    public function show(Request $request){ //? belum
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
            $p = Tlesetting::select(
                    'id',
                    'tle_id as tleId',
                    'tle_eft_sec as tleEftSec',
                    'acquirer_id as acquirerId',
                    'ltmk_aid as ltmkAid',
                    'vendor_id as vendorId',
                    'tle_ver as tleVer',
                    'kms_secure_nii as kmsSecureNii',
                    'edc_secure_nii  as edcSecureNii',
                    'capk_exponent  as capkExponent',
                    'capk_length  as capkLength',
                    'capk_value  as capkValue',
                    'aid_length  as aidLength',
                    'aid_value   as aidValue',
                    'field_encrypted1  as encryptedField1',
                    'field_encrypted2  as encryptedField2',
                    'field_encrypted3  as encryptedField3',
                    'field_encrypted4  as encryptedField4',
                    'field_encrypted5  as encryptedField5',
                    'field_encrypted6  as encryptedField6',
                    'field_encrypted7  as encryptedField7',
                    'field_encrypted8  as encryptedField8',
                    'field_encrypted9  as encryptedField9',
                    'field_encrypted10  as encryptedField10',
                    'field_encrypted11  as encryptedField11',
                    'version',
                    'created_by as createdBy',
                    'create_ts as createdTime',
                    'updated_by as lastUpdatedBy',
                    'update_ts as lastUpdatedTime'
                    )
            ->where('id', 'ILIKE', '%' . $request->id . '%')
            ->whereNull('deleted_by')
            ->get();
            if($p->count()>0)
            {
                $a=["responseCode"=>"0000",
                    "responseDesc"=>"OK",
                     "data" => $p
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
        //Delete existing Terminal Extended and its Acquirers, Issuers, and Card / BIN Ranges. Linked 
        //Terminal Extended cannot be deleted. ???
        DB::beginTransaction();
        try {
            $m = TerminalExt::where('id','=',$request->id)
            ->whereNull('deleted_by')
            ->where('version','=',$request->version)
            ->where('tenant_id',$request->header('Tenant-id'));
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
