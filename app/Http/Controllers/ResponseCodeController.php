<?php

namespace App\Http\Controllers;
use App\Models\ResponseCode;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;


class ResponseCodeController extends Controller
{
    public function list(Request $request){

        try {

                $pageSize = ($request->pageSize)?$request->pageSize:10;
                $pageNum = ($request->pageNum)?$request->pageNum:1;
                $query = 
                ResponseCode:: 
                select('id','type','code','description','version','created_by as createdBy','create_ts as createdTime', 'updated_by as lastUpdatedBy','update_ts as lastUpdatedTime')
                ->whereNull('deleted_by');                
                
                
                if($request->type != '')
                {
                    $query->where('type', 'ILIKE', '%' . $request->type . '%');
                }

                if($request->code != '')
                {
                    $query->where('code', 'ILIKE', '%' . $request->code . '%');
                }

                if($request->description != '')
                {
                    $query->where('description', 'ILIKE', '%' . $request->description . '%');
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


    public function add(Request $request){

        $validator = Validator::make($request->all(), [
            'code' => 'required|max:2',
            'type' => 'required|max:50',
            'description' => 'required|max:255'
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

            $ta = new ResponseCode();
            $ta->version = 1; 
            $ta->code = $request->code;
            $ta->type = $request->type;
            $ta->description = $request->description;
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

    
        $check = ResponseCode::where([
            ['id',$request->id],
            ['version',$request->version],
           
        ])->get();

        
        $appa = [
            'type' => 'required|max:50',
            'description' => 'required|max:255'
          
        ];
        
        if($check->count() == 0){
     
            $appa['code'] = 'required|max:2';
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

            $ta = ResponseCode::where([
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
            $ta->type = $request->type;
            $ta->code = $request->code;
            $ta->description = $request->description;
           

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
            $ta = ResponseCode::select(
                    'id',
                    'type',
                    'code',
                    'description',
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
            $m = ResponseCode::where('id','=',$request->id)
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
