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
                TerminalExt::query()
                ->join('tmsext_template', 'tmsext_terminal_ext.templateId', '=', 'tmsext_template.id')
                ->select('tmsext_terminal_ext.id as id',
                        'tmsext_terminal_ext.terminal_id as tid',
                        'tmsext_terminal_ext.merchant_id as mid',
                        'merchant_name1 as merchantName1',
                        'merchant_name2 as merchantName2',
                        'merchant_name3 as merchantName3',
                        'tmsext_template.name as templateName',
                        'tmsext_terminal_ext.version',
                        'tmsext_terminal_ext.created_by as createdBy',
                        'tmsext_terminal_ext.create_ts as createdTime',
                        'tmsext_terminal_ext.updated_by as lastUpdatedBy',
                        'tmsext_terminal_ext.update_ts as lastUpdatedTime')
                ->whereNull('tmsext_terminal_ext.deleted_by');
              
                
                if($request->merchantName != '')
                {
                    //$query->where('tle_id', 'ILIKE', '%' . $request->merchantName . '%');
                    //$query->join('tmsext_terminal_ext.merchant_id','=','merchant.id');
                    //$rp = $request->merchantName;
                    //$query->whereHas('merchant', function($q) use ($rp) {
                    //    $q->where('name', 'ILIKE', '%' . $rp . '%');
                    //});
                     $query->where('merchant_name1', 'ILIKE', '%' . $request->merchantName . '%');
                     $query->where('merchant_name2', 'ILIKE', '%' . $request->merchantName . '%');
                     $query->where('merchant_name3', 'ILIKE', '%' . $request->merchantName . '%');
                }
                if($request->tid != '')
                {
                    $query->where('terminal_id', 'ILIKE', '%' . $request->tid . '%');
                }

                if($request->mid != '')
                {
                    $query->where('merchant_id', 'ILIKE', '%' . $request->mid . '%');
                }

                if($request->templateName != '')
                {
                    $query->where('tmsext_template.name', 'ILIKE', '%' . $request->templateName . '%');
                }
                if($request->templateId != '')
                {
                    $query->where('templateId', 'ILIKE', '%' . $request->templateId . '%');
                }

                /*
                if($request->sn != '')
                {
                    $query->where('sn', 'ILIKE', '%' . $request->sn . '%');
                }*/

                $count = $query->get()->count();
            
                $results = $query->offset(($pageNum-1) * $pageSize) 
                ->limit($pageSize)->orderBy('tmsext_terminal_ext.create_ts', 'DESC')->get();
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
            'tid' => 'required|max:8',
            'mid' => 'required|max:15',
            'merchantName1'  => 'required|max:30',
            'templateId' => 'required|max:36',
           
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
            $c->merchant_name2  = $request->merchantName2;
            $c->merchant_name3  = $request->merchantName3;
            $c->templateId = $request->templateId;
            

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

        $appa = [
            'tid' => 'required|max:8',
            'mid' => 'required|max:15',
            'merchantName1'  => 'required|max:30',
            'templateId' => 'required|max:36',
          
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
            $c->merchant_name2  = $request->merchantName2;
            $c->merchant_name3  = $request->merchantName3;
            $c->templateId = $request->templateId;
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
    
    public function get(Request $request){ //? belum
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
            $p = TerminalExt::select(
                    'id',
                    'terminal_id as tid',
                    'merchant_id as mid',
                    'merchant_name1 as merchantName1',
                    'merchant_name2 as merchantName2',
                    'merchant_name3 as merchantName3',
                    'templateId',
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
                

                 $tass = $p->map(function ($item) {
                    //
                    $g = DB::table('tmsext_template')->where('id',$item->templateId)->get();
                   
                    $d = null;
                    foreach($g as $c)
                    {
                        $d= array('id'=>$c->id,
                                            'name'=>$c->name,
                                            'description'=>$c->description
                                        );
                    }    
                    $item['template'] = $d;
                    return $item;

                    });
                    unset($tass[0]['templateId']);

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
        //Delete existing Terminal Extended and its Acquirers, Issuers, and Card / BIN Ranges. Linked 
        //Terminal Extended cannot be deleted. ???
        DB::beginTransaction();
        try {
            $m = TerminalExt::where('id','=',$request->id)
            ->whereNull('deleted_by')
            ->where('version','=',$request->version);
            //->where('tenant_id',$request->header('Tenant-id'));
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
