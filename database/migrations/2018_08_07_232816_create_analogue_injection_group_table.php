<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnalogueInjectionGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('analogue_injection_group', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_id')->default(0)->comment('用户id');
            $table->timestamp('betting_date')->nullable()->comment('投注日期');
            $table->timestamp('max_end_time')->nullable()->comment('最大的结束时间');
            $table->tinyInteger('is_finish')->default(0)->comment('是否结束 0 未结束 1结束');
            $table->tinyInteger('is_correct')->default(0)->comment('是否中奖 1 是 ，0 否');
            $table->tinyInteger('match_num')->default(0)->comment('几串一');
            $table->integer('betting_money')->default(20)->comment('投注金额');
            $table->float('sum_rate',6,2)->default(1)->comment('总的赔率');
            $table->float('money',10,2)->default(0)->comment('胜平负/让球胜平负 回报金额');
            $table->float('score_money',10,2)->default(0)->comment('总进球数回报总额');
            $table->tinyInteger('correct_num')->default(0)->comment('命中个数');
            $table->tinyInteger('correct_score_num')->default(0)->comment('进球数命中个数');
            $table->string('remark')->nullable()->comment('备注');
            $table->integer('sort')->default(0)->comment('排序值');
            $table->timestamp('end_time')->nullabble()->comment('最大比赛结束时间');
            $table->tinyInteger('is_finish')->default(0)->comment('订单是否完成');
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
        Schema::dropIfExists('analogue_injection_group');
    }
}
