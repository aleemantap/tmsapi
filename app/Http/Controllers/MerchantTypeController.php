<?php

namespace App\Http\Controllers;
use App\Models\MerchantType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class MerchantTypeController extends Controller
{
    public function list(Request $request){

        try {

            $pageSize = ($request->pageSize)?$request->pageSize:10;
            $pageNum = ($request->pageNum)?$request->pageNum:1;
                $query = MerchantType::
                select('id','name','description','version','created_by as createdBy', 'create_ts as createdTime','updated_by as lastUpdateBy','update_ts as lastUpdateTime')->
                whereNull('deleted_by');
                
                if($request->name != '')
                {
                    $query->where('name', $request->name);
                }

                $count = $query->get()->count();
            
                $results = $query->offset(($pageNum-1) * $pageSize) 
                ->limit($pageSize)->orderBy('name', 'ASC')->get();
                
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


    public function create(Request $request){

        
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:50|unique:tms_merchant_type'
            //'description' => 'required' 
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

            $merchantType = new MerchantType();
            $merchantType->version = 1; 
            $merchantType->name = $request->name;
            //$merchantType->created_by = $request->header('Tenant-id');
            $merchantType->description = $request->description;
            $this->saveAction($request, $merchantType);
            if ($merchantType->save()) {
                DB::commit();
                $a  =   [   
                    "responseCode"=>"0000",
                    "responseDesc"=> "OK",
                    "generatedId" =>  $merchantType->id
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

        $validator = Validator::make($request->all(), [
            'version' => 'required|numeric|max:32',
            'name' => 'required|max:50|unique:tms_merchant_type',
            //'description' => 'required',
            'id' => 'required' 
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

            $mt = MerchantType::where([
                ['id',$request->id],
                ['version',$request->version]
                
            ])->first();

            $mt->version = $request->version + 1;
            $mt->name = $request->name;
            $mt->description = $request->description;
            $this->updateAction($request,$mt);
            
            if ($mt->save()) {
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
        try {
            $mt = MerchantType::select('id','name','description','version','created_by as createdBy', 'create_ts as createdTime','updated_by as lastUpdateBy','update_ts as lastUpdateTime')
            ->where('id', $request->id)->get();
            if($mt->count()>0)
            {
                $a=["responseCode"=>"0000",
                "responseDesc"=>"OK",
                 "data" => $mt
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

        $validator = Validator::make($request->all(), [
           'version' => 'required' ,
           'id' => 'required'
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
            $mt = MerchantType::where([['id','=',$request->id],['version','=',$request->version]]);
             $cn = $mt->get()->count();
             if( $cn > 0)
             {
              
                $re = $this->deleteAction($request, $mt);
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
