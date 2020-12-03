<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePhSlideshowTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ph_slideshow', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('slideshow_name', 100)->default('')->comment('轮播图名称');
			$table->string('slideshow_url')->default('')->comment('图片链接');
			$table->boolean('status')->default(0)->comment('状态：0-启用，1-禁用');
			$table->string('description', 100)->default('')->comment('描述');
			$table->boolean('is_delete')->default(0)->comment('删除状态：0-正常，1-已删除');
			$table->integer('update_time')->unsigned()->default(0)->comment('更新时间');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('ph_slideshow');
	}

}
