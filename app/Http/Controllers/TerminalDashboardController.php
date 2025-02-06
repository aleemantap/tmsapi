<?php

namespace App\Http\Controllers;

use App\Models\Tms_v_terminal_online_stat;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;


class TerminalDashboardController extends Controller
{
    
    public function terminalOnline(Request $request){
     
        try {

            $ta = Tms_v_terminal_online_stat::query()->get();
            $data = array('OFFLINE' => 0, 'ONLINE'=>0, 'DISCONNECTED'=>0);
            if($ta->count()>0)
            {   
                   
                    
                    foreach($ta as $s=>$item)
                    {
                            if($item->status=='OFFLINE')
                            {
                               
                                 $data['OFFLINE'] = $item->total;
                            }

                           else if($item->status=='ONLINE')
                            {
                                 $data['ONLINE'] = $item->total;
                            }

                            else if($item->status=='DISCONNECTED')
                            {
                                $data['DISCONNECTED'] = $item->total;
                            }
                    }

              
                $a=["responseCode"=>"0000",
                    "responseDesc"=>"OK",
                     "data" => $data
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

   
    
}
