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

        //return $this->checkTenant($request);

        try {   
            $pageSize = $request->pageSize;
            $pageNum = $request->pageNum;
          
            
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
                    'rows' => $results
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
            'code' => 'required|max:2'
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

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:50',
            'code' => 'required|max:2',
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
           
            $country = Country::where([
                ['id',$request->id],
                //['code',$request->code],
                ['version', $request->version]
            ])->first();

            $country->version = $request->version + 1;
            $country->code = $request->code;
            $country->name = $request->name;
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

        try {
            //$country = Country::where('id',$request->id)->get();
            $country = Country::select('id', 'code', 'name', 'version','created_by as createdBy', 'create_ts as createdTime', 'updated_by as lastUpdatedBy','update_ts as lastUpdatedTime')->
            where('id',$request->id)->get();

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
                     "data" => $country
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
            $country = Country::where([
                ['id',$request->id],
                ['version', $request->version]
            ])->first();
            $current_date_time = \Carbon\Carbon::now()->toDateTimeString();
            $country->delete_ts = $current_date_time; 
            $country->deleted_by = "admin";//Auth::user()->id
            
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
    
}
