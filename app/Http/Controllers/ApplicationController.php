<?php

namespace App\Http\Controllers;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class ApplicationController extends Controller
{
    public function list(Request $request){

        try {
           
            $pageSize = ($request->pageSize)?$request->pageSize:10;
            $pageNum = ($request->pageNum)?$request->pageNum:1;
                $query = Application::whereNull('deleted_by')
                ->select(
                    'id',
                    'package_name as packageName',
                    'name',
                    'description',
                    'app_version as app_version',
                    'uninstallable',
                    'company_name as companyName',
                    'icon_url as iconUrl',
                    'version',
                    'created_by as createdBy',
                    'create_ts as createdTime',
                    'updated_by as lastUpdatedBy',
                    'update_ts as lastUpdatedTime'
                );
                if($request->name != '')
                {
                    $query->where('name', 'ILIKE','%'.$request->name.'%');
                }
                $count = $query->get()->count();
                $results = $query->offset(($pageNum-1) * $pageSize) 
                ->limit($pageSize)->orderBy('create_ts', 'DESC')
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
            $a  =   [
                "responseCode"=>"3333",
                "responseDesc"=>$e->getMessage()
                ];    
            return $this->failedInssertResponse($a,$request);
        }
    }
    
   
    public function create(Request $request){
       
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100|unique:tms_application',
            'packageName' =>  'required|max:255|unique:tms_application,package_name',
            'appVersion' => 'required|max:50',
            'description' => 'max:255',
            'uninstallable'=>  'boolean',  
            'companyName'=>  'required|max:100', 
          
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

            $cloud = \Storage::cloud();

            $path =null;
            if($request->file('icon'))
            {
                $path =$cloud->put(env('MINIO_BUCKET_ICON_PATH_SERVER'), $request->file('icon'));
            }
            
            $path2 =null;
            if($request->file('apk'))
            {
                $path2 = $cloud->put(env('MINIO_BUCKET_APP_PATH_SERVER'), $request->file('apk'));
            } 

          
            $app = new Application();
            $app->version = 1; 
            $app->name = $request->name;
            $app->package_name = $request->packageName;
            $app->app_version = $request->appVersion;
            $app->description = $request->description;
            $app->uninstallable = $request->uninstallable;
            $app->company_name = $request->companyName;
            $app->checksum = MD5($request->file('apk'));
            $app->unique_name = substr($path2,6); 
            $app->unique_icon_name = substr($path,6);
            $app->icon_url = $path;
            $app->tenant_id = $request->header('tenant-id');
            $app->create_ts = \Carbon\Carbon::now()->toDateTimeString();
        
            if ($app->save()) {
                DB::commit();
                $a  =   [   
                    "responseCode"=>"0000",
                    "responseDesc"=>"OK",
                    "generatedId" =>  $app->id
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

        $check = Application::where([
            ['id',$request->id],
            ['name',$request->name],
            ['package_name',$request->packageName ]
        
        ])->first();
           
        $appa = [
            'name' => 'required',
            'packageName' =>  'required',
            'appVersion' => 'required|max:50',
            'description' => 'max:255',
            'uninstallable'=>  'boolean',  
            'companyName'=>  'required|max:100', 
            'version' => 'required|numeric'
          
        ];
        
        if(!empty($check)){
     
            $appa['name'] = 'required|max:100|unique:tms_application';
            $appa['packageName'] = 'required|max:255|unique:tms_application,package_name';
        }
        $validator = Validator::make($request->all(),$appa);
 
        if ($validator->fails()) {
            $a  = [   
                "responseCode"=>"5555",
                "responseDesc"=>$validator->errors()
                ];    
            return $this->headerResponse($a,$request);
        }

        try {

            $app = Application::where([
                ['id',$request->id],
                ['version',$request->version]
               
            ])->first();
            
            if(!empty($app))
            {


                    $cloud = \Storage::cloud();
            
                    $path =null;
                    if($request->file('icon'))
                    {
                        $path = $cloud->put(env('MINIO_BUCKET_ICON_PATH_SERVER'), $request->file('icon'));
                    }
                    
                    $path2 =null;
                    if($request->file('apk'))
                    {
                        $path2 = $cloud->put(env('MINIO_BUCKET_APP_PATH_SERVER'), $request->file('apk'));
                    } 
                    $app->version =  $request->version + 1; 
                    $app->name = $request->name;
                    $app->package_name = $request->packageName;
                    $app->app_version = $request->appVersion;
                    $app->description = $request->description;
                    $app->uninstallable = $request->uninstallable;
                    $app->company_name = $request->companyName;
                    $app->checksum = MD5($request->file('apk'));
                    $app->unique_name = substr($path2,6); //apk
                    $app->unique_icon_name = substr($path,6);//icon $request->name.'-ICO-'.date('YmdHis');
                    //$app->apk = $request->apk;
                    $app->icon_url = $path;
                    $app->tenant_id = $request->header('tenant-id');
                    $app->update_ts = \Carbon\Carbon::now()->toDateTimeString();
                
                    if ($app->save()) {
                        DB::commit();
                        $a  =   [   
                            "responseCode"=>"0000",
                            "responseDesc"=>"OK"
                            ];    
                        return $this->headerResponse($a,$request);
                    }else{
                        $a=["responseCode"=>"0400",
                        "responseDesc"=>"Data Not Found"
                        ];    
                    return $this->headerResponse($a,$request);
                    }    
                }
                else
                {
                    $a=["responseCode"=>"0400",
                    "responseDesc"=>"Data Not Found"
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
    
    
    public function show(Request $request){
        try {
            $app = Application::where('id', $request->id)
            ->where('tenant_id',$request->header('Tenant-id'))
            ->whereNull('deleted_by');
            
            if($app->get()->count()>0)
            {
                $app =  $app->get()->makeHidden(['deleted_by', 'delete_ts']);
                
                $jsonr =[
                    "id" => $app[0]['id'],
                    "packageName" => $app[0]['package_name'],
                    "name" =>  $app[0]['name'],
                    "description" => $app[0]['description'],
                    "appVersion" => $app[0]['app_version'],
                    "uninstallable" => $app[0]['uninstallable'],
                    "company_name" => $app[0]['company_name'],
                    "iconUrl"  => $app[0]['icon_url'],
                    "version" => $app[0]['version'],
                    "createdBy"=> $app[0]['created_by'],
                    "createdTime" => $app[0]['create_ts'],
                    "lastUpdatedBy"=> $app[0]['updated_by'],
                    "lastUpdatedTime"=> $app[0]['update_ts']
                ];

                $a=["responseCode"=>"0000",
                    "responseDesc"=>"OK",
                     "data" => $jsonr
                    ];    
                return $this->headerResponse($a,$request);
            }
            else
            {
                $a=["responseCode"=>"0400",
                    "responseDesc"=>"Data Not Found",
                    "data" => []
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
            $m = Application::where('id','=',$request->id)
            ->where('version','=',$request->version);
             $cn = $m->get()->count();
             if( $cn > 0)
             {
                $updateMt = $m->first();
                $this->deleteAction($request, $updateMt);
                if ($updateMt->save()) {
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

    public function getApk(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            
        ]);
 
        if ($validator->fails()) {
            $a  = [   
                "responseCode"=>"5555",
                "responseDesc"=>$validator->errors()
                ];    
            return $this->headerResponse($a,$request);
        }    
        
        try {

            $m = Application::where('id','=',$request->id)->whereNull('deleted_by');
           
            $cn = $m->get()->count();
             if( $cn > 0)
             {
                $path = $m->select("icon_url")->get(); 
                $path = $path[0]['icon_url'];
                
                $url = \Storage::cloud()->temporaryUrl($path,\Carbon\Carbon::now()->addMinutes(30));
    
                $a  =   [   
                "responseCode"=>"0000",
                "responseDesc"=>"OK",
                "url" => $url
                ];    
                return $this->headerResponse($a,$request);
               
             }
             else
             {
                $a  =   [   
                    "responseCode"=>"0400",
                    "responseDesc"=>"Data No Found"
                    ];    
                return $this->headerResponse($a,$request);
             }

        } 
        catch (\Exception $e)
        {
            $a  =   [   
                "responseCode"=>"3333",
                "responseDesc"=>$e->getMessage()
                ];    
            return $this->headerResponse($a,$request);
        }   
    }

    
}