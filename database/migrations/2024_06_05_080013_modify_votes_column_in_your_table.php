<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyVotesColumnInYourTable extends Migration
{
    public function up()
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->bigInteger('votes')->default(0)->change();
        });
    }

    public function down()
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->integer('votes')->default(0)->change();
        });
    }
}
