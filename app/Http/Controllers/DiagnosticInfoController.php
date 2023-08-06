<?php

namespace App\Http\Controllers;
use App\Models\DiagnosticInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;


class DiagnosticInfoController extends Controller
{
    public function list(Request $request){

        try {

            $pageSize = ($request->pageSize)?$request->pageSize:10;
            $pageNum = ($request->pageNum)?$request->pageNum:1;
                $sn = $request->sn;
                $battery_temp = $request->battery_temp;
                $battery_percentage = $request->battery_percentage;
                 
                $query = DiagnosticInfo::whereNotNull('id');

                 
                if($request->sn != '')
                {
                    $query->where('sn', $request->sn);
                }
                if($request->battery_temp != '')
                {
                    $query->where('battery_temp', $request->battery_temp);
                }
                if($request->battery_percentage != '')
                {
                    $query->where('battery_percentage', $request->battery_percentage);
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
            'meid' => 'max:20',
            'switching_times' => 'numeric',
            'swiping_card_times' => 'numeric',
            'dip_inserting_times' => 'numeric',
            'nfc_card_reading_times' => 'numeric',
            'front_camera_open_times' => 'numeric',
            'rear_camera_open_times' => 'numeric',
            'charge_times' => 'numeric',
            'version' => 'required|numeric',
            'total_memory' => 'numeric',
            'available_memory'  => 'numeric',
            'available_flash_memory' => 'numeric',
            'total_mobile_data' => 'numeric',
            'current_boot_time' => 'numeric',
            'total_boot_time' => 'numeric',
            'total_length_printed'  => 'numeric',
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

            $di = new DiagnosticInfo();
            $di->version = 1; 
            $di->sn = $request->sn;
            $di->battery_temp  =  $request->battery_temp;
            $di->battery_percentage =   $request->battery_percentage;
            $di->latitude =   $request->latitude;
            $di->longitude =   $request->longitude;
            $di->meid =   $request->meid;
            $di->switching_times =   $request->switching_times;
            $di->swiping_card_times =   $request->swiping_card_times;
            $di->dip_inserting_times =   $request->dip_inserting_times;
            $di->nfc_card_reading_times =   $request->nfc_card_reading_times;
            $di->front_camera_open_times =   $request->front_camera_open_times;
            $di->rear_camera_open_times =   $request->rear_camera_open_times;
            $di->charge_times =   $request->charge_times;
            $di->total_memory =   $request->total_memory;
            $di->available_memory =   $request->available_memory;
            $di->available_flash_memory =   $request->available_flash_memory;
            $di->total_mobile_data =   $request->total_mobile_data;
            $di->current_boot_time =   $request->current_boot_time;
            $di->total_boot_time =   $request->total_boot_time;
            $di->total_length_printed =   $request->total_length_printed;
            $di->cell_name =   $request->cell_name;
            $di->cell_type =   $request->cell_type;
            $di->cell_strength =   $request->cell_strength;
            $di->installed_apps_string = $request->installed_apps_string;
            $this->saveAction($request, $di);
           
        
            if ($di->save()) {
                return response()->json(['responseCode' => '0000', //sukses insert
                                          'responseDesc' => 'Diagnostic Info created successfully',
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
            'sn' => 'required|max:50',
            'battery_temp' => 'required|numeric',
            'battery_percentage' =>  'required|numeric',
            'latitude' =>   'max:53',
            'longitude' =>  'max:53',
            'meid' => 'max:20',
            'switching_times' => 'numeric',
            'swiping_card_times' => 'numeric',
            'dip_inserting_times' => 'numeric',
            'nfc_card_reading_times' => 'numeric',
            'front_camera_open_times' => 'numeric',
            'rear_camera_open_times' => 'numeric',
            'charge_times' => 'numeric',
            'version' => 'required|numeric',
            'total_memory' => 'numeric',
            'available_memory'  => 'numeric',
            'available_flash_memory' => 'numeric',
            'total_mobile_data' => 'numeric',
            'current_boot_time' => 'numeric',
            'total_boot_time' => 'numeric',
            'total_length_printed'  => 'numeric',
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

            $di = DiagnosticInfo::where([
                ['id',$request->id],
                ['version',$request->version]
               
            ])->first();

            $di->version = $request->version + 1;
            $di->sn = $request->sn;
            $di->battery_temp  =  $request->battery_temp;
            $di->battery_percentage =   $request->battery_percentage;
            $di->latitude =   $request->latitude;
            $di->longitude =   $request->longitude;
            $di->meid =   $request->meid;
            $di->switching_times =   $request->switching_times;
            $di->swiping_card_times =   $request->swiping_card_times;
            $di->dip_inserting_times =   $request->dip_inserting_times;
            $di->nfc_card_reading_times =   $request->nfc_card_reading_times;
            $di->front_camera_open_times =   $request->front_camera_open_times;
            $di->rear_camera_open_times =   $request->rear_camera_open_times;
            $di->charge_times =   $request->charge_times;
            $di->total_memory =   $request->total_memory;
            $di->available_memory =   $request->available_memory;
            $di->available_flash_memory =   $request->available_flash_memory;
            $di->total_mobile_data =   $request->total_mobile_data;
            $di->current_boot_time =   $request->current_boot_time;
            $di->total_boot_time =   $request->total_boot_time;
            $di->total_length_printed =   $request->total_length_printed;
            $di->cell_name =   $request->cell_name;
            $di->cell_type =   $request->cell_type;
            $di->cell_strength =   $request->cell_strength;
            $di->installed_apps_string = $request->installed_apps_string;
            $this->updateAction($request, $di);
          
            
            if ($di->save()) {
                return response()->json(['responseCode' => '0000', //sukses update
                                          'responseDesc' => 'Diagnostic updated successfully',
                                        
                                        ]);
            }
        } catch (\Exception $e) {
            return response()->json([
            'responseCode' => '3333', 
            'responseDesc' => "Diagnostic Update Failure"
        ]);
        }
    }
    
    public function show(Request $request){
        try {
            $hb = Diagnostic::where('id', $request->id);
            
            
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
            $m = DiagnosticInfo::where('id','=',$request->id)
            ->where('version','=',$request->version);
             $cn = $m->get()->count();
             if( $cn > 0)
             {
                
                $r= $this->deleteAction($request, $m);
                if ($r) {
                     return response()->json(['responseCode' => '0000', 'responseDesc' => 'Diagnostic Info deleted successfully']);
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
