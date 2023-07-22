<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExportprocessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exportProcesses', function (Blueprint $table) {
            //$table->id();
            //$table->timestamps();

            $table->uuid('id')->primary();
            $table->string('export', '50');
            $table->string('fileName', '250');
            $table->enum('status',['DONE', 'ON PROGRESS','FAILED']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exportProcesses');
    }
}
