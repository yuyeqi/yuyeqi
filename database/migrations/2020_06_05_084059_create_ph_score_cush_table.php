<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePhScoreCushTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ph_score_cush', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('deal_no', 11)->default('')->comment('交易单号');
			$table->integer('user_id')->unsigned()->default(0)->comment('用户id');
			$table->integer('deal_score')->unsigned()->default(0)->comment('提现积分');
			$table->integer('surplus_score')->unsigned()->default(0)->comment('剩余积分');
			$table->string('remark', 100)->default('')->comment('备注');
			$table->boolean('is_delete')->default(0)->comment('删除状态：0-正常，1-删除');
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
		Schema::drop('ph_score_cush');
	}

}
