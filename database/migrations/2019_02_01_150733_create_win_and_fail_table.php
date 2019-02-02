<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWinAndFailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * 澳客网的胜平负赔率记录
         */
        Schema::create('win_and_fail', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('match_id')->default(0)->comment('比赛id');
            $table->integer('aoke_id')->default(0)->comment('奥克表的主键id');
            $table->tinyInteger('give_score')->default(0)->comment('让球个数0,不让球');
            $table->tinyInteger('match_result')->default(0)->comment('比赛结果 3 胜 1 平 0 负');
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
        Schema::dropIfExists('win_and_fail');
    }
}
