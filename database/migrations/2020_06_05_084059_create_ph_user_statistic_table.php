<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePhUserStatisticTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ph_user_statistic', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned()->default(0)->unique('user_id')->comment('用户id');
			$table->decimal('amount', 10)->unsigned()->default(0.00)->comment('用户余额');
			$table->decimal('withdraw_amount', 10)->unsigned()->default(0.00)->comment('已提现金额');
			$table->decimal('frozen_amount', 10)->unsigned()->default(0.00)->comment('冻结金额');
			$table->integer('score')->unsigned()->default(0)->comment('用户积分');
			$table->integer('withdraw_score')->unsigned()->default(0)->comment('已用积分');
			$table->integer('frozen_score')->unsigned()->default(0)->comment('冻结积分');
			$table->integer('cush_score')->unsigned()->default(0)->comment('兑现积分');
			$table->integer('present_score')->unsigned()->default(0)->comment('礼物兑换积分');
			$table->integer('order_num')->unsigned()->default(0)->comment('订单数量');
			$table->integer('exchage_num')->unsigned()->default(0)->comment('兑换次数');
			$table->integer('book_num')->unsigned()->default(0)->comment('预约次数');
			$table->integer('children_num')->unsigned()->default(0)->comment('推广用户数量');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('ph_user_statistic');
	}

}
