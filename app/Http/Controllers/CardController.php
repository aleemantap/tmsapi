<?php

namespace App\Http\Controllers;
use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;



class CardController extends Controller
{
    public function list(Request $request){

        try {

                $pageSize = ($request->pageSize)?$request->pageSize:10;
                $pageNum = ($request->pageNum)?$request->pageNum:1;
                $query = 
                Card:: 
                select('id',
                        'name',
                        'bin_range_start  as binRangeStart',
                        'bin_range_end as binRangeEnd',
                        'card_num_length as cardNumLength',
                        'version',
                        'created_by as createdBy',
                        'create_ts as createdTime',
                        'updated_by as lastUpdatedBy',
                        'update_ts as lastUpdatedTime')
                ->whereNull('deleted_by');                
              

                if($request->name != '')
                {
                    $query->where('name', 'ILIKE', '%' . $request->name . '%');
                }

                if($request->issuerId != '')
                {
                   // $query->where('on_us',$request->issuerId);
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
            'binRangeStart' => 'required|max:10',
            'binRangeEnd' => 'required|max:10',
            'cardNumLength' => 'required|numeric',
            'panDigitUnmasking' =>  ['required',Rule::in(['true', 'false','TRUE','FALSE','True','False','1','0'])],
            'printCardholderCopy' =>   $ruleBool,
            'printMerchantCopy' =>   $ruleBool,
            'printBankCopy' =>    $ruleBool,
            'pinPrompt' =>    $ruleBool,
            'pinLength' => 'numeric'

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

            $ta = new Card();
            $ta->version = 1; 
            $ta->name = $request->name;
            $ta->bin_range_start =  $request->binRangeStart;
            $ta->bin_range_end =  $request->binRangeEnd;
            $ta->card_num_length =  $request->cardNumLength;
            $ta->pan_digit_unmasking =  $request->panDigitUnmasking;
            $ta->print_cardholder_copy =  $request->printCardholderCopy;
            $ta->print_merchant_copy =  $request->printMerchantCopy;
            $ta->print_bank_copy =  $request->printBankCopy;
            $ta->pin_prompt =  $request->pinPrompt;
            $ta->pin_length =  $request->pinLength;
           
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
      

        $check = Card::where([
            ['id',$request->id],
            ['version',$request->version],
            ['name',$request->name]
            
        ])->get();


        $appa = [
            'binRangeStart' => 'required|max:10',
            'binRangeEnd' => 'required|max:10',
            'cardNumLength' => 'required|numeric',
            'panDigitUnmasking' =>  ['required',Rule::in(['true', 'false','TRUE','FALSE','True','False','1','0'])],
            'printCardholderCopy' =>   $ruleBool,
            'printMerchantCopy' =>   $ruleBool,
            'printBankCopy' =>    $ruleBool,
            'pinPrompt' =>    $ruleBool,
            'pinLength' => 'numeric'    
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

            $ta = Card::where([
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
            $ta->bin_range_start =  $request->binRangeStart;
            $ta->bin_range_end =  $request->binRangeEnd;
            $ta->card_num_length =  $request->cardNumLength;
            $ta->pan_digit_unmasking =  $request->panDigitUnmasking;
            $ta->print_cardholder_copy =  $request->printCardholderCopy;
            $ta->print_merchant_copy =  $request->printMerchantCopy;
            $ta->print_bank_copy =  $request->printBankCopy;
            $ta->pin_prompt =  $request->pinPrompt;
            $ta->pin_length =  $request->pinLength;
            
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
        //acquirer
            // id
            // type
            //  name
            // description
            // hostId
            
        try {
            $ta = Card::select(
                    'id',
                    'name',
                    'bin_range_start as binRangeStart',
                    'bin_range_end as binRangeEnd',
                    'card_num_length as cardNumLength',
                    'pan_digit_unmasking as panDigitUnmasking',
                    'print_cardholder_copy as printCardholderCopy',
                    'print_merchant_copy as printMerchantCopy',
                    'print_bank_copy as printBankCopy',
                    'pin_prompt as pinPrompt',
                    'pin_length as pinLength',
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
            $m = Card::where('id','=',$request->id)
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
