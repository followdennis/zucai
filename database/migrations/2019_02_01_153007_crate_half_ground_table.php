<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrateHalfGroundTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('half_ground', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('aoke_id')->default(0)->comment('奥克表的主键id');
            $table->integer('match_id')->default(0)->comment('赛事id');
            //这个也从1 开始
            $table->integer('half_result_id')->default(0)->comment('比赛结果id');
            $table->string('half_result_name',6)->comment('比赛文字结果  1胜胜，2胜平，3胜负，4平胜，5平平，6平负，7负胜，8负平，9负负');
            $table->float('rate',6,2)->default(0)->comment('赔率');
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
        Schema::dropIfExists('half_ground');
    }
}
