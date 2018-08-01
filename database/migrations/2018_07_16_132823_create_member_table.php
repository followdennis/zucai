<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member', function (Blueprint $table) {
            $table->increments('id');
            $table->string('member_name')->nullable()->comment('成员名称');
            $table->integer('team_id')->default(0)->comment('所属队伍id');
            $table->tinyInteger('age')->default(0)->comment('年龄');
            $table->smallInteger('height')->default(0)->comment('身高:厘米');
            $table->smallInteger('weight')->default(0)->comment('体重:千克');
            $table->tinyInteger('position_id')->default(0)->comment('位置id 如：前锋');
            $table->timestamp('birthday')->nullable()->comment('生日');
            $table->text('personal_details')->nullable()->comment('个人履历');
            $table->integer('sort')->default(0)->comment('排序');
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
        Schema::dropIfExists('member');
    }
}
