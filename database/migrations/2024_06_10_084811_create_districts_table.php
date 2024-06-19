<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDistrictsTable extends Migration
{

    public function up()
    {
        Schema::create('districts', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name');

            $table->foreignId('state_id')->constrained('states')->onDelete('cascade');

        });
    }

    public function down()
    {
        Schema::dropIfExists('districts');
    }
}
