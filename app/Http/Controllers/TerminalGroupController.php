<?php

namespace App\Http\Controllers;
use App\Models\TerminalGroup;
use App\Models\TerminalGroupLink;
use App\Models\Terminal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class TerminalGroupController extends Controller
{
    public function listTerminal(Request $request){

        try {

            $pageSize = ($request->pageSize)?$request->pageSize:10;
            $pageNum = ($request->pageNum)?$request->pageNum:1;
                
                $query = TerminalGroup::
                select(
                    'tms_terminal_group.id',
                    'tms_terminal_group.name',
                    'tms_terminal.sn',
                    'tms_terminal.id as terminalId',
                    'tms_terminal_group.description',
                    'tms_terminal_group.version',
                    'tms_terminal_group.created_by as createdBy',
                    'tms_terminal_group.create_ts as createdTime',
                    'tms_terminal_group.updated_by as lastUpdatedBy',
                    'tms_terminal_group.update_ts as lastUpdatedTime'
                )
                ->where('tms_terminal_group.tenant_id',$request->header('Tenant-id'))
                ->where('tms_terminal_group.id',$request->id)
                ->Join('tms_terminal_group_link', 'tms_terminal_group_link.terminal_group_id', '=', 'tms_terminal_group.id')
                ->Join('tms_terminal', 'tms_terminal.id', '=', 'tms_terminal_group_link.terminal_id')
                ->whereNull('tms_terminal_group.deleted_by');
                
                //if($request->id != '')
                //{
                    //$query->where('tms_terminal_group.id', 'ILIKE', '%' . $request->id . '%');

                //}

                if($request->name != '')
                {
                    $query->where('tms_terminal_group.name', 'ILIKE', '%' . $request->name . '%');
                }
                 
                if($request->sn != '')
                {
                    $query->whereIn('tms_terminal_group.id', TerminalGroupLink::select('terminal_group_id')->whereIn('terminal_id', Terminal::select('id')->where('sn', 'ILIKE', '%' . $request->sn . '%'))->groupBy('terminal_group_id')); //'Terminal::whereIn('id',$request->sn)
                }

                if($request->terminalId != '')
                {
                    $query->whereIn('tms_terminal_group.id', TerminalGroupLink::select('terminal_group_id')->where('terminal_id',$request->terminalId));
                    
                }
               
                $count = $query->get()->count();
            
                $results = $query->offset(($pageNum-1) * $pageSize) 
                ->limit($pageSize)->orderBy('tms_terminal_group.name', 'ASC')
                ->get()->makeHidden(['tms_terminal_group.deleted_by','tms_terminal_group.delete_ts']);
                
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


    public function list(Request $request){

        try {

            $pageSize = ($request->pageSize)?$request->pageSize:10;
            $pageNum = ($request->pageNum)?$request->pageNum:1;
                
                $query = TerminalGroup::
                select(
                    'id',
                    'name',
                    'description',
                    'version',
                    'created_by as createdBy',
                    'create_ts as createdTime',
                    'updated_by as lastUpdatedBy',
                    'update_ts as lastUpdatedTime'
                )
                ->where('tenant_id',$request->header('Tenant-id'))
                ->whereNull('deleted_by');

                 
                if($request->name != '')
                {
                    $query->where('name', 'ILIKE', '%' . $request->name . '%');
                }
                 
                if($request->terminalId != '')
                {
                    $query->where('id', 'ILIKE', '%' . $request->terminalId . '%');
                }
                 

                $count = $query->get()->count();
            
                $results = $query->offset(($pageNum-1) * $pageSize) 
                ->limit($pageSize)->orderBy('name', 'ASC')
                ->get()->makeHidden(['deleted_by','delete_ts']);
                
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
            'name' => 'required|max:100',
            'description' => 'max:255'
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

            $tg = new TerminalGroup();
            $tg->version = 1; 
            $tg->name = $request->name;
            $tg->description = $request->description;
            $tg->tenant_id = $request->header('Tenant-id');
            $this->saveAction($request,$tg);
            
            $tg->save();

            if($request->terminalIds){

                $dataSet = [];
                    foreach ($request->terminalIds as $terminal) {
                        $dataSet[] = [
                            'terminal_id'  => $terminal,
                            'terminal_group_id'    => $tg->id,
                            'version' => 1
                        ];
                    }

               TerminalGroupLink::insert($dataSet);
                
            }
            
            DB::commit();
                $a  =   [   
                    "responseCode"=>"0000",
                    "responseDesc"=>"OK",
                    "generatedId" =>  $tg->id
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

    public function update(Request $request){

        $check = TerminalGroup::where([
            ['id',$request->id],
            ['name',$request->name]
           
        ])->first();
        

        $terminalGrp = [
            'version' => 'required|numeric|max:32',
            'id' => 'required',
            'name' => 'required',
        ];

        if(!$check){
         
            $terminalGrp['name'] = 'required|max:100';
        }
        $validator = Validator::make($request->all(), $terminalGrp);
        
       
        if ($validator->fails()) {
            $a  =   [   
                "responseCode"=>"5555",
                "responseDesc"=>$validator->errors()
                ];    
            return $this->headerResponse($a,$request);
        }

        DB::beginTransaction();
        try {

            $tg = TerminalGroup::where([
                ['id',$request->id],
                ['version',$request->version],
                ['tenant_id',$request->header('Tenant-id')]

               
            ])->whereNull('deleted_by')->first();

            if($tg->get()->count()==0){
                $a=["responseCode"=>"0400",
                    "responseDesc"=>"Data Not Found"
                    ];    
                return $this->headerResponse($a,$request);
            }

            $tg->version = $request->version + 1;
            $tg->name = $request->name;
            $tg->description = $request->description;
            $this->updateAction($request, $tg);
                             
        
            $tg->save();

            if($request->terminalIds){

                TerminalGroupLink::where('terminal_group_id', $tg->id)->delete();
                $dataSet = [];
                    foreach ($request->terminalIds as $terminal) {
                        $dataSet[] = [
                            'terminal_id'  => $terminal,
                            'terminal_group_id'    => $tg->id,
                            'version' => $request->version + 1,
                        ];
                    }

               TerminalGroupLink::insert($dataSet);
                
            }
            DB::commit();
            
            $a  =   [   
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
            return $this->headerResponse($a,$request);
        }
    }
    
    public function show(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);
 
        if ($validator->fails()) {
            $a  =   [   
                "responseCode"=>"5555",
                "responseDesc"=>$validator->errors()
                ];    
            return $this->headerResponse($a,$request);
        }
        
        try {
            $tg = TerminalGroup::where('id', $request->id)
            ->select(
                'id',
                'name',
                'description',
                'version',
                'created_by as createdBy',
                'create_ts as createdTime',
                'updated_by as lastUpdatedBy',
                'update_ts as lastUpdatedTime'
            )
            ->whereNull('deleted_by');
          
            
            if($tg->get()->count()>0)
            {
                $tg =  $tg->get()->makeHidden(['deleted_by', 'delete_ts']);
                $a=["responseCode"=>"0000",
                    "responseDesc"=>"OK",
                     "data" => $tg
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
            $tg= TerminalGroup::where('id','=',$request->id)
            ->whereNull('deleted_by')
            ->where('version','=',$request->version)
            ->where('tenant_id',$request->header('Tenant-id'));
             $cn = $tg->get()->count();

            

             if( $cn > 0)
             {
           
                $this->deleteAction($request, $tg);

               

                TerminalGroupLink::where('terminal_group_id', $request->id)->delete();

              DB::commit();

                    $a  =   [   
                        "responseCode"=>"0000",
                        "responseDesc"=>"OK"
                        ];    
                    return $this->headerResponse($a,$request);
                 
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

    public function addTerminals(Request $request){
     
        $validator = Validator::make($request->all(), [
            'id' => 'required|max:36',
            'version' => 'required|numeric'
        ]);
 
        if ($validator->fails()) {
            $a  =   [   
                "responseCode"=>"5555",
                "responseDesc"=>$validator->errors()
                ];    
            return $this->headerResponse($a,$request);
        }

        $tgl= TerminalGroupLink::where('terminal_group_id',$request->id)->whereIn('terminal_id',$request->terminalIds);
        
        
        $tg= TerminalGroup::where('id',$request->id)
        ->where('tenant_id',$request->header('Tenant-id'))
        ->where('version','=',$request->version)
        ->whereNull('deleted_by');
        
        $cntgl = $tgl->get()->count();
        $cntg = $tg->get()->count();
        
       

            
            try {
                DB::beginTransaction();
                if($cntgl>0 && $cntg>0){
              


                        $a  =   [   
                            "responseCode"=>"0001",
                            "responseDesc"=>"Data already exist"
                            ];    
                    return $this->headerResponse($a,$request);
            
                }elseif($cntgl==0 && $cntg>0){
                    if($request->terminalIds)
                    {

                        $dataSet = [];
                            foreach ($request->terminalIds as $terminal) {
                                $dataSet[] = [
                                    'terminal_id'  => $terminal,
                                    'terminal_group_id'    => $request->id,
                                    'version'    => 1
                                ];
                            }
                            
                            //TerminalGroup::where('id',$request->id)->where('version',$request->version)->update(['version' => $request->version+1]);
                            
                            $t = TerminalGroup::where([
                                ['id',$request->id],
                                ['version',$request->version],
                                ['tenant_id',$request->header('Tenant-id')]
                               
                            ])->first();
                            $t->version = $request->version + 1;
                           
                            $t->save();
                            
                            TerminalGroupLink::insert($dataSet);
                    
                    }
                    DB::commit();
                    $a  =   [   
                        "responseCode"=>"0000",
                        "responseDesc"=>"OK"
                        ];    
                    return $this->headerResponse($a,$request);
                }else{
                    
                    $a=["responseCode"=>"0200",
                    "responseDesc"=>"Data Not Found"
                    ];    
                return $this->headerResponse($a,$request);
                }
            }
            catch (\Exception $e) {
                DB::rollBack();
                $a  =   [
                    "responseCode"=>"3333",
                    "responseDesc"=>$e->getMessage()
                    ];    
                return $this->failedInssertResponse($a,$request);
            }

    }
   
    public function deleteTerminals(Request $request){
     
        $validator = Validator::make($request->all(), [
            'id' => 'required|max:36',
            'version' => 'required|numeric'
        ]);
 
        if ($validator->fails()) {
            $a  =   [   
                "responseCode"=>"5555",
                "responseDesc"=>$validator->errors()
                ];    
            return $this->headerResponse($a,$request);
        }

        $tgl= TerminalGroupLink::where('terminal_group_id',$request->id)->whereIn('terminal_id',$request->terminalIds);
        
        
        $tg= TerminalGroup::where('id',$request->id)
        ->where('tenant_id',$request->header('Tenant-id'))
        ->where('version','=',$request->version)
        ->whereNull('deleted_by');
        
        $cntgl = $tgl->get()->count();
        $cntg = $tg->get()->count();
        
       

            
            try {
                DB::beginTransaction();
                if($cntgl==0){
              


                        $a  =   [   
                            "responseCode"=>"0200",
                            "responseDesc"=>"Data Not Found"
                            ];    
                    return $this->headerResponse($a,$request);
            
                }elseif($cntgl>0 && $cntg>0){
                    if($request->terminalIds){

                        foreach ($request->terminalIds as $terminal) {
                              
                                TerminalGroupLink::where('terminal_group_id', $request->id)->where('terminal_id', $terminal)->delete();
                            }
                        //$org->products()->whereIn('id', $ids)->delete()
                        

                       
                    $t = TerminalGroup::where([
                        ['id',$request->id],
                        ['version',$request->version],
                        ['tenant_id',$request->header('Tenant-id')]
                       
                    ])->first();
                    $t->version = $request->version + 1;
                   
                    $t->save();
                    
                     
                    }
                    DB::commit();
                    $a  =   [   
                        "responseCode"=>"0000",
                        "responseDesc"=>"OK"
                        ];    
                    return $this->headerResponse($a,$request);
                }else{
                    
                    $a=["responseCode"=>"0200",
                    "responseDesc"=>"Data Not Found"
                    ];    
                return $this->headerResponse($a,$request);
                }
            }
            catch (\Exception $e) {
                DB::rollBack();
                $a  =   [
                    "responseCode"=>"3333",
                    "responseDesc"=>$e->getMessage()
                    ];    
                return $this->failedInssertResponse($a,$request);
            }

    }
    
}