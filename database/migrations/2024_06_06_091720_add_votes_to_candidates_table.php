<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVotesToCandidatesTable extends Migration
{

    public function up()
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->integer('votes')->default(0)->after('candidate_photo');
        });
    }
    
    public function down()
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->dropColumn('votes');
        });
    }
    
}