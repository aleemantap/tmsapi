<?php

namespace App\Http\Controllers;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class StateController extends Controller
{
    public function list(Request $request){

        try {

                $pageSize = $request->pageSize;
                $pageNum = $request->pageNum;
                $country_id = $request->countryId;
                $name = $request->name;
                $query = State::select('id','country_id','name','version','created_by as createdBy','create_ts as createdTime', 'updated_by as lastUpdatedBy','update_ts as lastUpdatedTime')->whereNull('deleted_by')
                    ->with(['country' => function ($query) {
                        $query->select('id', 'code','name');
                    }]);
                if($request->country_id != '')
                {
                    $query->where('country_id', 'ILIKE', '%' . $request->countryId . '%');
                }
                if($request->name != '')
                {
                    $query->where('name', 'ILIKE', '%' . $request->name . '%');
                }

                $count = $query->get()->count();
            
                $results = $query->offset(($pageNum-1) * $pageSize) 
                ->limit($pageSize)->orderBy('name', 'ASC')->get()->makeHidden('country_id');
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
            'country_id' => 'required' 
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

            $st = new State();
            $st->version = 1; 
            $st->name = $request->name;
            $st->country_id = $request->country_id;

            if ($st->save()) {
                DB::commit();
                $a  =   [   
                    "responseCode"=>"0000",
                    "responseDesc"=>"OK",
                    "generatedId" =>  $st->id
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
            'name' => 'required|max:50',
            'country_id' => 'required',
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

            $st = State::where([
                ['id',$request->id],
                ['version',$request->version],
                ['country_id', $request->country_id]
            ])->first();

            $st->version = $request->version + 1;
            $st->name = $request->name;
            
            if ($st->save()) {
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
            $state = State::select('id','name','country_id','version','created_by as createdBy', 'create_ts as createdTime','updated_by as lastUpdatedBy', 'update_ts as lastUpdatedTime')
            ->where('id', 'ILIKE', '%' . $request->id . '%')->with(['country' => function ($query) {
                $query->select('id', 'code','name');
            }])->get();
            if($state->count()>0)
            {
                $a=["responseCode"=>"0000",
                    "responseDesc"=>"OK",
                     "data" => $state
                    ];    
                return $this->headerResponse($a,$request);
            }
            else
            {
                $a=["responseCode"=>"0400",
                    "responseDesc"=>"Data Not Found",
                     "data" => $state
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
            $state = State::where([
                ['id',$request->id],
                ['version', $request->version]
            ])->first();
            $current_date_time = \Carbon\Carbon::now()->toDateTimeString();
            $state->delete_ts = $current_date_time; 
            $state->deleted_by = "admin";//Auth::user()->id
            
            if ($state->save()) {
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
