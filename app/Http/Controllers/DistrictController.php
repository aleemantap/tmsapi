<?php

namespace App\Http\Controllers;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;


class DistrictController extends Controller
{
    public function list(Request $request){

        try {

                $pageSize = $request->pageSize;
                $pageNum = $request->pageNum;
                $city_id = $request->city_id;
                $name = $request->name;
                $query = District::query()
                    ->with(['city' => function ($query) {
                        $query->select('id', 'name');
                    }]);
                if($request->city_id != '')
                {
                    $query->where('city_id', $request->city_id);
                }
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
            'name' => 'required|max:50|unique:tms_district',
            'city_id' => 'required' 
        ]);
 
        if ($validator->fails()) {
            return response()->json(['responseCode' => '5555', //gagal validasi
                                     'responseDesc' => $validator->errors()]
                                    );
        }

        try {

            $district = new District();
            $district->version = 1; 
            $district->name = $request->name;
            $district->city_id = $request->city_id;

            if ($district->save()) {
                return response()->json(['responseCode' => '0000', //sukses insert
                                          'responseDesc' => 'District created successfully',
                                          
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
            'name' => 'required|max:50|unique:tms_district',
            'city_id' => 'required',
            'id' => 'required' 
        ]);
 
        if ($validator->fails()) {
            return response()->json(['responseCode' => '5555', //gagal validasi
                                     'responseDesc' => $validator->errors()]
                                    );
        }

        try {

            $district = District::where([
                ['id',$request->id],
                ['version',$request->version],
                ['city_id', $request->city_id]
            ])->first();

            $district->version = $request->version + 1;
            $district->name = $request->name;
            
            if ($district->save()) {
                return response()->json(['responseCode' => '0000', //sukses update
                                          'responseDesc' => 'District updated successfully',
                                        ]);
            }
        } catch (\Exception $e) {
            return response()->json(['responseCode' => '3333', 'responseDesc' => "District Update Failure"]);
        }
    }
    
    public function show(Request $request){
        try {
            $district = District::where('id', $request->id)->with(['city' => function ($query) {
                $query->select('id', 'name');
            }])->get();
            if($district->count()>0)
            {
                return response()->json([
                    'responseCode' => '0000', 
                    'responseDesc' => 'OK',
                    'data' => $district
                    
                ]);
            }
            else
            {
                return response()->json([
                    'responseCode' => '0400', 
                    'responseDesc' => 'Data Not Found',
                    'data' =>  $district                    
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
            $district = District::where('id',$request->id)
            ->where('version',$request->version)->get();

            
            $current_date_time = \Carbon\Carbon::now()->toDateTimeString();
            $district->delete_ts = $current_date_time; 
            $district->deleted_by = "admin";//Auth::user()->id
            
             if($district->count() > 0)
             {
                 if ($district->save()) {
                     return response()->json(['responseCode' => '0000', 'responseDesc' => 'District deleted successfully']);
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
