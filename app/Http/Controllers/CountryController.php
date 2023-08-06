<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class CountryController extends Controller
{
	
    public function list(Request $request){

       

        try {   
            $pageSize = ($request->pageSize)?$request->pageSize:10;
            $pageNum = ($request->pageNum)?$request->pageNum:1;
          
            
            $query = Country::select(
                    'id',
                    'code',
                    'name',
                    'version',
                    'created_by as createdBy', 
                    'create_ts as createdTime', 
                    'updated_by as lastUpdatedBy', 
                    'update_ts as lastUpdatedTime'
                    )
            ->whereNull('deleted_by');
            
            if($request->code != '')
            {
                $query->where('code', 'ILIKE', '%' . $request->code . '%');
            }
            
            
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

    public function create(Request $request){

 
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:50',
            'code' => 'required|max:2|unique:tms_country'
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
           
            $country = new Country();
            $country->version = 1; 
            $country->code = $request->code;
            $country->name = $request->name;
            $this->saveAction($request, $country);

            if ($country->save()) {
                DB::commit();
                $a  =   [   
                        "responseCode"=>"0000",
                        "responseDesc"=>"OK",
                        "generatedId" =>  $country->id
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

        $check = Country::where([
            ['id',$request->id],
            ['name',$request->name],
        ])->first();

        
        $appa = [
            'name' => 'required',
            'code' => 'required',
            'id' => 'required',
            'version' => 'required'
          
        ];
        
        if(!empty($check)){
     
            $appa['name'] = 'required|max:50';
            $appa['code'] = 'required|max:50|unique:tms_country';
           
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
           
            $country = Country::where([
                ['id',$request->id],
                ['version', $request->version]
                
            ])
            ->whereNull('deleted_by')
            ->first();

            if(empty($country)){
                $a=["responseCode"=>"0400",
                        "responseDesc"=>"Data Not Found",
                        'rows' => null
                        ];    
                        return $this->headerResponse($a,$request);
            }

            $country->version = $request->version + 1;
            $country->code = $request->code;
            $country->name = $request->name;
            $this->updateAction($request, $country);
            if ($country->save()) {
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
            //$country = Country::where('id',$request->id)->get();
            $country = Country::select('id', 'code', 'name', 'version','created_by as createdBy', 'create_ts as createdTime', 'updated_by as lastUpdatedBy','update_ts as lastUpdatedTime')
            ->where('id',$request->id)
            ->whereNull('deleted_by')
            ->get();

            if($country->count()>0)
            {
               
                $a=["responseCode"=>"0000",
                    "responseDesc"=>"OK",
                     "data" => $country
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
           
            $country =  DB::table('tms_country')
            ->whereNull('deleted_by')
            ->where([
                ['id',$request->id],
                ['version', $request->version]
            ]);
            
            if($country->get()->count()>0){

                $re = $this->deleteAction($request,$country);
                if ($re) {
                    DB::commit();

                    $a  =   [   
                    "responseCode"=>"0000",
                    "responseDesc"=>"OK"
                    ];    
                return $this->headerResponse($a,$request);
                }
            }else
            {
            $a=["responseCode"=>"0400",
                    "responseDesc"=>"Data Not Found"
                   
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
