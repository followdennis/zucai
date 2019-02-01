<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTotalScoreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * 澳客网总进球赔率
         */
        Schema::create('total_score', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('match_id')->default(0)->comment('比赛id');
            /**
             * 进球数类型 0 1 2 3 4 5 6  大于6
             */
            $table->tinyInteger('score')->default(0)->comment('总进球数');
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
        Schema::dropIfExists('total_score');
    }
}
