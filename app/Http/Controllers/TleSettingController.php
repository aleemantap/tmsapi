<?php

namespace App\Http\Controllers;

use App\Models\Tlesetting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class TleSettingController extends Controller
{
    public function list(Request $request){

        try {

                $pageSize = ($request->pageSize)?$request->pageSize:10;
                $pageNum = ($request->pageNum)?$request->pageNum:1;
                $query = 
                Tlesetting:: 
                select( 
                        'id',
                        'tle_id as tleId',
                        'tle_eft_sec as tleEftSec',
                        'acquirer_id as acquirerId',
                        'tle_ver as tleVer',
                        'version',
                        'created_by as createdBy',
                        'create_ts as createdTime',
                        'updated_by as lastUpdatedBy',
                        'update_ts as lastUpdatedTime')
                ->whereNull('deleted_by');                
                
               
                if($request->tleId != '')
                {
                    $query->where('tle_id', 'ILIKE', '%' . $request->tleId . '%');
                }

                $count = $query->get()->count();
            
                $results = $query->offset(($pageNum-1) * $pageSize) 
                ->limit($pageSize)->orderBy('tleId', 'ASC')->get();
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


    public function add(Request $request){


        $validator = Validator::make($request->all(), [
            'tleId' => 'required|max:2',
            'tleEftSec' => 'required|max:10',
            'acquirerId'  => 'required|max:3',
            'ltmkAid' => 'required|max:3',
            'vendorId' => 'required|max:8',
            'tleVer' => 'required|max:1',
            'kmsSecureNii' => 'max:4',
            'edcSecureNii' => 'max:4',
            'capkExponent' => 'max:2',
            'capkLength' => 'numeric',
            'aidLength' => 'numeric',
            'encryptedField1' => 'max:3',
            'encryptedField2' => 'max:3',
            'encryptedField3' => 'max:3',
            'encryptedField4' => 'max:3',
            'encryptedField5' => 'max:3',
            'encryptedField6' => 'max:3',
            'encryptedField7' => 'max:3',
            'encryptedField8' => 'max:3',
            'encryptedField9' => 'max:3',
            'encryptedField10' => 'max:3'
           
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

            $c = new Tlesetting();
            $c->version = 1; 
            $c->tle_id  = $request->tleId;
            $c->tle_eft_sec  = $request->tleEftSec;
            $c->acquirer_id  = $request->acquirerId;
            $c->ltmk_aid  = $request->ltmkAid;
            $c->vendor_id  = $request->vendorId;
            $c->tle_ver  = $request->tleVer;
            $c->kms_secure_nii  = $request->kmsSecureNii;
            $c->edc_secure_nii  = $request->edcSecureNii;
            $c->capk_exponent  = $request->capkExponent;
            $c->capk_length  = $request->capkLength;
            $c->capk_value  = $request->capkValue;
            $c->aid_length  = $request->aidLength;
            $c->aid_value  = $request->aidValue;
            $c->field_encrypted1  = $request->encryptedField1;
            $c->field_encrypted2  = $request->encryptedField2;
            $c->field_encrypted3  = $request->encryptedField3;
            $c->field_encrypted4  = $request->encryptedField4;
            $c->field_encrypted5  = $request->encryptedField5;
            $c->field_encrypted6  = $request->encryptedField6;
            $c->field_encrypted7  = $request->encryptedField7;
            $c->field_encrypted8  = $request->encryptedField8;
            $c->field_encrypted9  = $request->encryptedField9;
            $c->field_encrypted10  = $request->encryptedField10;
           // $c->field_encrypted11  = $request->encryptedField11;

            $this->saveAction($request, $c);

            if ($c->save()) {
                DB::commit();
                $a  =   [   
                    "responseCode"=>"0000",
                    "responseDesc"=>"OK",
                    "generatedId" =>  $c->id
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
  
        $check = Tlesetting::where([
            ['id',$request->id],
            ['version',$request->version]
            //['name',$request->name]
        ])->get();


        $appa = [
            //'name' => 'required',
            'version' => 'required',
            'tleId' => 'required|max:2',
            'tleEftSec' => 'required|max:10',
            'acquirerId'  => 'required|max:3',
            'ltmkAid' => 'required|max:3',
            'vendorId' => 'required|max:8',
            'tleVer' => 'required|max:1',
            'kmsSecureNii' => 'max:4',
            'edcSecureNii' => 'max:4',
            'capkExponent' => 'max:2',
            'capkLength' => 'numeric',
            'aidLength' => 'numeric',
            'encryptedField1' => 'max:3',
            'encryptedField2' => 'max:3',
            'encryptedField3' => 'max:3',
            'encryptedField4' => 'max:3',
            'encryptedField5' => 'max:3',
            'encryptedField6' => 'max:3',
            'encryptedField7' => 'max:3',
            'encryptedField8' => 'max:3',
            'encryptedField9' => 'max:3',
            'encryptedField10' => 'max:3'
          
        ];
        
        // if($check->count() == 0){
     
        //     $appa['name'] = 'required';
           
           
        // }
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

            $c = Tlesetting::where([
                ['id',$request->id],
                ['version',$request->version]
                
            ])
            ->whereNull('deleted_by')
            ->first();

            if(empty($c)){
                $a=["responseCode"=>"0400",
                "responseDesc"=>"Data Not Found"
                ];    
            return $this->headerResponse($a,$request);
            }
            
            $c->version = $request->version + 1;
            $c->tle_id  = $request->tleId;
            $c->tle_eft_sec  = $request->tleEftSec;
            $c->acquirer_id  = $request->acquirerId;
            $c->ltmk_aid  = $request->ltmkAid;
            $c->vendor_id  = $request->vendorId;
            $c->tle_ver  = $request->tleVer;
            $c->kms_secure_nii  = $request->kmsSecureNii;
            $c->edc_secure_nii  = $request->edcSecureNii;
            $c->capk_exponent  = $request->capkExponent;
            $c->capk_length  = $request->capkLength;
            $c->capk_value  = $request->capkValue;
            $c->aid_length  = $request->aidLength;
            $c->aid_value  = $request->aidValue;
            $c->field_encrypted1  = $request->encryptedField1;
            $c->field_encrypted2  = $request->encryptedField2;
            $c->field_encrypted3  = $request->encryptedField3;
            $c->field_encrypted4  = $request->encryptedField4;
            $c->field_encrypted5  = $request->encryptedField5;
            $c->field_encrypted6  = $request->encryptedField6;
            $c->field_encrypted7  = $request->encryptedField7;
            $c->field_encrypted8  = $request->encryptedField8;
            $c->field_encrypted9  = $request->encryptedField9;
            $c->field_encrypted10  = $request->encryptedField10;
            //$c->field_encrypted11  = $request->encryptedField11;
            
          
            $this->updateAction($request, $c);
            
            if ($c->save()) {
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
    
    public function get(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required|max:36'
        ]);
 
        if ($validator->fails()) {
            $a  =   [   
                "responseCode"=>"5555",
                "responseDesc"=>$validator->errors()
                ];    
            return $this->headerResponse($a,$request);
        }
        
           

        try {
            $p = Tlesetting::select(
                    'id',
                    'tle_id as tleId',
                    'tle_eft_sec as tleEftSec',
                    'acquirer_id as acquirerId',
                    'ltmk_aid as ltmkAid',
                    'vendor_id as vendorId',
                    'tle_ver as tleVer',
                    'kms_secure_nii as kmsSecureNii',
                    'edc_secure_nii  as edcSecureNii',
                    'capk_exponent  as capkExponent',
                    'capk_length  as capkLength',
                    'capk_value  as capkValue',
                    'aid_length  as aidLength',
                    'aid_value   as aidValue',
                    'field_encrypted1  as encryptedField1',
                    'field_encrypted2  as encryptedField2',
                    'field_encrypted3  as encryptedField3',
                    'field_encrypted4  as encryptedField4',
                    'field_encrypted5  as encryptedField5',
                    'field_encrypted6  as encryptedField6',
                    'field_encrypted7  as encryptedField7',
                    'field_encrypted8  as encryptedField8',
                    'field_encrypted9  as encryptedField9',
                    'field_encrypted10  as encryptedField10',
                    //'field_encrypted11  as encryptedField11',
                    'version',
                    'created_by as createdBy',
                    'create_ts as createdTime',
                    'updated_by as lastUpdatedBy',
                    'update_ts as lastUpdatedTime'
                    )
            ->where('id', 'ILIKE', '%' . $request->id . '%')
            ->whereNull('deleted_by')
            ->get();
            if($p->count()>0)
            {
                $a=["responseCode"=>"0000",
                    "responseDesc"=>"OK",
                     "data" => $p
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
            $m = Tlesetting::where('id','=',$request->id)
            ->whereNull('deleted_by')
            ->where('version','=',$request->version);
            //->where('tenant_id',$request->header('Tenant-id'));
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
