<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnalogueInjectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //模拟投注
        Schema::create('analogue_injection', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_id')->default(0)->comment('用户名');
            $table->integer('match_id')->default(0)->comment('比赛id');
            $table->tinyInteger('give_score')->default(0)->comment('让球数');
            $table->tinyInteger('betting_result')->default(0)->comment('投注结果 1 胜 2 平 3负');
            $table->float('rate',6,2)->default(0)->comment('投注赔率');
            $table->tinyInteger('is_total')->default(0)->comment('是否投注总进球 0 否 1是');
            $table->tinyInteger('total')->default(0)->comment('总进球');
            $table->float('total_rate',6,2)->default(0)->comment('进球对应的赔率');
            $table->string('remark')->nullable()->comment('备注');
            $table->integer('group_id')->default(0)->comment('分组标记，唯一字符串');
            $table->smallInteger('sort')->default(0)->comment('排序值');
            $table->tinyInteger('is_finish')->default(0)->comment('比赛是否结束 1 结束 0 未结束');
            $table->tinyInteger('is_correct')->default(0)->comment('胜平负是否正确 1 正确 0 不正确');
            $table->tinyInteger('is_correct_total')->default(0)->comment('总进球是否正确 1 正确 0 不正确');
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
        Schema::dropIfExists('analogue_injection');
    }
}
