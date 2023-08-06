<?php

namespace App\Http\Controllers;
use App\Models\TerminalGroupLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;


class TerminalGroupController extends Controller
{
    public function list(Request $request){

        try {
            $pageSize = ($request->pageSize)?$request->pageSize:10;
            $pageNum = ($request->pageNum)?$request->pageNum:1;
                $terminal_group_id = $request->terminal_group_id;
                $terminal_id = $request->terminal_id;
               
                
                $query = TerminalGroupLink::whereNotNull('terminal_id');

                 
                if($request->terminal_group_id != '')
                {
                    $query->where('terminal_group_id', $request->terminal_group_id);
                }
                if($request->terminal_id != '')
                {
                    $query->where('terminal_id', $request->terminal_id);
                }
               
                $count = $query->get()->count();
            
                $results = $query->offset(($pageNum-1) * $pageSize) 
                ->limit($pageSize)
                ->get();
                
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
                    'rows' => $results
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
            'terminal_id' => 'required',
            'terminal_group_id' => 'required',
        
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

            $dt = new TerminalGroupLink();
            $dt->terminal_id = $request->terminal_id;
            $dt->terminal_group_id = $request->terminal_group_id;
           
            if ($dt->save()) {
                DB::commit();
                $a  =   [   
                    "responseCode"=>"0000",
                    "responseDesc"=>"OK",
                    "generatedId" =>  $dt->id
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
            'terminal_id' => 'required',
            'terminal_group_id' => 'required',
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

            $dt = TerminalGroupLink::where([
                ['terminal_id',$request->terminal_id],
                ['terminal_group_id',$request->terminal_group_id]
               
            ])->first();

         
            $dt->terminal_id = $request->terminal_id;
            $dt->terminal_group_id = $request->terminal_group_id;

            if ($dt->save()) {
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
            $t = TerminalGroupLink::where('terminal_id', $request->terminal_id)->where('terminal_group_id', $request->terminal_group_id);
            
            if($t->get()->count()>0)
            {
                $t =  $t->get();
                $a=["responseCode"=>"0000",
                    "responseDesc"=>"OK",
                     "data" => $t
                    ];    
                return $this->headerResponse($a,$request);
            }
            else
            {
           
                $a=["responseCode"=>"0400",
                "responseDesc"=>"Data Not Found",
                 "data" => $t
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
            $t = TerminalGroupLink::where('terminal_id', $request->terminal_id)->where('terminal_group_id', $request->terminal_group_id);
            $cn = $t->get()->count();
             if( $cn > 0)
             {
                $update_t = $t->first();
                $current_date_time = \Carbon\Carbon::now()->toDateTimeString();
                $update_t->delete_ts = $current_date_time; 
                $update_t->deleted_by = "admin";//Auth::user()->id 
                if ($update_t->save()) {

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
