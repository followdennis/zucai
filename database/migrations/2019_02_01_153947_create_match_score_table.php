<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatchScoreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * 澳客网 比分赔率
         */
        Schema::create('match_score', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('aoke_id')->default(0)->comment('奥克表的主键id');
            $table->integer('match_id')->default(0)->comment('赛事id');

            /**
             * 比分类型
             * 1:0 2:0 2:1 3:0 3:1 3:2 4:0 4:1 4:2 5:0 5:1 5:2 胜其他
             * 0:0  1:1 2:2 3:3  平其他
             * 0:1 0:2 1:2 0:3 1:3 2:3 0:4 1:4 2:4 0:5 1:5 2:5 负其他
             *
             * 对应的id
             * 1  2  3  4  5  6  7  8  9  10 11 12 13
             * 14 15 16 17 18
             * 19 20 21 22 23 24 25 26 27 28 29 30 31
             */
            $table->string('score_type')->comment('比分类型');
            $table->tinyInteger('score_type_id')->default(0)->comment('比分类型id');
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
        Schema::dropIfExists('match_score');
    }
}
