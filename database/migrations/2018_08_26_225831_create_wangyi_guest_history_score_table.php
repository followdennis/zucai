<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWangyiGuestHistoryScoreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wangyi_guest_history_score', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('match_id')->default(0)->comment('比赛队伍,冗余字段');
            $table->integer('host_team_id')->default(0)->comment('主队id');
            $table->integer('guest_team_id')->default(0)->comment('客队id');
            $table->string('host_team_name')->nullable()->comment('交战主队名称');
            $table->string('guest_team_name')->nullable()->comment('客队名称');
            $table->tinyInteger('host_score')->default(0)->comment('主队进球数');
            $table->tinyInteger('guest_score')->default(0)->comment('客队进球数');
            $table->tinyInteger('is_host')->default(0)->comment('是否是主队');
            $table->timestamp('match_time')->nullable()->comment('比赛时间');
            $table->string('league_name')->nullable()->comment('联盟名称');
            $table->integer('league_id')->default(0)->comment('联盟id,冗余字段');
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
        Schema::dropIfExists('wangyi_guest_history_score');
    }
}
