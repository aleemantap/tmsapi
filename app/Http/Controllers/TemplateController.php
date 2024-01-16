<?php

namespace App\Http\Controllers;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;


class TemplateController extends Controller
{
    public function list(Request $request){

        try {

                $pageSize = ($request->pageSize)?$request->pageSize:10;
                $pageNum = ($request->pageNum)?$request->pageNum:1;
                $query = 
                Template:: 
                select('id','name','version','description','created_by as createdBy','create_ts as createdTime', 'updated_by as lastUpdatedBy','update_ts as lastUpdatedTime')
                ->whereNull('deleted_by');                
                
                
                if($request->name != '')
                {
                    $query->where('name', 'ILIKE', '%' . $request->name . '%');
                }

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

    public function listAcquirer(Request $request){

        try {

                $pageSize = ($request->pageSize)?$request->pageSize:10;
                $pageNum = ($request->pageNum)?$request->pageNum:1;
                $query = 
                DB::table('tmsext_template_acquirer_link') 
                ->join('tmsext_template_acquirer_link', 'tmsext_template_acquirer_link.acquired_id', '=', 'tmsext_acquirer.id')
                ->select('tmsext_acquirer.id as id','tmsext_acquirer.name as name','tmsext_acquirer.version','tmsext_acquirer.type','tmsext_acquirer.description','tmsext_acquirer.host_id as hostId','created_by as createdBy','create_ts as createdTime', 'updated_by as lastUpdatedBy','update_ts as lastUpdatedTime');              
                
                
                if($request->name != '')
                {
                    $query->where('tmsext_acquirer.name', 'ILIKE', '%' . $request->name . '%');
                }

                if($request->type != '')
                {
                    $query->where('tmsext_acquirer.type', 'ILIKE', '%' . $request->type . '%');
                }

                $count = $query->get()->count();
            
                $results = $query->offset(($pageNum-1) * $pageSize) 
                ->limit($pageSize)->orderBy('tmsext_acquirer.create_ts', 'DESC')->get();
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
            'hostReport' => $ruleBool,
            'hostReportUrl' => ['required_if:hostReport,1,True,TRUE,true'],
            'hostReportApiKey' => ['required_if:hostReport,1,True,TRUE,true'],
            'hostReportTimeout' => ['required_if:hostReport,1,True,TRUE,true'],
            'hostLogging' => $ruleBool,
            'hostLoggingUrl'  => ['required_if:hostLogging,1,True,TRUE,true'],
            'hostLoggingApiKey'  => ['required_if:hostLogging,1,True,TRUE,true'],
            'hostLoggingInterval' => ['required_if:hostLogging,1,True,TRUE,true'],
            'hostLoggingTimeout' => ['required_if:hostLogging,1,True,TRUE,true'],
            'featureSale' => $ruleBool,
            'featureSaleTip' => $ruleBool,
            'featureSaleRedemption' => $ruleBool,
            'featureSaleCompletion' => $ruleBool,
            'featureSaleFareNonFare' => $ruleBool,
            'featureCardVer'  => $ruleBool,
            'featureInstallment' => $ruleBool,
            'featureManualKeyIn' => $ruleBool,
            'featureQris' => $ruleBool,
            'featureContactless' => $ruleBool,
            'fallbackEnabled' => $ruleBool,
            'randomPinKeypad'  => $ruleBool,
            'beepPinKeypad' => $ruleBool,
            'nextLogon'  => 'nullable|numeric',
            'autoLogon' => $ruleBool,
            'pushLogon' => $ruleBool,
            'qrisCountDown' => 'nullable|numeric',
            'reprintOnlineRetry' => 'nullable|numeric',
            'settlementWarningTrxCount'  => 'nullable|numeric',
            'settlementMaxTrxCount'  => 'nullable|numeric',
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

            $ta = new Template();
            $ta->version = 1; 
            $ta->name = $request->name; 
            $ta->description = $request->description; 
            $ta->host_report = $this->convertToBool($request->hostReport); 
            $ta->host_report_url = $request->hostReportUrl;
            $ta->host_report_api_key = $request->hostReportApiKey;
            $ta->host_report_timeout = $request->hostReportTimeout;
            $ta->host_logging = $this->convertToBool($request->hostLogging);
            $ta->host_logging_url = $request->hostLoggingUrl;
            $ta->host_logging_api_key = $request->hostLoggingApiKey;
            $ta->host_logging_interval = $request->hostLoggingInterval;
            $ta->host_logging_timeout = $request->hostLoggingTimeout;
            $ta->feature_sale = $this->convertToBool($request->featureSale);
            $ta->feature_sale_tip = $this->convertToBool($request->featureSaleTip);
            $ta->feature_sale_redemption = $this->convertToBool($request->featureSaleRedemption);
            $ta->feature_sale_completion = $this->convertToBool($request->featureSaleCompletion);
            $ta->feature_sale_fare_non_fare = $this->convertToBool($request->featureSaleFareNonFare);
            $ta->feature_card_verification = $this->convertToBool($request->featureCardVer);
            $ta->feature_installment = $this->convertToBool($request->featureInstallment);
            $ta->feature_manual_key_in = $this->convertToBool($request->featureManualKeyIn);
            $ta->feature_qris = $this->convertToBool($request->featureQris);
            $ta->feature_contactless = $this->convertToBool($request->featureContactless);
            $ta->fallback_enabled = $this->convertToBool($request->fallbackEnabled);
            $ta->random_pin_keypad = $this->convertToBool($request->randomPinKeypad);
            $ta->beep_pin_keypad = $this->convertToBool($request->beepPinKeypad);
            $ta->next_logon = $request->nextLogon;
            $ta->auto_logon = $this->convertToBool($request->autoLogon);
            $ta->push_logon = $this->convertToBool($request->pushLogon);
            $ta->qris_count_down = $request->qrisCountDown;
            $ta->reprint_online_retry = $request->reprintOnlineRetry;
            $ta->settle_warning_trx_count = $request->settlementWarningTrxCount;
            $ta->settle_max_trx_count = $request->settlementMaxTrxCount;
            $ta->call_center1 = $request->callCenter1;
            $ta->call_center2 = $request->callCenter2;
            $ta->merchant_password = $request->merchantPassword;
            $ta->admin_password = $request->adminPassword;
            $ta->settlement_password = $request->settlementPassword;
            $ta->void_password = $request->voidPassword;
            $ta->brizzi_discount_percentage = $request->brizziDiscountPercentage;
            $ta->brizzi_discount_amount = $request->brizziDiscountAmount;
            $ta->installment1_options = $request->installment1Options;
            $ta->installment2_options = $request->installment2Options;
            $ta->installment3_options = $request->installment3Options;
            
            $this->saveAction($request, $ta);

            if ($ta->save()) {

                $iD =  $ta->id;  
                if($request->acquirerIds)
                {
                    foreach($request->acquirerIds as $c)
                    {
                         DB::table('tmsext_template_acquirer_link')->insert(
                            ['template_id' => $iD, 'acquirer_id' => $c]
                        );
                    }    
                   
                }  

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
        
        $check = Template::where([
            ['id',$request->id],
            ['version',$request->version],
            
        ])->get();

        
        $appa = [
            'name' => 'required|max:100',
            'hostReport' => $ruleBool,
            'hostReportUrl' => ['required_if:hostReport,1,True,TRUE,true'],
            'hostReportApiKey' => ['required_if:hostReport,1,True,TRUE,true'],
            'hostReportTimeout' => ['required_if:hostReport,1,True,TRUE,true'],
            'hostLogging' => $ruleBool,
            'hostLoggingUrl'  => ['required_if:hostLogging,1,True,TRUE,true'],
            'hostLoggingApiKey'  => ['required_if:hostLogging,1,True,TRUE,true'],
            'hostLoggingInterval' => ['required_if:hostLogging,1,True,TRUE,true'],
            'hostLoggingTimeout' => ['required_if:hostLogging,1,True,TRUE,true'],
            'featureSale' => $ruleBool,
            'featureSaleTip' => $ruleBool,
            'featureSaleRedemption' => $ruleBool,
            'featureSaleCompletion' => $ruleBool,
            'featureSaleFareNonFare' => $ruleBool,
            'featureCardVer'  => $ruleBool,
            'featureInstallment' => $ruleBool,
            'featureManualKeyIn' => $ruleBool,
            'featureQris' => $ruleBool,
            'featureContactless' => $ruleBool,
            'fallbackEnabled' => $ruleBool,
            'randomPinKeypad'  => $ruleBool,
            'beepPinKeypad' => $ruleBool,
            'nextLogon'  => 'nullable|numeric',
            'autoLogon' => $ruleBool,
            'pushLogon' => $ruleBool,
            'qrisCountDown' => 'nullable|numeric',
            'reprintOnlineRetry' => 'nullable|numeric',
            'settlementWarningTrxCount'  => 'nullable|numeric',
            'settlementMaxTrxCount'  => 'nullable|numeric',
          
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

            $ta = Template::where([
                ['id',$request->id],
                ['version',$request->version]
              
                
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
            $ta->description = $request->description; 
            $ta->host_report = $this->convertToBool($request->hostReport); 
            $ta->host_report_url = $request->hostReportUrl;
            $ta->host_report_api_key = $request->hostReportApiKey;
            $ta->host_report_timeout = $request->hostReportTimeout;
            $ta->host_logging = $this->convertToBool($request->hostLogging);
            $ta->host_logging_url = $request->hostLoggingUrl;
            $ta->host_logging_api_key = $request->hostLoggingApiKey;
            $ta->host_logging_interval = $request->hostLoggingInterval;
            $ta->host_logging_timeout = $request->hostLoggingTimeout;
            $ta->feature_sale = $this->convertToBool($request->featureSale);
            $ta->feature_sale_tip = $this->convertToBool($request->featureSaleTip);
            $ta->feature_sale_redemption = $this->convertToBool($request->featureSaleRedemption);
            $ta->feature_sale_completion = $this->convertToBool($request->featureSaleCompletion);
            $ta->feature_sale_fare_non_fare = $this->convertToBool($request->featureSaleFareNonFare);
            $ta->feature_card_verification = $this->convertToBool($request->featureCardVer);
            $ta->feature_installment = $this->convertToBool($request->featureInstallment);
            $ta->feature_manual_key_in = $this->convertToBool($request->featureManualKeyIn);
            $ta->feature_qris = $this->convertToBool($request->featureQris);
            $ta->feature_contactless = $this->convertToBool($request->featureContactless);
            $ta->fallback_enabled = $this->convertToBool($request->fallbackEnabled);
            $ta->random_pin_keypad = $this->convertToBool($request->randomPinKeypad);
            $ta->beep_pin_keypad = $this->convertToBool($request->beepPinKeypad);
            $ta->next_logon = $request->nextLogon;
            $ta->auto_logon = $this->convertToBool($request->autoLogon);
            $ta->push_logon = $this->convertToBool($request->pushLogon);
            $ta->qris_count_down = $request->qrisCountDown;
            $ta->reprint_online_retry = $request->reprintOnlineRetry;
            $ta->settle_warning_trx_count = $request->settlementWarningTrxCount;
            $ta->settle_max_trx_count = $request->settlementMaxTrxCount;
            $ta->call_center1 = $request->callCenter1;
            $ta->call_center2 = $request->callCenter2;
            $ta->merchant_password = $request->merchantPassword;
            $ta->admin_password = $request->adminPassword;
            $ta->settlement_password = $request->settlementPassword;
            $ta->void_password = $request->voidPassword;
            $ta->brizzi_discount_percentage = $request->brizziDiscountPercentage;
            $ta->brizzi_discount_amount = $request->brizziDiscountAmount;
            $ta->installment1_options = $request->installment1Options;
            $ta->installment2_options = $request->installment2Options;
            $ta->installment3_options = $request->installment3Options;

            $this->updateAction($request, $ta);
            
            if ($ta->save()) {
                $iD =  $request->id;  
                if($request->acquirerIds)
                {
                    DB::table('tmsext_template_acquirer_link')->where('template_id', $iD)->delete();
                    $s =array();
                    foreach($request->acquirerIds as $c)
                    {
                         
                           $s[] = ['template_id' => $iD, 'acquirer_id' => $c];
                        
                    }    
                    DB::table('tmsext_template_acquirer_link')->insert($s);
                   
                }  


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
            $ta = Template::select(
                    'id',
                    'name',
                    'description',
                    'host_report as hostReport',
                    'host_report_url as hostReportUrl',
                    'host_report_api_key as hostReportApiKey',
                    'host_report_timeout as hostReportTimeout', 
                    'host_logging as hostLogging', 
                    'host_logging_url as hostLoggingUrl', 
                    'host_logging_api_key as hostLoggingApiKey',
                    'host_logging_interval as hostLoggingInterval',
                    'host_logging_timeout as hostLoggingTimeout',
                    'feature_sale as featureSale',
                    'feature_sale_tip as featureSaleTip',
                    'feature_sale_redemption as featureSaleRedemption',
                    'feature_sale_completion as featureSaleCompletion',
                    'feature_sale_fare_non_fare as featureSaleFareNonFare',
                    'feature_card_verification as featureCardVer',
                    'feature_installment as featureInstallment',
                    'feature_manual_key_in as featureManualKeyIn',
                    'feature_qris as featureQris',
                    'feature_contactless as featureContactless',
                    'fallback_enabled as fallbackEnabled',
                    'random_pin_keypad as randomPinKeypad',
                    'beep_pin_keypad as beepPinKeypad',
                    'next_logon as nextLogon',
                    'auto_logon as autoLogon',
                    'push_logon as pushLogon', 
                    'qris_count_down as qrisCountDown',
                    'reprint_online_retry as reprintOnlineRetry',
                    'settle_warning_trx_count as settlementWarningTrxCount',
                    'settle_max_trx_count as settlementMaxTrxCount',
                    'call_center1 as callCenter1',
                    'call_center2 as callCenter2',
                    'merchant_password as merchantPassword',
                    'admin_password as adminPassword',
                    'settlement_password as settlementPassword',
                    'void_password as voidPassword',
                    'brizzi_discount_percentage as brizziDiscountPercentage',
                    'brizzi_discount_amount as brizziDiscountAmount',
                    'installment1_options as installment1Options',
                    'installment2_options as installment2Options',
                    'installment3_options as installment3Options',
                    'version',
                    'created_by as createdBy',
                    'create_ts as createdTime',
                    'updated_by as lastUpdatedBy',
                    'update_ts as lastUpdatedTime'
                    )

            ->where('id', 'ILIKE', '%' . $request->id . '%')
            ->whereNull('deleted_by')
            ->get();
            if($ta->count()>0)
            {
               
                 $tass = $ta->map(function ($item) {
                    //
                    $g = DB::table('tmsext_template_acquirer_link')->where('template_id',$item->id)
                    ->join('tmsext_acquirer', 'tmsext_template_acquirer_link.acquirer_id', '=', 'tmsext_acquirer.id')->get();
                    $d = array();
                    foreach($g as $c)
                    {
                        $d[]= array('id'=>$c->id,
                                            'name'=>$c->name,
                                            'description'=>$c->description,
                                            'type'=>$c->acquirer_type,
                                            'acquirerId' => $c->acquirer_id
                                        );
                    }    
                    $item['acquirer'] = $d;
                    return $item;

                    });

                $a=["responseCode"=>"0000",
                    "responseDesc"=>"OK",
                     "data" => $tass
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
            $m = Template::where('id','=',$request->id)
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
