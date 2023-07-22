<?php

namespace App\Http\Controllers;
use App\Models\DeviceModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class DeviceModelController extends Controller
{
    public function list(Request $request){

      
        try {

            $pageSize = ($request->pageSize)?$request->pageSize:10;

            $pageNum = ($request->pageNum)?$request->pageNum:1;
               
                $query = DeviceModel::whereNull('deleted_by');

                 
                if($request->model != '')
                {
                    $query->where('model', 'ILIKE', '%' . $request->modelName .'%');
                }
                if($request->vendor_name != '')
                {
                    $query->where('vendor_name', 'ILIKE', '%' . $request->vendorName . '%');
                }
                if($request->vendor_country != '')
                {
                    $query->where('vendor_country', 'ILIKE', '%' . $request->vendorCountry .'%');
                }
               
                $count = $query->get()->count();
            
                $results = $query->offset(($pageNum-1) * $pageSize) 
                ->limit($pageSize)->orderBy('create_ts', 'DESC')
                ->get(['id','model','vendor_name','vendor_country','version','created_by as createdBy','create_ts as createdTime','updated_by as lastUpdatedBy','update_ts']);
                
               

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
            return response()->json(['status' => '3333', 'message' => $e->getMessage()]);
        }
    }


    public function create(Request $request){
     
        $validator = Validator::make($request->all(), [
            'model' => 'required|max:50|unique:tms_device_model',
            'vendorName' => 'required|max:100|unique:tms_device_model,vendor_name',
            'vendorCountry' => 'required',
           
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

            $model = new DeviceModel();
            $model->version = 1; 
            $model->model = $request->model;
            $model->vendor_name = $request->vendorName;
            $model->vendor_country = $request->vendorCountry;
            $model->model_information = $request->modelInformation;
        
            if ($model->save()) {
                DB::commit();
                $a  =   [   
                    "responseCode"=>"0000",
                    "responseDesc"=>"OK",
                    "generatedId" =>  $model->id
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
            'id' => 'required',
            'model' => 'required'
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

            $dm = DeviceModel::where([
                ['id',$request->id],
                ['version',$request->version]
               
            ])->first();

            $dm->version = $request->version + 1;
            $dm->model = $request->model;
            $dm->vendor_name = $request->vendorName;
            $dm->vendor_country = $request->vendorCountry;
            $dm->model_information = $request->modelInformation;
          
            
            if ($dm->save()) {
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
            $DeviceModel = DeviceModel::where('id', $request->id)->whereNull('deleted_by');
            
            
            if($DeviceModel->get()->count()>0)
            {
                $DeviceModel =  $DeviceModel->get()->makeHidden(['deleted_by', 'delete_ts']);
                return response()->json([
                    'responseCode' => '0000', 
                    'responseDesc' => 'OK',
                    'data' => $DeviceModel
                    
                ]);
            }
            else
            {
           
                return response()->json([
                    'responseCode' => '0400', 
                    'responseDesc' => 'Data Not Found',
                    'data' => []                   
                ]);
            }
            
        }
        catch(\Exception $e)
        {
            return response()->json(['responseCode' => '3333', 'responseDesc' => $e->getMessage()]);
        }
    }


    public function delete(Request $request){

        DB::beginTransaction();
        try {
            $m = DeviceModel::where('id','=',$request->id)
            ->where('version','=',$request->version);
             $cn = $m->get()->count();
             if( $cn > 0)
             {
                $updateMt = $m->first();
                $current_date_time = \Carbon\Carbon::now()->toDateTimeString();
                $updateMt->delete_ts = $current_date_time; 
                $updateMt->deleted_by = "admin";//Auth::user()->id 
                if ($updateMt->save()) {
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
            return response()->json(['responseCode' => '3333', 'responseDesc' => $e->getMessage()]);
        }
    }


    
}
