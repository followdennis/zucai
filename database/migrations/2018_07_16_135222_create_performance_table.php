<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePerformanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('performance', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('member_id')->default(0)->comment('成员id');
            $table->integer('competition_id')->default(0)->comment('赛事id');
            $table->tinyInteger('times')->default(0)->comment('出场次数');
            $table->smallInteger('score')->default(0)->comment('进球数');
            $table->smallInteger('yellow_card')->default(0)->comment('黄牌数');
            $table->smallInteger('red_card')->default(0)->comment('红牌数');
            $table->smallInteger('assisting')->default(0)->comment('助攻');
            $table->smallInteger('passing')->default(0)->comment('过人');
            $table->smallInteger('shoot')->default(0)->comment('射门');
            $table->smallInteger('importance')->default(0)->comment('重要性打分，满分100');
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
        Schema::dropIfExists('performance');
    }
}
