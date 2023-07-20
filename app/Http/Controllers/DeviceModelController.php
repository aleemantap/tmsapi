<?php

namespace App\Http\Controllers;
use App\Models\DeviceModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;


class DeviceModelController extends Controller
{
    public function list(Request $request){

        try {

                $pageSize = $request->pageSize;
                $pageNum = $request->pageNum;
                $model = $request->model;
                $vendor_name = $request->vendor_name;
                $vendor_country = $request->vendor_country;
                
                $query = DeviceModel::whereNull('deleted_by');

                 
                if($request->model != '')
                {
                    $query->where('model', $request->model);
                }
                if($request->vendor_name != '')
                {
                    $query->where('vendor_name', $request->vendor_name);
                }
                if($request->vendor_country != '')
                {
                    $query->where('vendor_country', $request->vendor_country);
                }
               
                $count = $query->get()->count();
            
                $results = $query->offset(($pageNum-1) * $pageSize) 
                ->limit($pageSize)->orderBy('model', 'ASC')
                ->get(['id','model','vendor_name','vendor_country','version','created_by','create_ts','updated_by','update_ts']);
                
                if($count > 0)
                {
                    return response()->json(['responseCode' => '0000', 
                                        'responseDesc' => 'OK',
                                        'pageSize'  =>  $pageSize,
                                        'totalPage' => ceil($count/$pageSize),
                                        'total' => $count,
                                        'rows' => $results
                                    ]);
                }
                else
                {
                    return response()->json(['responseCode' => '0400', 
                                        'responseDesc' => 'Data Not Found',
                                        'rows' => $results
                                        
                                    ]);
                }
                
        } catch (\Exception $e) {
            return response()->json(['status' => '3333', 'message' => $e->getMessage()]);
        }
    }


    public function create(Request $request){
     
        $validator = Validator::make($request->all(), [
            'model' => 'required|max:50|unique:tms_device_model',
            'vendor_name' => 'required|max:100|unique:tms_device_model',
            'vendor_country' => 'required',
           
        ]);
 
        if ($validator->fails()) {
            return response()->json(['responseCode' => '5555', //gagal validasi
                                     'responseDesc' => $validator->errors()]
                                    );
        }

        try {

            $model = new DeviceModel();
            $model->version = 1; 
            $model->model = $request->model;
            $model->vendor_name = $request->vendor_name;
            $model->vendor_country = $request->vendor_country;
            $model->model_information = $request->model_information;
        
            if ($model->save()) {
                return response()->json(['responseCode' => '0000', //sukses insert
                                          'responseDesc' => 'Device Model created successfully',
                                          'generatedId' =>  $model->id
                                        ]);
            }
        } catch (\Exception $e) {
            return response()->json(['responseCode' => '3333', //gagal exception 
                                     'responseDesc' => $e->getMessage()
                                    ]);
        }

    }

    public function update(Request $request){

        $validator = Validator::make($request->all(), [
            'version' => 'required|numeric|max:32',
            'id' => 'required' 
        ]);
 
        if ($validator->fails()) {
            return response()->json(['responseCode' => '5555', //gagal validasi
                                     'responseDesc' => $validator->errors()]
                                    );
        }

        try {

            $dm = DeviceModel::where([
                ['id',$request->id],
                ['version',$request->version]
               
            ])->first();

            $dm->version = $request->version + 1;
            $dm->model = $request->model;
            $dm->vendor_name = $request->vendor_name;
            $dm->vendor_country = $request->vendor_country;
            $dm->model_information = $request->model_information;
          
            
            if ($dm->save()) {
                return response()->json(['responseCode' => '0000', //sukses update
                                          'responseDesc' => 'Device Model updated successfully',
                                        
                                        ]);
            }
        } catch (\Exception $e) {
            return response()->json([
            'responseCode' => '3333', 
            'responseDesc' => "Device Model Update Failure"
        ]);
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
                     return response()->json(['responseCode' => '0000', 'responseDesc' => 'Device Model  deleted successfully']);
                 }
             }
             else
             {
                     return response()->json(['responseCode' => '0400', 'responseDesc' => 'Data Not Found']);
              }

            
        } catch (\Exception $e) {
            return response()->json(['responseCode' => '3333', 'responseDesc' => $e->getMessage()]);
        }
    }


    
}
