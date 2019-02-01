<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateZgjmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //周公解梦
        Schema::create('zgjm', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable()->comment('标题');
            $table->string('author')->nullable()->comment('作者');
            $table->string('cate_name')->nullable()->comment('分类名称');
            $table->integer('cate_id')->default(0)->comment('分类id');
            $table->tinyInteger('cate_level')->default(0)->comment('分类层级');
            //标签为一对多或多对多的关系，另行添加
            $table->string('thumb')->nullable()->comment('图片');
            $table->tinyInteger('has_thumb')->default(0)->comment('是否有缩略图');
            $table->string('keywords')->nullable()->comment('关键词');
            $table->string('desc')->nullable()->comment('描述');
            $table->tinyInteger('is_show')->default(0)->comment('是否展示');
            $table->longText('content')->nullable()->comment('正文');
            $table->integer('view')->default(0)->comment('点击次数');
            $table->integer('sort')->default(0)->comment('排序');
            $table->tinyInteger('is_hot')->default(0)->comment('是否热门');
            $table->tinyInteger('is_recommend')->default(0)->comment('是否推荐');
            $table->tinyInteger('edit_times')->default(0)->comment('编辑次数');
            $table->tinyInteger('is_del')->default(0)->comment('是否删除');
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
        Schema::dropIfExists('zgjm');
    }
}
