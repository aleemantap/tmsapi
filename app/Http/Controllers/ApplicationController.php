<?php

namespace App\Http\Controllers;
use App\Models\Application;
use App\Models\ApplicationView;
use App\Models\DeviceModel;
use App\Models\Terminal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    
    public function list(Request $request){

        try {
            $pageSize = ($request->pageSize)?$request->pageSize:10;
            $pageNum = ($request->pageNum)?$request->pageNum:1;
            
                $query = ApplicationView::whereNull('deleted_by')
                ->where('tenant_id','=', $request->header('Tenant-id'))
                ->select(
                    'id',
                    'package_name as packageName',
                    'name',
                    'description',
                    'app_version as appVersion',
                    'uninstallable',
                    'company_name as companyName',
                    'icon_url as iconUrl',
            'file_size as fileSize',
            'version',
                    'created_by as createdBy',
                    'create_ts as createdTime',
                    'updated_by as lastUpdatedBy',
                    'update_ts as lastUpdatedTime',
                    'icon_url_exp'
                )
                ->addSelect(['deviceModelName' => DeviceModel::select('model')->whereColumn('id', 'tms_v_application.device_model_id')]);
                
                
                if($request->deviceModelId != '')
                {
                    $query->where('device_model_id',$request->deviceModelId);
                }
                
                if($request->name != '')
                {
                    $query->where('name', 'ILIKE','%'.$request->name.'%');
                }
        
         if($request->sn != '')
                {
                    $q = Terminal::select('model_id')->where('sn',$request->sn);
                    $cnt = $q->get()->count();
                    if($cnt>0){

                        $query->where('device_model_id', $q->get()[0]->model_id);
                    }
                   
                }       
    
                $count = $query->get()->count();
                $results = $query->offset(($pageNum-1) * $pageSize) 
                ->limit($pageSize)->orderBy('create_ts', 'DESC')
                
                ->get()->makeHidden(['deviceModelId']);
                
                if($count > 0)
                {
                  
                    $result2 = collect($results)->map(function ($data) {

                        
                         $url = null;
                        if($data['iconUrl'])
                        { 
                         //$url = \Storage::cloud()->temporaryUrl($data['iconUrl'],\Carbon\Carbon::now()->addMinutes(30));
                         $url = \Storage::cloud()->temporaryUrl('icons/'.$data['unique_icon_name'],\Carbon\Carbon::now()->addMinutes(10075));
                        } 
                       

                        $dtime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data['icon_url_exp']);
                        
                        $url = null;
                        $expired = null;
                        if($data['icon_url_exp']!==''){
                            if(\Carbon\Carbon::now() < $dtime )
                            {
                                $url = $data['iconUrl']; 
                                //$expired  = $data['icon_url_exp']; 
                            }
                            else if(\Carbon\Carbon::now() >= $dtime)
                            {
                                //$url = \Storage::cloud()->temporaryUrl($data['iconUrl'],\Carbon\Carbon::now()->addMinutes(10075));
                                $url = \Storage::cloud()->temporaryUrl('icons/'.$data['unique_icon_name'],\Carbon\Carbon::now()->addMinutes(10075));
                                $expired  = \Carbon\Carbon::now()->addMinutes(10075);
                                $update = DB::table('tms_application') ->where('id', $data['id'])
                                ->limit(1)->update( [ 'icon_url_exp' => $expired,'icon_url'=>$url]);
                                
                            } 
                        }else{
                                //$url = \Storage::cloud()->temporaryUrl($data['iconUrl'],\Carbon\Carbon::now()->addMinutes(10075));
                                $url = \Storage::cloud()->temporaryUrl('icons/'.$data['unique_icon_name'],\Carbon\Carbon::now()->addMinutes(10075));
                                $expired  = \Carbon\Carbon::now()->addMinutes(10075);
                                $update = DB::table('tms_application') ->where('id', $data['id'])
                                ->limit(1)->update( [ 'icon_url_exp' => $expired,'icon_url'=>$url]);
                        }


                        $d = [];
                        $d['id']              = $data['id']; 
                        $d['packageName'] = $data['packageName']; 
                        $d['name'] = $data['name']; 
                        $d['description'] = $data['description']; 
                        $d['appVersion'] = $data['appVersion'];
                        $d['uninstallable'] = $data['uninstallable'];
                        $d['companyName'] =  $data['companyName'];
                        $d['iconUrl'] =   $url;
            $d['fileSize'] = $data['fileSize'];
                        $d['version'] =  $data['version'];
                        $d['createdBy'] =  $data['createdBy'];
                        $d['createdTime'] =  $data['createdTime'];
                        $d['lastUpdatedBy'] =  $data['lastUpdatedBy'];
                        $d['lastUpdatedTime'] =  $data['lastUpdatedTime'];
                    
                        return $d;
                    
                    });
                    
                    $a=['responseCode' => '0000', 
                                    'responseDesc' => "OK",
                                    'pageSize'  =>  $pageSize,
                                    'totalPage' => ceil($count/$pageSize),
                                    'total' => $count,
                                    'rows' =>$result2
                                    
                                    ];    
                    return $this->listResponse($a,$request);
                }
                else
                {
                    $a=["responseCode"=>"0400",
                    "responseDesc"=>"Data Not Found",
                    'rows' => null
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
            'name' => 'required|max:100',
            'packageName' =>  'required',//|max:255|unique:tms_application
            'appVersion' => 'required|max:50',
            'description' => 'max:255',
            'uninstallable'=>   ['required', Rule::in(['true', 'false','TRUE','FALSE','True','False','1','0'])],
            'companyName'=>  'required|max:100', 
            'deviceModelId' => 'required', 
            'icon' => 'image|mimes:jpeg,png,jpg,gif,svg',
        'apk' => 'max:51200',   
          
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
                //$path =$cloud->put("icons", $request->file('icon'));
                $file = $request->file('icon');
                $filename = now()->getTimestampMs().''.$file->getClientOriginalName();
                $path = $cloud->putFileAs("icons", $request->file('icon'),$filename);
            }
            
            $path2 =null;
            if($request->file('apk'))
            {
                //$path2 = $cloud->put(env('MINIO_BUCKET_APP_PATH_SERVER'), $request->file('apk'));
                //$path2 = $cloud->put(env('MINIO_BUCKET_APP_PATH_SERVER'), $request->apk);
                //$path2 = $cloud->put('apps',$request->file('apk'),['mimetype' => 'application/vnd.android.package-archive']);
                $file = $request->file('apk');
                $filename = now()->getTimestampMs().''.$file->getClientOriginalName();
                $fileext = $file->getClientOriginalExtension();
                if($fileext != 'apk')
                {
                    $a  =   [   
                        "responseCode"=>"5555",
                        "responseDesc"=> array("apk"=> [
                            "The apk must be a file of type: apk."
                        ])
                        ];    
                    return $this->headerResponse($a,$request);
                }
                
                $path2 = $cloud->putFileAs("apps", $request->file('apk'),$filename);
              
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
            $app->file_size = $request->file('apk')->getSize();
            $app->unique_name = substr($path2,5); 
            $app->unique_icon_name =substr($path,6);
            $app->icon_url = \Storage::cloud()->temporaryUrl($path,\Carbon\Carbon::now()->addMinutes(10075));
            //$url = 
            $app->tenant_id = $request->header('tenant-id');
            $app->device_model_id = $request->deviceModelId;
            $app->icon_url_exp = \Carbon\Carbon::now()->addMinutes(10075);
            $this->saveAction($request,$app);
        
            if ($app->save()) {
                DB::commit();
                $a  =   [   
                    "responseCode"=>"0000",
                    "responseDesc"=>"OK",
                    "generatedId" => $app->id
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

        /*$check = Application::where([
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
     
            $appa['name'] = 'required|max:100';
            $appa['packageName'] = 'required|max:255';
        }
         $validator = Validator::make($request->all(),$appa);
        */
         $validator = Validator::make($request->all(), [
            'name' => 'required|max:100',
            'packageName' =>  'required|max:255',
            'appVersion' => 'required|max:50',
            'description' => 'max:255',
            'uninstallable'=>  ['required', Rule::in(['true', 'false','TRUE','FALSE','True','False','1','0'])],  
            'companyName'=>  'required|max:100', 
            'deviceModelId' => 'required', 
            'icon' => 'image|mimes:jpeg,png,jpg,gif,svg|nullable',
        'apk' => 'max:51200',
          
        ]);
        
       
 
        if ($validator->fails()) {
            $a  = [   
                "responseCode"=>"5555",
                "responseDesc"=>$validator->errors()
                ];    
            return $this->headerResponse($a,$request);
        }

        DB::beginTransaction();
        try {

            $app = Application::where([
                ['id',$request->id],
                ['version',$request->version]
               
            ])
            ->whereNull('deleted_by')
            ->first();
            
            if(!empty($app))
            {


                    $cloud = \Storage::cloud();
            
                    $path =null;
                    if($request->file('icon'))
                    {
                        //$path = $cloud->put(env('MINIO_BUCKET_ICON_PATH_SERVER'), $request->file('icon'));
                        $file = $request->file('icon');
                        $filename = now()->getTimestampMs().''.$file->getClientOriginalName();
                        $path = $cloud->putFileAs("icons", $request->file('icon'),$filename);
                    }
                    
                    $path2 =null;
                    if($request->file('apk'))
                    {
                        //$path2 = $cloud->put(env('MINIO_BUCKET_APP_PATH_SERVER'), $request->file('apk'));
                        $file = $request->file('apk');
                        $filename = now()->getTimestampMs().''.$file->getClientOriginalName();
                        $fileext = $file->getClientOriginalExtension();
                        if($fileext != 'apk')
                        {
                            $a  =   [   
                                "responseCode"=>"5555",
                                "responseDesc"=> array("apk"=> [
                                    "The apk must be a file of type: apk."
                                ])
                                ];    
                            return $this->headerResponse($a,$request);
                        }
                        
                        $path2 = $cloud->putFileAs("apps", $request->file('apk'),$filename);
                    } 
                    $app->version =  $request->version + 1; 
                    $app->name = $request->name;
                    $app->package_name = $request->packageName;
                    $app->app_version = $request->appVersion;
                    $app->description = $request->description;
                    $app->uninstallable = $request->uninstallable;
                    $app->company_name = $request->companyName;
                    $app->checksum = MD5($request->file('apk'));
                    //$app->unique_name = $path2; //apk
                    //$app->unique_icon_name = $path;
                    $app->unique_name = substr($path2,5); 
                    $app->unique_icon_name =substr($path,6);
                  
                    $app->icon_url = \Storage::cloud()->temporaryUrl($path,\Carbon\Carbon::now()->addMinutes(10075));
                    $app->tenant_id = $request->header('tenant-id');
                    $app->device_model_id = $request->deviceModelId;
                    $this->updateAction($request, $app); 
                
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
        $validator = Validator::make($request->all(), [
            'id' => 'required|max:36'
        ]);
 
        if ($validator->fails()) {
            $a  =   [   
                "responseCode"=>"5555",
                "responseDesc"=>$validator->errors()
                ];    
            return $this->headerResponse($a,$request);
        }
        
        try {
            $app = Application::
            where('id', $request->id)
            ->where('tenant_id',$request->header('Tenant-id'))
            ->whereNull('deleted_by')
            ->with(['deviceModel' => function ($query) {
                    $query->select('id', 'model');
                    
            }]);
            
            
            if($app->get()->count()>0)
            {
                $app =  $app->get()->makeHidden(['deleted_by', 'delete_ts']);
                
              
                $dtime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $app[0]['icon_url_exp']);

                $urlIcon = null;
                $expired = null;
                if($app[0]['icon_url_exp']!==''){
                    if(\Carbon\Carbon::now() < $dtime )
                    {
                        $urlIcon = $app[0]['icon_url']; 
                       
                    }
                    else if(\Carbon\Carbon::now() >= $dtime)
                    {
                        $urlIcon = \Storage::cloud()->temporaryUrl($app[0]['icon_url'],\Carbon\Carbon::now()->addMinutes(10075));
                        $expired  = \Carbon\Carbon::now()->addMinutes(10075);
                        $update = DB::table('tms_application') ->where('id', $app[0]['id'])
                        ->limit(1)->update( [ 'icon_url_exp' => $expired,'icon_url'=>$urlIcon]);
                        
                    }
                }else{
                    $urlIcon = \Storage::cloud()->temporaryUrl($app[0]['icon_url'],\Carbon\Carbon::now()->addMinutes(10075));
                    $expired  = \Carbon\Carbon::now()->addMinutes(10075);
                    $update = DB::table('tms_application') ->where('id', $app[0]['id'])
                    ->limit(1)->update( [ 'icon_url_exp' => $expired,'icon_url'=>$urlIcon]);
                }
                
         $jsonr =[
                    "id" => $app[0]['id'],
                    "packageName" => $app[0]['package_name'],
                    "name" =>  $app[0]['name'],
                    "description" => $app[0]['description'],
                    "appVersion" => $app[0]['app_version'],
                    "uninstallable" => $app[0]['uninstallable'],
                    "companyName" => $app[0]['company_name'],
                    "iconUrl"  => $urlIcon,
                    "deviceModel" => $app[0]['deviceModel'],
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


    public function delete(Request $request){
        DB::beginTransaction();
        try {
            $m = Application::where('id','=',$request->id)
            ->whereNull('deleted_by')
            ->where('version','=',$request->version);
             $cn = $m->get()->count();
             if( $cn > 0)
             {
               
                $re = $this->deleteAction($request, $m);
                if ($re) {
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
                $path = $m->select("unique_name","checksum")->get(); //unique_name icon_url
                //$path = env('MINIO_BUCKET_ICON_PATH_SERVER')."\\".$path[0]['icon_url'];
                //env('MINIO_BUCKET_ICON_PATH_SERVER')
                $path2 = 'apps/'.$path[0]['unique_name'];
                $url = \Storage::cloud()->temporaryUrl($path2,\Carbon\Carbon::now()->addMinutes(30));
    
                $a  =   [   
                "responseCode"=>"0000",
                "responseDesc"=>"OK",
                "url" => $url,
        "md5"=>  $path[0]['checksum']
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
