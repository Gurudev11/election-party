<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartiesTable extends Migration
{
   
    public function up()
    {
        Schema::create('parties', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('registration_date');
            $table->unsignedBigInteger('number');
            $table->string('party_logo');
            
            $table->foreignId('address_key')->constrained('address')->onDelete('cascade');

        });
        
    }
    
    public function down()
    {
        Schema::dropIfExists('parties');
    }
}
