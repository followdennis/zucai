<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSourceWangyicaipiaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('source_wangyicaipiao', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('match_id')->default(0)->comment('正文对应的match_id');
            $table->string('match_number')->nullable()->comment('比赛编号');
            $table->string('competition_name')->nullable()->comment('赛事名称');
            $table->timestamp('match_time')->nullable()->comment('比赛时间');
            $table->string('host_team_name')->nullable()->comment('主队名称');
            $table->string('host_team_rank')->default(0)->comment('主队排名');
            $table->string('guest_team_name')->nullable()->comment('客队名称');
            $table->string('guest_team_rank')->default(0)->comment('客队排名');
            $table->tinyInteger('give_score_1')->default(0)->comment('让球1');
            $table->float('win_rate_1',6,2)->default(0)->comment('胜赔率');
            $table->float('draw_rate_1',6,2)->default(0)->comment('平赔率');
            $table->float('fail_rate_1',6,2)->default(0)->comment('败赔率');
            $table->tinyInteger('give_score_2')->default(0)->comment('让球2');
            $table->float('win_rate_2',6,2)->default(0)->comment('胜赔率');
            $table->float('draw_rate_2',6,2)->default(0)->comment('平赔率');
            $table->float('fail_rate_2',6,2)->default(0)->comment('败赔率');
            $table->tinyInteger('status')->default(0)->comment('状态 0：未开赛 1：进行中 2结束');
            $table->tinyInteger('host_team_score')->default(0)->comment('主队进球数');
            $table->tinyInteger('guest_team_score')->default(0)->comment('客队进球总数');
            $table->tinyInteger('match_result')->default(0)->comment('比赛结果 1:主队胜 2：主队平 3:主队负');
            $table->float('final_rate',6,2)->default(0)->comment('最终赔率');
            $table->tinyInteger('match_give_score_result')->default(0)->comment('让球结果');
            $table->float('final_give_score_rate',6,2)->default(0)->comment('让球赔率');
            $table->tinyInteger('total')->default(0)->comment('总进球');
            $table->float('total_rate',6,2)->default(0)->comment('让球赔率');
            $table->tinyInteger('is_hope')->default(0)->comment('是否符合预期 0 否，1是');
            $table->smallInteger('recommend_level')->default(0)->comment('推荐程度');
            $table->tinyInteger('big_score')->default(0)->comment('大比分，主队减客队');
            $table->timestamp('betting_date')->nullable()->comment('投注日期');
            $table->integer('host_team_id')->default(0)->comment('主队id');
            $table->integer('guest_team_id')->default(0)->comment('客队id');
            $table->string('detail_url',255)->nullable()->comment('详情页url');
            $table->tinyInteger('has_history_score')->default(0)->comment('是否有历史进球数 0 无 1 有');
            $table->float('host_average',6,2)->default(0)->comment('主队平均进球数');
            $table->float('guest_average',6,2)->default(0)->comment("客队平均进球数");
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
        Schema::dropIfExists('source_wangyicaipiao');
    }
}
