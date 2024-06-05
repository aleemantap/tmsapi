<?php

namespace App\Http\Controllers;
use App\Models\Terminal;
use App\Models\TerminalGroupLink;
use App\Models\DeviceModel;
use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
// use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Models\DeviceProfile;
use App\Models\TerminalGroup;



class ImportTerminalController extends Controller
{

    public function importCek(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
           
            'userName' => 'required', 
            'file' => 'required|mimes:xlsx, xls',
          
        ]);
 
        if ($validator->fails()) {
             $a  =   [   
                "responseCode"=>"5555",
                "responseDesc"=>$validator->errors()
                ];    
            return $this->headerResponse($a,$request);
        }

       // DB::beginTransaction();
        try {

            $file = $request->file('file');
            $tenant = $request->header('Tenant-id');
            $userName = $request->userName;
            //$collection = (new FastExcel)->import($file);
            $collection = (new FastExcel)->import($file, function ($line) use($tenant,$userName) {
                            
                            $list = null;
                
                            $s = $this->cekSn($line['SN'],$tenant);
                            $m = $this->cekModel($line['Model'],$tenant);
                            $mer = $this->cekMerchant($line['Merchant'],$tenant);
                            $p = $this->cekProfile($line['Profile'],$tenant);
                            $g = $this->cekGroup($line['Group'],$tenant);

                            $bo = true;
                            if($s[1]==0)
                            {
                                $bo = false;
                            }
                            if($m[1]==0)
                            {
                                $bo = false;
                            }
                            if($mer[1]==0)
                            {
                                $bo = false;
                            }
                            if($p[1]==0)
                            {
                                $bo = false;
                            }

                            if($g[1]==0)
                            {
                                $bo = false;
                            }

                            if($bo==true)
                            {
                                $grupId = $g[0];
                                $grupN = $g[2];
                             
                                $gId = implode(',', $grupId); 
                                $gN = implode(',', $grupN);    
                                
                                $list = collect([
                                    "user_name"=>$userName,
                                    "terminal_id"=>null,
                                    "sn"=>$line['SN'],
                                    "model_id"=>$m[0],
                                    "modelName"=>$line['Model'],
                                    "profile_id"=>$p[0],
                                    "profileName"=>$line['Profile'],
                                    "merchant_id"=>$mer[0],
                                    "merchantName"=>$line['Merchant'],
                                    "group_id" => $gId,
                                    "groupName" => $gN,
                                    "note" => "Group yang dapat diimport sebanyak ". count($grupId) ." dari ". count(explode(",",$line['Group'])),
                                    
                                ]);

                                 return  $list;

                            }
                   
                         
                });
            
                $a  =   [   
                "responseCode"=>"0000",
                "responseDesc"=>"OK",
                "result" => $collection
                ];   

                return $this->headerResponse($a,$request);
           

            
        } catch (\Exception $e) {
            //DB::rollBack();
            $a  =   [
                "responseCode"=>"3333",
                "responseDesc"=>$e->getMessage()
                ];    
            return $this->failedInssertResponse($a,$request);
        }

    }


   

    private function cekSn($sn,$tenant_id)
    {
        
         $query = Terminal::query()
                 ->where('tms_terminal.tenant_id',$tenant_id)
                 ->where('sn',$sn)
                 ->where(function(\Illuminate\Database\Eloquent\Builder $query) {
                        $query->where('tms_terminal.deleted_by', '')->orWhereNull('tms_terminal.deleted_by');
                  });

        $count = $query->get()->count();
        if($count > 0)
        {
            return [null,0];
        }
        else
        {
            return [$sn,1]; 
        }

        
    } 

    private function cekProfile($p,$tenant_id)
    {
        $query = DeviceProfile::whereNull('deleted_by')->where('name',$p);
        $count = $query->get()->count();
        if($count > 0)
        {
              $results = $query->get(['id']);
              return [$results[0]['id'],1];
        
        }
        else
        {
             return [null,0];
        }
    }

    private function cekModel($m,$tenant_id)
    {
        
            $query = DeviceModel::whereNull('deleted_by')->where('model',$m);

            $count = $query->get()->count();

            if($count > 0)
            {
                  $results = $query->get(['id']);
                  return [$results[0]['id'],1];
            
            }
            else
            {
                 return [null,0];
            }

    }

    private function cekMerchant($m,$tenant_id)
    {
       
                $query = Merchant::query()
                ->where('tms_merchant.tenant_id', $tenant_id)->whereNull('tms_merchant.deleted_by')
                ->where('tms_merchant.name', $m );
                $count = $query->get()->count();
          
                if($count > 0)
                {
                      $results = $query->get(['tms_merchant.id']);
                      return [$results[0]['id'],1];
                }
                else
                {
                     return [null,0];
                }

    }
  
    private function cekGroup($g,$tenant_id)
    {
        
        $r = explode(",",$g);
        $ar = array();
        $arG = array();

        if(count($r)>0)
        {
            for($i=0; $i<count($r); $i++)
            {
               

                $query = TerminalGroup::query()
                ->where('tenant_id',$tenant_id)
                ->where('name',$g)
                ->whereNull('deleted_by');

                $count = $query->get()->count();
                $response = $query->get(['id']);
                if($count > 0)
                 {
                    
                    array_push($ar,$response[0]['id']);
                    array_push($arG,$r[$i]);
                    
                }
                
            }
        }

        if(count($r)==0 or count($ar)==0  )
        {
            return [null,0];
        }
        else
        {
            return [$ar,1,$arG];
        }

        
    }

     
    public function importBatch(Request $request)
    {
        $validator = Validator::make($request->all(), [
           
            'dataArrayTerminal' => 'required'
          
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
                  if($request->dataArrayTerminal){

                        $dataG = [];

                        foreach(json_decode($request->dataArrayTerminal) as $req)
                        {

                            $t = new Terminal();
                            $t->version = 1; 
                            //$t->imei = $request->sn;
                            $t->model_id = $req->modelId;
                            $t->merchant_id = $req->merchantId;
                            $t->tenant_id = $request->header('Tenant-id');
                            $t->sn = $req->sn;
                            $t->profile_id = $req->profileId;
                            $this->saveAction($request, $t); 
                            
                         
                            $t->save();

                            if($req->terminalGroupIds){

                                // $dataSet = [];
                                    foreach ($req->terminalGroupIds as $terminalGroup) {
                                        $dataG[] = [
                                            'terminal_id'  => $t->id,
                                            'terminal_group_id'    => $terminalGroup,
                                            'version' => 1,
                                        ];
                                    }

                               //TerminalGroupLink::insert($dataSet);
                                
                            }
                        }

                        TerminalGroupLink::insert($dataG);

                        // $data = [];
                        //     foreach ($request->dataArrayTerminal as $t) {
                        //         $data[] = [
                        //             'terminal_id'  => $t->id,
                        //             'terminal_group_id'    => $t,
                        //             'version' => 1,
                        //         ];
                        //     }
                        // //$data = json_encode($request->dataArrayTerminal,true); 
                        // Terminal::insert($data);
                        /*
                         $gl = explode(",",$t->group_id);
                            $rg = array();
                            for($i=0; $i<count($gl); $i++)
                            {
                                $rg[$i] = $gl[$i];
                            }

                            $response = $this->httpWithHeaders()
                            ->post( $this->apiTms()  . 'api/v1/terminal/add', [
                                'sn' => $t->sn,
                                'modelId' => $t->model_id,
                                'merchantId' => $t->merchant_id,
                                'profileId' => $t->profile_id,
                                'terminalGroupIds' =>  $rg,
                            ])->json();

                       */
                    }
            
                   DB::commit();
                    $a  =[   
                        "responseCode"=>"0000",
                        "responseDesc"=>"OK"
                    ];   

                   return $this->headerResponse($a,$request);
           

            
        } catch (\Exception $e) {
            DB::rollBack();
            $a  =   [
                "responseCode"=>"3333",
                "responseDesc"=>$e->getMessage()
                ];    
            return $this->failedInssertResponse($a,$request);
        }

    }
    

   
    
}
