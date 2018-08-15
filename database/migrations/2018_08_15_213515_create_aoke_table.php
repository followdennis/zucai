<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAokeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //澳客网数据
        Schema::create('aoke', function (Blueprint $table) {
            $table->increments('id');
            $table->string('num')->nullable()->comment('当天比赛编号');
            $table->string('competition_name',30)->comment('比赛名称');
            $table->timestamp('match_time')->nullable()->comment('比赛时间');
            $table->string('host_team_name',30)->comment('主队名称');
            $table->smallInteger('host_team_rank')->default(0)->comment('主队排名');
            $table->string('host_team_competition',20)->nullable()->comment('主队排名的赛事名称');
            $table->string('guest_team_name',30)->comment('客队名称');
            $table->smallInteger('guest_team_rank')->default(0)->comment('客队排名');
            $table->string('guest_team_competition',20)->nullable()->comment('客队排名比赛名称');
            $table->float('win_rate',6,2)->default(0)->comment('胜赔率');
            $table->float('draw_rate',6,2)->default(0)->comment('平局赔率');
            $table->float('fail_rate',6,2)->default(0)->comment('负赔率');
            $table->tinyInteger('give_score')->default(0)->comment('让球数');
            $table->float('give_score_win_rate',6,2)->default(0)->comment('让球胜赔率');
            $table->float('give_score_draw_rate',6,2)->default(0)->comment('让球平赔率');
            $table->float('give_score_fail_rate',6,2)->default(0)->comment('让球负赔率');
            $table->tinyInteger('host_score')->default(0)->comment('主队进球数');
            $table->tinyInteger('guest_score')->default(0)->comment('客队进球数');
            $table->smallInteger('total')->default(0)->comment('总进球数');
            $table->float('total_rate',6,2)->default(0)->comment('总进球数赔率');
            $table->tinyInteger('status')->default(0)->comment('0 未开赛 1 进行中 2 结束');
            $table->timestamp('betting_date')->nullable()->comment('投注日期');
            $table->tinyInteger('is_hope')->default(0)->comment('是否符合预期');
            $table->float('score_rate',8,2)->default(0)->comment('比分赔率');
            $table->tinyInteger('result')->default(0)->comment('比赛结果 1 胜 2 平 3 负');
            $table->tinyInteger('give_score_result')->default(0)->comment('让球比赛结果');
            $table->tinyInteger('big_score')->default(0)->comment('大比分，比分差');
            $table->tinyInteger('recommend_level')->default(0)->comment('推荐指数 满分100');
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
        Schema::dropIfExists('aoke');
    }
}
