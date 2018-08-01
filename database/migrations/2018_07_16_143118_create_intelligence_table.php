<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntelligenceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //情报
        Schema::create('intelligence', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('match_id')->default(0)->comment('比赛场次id');
            $table->integer('member_id')->default(0)->comment('成员id');
            $table->integer('member_status')->default(0)->comment('成员竞技状态');
            $table->string('description')->nullable()->comment('成员状态简述');
            $table->integer('match_importance')->default(0)->comment('比赛重要性，满分100');
            $table->tinyInteger('position_id')->default(0)->comment('场次角色');
            $table->integer('sort')->default(0)->comment('排序');
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
        Schema::dropIfExists('intelligence');
    }
}
