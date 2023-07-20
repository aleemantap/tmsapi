<?php

namespace App\Http\Controllers;
use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;


class MerchantController extends Controller
{
    public function list(Request $request){

        try {

                $pageSize = $request->pageSize;
                $pageNum = $request->pageNum;
                $type_id = $request->type_id;
                $name = $request->name;
                $address = $request->address;
                $zipcode = $request->zipcode;
                // $query = Merchant::query()
                //     ->with(['merchanttype' => function ($query) {
                //         $query->select('id', 'name');
                //     },['district']=> function ($query) {
                //         $query->select('id', 'name');
                //     }
                //     ]);

                $query = Merchant::join('tms_merchant_type', 'tms_merchant.type_id', '=', 'tms_merchant_type.id')
                ->join('tms_district', 'tms_merchant.district_id', '=', 'tms_district.id')
                ->join('tms_city', 'tms_district.city_id', '=', 'tms_city.id')
                ->join('tms_states', 'tms_city.states_id', '=', 'tms_states.id')
                ->join('tms_country', 'tms_states.country_id', '=', 'tms_country.id');
                
                //$query = Merchant::query()->with(['merchanttype','district']);

                // $query = Merchant::whereHas(['merchanttype' => function ($query) {
                 //       $query->where('deleted_by', null);
                   // }])->with('district.customer');// ->withCount('diagnosis');
                
                if($request->type_id != '')
                {
                    $query->where('tms_merchant.type_id', $request->type_id);
                }
                if($request->name != '')
                {
                    $query->where('tms_merchant.name', $request->name);
                }
                if($request->address != '')
                {
                    $query->where('tms_merchant.address', $request->address);
                }
                if($request->zipcode != '')
                {
                    $query->where('tms_merchant.zipcode', $request->zipcode);
                }

                $count = $query->get()->count();
            
                $results = $query->offset(($pageNum-1) * $pageSize) 
                ->limit($pageSize)->orderBy('name', 'ASC')
                ->get(['tms_merchant.*',
                      'tms_merchant_type.name as merchanttype',
                      'tms_district.name as districtname',
                      'tms_city.name as cityname',
                      'tms_states.name as statename',
                      'tms_country.code as countrycode',
                      'tms_country.name as countryname',
                    ]);
                
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
            'name' => 'required|max:100|unique:tms_merchant',
            'company_name' => 'required|max:100|unique:tms_merchant',
            'address' => 'required|max:255',
            'tenant_id' => 'required',
            'district_id' => 'required',
            'zipcode' => 'required|max:5',
            'type_id' => 'required',
             
        ]);
 
        if ($validator->fails()) {
            return response()->json(['responseCode' => '5555', //gagal validasi
                                     'responseDesc' => $validator->errors()]
                                    );
        }

        try {

            $merchant = new Merchant();
            $merchant->version = 1; 
            $merchant->name = $request->name;
            $merchant->company_name = $request->company_name;
            $merchant->address = $request->address;
            $merchant->tenant_id = $request->tenant_id;
            $merchant->district_id = $request->district_id;
            $merchant->zipcode = $request->zipcode;
            $merchant->type_id = $request->type_id;

            if ($merchant->save()) {
                return response()->json(['responseCode' => '0000', //sukses insert
                                          'responseDesc' => 'Merchant created successfully',
                                          
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
            'name' => 'required|max:100|unique:tms_merchant',
            'id' => 'required' 
        ]);
 
        if ($validator->fails()) {
            return response()->json(['responseCode' => '5555', //gagal validasi
                                     'responseDesc' => $validator->errors()]
                                    );
        }

        try {

            $merchant = Merchant::where([
                ['id',$request->id],
                ['version',$request->version]
               
            ])->first();

            $merchant->version = $request->version + 1;
            $merchant->name = $request->name;
            $merchant->company_name = $request->company_name;
            $merchant->address = $request->address;
            $merchant->tenant_id = $request->tenant_id;
            $merchant->district_id = $request->district_id;
            $merchant->zipcode = $request->zipcode;
            $merchant->type_id = $request->type_id;
            
            if ($merchant->save()) {
                return response()->json(['responseCode' => '0000', //sukses update
                                          'responseDesc' => 'Merchant updated successfully',
                                        ]);
            }
        } catch (\Exception $e) {
            return response()->json(['responseCode' => '3333', 'responseDesc' => "Merchant Update Failure"]);
        }
    }
    
    public function show(Request $request){
        try {
            $district = Merchant::where('id', $request->id)->select('id','name','company_name','version','zipcode','address','district_id','type_id');
            
            if($district->get()->count()>0)
            {
                $district =  $district->with(['district' => function ($query) {
                       $query->select('id', 'name');
                    }, 'merchanttype' => function($query){
                        $query->select('id', 'name');
                    }])->get();
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
            $m = Merchant::where('id','=',$request->id)
            ->where('version','=',$request->version);
             $cn = $m->get()->count();
             if( $cn > 0)
             {
                $updateMt = $m->first();
                $current_date_time = \Carbon\Carbon::now()->toDateTimeString();
                $updateMt->delete_ts = $current_date_time; 
                $updateMt->deleted_by = "admin";//Auth::user()->id 
                if ($updateMt->save()) {
                     return response()->json(['responseCode' => '0000', 'responseDesc' => 'Merchant  deleted successfully']);
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
