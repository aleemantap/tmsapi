<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tes;
use Illuminate\Support\Facades\DB;
use App\Models\Exportprocesses;
use App\Jobs\ExportJob;

class FileController extends Controller
{
    // private $request;

    // public function __construct(Request $request)
    //  {
    //     $this->request = $request;
    // }

    

    public function store(Request $request)
    {
        
        $jResponse = array();
        $jResponse['success'] = true;
        $jResponse['message'] = 'OK';
        $path = \Storage::cloud()->put('files', $request->file('item'));
        $url=\Storage::cloud()->temporaryUrl($path, \Carbon\Carbon::now()->addMinutes(1));
        
        $jResponse['data'] = array(
            "url"=>$url,
            "path"=>$path
        );
        
       // DB::beginTransaction();
        try {
        
            

            $h = new Tes();
            $h->path = $path; 
            $h->url = $url;

            if ($h->save()) {
                return response()->json(['responseCode' => '0000', //sukses insert
                                          'responseDesc' => 'File created successfully',
                                          'generatedId' =>  $h->id,
                                          'path'=>$path,
                                          'url'=>$url,
                                        ]);
               
            }
           // DB::commit();
           //
            //return \Response::json($jResponse, 201);
            //

        } catch (\Exception $e) {
          // DB::rollback();
            return response()->json(['responseCode' => '3333', //gagal exception 
                                    'responseDesc' => $e->getMessage()
                                    ]);
        } 
    }

    public function show(Request $request)
    {
       
		$jResponse = array();
        $jResponse['success'] = true;
        $jResponse['message'] = 'OK';
        $jResponse['data'] = array(
            "url" => \Storage::cloud()->temporaryUrl($request->path, 
            \Carbon\Carbon::now()->addMinutes(1))
        );
        return response()->json($jResponse, 201);
    }


    public function export(Request $request){

            $process = new Exportprocesses();
            $namaFile = 'export-'.date("Y-m-dh.i.s.u").".xlsx";
            $namaFile = 'export-'.md5($namaFile. time()).".xlsx";
			$process->export =  'export';
			$process->fileName =   $namaFile;
			$process->status =   'ON PROGRESS';
			$process->save();
			$processId = $process->id;
			
			//ExportJob::dispatch($namaFile, $processId)->delay(now()->addSeconds(3));
            $cob = new ExportJob($namaFile, $processId);
            dispatch($cob);
			return response()->json(['message'=>'export running in background','generatingFileName'=> $namaFile, 'status' => 'ON PROGRESS']);
	
    }

    public function download($file){
		return response()->download(storage_path('app/public/'.$file));
    }

	public function statusExport($file){
		
		$query = Exportprocesses::where('fileName',$file)->get();
		return response()->json(['fileName'=> $file, 'status' => $query[0]->status]);
		//return response()->download(storage_path('app/public/'.$file));
    }
}