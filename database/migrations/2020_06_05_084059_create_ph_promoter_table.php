<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePhPromoterTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ph_promoter', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('promoter_id')->unsigned()->default(0)->comment('推广人');
			$table->integer('promoter_user_id')->unsigned()->default(0)->comment('推广用户');
			$table->decimal('promoter_amount', 10)->unsigned()->default(0.00)->comment('推广者佣金');
			$table->decimal('promoter_surplus_amount', 10)->unsigned()->default(0.00)->comment('推广者账户剩余金额');
			$table->boolean('promoter_type')->default(1)->comment('推广者身份：1-设计师，2-异业，3-老用户，4-员工，5-其他');
			$table->decimal('amount', 10)->unsigned()->default(0.00)->comment('赠送用户金额');
			$table->boolean('share_type')->default(0)->comment('推广方式：1-扫码，2-分享');
			$table->integer('create_time')->unsigned()->default(0)->comment('推广时间');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('ph_promoter');
	}

}
