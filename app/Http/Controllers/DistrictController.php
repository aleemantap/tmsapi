<?php

namespace App\Http\Controllers;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class DistrictController extends Controller
{
    public function list(Request $request){

        try {

            $pageSize = ($request->pageSize)?$request->pageSize:10;
            $pageNum = ($request->pageNum)?$request->pageNum:1;
                 $query = District::select('id','name','city_id','version','created_by as createdBy','create_ts as createdTime', 'updated_by as lastUpdateBy','update_ts as lastUpdatedTime')
                    ->with(['city' => function ($query) {
                        $query->select('id', 'name');
                    }]);
                if($request->cityId != '')
                {
                    $query->where('city_id','ILIKE','%' . $request->cityId . '%');
                }
                if($request->name != '')
                {
                    $query->where('name', 'ILIKE','%' . $request->name . '%');
                }

                $count = $query->get()->count();
            
                $results = $query->offset(($pageNum-1) * $pageSize) 
                ->limit($pageSize)->orderBy('name', 'ASC')->get()->makeHidden(['city_id']);
                
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
            'name' => 'required|max:50|unique:tms_district',
            'city_id' => 'required' 
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

            $district = new District();
            $district->version = 1; 
            $district->name = $request->name;
            $district->city_id = $request->city_id;
            $this->saveAction($request, $district);

            if ($district->save()) {
                DB::commit();
                $a  =   [   
                    "responseCode"=>"0000",
                    "responseDesc"=>"OK",
                    "generatedId" =>  $district->id
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
            'name' => 'required|max:50|unique:tms_district',
            'city_id' => 'required',
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

            $district = District::where([
                ['id',$request->id],
                ['version',$request->version],
                ['city_id', $request->city_id]
            ])->first();

            $district->version = $request->version + 1;
            $district->name = $request->name;
            $this->saveAction($request, $district);
            
            
            if ($district->save()) {
                DB::commit();
                $a  =   [   
                    "responseCode"=>"0000",
                    "responseDesc"=>"OK"
                    ];    
                return $this->headerResponse($a,$request);
            }
        } catch (\Exception $e) {
            DB::rollback();
            $a  =   [   
                "responseCode"=>"3333",
                "responseDesc"=>$e->getMessage()
                ];    
            return $this->headerResponse($a,$request);
        }
    }
    
    public function show(Request $request){
        try {
            $district = District::select('id','name','version','city_id','created_by as createdBy','create_ts as createdTime','updated_by as lastUpdatedBy','update_ts as lastUpdatedTime')
            ->where('id', $request->id)->with(['city' => function ($query) {
                $query->select('id', 'name');
            }])->get()->makeHidden(['city_id']);
            if($district->count()>0)
            {
                $a=["responseCode"=>"0000",
                "responseDesc"=>"OK",
                 "data" => $district
                ];    
            return $this->headerResponse($a,$request);
            }
            else
            {
                $a=["responseCode"=>"0400",
                "responseDesc"=>"Data Not Found",
                 "data" => $district
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
           
            $m = District::where('id','=',$request->id)
            ->where('version','=',$request->version);
            
             if($m->get()->count() > 0)
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
