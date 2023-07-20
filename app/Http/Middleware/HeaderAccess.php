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
    // public function handle(Request $request, Closure $next)
    // {
    //     return $next($request);
    // }

    // public function handle(Request $request, Closure $next) {
    //     if (Auth::check() && Auth::user()->type == 'admin')  {
    //       return $next($request);
    //     } else{
    //       Auth::logout();
    //       return redirect()->route('admin.login');
    //     }
    //   }

    public function handle(Request $request, Closure $next) {

        /* header */
        if($request->header('tenant-id')){
            $querys  =Tenant::where('id',$request->header('tenant-id'));
            $tenant = $querys->get()->count();
            if($tenant==0){
                return response()->json([   'responseCode' => '4444', //gagal validasi
                                            'responseDesc' => 'Invalid Tenant-id']
               );
            }
            // else
            // {
            //     return $next($request);
            // }
            
        }else{
            return response()->json([   'responseCode' => '2222', //gagal validasi
                                        'responseDesc' => 'Tenant-id required']);
        }

        /* signature  */
        if($request->header('signature')){
            //$querys  =Tenant::where('id',$request->header('tenant-id'));
            //$tenant = $querys->get()->count();
            if($request->header('signature')!='tes'){
                return response()->json([   'responseCode' => '4444', //gagal validasi
                'responseDesc' => 'Invalid signature']
                );
            }
            else
            {
                return $next($request);
            }
           
            
        }else{
            return response()->json([   'responseCode' => '2222', //gagal validasi
                                        'responseDesc' => 'signature required']);
        }

        
       
    }
}
