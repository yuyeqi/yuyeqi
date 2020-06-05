<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePhPersonCateTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ph_person_cate', function(Blueprint $table)
		{
			$table->increments('id')->comment('分类id');
			$table->string('cate_name', 100)->default('')->comment('分类名称');
			$table->boolean('status')->default(10)->comment('状态：10-正常，20-禁用');
			$table->boolean('is_delete')->default(0)->comment('是否删除：0-正常，1-删除');
			$table->integer('create_time')->unsigned()->default(0)->comment('创建时间');
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
		Schema::drop('ph_person_cate');
	}

}
