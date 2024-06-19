<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCandidatesTable extends Migration
{

    public function up()
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('party');
            $table->date('dob')->nullable();
            $table->integer('age')->nullable();
            $table->unsignedBigInteger('number');
            $table->string('candidate_photo')->nullable();

            $table->foreignId('address_key')->constrained('address')->onDelete('cascade');

        });
    }

   
    public function down()
    {
        Schema::dropIfExists('candidates');
    }
}
// $table->unsignedBigInteger('party_id');
// $table->foreign('party_id')->references('id')->on('parties')->onDelete('cascade');