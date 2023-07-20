<?php

namespace App\Http\Controllers;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;


class CityController extends Controller
{
    public function list(Request $request){

        try {

                $pageSize = $request->pageSize;
                $pageNum = $request->pageNum;
                $states_id = $request->states_id;
                $name = $request->name;
                $query = City ::query()

                    ->with(['state' => function ($query) {
                        $query->select('id', 'name');
                        
                    }])
                    ->whereNull('deleted_by');
                if($request->states_id != '')
                {
                    $query->where('states_id', $request->states_id);
                }
                if($request->name != '')
                {
                    $query->where('name', $request->name);
                }

                //$count = $query->get()->count();
            
                $results = $query->offset(($pageNum-1) * $pageSize) 
                ->limit($pageSize)->orderBy('create_ts', 'DESC')
                
                ->get()->makeHidden(['delete_ts','deleted_by']);

                $count = count($results->toArray());

                if( $count  > 0)
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
                
            
                return response()->json(['responseCode' => '0000', 
                                        'responseDesc' => 'OK',
                                        'pageSize'  =>  $pageSize,
                                        'totalPage' => ceil($count/$pageSize),
                                        'total' => $count,
                                        'rows' => $results
                                    ]);
        } catch (\Exception $e) {
            return response()->json(['status' => '3333', 'message' => $e->getMessage()]);
        }
    }


    public function create(Request $request){

        
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:50|unique:tms_city',
            'states_id' => 'required' 
        ]);
 
        if ($validator->fails()) {
            return response()->json(['responseCode' => '5555', //gagal validasi
                                     'responseDesc' => $validator->errors()]
                                    );
        }

        try {

            $city = new City();
            $city->version = 1; 
            $city->name = $request->name;
            $city->states_id = $request->states_id;

            if ($city->save()) {
                return response()->json(['responseCode' => '0000', //sukses insert
                                          'responseDesc' => 'City created successfully',
                                          
                                        ]);
            }
        } catch (\Exception $e) {
            return response()->json(['responseCode' => '3333', //gagal exception 
                                     'responseDesc' =>  "City Create Failure"
           ]);
        }

    }

    public function update(Request $request){

        $validator = Validator::make($request->all(), [
            'version' => 'required|numeric|max:32',
            'name' => 'required|max:50|unique:tms_city',
            'states_id' => 'required',
            'id' => 'required' 
        ]);
 
        if ($validator->fails()) {
            return response()->json(['responseCode' => '5555', //gagal validasi
                                     'responseDesc' => $validator->errors()]
                                    );
        }

        try {

            $city = City::where([
                ['id',$request->id],
                ['version',$request->version],
                ['states_id', $request->states_id]
            ])->first();

            $city->version = $request->version + 1;
            $city->name = $request->name;
            
            if ($city->save()) {
                return response()->json(['responseCode' => '0000', //sukses update
                                          'responseDesc' => 'City updated successfully',
                                        ]);
            }
        } catch (\Exception $e) {
            return response()->json(['responseCode' => '3333', 'responseDesc' => "City Update Failure"]);
        }
    }
    
    public function show(Request $request){
        try {
            $city = City::where('id', $request->id)->with(['state' => function ($query) {
                $query->select('id', 'name');
            }])->get();
            if($city->count()>0)
            {
                return response()->json([
                    'responseCode' => '0000', 
                    'responseDesc' => 'OK',
                    'data' => $city
                   
                ]);
            }
            else
            {
                return response()->json([
                    'responseCode' => '0400', 
                    'responseDesc' => 'Data Not Found',
                    'data' =>  $city                    
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
            $t= City::where('id','=',$request->id)
            ->where('version','=',$request->version);
             $cn = $t->get()->count();
             if( $cn > 0)
             {
                $update_t = $t->first();
                $current_date_time = \Carbon\Carbon::now()->toDateTimeString();
                $update_t->delete_ts = $current_date_time; 
                $update_t->deleted_by = "admin";//Auth::user()->id 
                if ($update_t->save()) {
                     return response()->json(['responseCode' => '0000', 'responseDesc' => 'City deleted successfully']);
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
