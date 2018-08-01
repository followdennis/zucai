<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->default(0)->comment('用户id');
            $table->integer('match_id')->default(0)->comment('比赛编号');
            $table->tinyInteger('type')->default(0)->comment('订单类型,可多选');
            $table->float('win_rate',6,2)->default(0)->comment('胜');
            $table->float('draw',6,2)->default(0)->comment('平');
            $table->float('lose',6,2)->default(0)->comment('负');
            $table->tinyInteger('concede_points')->default(0)->comment('让球');
            $table->string('score')->nullable()->comment('比分，用数组或者json存储');
            $table->tinyInteger('points')->default(0)->comment('进球数');
            $table->string('half_match')->default(0)->comment('半场,用数组或json存储');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order');
    }
}
