<?php

namespace App\Http\Controllers;

use App\Models\Publickey;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class PublicKeyController extends Controller
{
    public function list(Request $request){

        try {

                $pageSize = ($request->pageSize)?$request->pageSize:10;
                $pageNum = ($request->pageNum)?$request->pageNum:1;
                $query = 
                Publickey:: 
                select('id',
                        'idx',
                        //'name',
                        'rid',
                        'version',
                        'created_by as createdBy',
                        'create_ts as createdTime', 
                        'updated_by as lastUpdatedBy',
                        'update_ts as lastUpdatedTime');
                //->whereNull('deleted_by');                
                
                
                // if($request->name != '')
                // {
                //     $query->where('name', 'ILIKE', '%' . $request->name . '%');
                // }

                $count = $query->get()->count();
            
                $results = $query->offset(($pageNum-1) * $pageSize) 
                //->limit($pageSize)->orderBy('name', 'ASC')->get();
                ->limit($pageSize)->orderBy('idx', 'ASC')->get();
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
           
            'idx' => 'required|max:4',
            'rid' => 'required|max:10',
            'modulus' => 'required',
            'exponent' => 'required|max:2',
            'algo' => 'required|max:2',
            'hash' => 'required'
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

            $c = new Publickey();
            $c->version = 1; 
            $c->idx = $request->idx;
            $c->rid = $request->rid;
            $c->modulus = $request->modulus;
            $c->exponent = $request->exponent;
            $c->algo = $request->algo;
            $c->hash = $request->hash;
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
  
        $check = Publickey::where([
            ['id',$request->id],
            ['version',$request->version]
            //['name',$request->name]
        ])->get();


        $appa = [
            //'name' => 'required',
            
            'idx' => 'required|max:4',
            'rid' => 'required|max:10',
            'modulus' => 'required',
            'exponent' => 'required|max:2',
            'algo' => 'required|max:2',
            'hash' => 'required'
            
          
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

            $c = Publickey::where([
                ['id',$request->id],
                ['version',$request->version]
                
            ])
            //->whereNull('deleted_by')
            ->first();

            if(empty($c)){
                $a=["responseCode"=>"0400",
                "responseDesc"=>"Data Not Found"
                ];    
            return $this->headerResponse($a,$request);
            }
            
            $c->version = $request->version + 1;
            //$c->name = $request->name;
            $c->idx = $request->idx;
            $c->rid = $request->rid;
            $c->modulus = $request->modulus;
            $c->exponent = $request->exponent;
            $c->algo = $request->algo;
            $c->hash = $request->hash;
          
            //$this->updateAction($request, $c);
            
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
            $p = Publickey::select(
                    'id',
                   // 'name',
                    'idx',
                    'rid',
                    'modulus',
                    'exponent',
                    'algo',
                    'hash',
                    'version',
                    'created_by as createdBy',
                    'create_ts as createdTime',
                    'updated_by as lastUpdatedBy',
                    'update_ts as lastUpdatedTime'
                    )
            ->where('id', 'ILIKE', '%' . $request->id . '%')
            //->whereNull('deleted_by')
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
        DB::beginTransaction();
        try {
            $m = Publickey::where('id','=',$request->id)
            //->whereNull('deleted_by');
            ->where('version','=',$request->version);
            //->where('tenant_id',$request->header('Tenant-id'));
             $cn = $m->get()->count();
             if( $cn > 0)
             {
            
                // $re = $this->deleteAction($request,$m);
               
                // if ($re) {
                //     DB::commit();
                //     $a  =   [   
                //         "responseCode"=>"0000",
                //         "responseDesc"=>"OK"
                //         ];    
                //     return $this->headerResponse($a,$request);
                //  }
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
            DB::rollBack();
            $a  =   [   
                "responseCode"=>"3333",
                "responseDesc"=>$e->getMessage()
                ];    
            return $this->headerResponse($a,$request);
        }
    }


    
}
