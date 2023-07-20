<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Country;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Storage;
use App\Models\exportProcesses;


class ExportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
   
    protected $processId;
    protected $namaFile;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($namaFile,$processId)
    {
        
        $this->processId = $processId;
        $this->namaFile = $namaFile;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {   
       
		$nama = $this->namaFile; 
		$dt = Country::all();
		// $data = array(
        //             'Name' => 'John', 
        //             'City' => 'Washington'
        //             );
		
		try
		{
			
		
			(new FastExcel($dt))->export($nama);
			//Storage::putFileAs('public')
			//Storage::disk('public')->put('example.txt', 'Contents');
			Storage::disk('public')->put($nama, file_get_contents($nama));
			//unlink(public_path($nama));
			
			if(Storage::disk('public')->exists($nama))
			{
				$process = exportProcesses::find($this->processId);
				$process->fileName = $nama;
				$process->status = 'DONE';
				$process->save();
			}
			else
			{
				$process = exportProcesses::find($this->processId);
				$process->fileName = $nama;
				$process->status = 'FAILED';
				$process->save();
			}
			
			
		}
		catch(\Exception $e)
		{
			echo $e->getMessage();
		}

    }
}
