<?php

namespace App\Http\Controllers;
use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class CityController extends Controller
{
    public function list(Request $request){

        try {


            $pageSize = ($request->pageSize)?$request->pageSize:10;
            $pageNum = ($request->pageNum)?$request->pageNum:1;
            

                $query = City ::select('id','name','version','states_id','created_by as createdBy','create_ts as createdTime','updated_by as lastUpdatedBy','update_ts as lastUpdatedTime')

                    ->with(['state' => function ($query) {
                        $query->select('id', 'name');
                        
                    }])
                    ->whereNull('deleted_by');
                if($request->stateId != '')
                {
                    $query->where('states_id', 'ILIKE', '%' . $request->stateId . '%');
                }
                if($request->name != '')
                {
                    $query->where('name', 'ILIKE', '%' . $request->name . '%');
                }

                //$count = $query->get()->count();
            
                $results = $query->offset(($pageNum-1) * $pageSize) 
                ->limit($pageSize)->orderBy('create_ts', 'DESC')
                
                ->get()->makeHidden(['delete_ts','deleted_by','states_id']);

                $count = count($results->toArray());

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
            'name' => 'required|max:50',
            'states_id' => 'required' 
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

            $city = new City();
            $city->version = 1; 
            $city->name = $request->name;
            $city->states_id = $request->states_id;
            $this->saveAction($request,$city);

            if ($city->save()) {
                DB::commit();
                $a  =   [   
                    "responseCode"=>"0000",
                    "responseDesc"=>"OK",
                    "generatedId" =>  $city->id
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
            ['name',$request->name]
        ])->first();

        
        $appa = [
            'name' => 'required',
            'states_id' => 'required',
            'id' => 'required',
            'version' => 'required'
          
        ];
        
        if(!empty($check)){
     
            $appa['name'] = 'required|max:50';
            // ini awalnya ada pengecekan unique choyyy... jadi bikinnya begini..
           
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

            $city = City::where([
                ['id',$request->id],
                ['version',$request->version]
            ])
            ->whereNull('deleted_by')
            ->first();

            if(empty($city)){
                $a=["responseCode"=>"0400",
                "responseDesc"=>"Data Not Found",
                 "data" => null
                ];    
                return $this->headerResponse($a,$request); 
            }

            $city->version = $request->version + 1;
            $city->name = $request->name;
            $city->states_id = $request->states_id;
            $this->updateAction($request,$city);
            
            if ($city->save()) {
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
            $city = City::select('id', 'name','version','states_id','created_by as createdBy', 'create_ts as createdTime','updated_by as lastUpdatedBy','update_ts as lastUpdatedTime')
            ->whereNull('deleted_by')
            ->where('id', 'ILIKE', '%' . $request->id . '%')->with(['state' => function ($query) {
                $query->select('id', 'name');
            }])->get()->makeHidden(['states_id']);
            if($city->count()>0)
            {
                $a=["responseCode"=>"0000",
                    "responseDesc"=>"OK",
                     "data" => $city
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
            $t= City::where('id','=',$request->id)
            ->whereNull('deleted_by')
            ->where('version','=',$request->version);
             $cn = $t->get()->count();
             if( $cn > 0)
             {
                //$update_t = $t->first();
                $re = $this->deleteAction($request, $t);
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