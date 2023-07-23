<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Tenant;

class HeaderAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
   
    // public function handle(Request $request, Closure $next) {
    //     if (Auth::check() && Auth::user()->type == 'admin')  {
    //       return $next($request);
    //     } else{
    //       Auth::logout();
    //       return redirect()->route('admin.login');
    //     }
    //   }


 

    /**
     * Summary of headerResponse
     * @param mixed $a
     * @param mixed $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function headerResponse($a,$request){

        return response()->json($a)
         ->header('Content-Type','app/json-application')
         ->header('Reference-Number',$request->header('Reference-Number'))
         ->header('Response-Timestamp',date('Y-m-d H:i:s'));
    }


    public function handle(Request $request, Closure $next) {

        /* header */
        if($request->header('tenant-id')){
            $querys  =Tenant::where('id',$request->header('tenant-id'));
            $tenant = $querys->get()->count();
            if($tenant==0){

                $a=["responseCode"=>"4444",
                    "responseDesc"=>"Invalid Tenant-id"
                ];    
                return $this->headerResponse($a,$request);

            }
         
        }else{

            $a=["responseCode"=>"2222",
                "responseDesc"=>"Tenant-id is required"
                ];    
                return $this->headerResponse($a,$request);
            }

        /* signature  */
        // if($request->header('signature')){
          
        //     if($request->header('signature')!='tes'){

        //         $a=["responseCode"=>"4444",
        //         "responseDesc"=>"Invalid signature"
        //         ];    
        //         return $this->headerResponse($a,$request);
        //     }

           
            
        // }else{
            
        //     $a=["responseCode"=>"2222",
        //     "responseDesc"=>"signature is required"
        //     ];    
        //     return $this->headerResponse($a,$request);

        // }

        /* Reference-Number */
        if(empty($request->header('Reference-Number'))){
            
            $a=["responseCode"=>"2222",
            "responseDesc"=>"Reference-Number is required"
            ];    
            return $this->headerResponse($a,$request);

       
        }

        /* Request-Timestamp */

        if(!empty($request->header('Request-Timestamp'))){
            
            return $next($request);
   
        }else{
            
            $a=["responseCode"=>"2222",
            "responseDesc"=>"Request-Timestamp is required"
            ];    
            return $this->headerResponse($a,$request);

        }
       
    }
}
