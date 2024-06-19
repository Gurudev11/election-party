<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressTable extends Migration
{
    
    public function up()
    {
        Schema::create('address', function (Blueprint $table) {
            $table->id();
            $table->string('address1');
            $table->string('address2');
            $table->string('city');
            $table->string('state');
           
        });
    }
   
           
       
    
    public function down()
    {
        Schema::dropIfExists('address');
    }
}
 // $table->unsignedBigInteger('state_id');
           
           // $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');