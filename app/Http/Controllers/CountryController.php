<?php

namespace App\Http\Controllers;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//use App\Http\Controllers\BaseController as BaseController;


class CountryController extends Controller
{
    //
    // function __construct(Request $request) {
       
    //     return parent::__construct($request);
        
    // }

    public function list(Request $request){

        //return $this->checkTenant($request);

        try {   
            $pageSize = $request->pageSize;
            $pageNum = $request->pageNum;
            $code = $request->code;
            $name = $request->name;

            
            $query = Country::query()->whereNull('deleted_by');
            if($request->code != '')
            {
                $query->where('code','ILIKE', '%'.$request->code.'%');
            }
            if($request->name != '')
            {
                $query->where('name', 'ILIKE','%'. $request->name.'%');
            }

            $count = $query->get()->count();

            $results = $query->offset(($pageNum-1) * $pageSize) 
            ->limit($pageSize)->orderBy('create_ts', 'DESC')->get();
            
        
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
            'name' => 'required|max:50',
            'code' => 'required|max:2'
        ]);
 
        if ($validator->fails()) {
            return response()->json(['responseCode' => '5555', //gagal validasi
                                     'responseDesc' => $validator->errors()]
                                    );
        }
        DB::beginTransaction();
        try {
            $country = new Country();
            $country->version = 1; 
            $country->code = $request->code;
            $country->name = $request->name;
            
            if ($country->save()) {

                DB::commit();
                return response()->json(['responseCode' => '0000', //sukses insert
                                          //'responseDesc' => 'Country created successfully',
                                          'responseDesc' => $this->responseSuccess,
                                          'generatedId'  =>  $country->id,
                                        ]); 
            }
           
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['responseCode' => '3333', //gagal exception 
                                     'responseDesc' => 'Country created Failure'
                                    ]);
        }

    }

    public function update(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:50',
            'code' => 'required|max:2',
            'id' => 'required' 
        ]);
 
        if ($validator->fails()) {
            return response()->json(['responseCode' => '5555', //gagal validasi
                                     'responseDesc' => $validator->errors()]
                                    );
        }

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
                return response()->json(['responseCode' => '0000', //sukses update
                                          'responseDesc' => 'Country updated successfully',
                                        ]);
            }
        } catch (\Exception $e) {
            return response()->json(['responseCode' => '3333', 'responseDesc' => $e->getMessage()]);
        }
    }

    public function show(Request $request){

        try {
            $country = Country::where('id',$request->id)->get();

            if($country->count()>0)
            {
                return response()->json([
                    'responseCode' => '0000', 
                    'responseDesc' => 'OK',
                    'data' =>  $country
                    
                ]);
            }
            else
            {
                return response()->json([
                    'responseCode' => '0400', 
                    'responseDesc' => 'Data Not Found',
                    'data' =>  $country
                    
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
            $country = Country::where([
                ['id',$request->id],
                ['version', $request->version]
            ])->first();
            $current_date_time = \Carbon\Carbon::now()->toDateTimeString();
            $country->delete_ts = $current_date_time; 
            $country->deleted_by = "admin";//Auth::user()->id
            
            if ($country->save()) {
                return response()->json(['responseCode' => '0000', 'responseDesc' => 'Country deleted successfully']);
            }
        } catch (\Exception $e) {
            return response()->json(['responseCode' => '3333', 'responseDesc' => $e->getMessage()]);
        }
    }
    
}
