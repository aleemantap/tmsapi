<?php

namespace App\Http\Controllers;
use App\Models\Terminal;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;


class TerminalController extends Controller
{
    public function list(Request $request){

        try {
                $pageSize = $request->pageSize;
                $pageNum = $request->pageNum;
                $model_id = $request->model_id;
                $merchant_id = $request->merchant_id;
                $sn = $request->sn;
                $profile_id = $request->profile_id;
                $id = $request->id;
                
                
                $query = Terminal::whereNull('deleted_by');

                 
                if($request->model_id != '')
                {
                    $query->where('model_id', $request->model_id);
                }
                if($request->merchant_id != '')
                {
                    $query->where('merchant_id', $request->merchant_id);
                }
                if($request->sn != '')
                {
                    $query->where('sn', $request->sn);
                }
                if($request->profile_id != '')
                {
                    $query->where('profile_id', $request->profile_id);
                }
                if($request->id != '')
                {
                    $query->where('id', $request->id);
                }
                $count = $query->get()->count();
            
                $results = $query->offset(($pageNum-1) * $pageSize) 
                ->limit($pageSize)->orderBy('create_ts', 'DESC')
                ->get()->makeHidden(['deleted_by','delete_ts']);
                
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
            'imei' => 'required|max:25',
            'model_id' => 'required|max:255',
            'merchant_id' => 'required|max:255',
            'sn' => 'max:255',
            'tenant_id' =>'required',
            'is_locked' => 'numeric',
            'locked_reason' => 'max:255'

        ]);
 
        if ($validator->fails()) {
            return response()->json(['responseCode' => '5555', //gagal validasi
                                     'responseDesc' => $validator->errors()]
                                    );
        }
      
        try {

            $t = new Terminal();
            $t->version = 1; 
            $t->imei = $request->imei;
            $t->model_id = $request->model;
            $t->merchant_id = $request->merchant_id;
            $t->tenant_id = $request->tenant_id;
            $t->sn = $request->sn;
            $t->profile_id = $request->profile_id;
            $t->is_locked = $request->is_locked;
            $t->locked_reason = $request->locked_reason;
          
            if ($t->save()) {
                return response()->json(['responseCode' => '0000', //sukses insert
                                          'responseDesc' => 'Terminal  created successfully',
                                          'generatedId' =>  $t->id
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
            'id' => 'required',
            'imei' => 'required|max:25',
            'model_id' => 'required|max:255',
            'merchant_id' => 'required|max:255',
            'sn' => 'max:255',
            'tenant_id' =>'required',
            'is_locked' => 'numeric',
            'locked_reason' => 'max:255'
           
        ]);
 
        if ($validator->fails()) {
            return response()->json(['responseCode' => '5555', //gagal validasi
                                     'responseDesc' => $validator->errors()]
                                    );
        }

        try {

            $t = Terminal::where([
                ['id',$request->id],
                ['version',$request->version]
               
            ])->first();
            $t->version = $request->version + 1;
            $t->imei = $request->imei;
            $t->model_id = $request->model;
            $t->merchant_id = $request->merchant_id;
            $t->tenant_id = $request->tenant_id;
            $t->sn = $request->sn;
            $t->profile_id = $request->profile_id;
            $t->is_locked = $request->is_locked;
            $t->locked_reason = $request->locked_reason;
            if ($t->save()) {
                return response()->json(['responseCode' => '0000', //sukses update
                                          'responseDesc' => 'Termianl  updated successfully',
                                        
                                        ]);
            }
        } catch (\Exception $e) {
            return response()->json([
            'responseCode' => '3333', 
            'responseDesc' => "Terminal Update Failure"
        ]);
        }
    }
    
    public function show(Request $request){
        try {
            $t = TerminalGroup::where('id', $request->id)->whereNull('deleted_by');
            
            
            if($t->get()->count()>0)
            {
                $t =  $t->get()->makeHidden(['deleted_by', 'delete_ts']);
                return response()->json([
                    'responseCode' => '0000', 
                    'responseDesc' => 'OK',
                    'data' => $t
                    
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
            $t= Terminal::where('id','=',$request->id)
            ->where('version','=',$request->version);
             $cn = $t->get()->count();
             if( $cn > 0)
             {
                $update_t = $t->first();
                $current_date_time = \Carbon\Carbon::now()->toDateTimeString();
                $update_t->delete_ts = $current_date_time; 
                $update_t->deleted_by = "admin";//Auth::user()->id 
                if ($update_t->save()) {
                     return response()->json(['responseCode' => '0000', 'responseDesc' => 'Terminal deleted successfully']);
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

    public function restart(Request $request){

    }

    public function lockUnlock(Request $request){

    }


    
}
