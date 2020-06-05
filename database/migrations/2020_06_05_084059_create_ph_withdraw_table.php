<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePhWithdrawTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ph_withdraw', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned()->default(0)->comment('提现用户id');
			$table->decimal('amount', 10)->unsigned()->default(0.00)->comment('提现金额');
			$table->decimal('surplus_amount', 10)->unsigned()->default(0.00)->comment('提现后的剩余金额');
			$table->boolean('status')->default(10)->comment('提现状态；10-待审核，20-审核通过，30-已驳回');
			$table->integer('audit_id')->unsigned()->default(0)->comment('审核人id');
			$table->string('audit_name', 100)->default('')->comment('审核人姓名');
			$table->integer('audit_time')->unsigned()->default(0)->comment('审核时间');
			$table->string('remark', 200)->default('')->comment('提现备注');
			$table->string('audit_reason', 200)->default('')->comment('拒绝理由');
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
		Schema::drop('ph_withdraw');
	}

}
