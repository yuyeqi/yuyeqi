<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePhScoreDealTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ph_score_deal', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned()->default(0)->comment('用户id');
			$table->integer('deal_score')->unsigned()->default(0)->comment('交易积分');
			$table->integer('surplus_score')->unsigned()->default(0)->comment('剩余积分');
			$table->boolean('deal_type')->default(1)->comment('交易类型：1-预约，2-到店，3-订单');
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
		Schema::drop('ph_score_deal');
	}

}
