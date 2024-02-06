<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class TenantController extends Controller
{
	
    public function list(Request $request){

       

        try {   
            $pageSize = ($request->pageSize)?$request->pageSize:10;
            $pageNum = ($request->pageNum)?$request->pageNum:1;
          
            
            $query = Tenant::select(
                    'id',
                    'name',
                    'version',
                    'super_tenant_id',
                    'is_super',
                    'created_by as createdBy', 
                    'create_ts as createdTime', 
                    'updated_by as lastUpdatedBy', 
                    'update_ts as lastUpdatedTime'
                    )
            ->whereNull('deleted_by');
           
            if($request->name != '')
            {
                
                $query->where('name', 'ILIKE', '%' . $request->name . '%');
            }

            if($request->is_super != '')
            {
                
                $query->where('is_super',  $request->is_super);
            }

            // if($request->super_tenant_id !== '')
            // {
                
            //     $query->where('super_tenant_id',  $request->super_tenant_id);
            // }
            
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
            'name' => 'required|max:50'
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
           
            $tenant = new Tenant();
            $tenant->version = 1; 
            $tenant->super_tenant_id = $request->super_tenant_id;
            $tenant->is_super = $request->is_super;            
            $tenant->name = $request->name;
            $this->saveAction($request, $tenant);

            if ($tenant->save()) {
                DB::commit();
                $a  =   [   
                        "responseCode"=>"0000",
                        "responseDesc"=>"OK",
                        "generatedId" =>  $tenant->id
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

        $check = Tenant::where([
            ['id',$request->id],
            ['name',$request->name],
        ])->first();

        
        $appa = [
            'name' => 'required',
            'id' => 'required',
            'version' => 'required'
          
        ];
        
        if(!empty($check)){
     
            $appa['name'] = 'required|max:50';
           // $appa['code'] = 'required|max:50|unique:tms_country';
           
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
           
            $tenant = Tenant::where([
                ['id',$request->id],
                ['version', $request->version]
                
            ])
            ->whereNull('deleted_by')
            ->first();

            if(empty($tenant)){
                $a=["responseCode"=>"0400",
                        "responseDesc"=>"Data Not Found",
                        'rows' => null
                        ];    
                        return $this->headerResponse($a,$request);
            }

            

            $tenant->version = $request->version + 1;
            $tenant->name = $request->name;
            $tenant->super_tenant_id = $request->super_tenant_id;
            $tenant->is_super = $request->is_super;  
            $this->updateAction($request, $tenant);
            if ($tenant->save()) {
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
            $country = Tenant::select('id', 'name', 'version', 'is_super','super_tenant_id', 'created_by as createdBy', 'create_ts as createdTime', 'updated_by as lastUpdatedBy','update_ts as lastUpdatedTime')
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
           
            $country =  DB::table('tms_tenant')
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
