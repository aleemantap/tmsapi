<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function successRes($request){

        return response()->json([
                    'responseCode' => '0000', 
                    'responseDesc' => 'OK',
                    
               ]);
    }

    public function falidedRes($request){

        return response()->json([
            'responseCode' => '0000', 
            'responseDesc' => 'Failed',
            
       ]);
    }

    public function headerRes($request){

        return response()->json([
            'responseCode' => '0000', 
            'responseDesc' => 'Failed',
            
       ])->header('Content-Type','app/json_application')
         ->header('Reference-Number',$request->header('Reference-Number'))
         ->header('Response-Timestamp',date('Y-m-d H:i:s'));
    }

    public function headerResponse($a,$request){ 

        return response()->json($a)
         ->header('Content-Type','app/json-application')
         ->header('Reference-Number',$request->header('Reference-Number'))
         ->header('Response-Timestamp',date('Y-m-d H:i:s'));
    }

   
    
}
