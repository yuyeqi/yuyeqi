<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePhExchangeRecordTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ph_exchange_record', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->integer('address_id')->unsigned()->default(0)->comment('收货地址id');
			$table->string('user_name', 100)->default('')->comment('兑换人姓名');
			$table->integer('goods_id')->unsigned()->default(0)->comment('兑换商品id');
			$table->string('goods_name', 10)->default('')->comment('兑换商品名称');
			$table->integer('deal_score')->unsigned()->default(0)->comment('兑换使用积分');
			$table->integer('surplus_score')->unsigned()->default(0)->comment('用户剩余积分');
			$table->boolean('deal_status')->default(10)->comment('兑换状态：10-待处理，20已发货，30-已完成，40-驳回');
			$table->string('reject_reason', 100)->default('')->comment('拒绝理由');
			$table->integer('deliver_time')->unsigned()->default(0)->comment('发货时间');
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
		Schema::drop('ph_exchange_record');
	}

}
