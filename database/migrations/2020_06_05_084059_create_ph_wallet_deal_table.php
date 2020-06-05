<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePhWalletDealTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ph_wallet_deal', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->string('deal_no', 50)->default('')->comment('交易单号');
			$table->integer('user_id')->unsigned()->default(0)->comment('用户id');
			$table->decimal('amount', 10)->unsigned()->default(0.00)->comment('金额');
			$table->decimal('surplus_amount', 10)->unsigned()->default(0.00)->comment('剩余金额');
			$table->boolean('deal_type')->default(1)->comment('交易类型：1-提现，2-积分充值，3-注册，4-推广');
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
		Schema::drop('ph_wallet_deal');
	}

}
