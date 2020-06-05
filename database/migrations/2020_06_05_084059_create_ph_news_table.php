<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePhNewsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ph_news', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->default(0)->primary()->comment('新闻id');
			$table->string('news_name', 100)->default('')->comment('新闻名称');
			$table->string('news_desc', 200)->default('')->comment('新闻简介');
			$table->text('content', 65535)->comment('新闻内容');
			$table->integer('sort')->unsigned()->default(0)->comment('排序');
			$table->boolean('is_recommend')->default(0)->comment('是否推荐：0-正常，1-推荐');
			$table->boolean('status')->default(10)->comment('新闻状态：10-正常，20-隐藏');
			$table->boolean('is_delete')->default(0)->comment('删除状态：0-正常，1-删除');
			$table->integer('update_time')->unsigned()->default(0)->comment('更新时间');
			$table->integer('create_time')->unsigned()->default(0)->comment('创建时间');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('ph_news');
	}

}
