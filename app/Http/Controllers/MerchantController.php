<?php

namespace App\Http\Controllers;
use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class MerchantController extends Controller
{
    public function list(Request $request){

        try {

            $pageSize = ($request->pageSize)?$request->pageSize:10;
            $pageNum = ($request->pageNum)?$request->pageNum:1;
                

                $query = Merchant::select
                            (
                                'tms_merchant.id',
                                'tms_merchant.name',
                                'tms_merchant.company_name as companyName',
                                'tms_district.name as districtName',
                                'tms_city.name as cityName',
                                'tms_states.name as stateName',
                                'tms_country.code as countryCode',
                                'tms_country.name as countryName',
                                'tms_merchant.address',
                                'tms_merchant.zipcode',
                                'tms_merchant_type.name as merchantType',
                                'tms_merchant.version',
                                'tms_merchant.created_by as createdBy',
                                'tms_merchant.create_ts as createdTime',
                                'tms_merchant.updated_by as lastUpdatedBy',
                                'tms_merchant.update_ts as lastUpdatedTime',
                                )
                ->join('tms_merchant_type', 'tms_merchant.type_id', '=', 'tms_merchant_type.id')
                ->join('tms_district', 'tms_merchant.district_id', '=', 'tms_district.id')
                ->join('tms_city', 'tms_district.city_id', '=', 'tms_city.id')
                ->join('tms_states', 'tms_city.states_id', '=', 'tms_states.id')
                ->join('tms_country', 'tms_states.country_id', '=', 'tms_country.id');
                $query->where('tms_merchant.tenant_id', $request->header('Tenant-id'));
              
                if($request->type_id != '')
                {
                    $query->where('tms_merchant.type_id', 'ILIKE', '%' . $request->type_id .'%');
                }
                if($request->name != '')
                {
                    $query->where('tms_merchant.name', 'ILIKE', '%' . $request->name . '%');
                }
                if($request->address != '')
                {
                    $query->where('tms_merchant.address', 'ILIKE', '%' . $request->address .'%');
                }
                if($request->zipcode != '')
                {
                    $query->where('tms_merchant.zipcode', 'ILIKE', '%' . $request->zipcode .'%');
                }

                $count = $query->get()->count();
            
                $results = $query->offset(($pageNum-1) * $pageSize) 
                ->limit($pageSize)->orderBy('tms_merchant.name', 'ASC')
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
            'name' => 'required|max:100|unique:tms_merchant',
            'companyName' => 'required|max:100',
            'address' => 'required|max:255',
            'districtId' => 'required|max:36',
            'zipcode' => 'required|max:5',
            'merchantTypeId' => 'required|max:36'
             
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

            $merchant = new Merchant();
            $merchant->version = 1; 
            $merchant->name = $request->name;
            $merchant->company_name = $request->companyName;
            $merchant->address = $request->address;
            $merchant->tenant_id = $request->header('Tenant-id');
            $merchant->district_id = $request->districtId;
            $merchant->zipcode = $request->zipcode;
            $merchant->type_id = $request->merchantTypeId;
            $this->saveAction($request, $merchant);

            if ($merchant->save()) {
                DB::commit();
                $a  =   [   
                    "responseCode"=>"0000",
                    "responseDesc"=>"OK",
                    "generatedId" =>  $merchant->id
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
            'name' => 'required|max:100|unique:tms_merchant',
            'id' => 'required',
            'companyName' => 'required',
            'districtId' => 'required|max:36',
            'address' => 'required|max:255',
            'zipcode' => 'required|max:5',
            'merchantTypeId' => 'required|max:36'
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

            $merchant = Merchant::where([
                ['id',$request->id],
                ['version',$request->version],
                ['tenant_id', $request->header('Tenant-id')]
               
            ])->first();

            $merchant->version = $request->version + 1;
            $merchant->name = $request->name;
            $merchant->company_name = $request->companyName;
            $merchant->address = $request->address;
            $merchant->district_id = $request->districtId;
            $merchant->zipcode = $request->zipcode;
            $merchant->type_id = $request->merchantTypeId;
            $this->updateAction($request,$merchant);
            
            if ($merchant->save()) {
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
            $merchant = Merchant::where('id', $request->id)
            ->where('tenant_id',$request->header('Tenant-id'))
            ->select('id','name','company_name as companyName','version','zipcode','address','district_id','type_id','created_by as createdBy','create_ts as CreatedTime','updated_by as lastUpadatedBy','update_ts as lastUpdateTime');
            
            if($merchant->get()->count()>0)
            {
                $merchant =  $merchant->with(['district' => function ($query) {
                       $query->select('id', 'name');
                    }, 'merchanttype' => function($query){
                        $query->select('id', 'name');
                    }])->get();
                    
                    $a=["responseCode"=>"0000",
                    "responseDesc"=>"OK",
                     "data" => $merchant
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
            $m = Merchant::where('id','=',$request->id)
            ->where('version','=',$request->version)
            ->where('tenant_id',$request->header('Tenant-id'));
             $cn = $m->get()->count();
             if( $cn > 0)
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
