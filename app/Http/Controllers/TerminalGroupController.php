<?php

namespace App\Http\Controllers;
use App\Models\TerminalGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class TerminalGroupController extends Controller
{
    public function list(Request $request){

        try {

                $pageSize = $request->pageSize;
                $pageNum = $request->pageNum;
                $name = $request->name;
                
                $query = TerminalGroup::whereNull('deleted_by');

                 
                if($request->name != '')
                {
                    $query->where('name', $request->name);
                }
               
                $count = $query->get()->count();
            
                $results = $query->offset(($pageNum-1) * $pageSize) 
                ->limit($pageSize)->orderBy('name', 'ASC')
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
            'name' => 'required|max:100|unique:tms_terminal_group',
            'description' => 'max:255',
            'tenant_id' =>'required'
        ]);
 
        if ($validator->fails()) {
            return response()->json(['responseCode' => '5555', //gagal validasi
                                     'responseDesc' => $validator->errors()]
                                    );
        }

        try {

            $tg = new TerminalGroup();
            $tg->version = 1; 
            $tg->name = $request->name;
            $tg->description = $request->description;
            $tg->tenant_id = $request->tenant_id;
          
            if ($tg->save()) {
                return response()->json(['responseCode' => '0000', //sukses insert
                                          'responseDesc' => 'Terminal Group created successfully',
                                          'generatedId' =>  $tg->id
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
            'tenant_id' => 'required',
            'name' => 'required|max:100|unique:tms_terminal_group',
            
        ]);
 
        if ($validator->fails()) {
            return response()->json(['responseCode' => '5555', //gagal validasi
                                     'responseDesc' => $validator->errors()]
                                    );
        }

        try {

            $tg = TerminalGroup::where([
                ['id',$request->id],
                ['version',$request->version]
               
            ])->first();

            $tg->version = $request->version + 1;
            $tg->name = $request->name;
            $tg->description = $request->description;
            $tg->tenant_id = $request->tenant_id;
        
            
            
            if ($tg->save()) {
                return response()->json(['responseCode' => '0000', //sukses update
                                          'responseDesc' => 'Termianl Group updated successfully',
                                        
                                        ]);
            }
        } catch (\Exception $e) {
            return response()->json([
            'responseCode' => '3333', 
            'responseDesc' => "Terminal Group Update Failure"
        ]);
        }
    }
    
    public function show(Request $request){
        try {
            $tg = TerminalGroup::where('id', $request->id)->whereNull('deleted_by');
            
            
            if($tg->get()->count()>0)
            {
                $tg =  $tg->get()->makeHidden(['deleted_by', 'delete_ts']);
                return response()->json([
                    'responseCode' => '0000', 
                    'responseDesc' => 'OK',
                    'data' => $tg
                    
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
            $tg= TermianlGroup::where('id','=',$request->id)
            ->where('version','=',$request->version);
             $cn = $tg->get()->count();
             if( $cn > 0)
             {
                $update_tg = $tg->first();
                $current_date_time = \Carbon\Carbon::now()->toDateTimeString();
                $update_tg->delete_ts = $current_date_time; 
                $update_tg->deleted_by = "admin";//Auth::user()->id 
                if ($update->save()) {
                     return response()->json(['responseCode' => '0000', 'responseDesc' => 'Terminal Group  deleted successfully']);
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
