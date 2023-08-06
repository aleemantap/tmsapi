<?php

namespace App\Http\Controllers;
use App\Models\HeartBeat;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;


class HeartBeatController extends Controller
{
    public function list(Request $request){

        try {

                $pageSize = $request->pageSize;
                $pageNum = $request->pageNum;
                $sn = $request->sn;
                $cell_name = $request->cell_name;
                 
                $query = HeartBeat::whereNotNull('id');

                 
                if($request->sn != '')
                {
                    $query->where('sn', $request->sn);
                }
                if($request->cell_name != '')
                {
                    $query->where('cell_name', $request->cell_name);
                }
                
               
                $count = $query->get()->count();
            
                $results = $query->offset(($pageNum-1) * $pageSize) 
                ->limit($pageSize)->orderBy('create_ts', 'DESC')
                ->get();
                
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
            'sn' => 'required|max:50',
            'battery_temp' => 'required|numeric',
            'battery_percentage' =>  'required|numeric',
            'latitude' =>   'max:53',
            'longitude' =>  'max:53',
            'version' => 'required|numeric',
            'cell_name' => 'max:53',
            'cell_type' => 'max:10',
            'cell_strength' => 'numeric',
           
        ]);
 
        if ($validator->fails()) {
            return response()->json(['responseCode' => '5555', //gagal validasi
                                     'responseDesc' => $validator->errors()]
                                    );
        }

        try {

            $hb = new HeartBeat();
            $hb->version = 1; 
            $hb->sn = $request->sn;
            $hb->battery_temp = $request->battery_temp;
            $hb->battery_percentage = $request->battery_percentage;
            $hb->latitude = $request->latitude;
            $hb->longitude = $request->longitude;
            $hb->version = $request->version;
            $hb->cell_name = $request->cell_name;
            $hb->cell_type = $request->cell_type;
            $hb->cell_strength = $request->cell_strength;
            $hb->create_ts = \Carbon\Carbon::now()->toDateTimeString();
            if ($hb->save()) {
                return response()->json(['responseCode' => '0000', //sukses insert
                                          'responseDesc' => 'Hear Beat created successfully',
                                          'generatedId' =>  $model->id
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
            'sn' => 'required|max:50',
            'battery_temp' => 'required|numeric',
            'battery_percentage' =>  'required|numeric',
            'latitude' =>   'max:53',
            'longitude' =>  'max:53',
            'cell_name' => 'max:53',
            'cell_type' => 'max:10',
            'cell_strength' => 'numeric',
        ]);
 
        if ($validator->fails()) {
            return response()->json(['responseCode' => '5555', //gagal validasi
                                     'responseDesc' => $validator->errors()]
                                    );
        }

        try {

            $hb = HearBeat::where([
                ['id',$request->id],
                ['version',$request->version]
               
            ])->first();

            $hb->version = $request->version + 1;
            $hb->sn = $request->sn;
            $hb->battery_temp = $request->battery_temp;
            $hb->battery_percentage = $request->battery_percentage;
            $hb->latitude = $request->latitude;
            $hb->longitude = $request->longitude;
            $hb->cell_name = $request->cell_name;
            $hb->cell_type = $request->cell_type;
            $hb->cell_strength = $request->cell_strength;
            $hb->update_ts = \Carbon\Carbon::now()->toDateTimeString();
            
            if ($hb->save()) {
                return response()->json(['responseCode' => '0000', //sukses update
                                          'responseDesc' => 'Hear Beat updated successfully',
                                        
                                        ]);
            }
        } catch (\Exception $e) {
            return response()->json([
            'responseCode' => '3333', 
            'responseDesc' => "Hear Beat Update Failure"
        ]);
        }
    }
    
    public function show(Request $request){
        try {
            $hb = HearBeat::where('id', $request->id);
            
            
            if($hb->get()->count()>0)
            {
                $hb =  $hb->get();
                return response()->json([
                    'responseCode' => '0000', 
                    'responseDesc' => 'OK',
                    'data' => $hb
                    
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
            $m = HearBeat::where('id','=',$request->id)
            ->where('version','=',$request->version);
             $cn = $m->get()->count();
             if( $cn > 0)
             {
                $update = $m->first();
                //$current_date_time = \Carbon\Carbon::now()->toDateTimeString();
                //$update->delete_ts = $current_date_time; 
                //$update->deleted_by = "admin";//Auth::user()->id 
                $this->deleteAction($request, $update);
                if ($update->save()) {
                     return response()->json(['responseCode' => '0000', 'responseDesc' => 'Hear Beat  deleted successfully']);
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
