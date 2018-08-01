<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rate', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('type')->default(0)->comemnt('赔率类型，0：胜平负，1：比分 2进球数，3，半场');
            $table->integer('match_id')->default(0)->comment('比赛id');
            $table->tinyInteger('concede_points')->default(0)->comment('让球数');
            $table->float('win_rate',6,2)->default(0)->comment('胜赔率');
            $table->float('draw_rate',6,2)->default(0)->comment('平赔率');
            $table->float('lose_rate',6,2)->default(0)->comment('负赔率');
            $table->string('score')->nullable()->comment('比分，用数组或者json存储');
            $table->tinyInteger('points')->default(0)->comment('进球数');
            $table->string('half_match')->default(0)->comment('半场,用数组或json存储');
            $table->timestamp('time')->nullable()->comment('赔率发布时间');
            $table->tinyInteger('is_latest')->default(0)->comment('是否最新 0：否，1：是');
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
        Schema::dropIfExists('rate');
    }
}
