<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePhCaseTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ph_case', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary()->comment('案例id');
			$table->string('case_name', 100)->default('')->comment('案例名称');
			$table->string('case_desc', 100)->default('')->comment('案例简介');
			$table->string('case_cover', 200)->default('')->comment('案例封面图');
			$table->boolean('status')->default(10)->comment('状态：10-正常，20-隐藏');
			$table->integer('sort')->unsigned()->default(0)->comment('排序');
			$table->text('content', 65535)->comment('案例内容');
			$table->boolean('is_delete')->default(0)->comment('状态：0-正常，1-删除');
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
		Schema::drop('ph_case');
	}

}
