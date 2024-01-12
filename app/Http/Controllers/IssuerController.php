<?php

namespace App\Http\Controllers;
use App\Models\Issuer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;


class IssuerController extends Controller
{
    public function list(Request $request){

        try {

                $pageSize = ($request->pageSize)?$request->pageSize:10;
                $pageNum = ($request->pageNum)?$request->pageNum:1;
                $query = 
                Issuer:: 
                //DB::table('tmsext_issuer')->
                whereNull('tmsext_issuer.deleted_by')
                ->join('tmsext_acquirer', 'tmsext_issuer.acquirer_id', '=', 'tmsext_acquirer.id')
                ->select('tmsext_issuer.id','tmsext_issuer.name','tmsext_issuer.issuer_id  as issuerId','on_us as onUs','tmsext_acquirer.name as acquirer','tmsext_acquirer.id as acquirer_id',
                           'tmsext_acquirer.acquirer_id as acquirerId','tmsext_issuer.version','tmsext_issuer.created_by as createdBy','tmsext_issuer.create_ts as createdTime', 'tmsext_issuer.updated_by as lastUpdatedBy','tmsext_issuer.update_ts as lastUpdatedTime');
                
                
                if($request->acquirerId != '')
                {
                    $query->where('tmsext_acquirer.acquirer_id', 'ILIKE', '%' . $request->acquirerId . '%');
                }

                if($request->name != '')
                {
                    $query->where('tmsext_acquirer.name', 'ILIKE', '%' . $request->name . '%');
                }

                if($request->onUs != '')
                {
                    $query->where('on_us',$request->onUs);
                }


                $count = $query->get()->count();
            
                $results = $query->offset(($pageNum-1) * $pageSize) 
                ->limit($pageSize)->orderBy('tmsext_issuer.name', 'ASC')->get();
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
            'issuerId' => 'required|max:10',
            'onUs' => $ruleBool,
            'acquirerId' => 'max:36' //uuid

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

            $ta = new Issuer();
            $ta->version = 1; 
            $ta->name = $request->name;
            $ta->issuer_id = $request->issuerId;
            $ta->on_us = $request->onUs;
            $ta->acquirer_id = $request->acquirerId; 

            $this->saveAction($request, $ta);

            if ($ta->save()) {
                DB::commit();
                $a  =   [   
                    "responseCode"=>"0000",
                    "responseDesc"=>"OK",
                    "generatedId" =>  $ta->id
                    ];    
                //add to card link issuer
                $iD =  $ta->id;  
                if($request->cards)
                {
                    foreach($request->cards as $card)
                    {
                         DB::table('tmsext_issuer_card_link')->insert(
                            ['issuer_id' => $iD, 'card_id' => $card]
                        );
                    }    
                   
                }    

                

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
      

        $check = Issuer::where([
            ['id',$request->id],
            ['version',$request->version],
            ['name',$request->name],
            ['issuer_id',$request->issuerId]
            
        ])->get();


        $appa = [
            'onUs' => $ruleBool,
            'acquirerId' => 'max:36'        
        ];
        
        if($check->count() == 0){
     
            $appa['name'] = 'required|max:100';
            $appa['issuerId'] = 'required|max:50';
           
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

            $ta = Issuer::where([
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
            $ta->issuer_id = $request->issuerId;
            $ta->on_us = $request->onUs;
            $ta->acquirer_id = $request->acquirerId; 
            
            $this->updateAction($request, $ta);
            
            if ($ta->save()) {
                
                $iD =  $request->id;  
                if($request->cards)
                {
                    DB::table('tmsext_issuer_card_link')->where('issuer_id', $iD)->delete();
                    $s =array();
                    foreach($request->cards as $card)
                    {
                         
                           $s[] = ['issuer_id' => $iD, 'card_id' => $card];
                        
                    }    
                    DB::table('tmsext_issuer_card_link')->insert($s);
                   
                }        
                $a  =   [   
                    "responseCode"=>"0000",
                    "responseDesc"=>"OK"
                    ];  
                DB::commit();    

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
            $ta = Issuer::select(
                    'id',
                    'name',
                    'issuer_id as issuerId',
                    'on_us as onUs',
                    'version',
                    'acquirer_id',
                    'created_by as createdBy',
                    'create_ts as createdTime',
                    'updated_by as lastUpdatedBy',
                    'update_ts as lastUpdatedTime'
                    )
            ->where('id', 'ILIKE', '%' . $request->id . '%')
            ->whereNull('deleted_by')
            ->with(['acquirer' => function ($query) {
                         $query->select('id', 'name','acquirer_type as type','description','host_id as hostId');
                        
                     }])
            ->get();//->makeHidden('acquirer_id');
            if($ta->count()>0)
            {
                
                 $tass = $ta->map(function ($item) {
                    //
                    $g = DB::table('tmsext_issuer_card_link')->where('issuer_id',$item->id)
                    ->join('tmsext_card', 'tmsext_issuer_card_link.card_id', '=', 'tmsext_card.id')->get();
                    $d = array();
                    foreach($g as $c)
                    {
                        $d[]= array('id'=>$c->id,
                                            'name'=>$c->name,
                                            'binRangeStart'=>$c->bin_range_start,
                                            'binRangeEnd'=>$c->bin_range_end,
                                            'cardNumLength'=>$c->card_num_length,
                                            'panDigitUnmasking'=>$c->pan_digit_unmasking
                                        );
                    }    
                    $item['cards'] = $d;
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
            $m = Issuer::where('id','=',$request->id)
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
                    //hard delete tmsext_issuer_card_link
                    DB::table('tmsext_issuer_card_link')->where('issuer_id', $request->id)->delete();    
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

     public function linkUnlink(Request $request){
        DB::beginTransaction();
        try {
            $m = Issuer::where('id','=',$request->id)
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
