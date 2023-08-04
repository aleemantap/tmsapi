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

    public function failededRes($request){

        return response()->json([
            'responseCode' => '0000', 
            'responseDesc' => 'Failed',
            
       ]);
    }

    /**
     * Summary of headerRes
     * @param mixed $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function headerRes($request){

        return response()->json([
            'responseCode' => '0000', 
            'responseDesc' => 'OK',
            
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

    public function failedInssertResponse($a,$request){

        return response()->json($a)
         ->header('Content-Type','app/json-application')
         ->header('Reference-Number',$request->header('Reference-Number'))
         ->header('Response-Timestamp',date('Y-m-d H:i:s'));
    }

    /**
     * Summary of listResponse
     * @param mixed $a
     * @param mixed $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listResponse($a,$request){

        return response()->json($a)
         ->header('Content-Type','app/json-application')
         ->header('Reference-Number',$request->header('Reference-Number'))
         ->header('Response-Timestamp',date('Y-m-d H:i:s'));
    }
   /**
    * Summary of deleteAction
    * @param mixed $request
    * @param mixed $objDelete
    * @return void
    */
   public function deleteAction($request, $objDelete){

        $objDelete->timestamps = false;
        $current_date_time = \Carbon\Carbon::now()->toDateTimeString();
        $objDelete->delete_ts = $current_date_time; 
        $objDelete->deleted_by = $request->header('X-Consumer-Username');

   }

    
}