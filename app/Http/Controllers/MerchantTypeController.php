<?php

namespace App\Http\Controllers;
use App\Models\MerchantType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;


class MerchantTypeController extends Controller
{
    public function list(Request $request){

        try {

                $pageSize = $request->pageSize;
                $pageNum = $request->pageNum;
                $name = $request->name;
                $query = MerchantType::whereNull('deleted_by');
                
                if($request->name != '')
                {
                    $query->where('name', $request->name);
                }

                $count = $query->get()->count();
            
                $results = $query->offset(($pageNum-1) * $pageSize) 
                ->limit($pageSize)->orderBy('name', 'ASC')->get();
                
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
            'name' => 'required|max:50|unique:tms_merchant_type'
            //'description' => 'required' 
        ]);
 
        if ($validator->fails()) {
            return response()->json(['responseCode' => '5555', //gagal validasi
                                     'responseDesc' => $validator->errors()]
                                    );
        }

        try {

            $merchantType = new MerchantType();
            $merchantType->version = 1; 
            $merchantType->name = $request->name;
            $merchantType->description = $request->description;

            if ($merchantType->save()) {
                return response()->json(['responseCode' => '0000', //sukses insert
                                          'responseDesc' => 'MerchantType created successfully',
                                          'generatedId'  =>  $merchantType->id
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
            'name' => 'required|max:50|unique:tms_merchant_type',
            //'description' => 'required',
            'id' => 'required' 
        ]);
 
        if ($validator->fails()) {
            return response()->json(['responseCode' => '5555', //gagal validasi
                                     'responseDesc' => $validator->errors()]
                                    );
        }

        try {

            $mt = MerchantType::where([
                ['id',$request->id],
                ['version',$request->version]
                
            ])->first();

            $mt->version = $request->version + 1;
            $mt->name = $request->name;
            $mt->description = $request->name;
            
            if ($mt->save()) {
                return response()->json(['responseCode' => '0000', //sukses update
                                          'responseDesc' => 'MechantType updated successfully',
                                        ]);
            }
        } catch (\Exception $e) {
            return response()->json(['responseCode' => '3333', 'responseDesc' => "MechantType Update Failure"]);
        }
    }
    
    public function show(Request $request){
        try {
            $mt = MerchantType::where('id', $request->id)->get();
            if($mt->count()>0)
            {
                return response()->json([
                    'responseCode' => '0000', 
                    'responseDesc' => 'OK',
                    'data' => $mt
                    
                ]);
            }
            else
            {
                return response()->json([
                    'responseCode' => '0400', 
                    'responseDesc' => 'Data Not Found',
                    'data' =>  $mt                    
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
            $mt = MerchantType::where('id','=',$request->id)
            ->where('version','=',$request->version);
             $cn = $mt->get()->count();
             if( $cn > 0)
             {
                $updateMt = $mt->first();
                $current_date_time = \Carbon\Carbon::now()->toDateTimeString();
                $updateMt->delete_ts = $current_date_time; 
                $updateMt->deleted_by = "admin";//Auth::user()->id 
                if ($updateMt->save()) {
                     return response()->json(['responseCode' => '0000', 'responseDesc' => 'Merchant Type deleted successfully']);
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
