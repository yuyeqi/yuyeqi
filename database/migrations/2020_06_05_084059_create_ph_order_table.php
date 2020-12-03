<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePhOrderTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ph_order', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary()->comment('订单表');
			$table->integer('user_id')->unsigned()->default(0)->comment('用户id');
			$table->string('order_no', 100)->default('')->comment('订单号');
			$table->decimal('total_price', 10)->unsigned()->default(0.00)->comment('订单总金额');
			$table->decimal('goods_price', 10)->unsigned()->default(0.00)->comment('商品价格');
			$table->decimal('pay_price', 10)->unsigned()->default(0.00)->comment('支付价格');
			$table->decimal('update_price', 10)->unsigned()->default(0.00)->comment('更新价格');
			$table->integer('score')->unsigned()->default(0)->comment('商品赠送积分');
			$table->string('buyer_remark', 200)->default('')->comment('买家留言');
			$table->boolean('pay_status')->default(10)->comment('订单状态：10-未支付，20-已支付，30-退款');
			$table->integer('pay_time')->unsigned()->default(0)->comment('支付时间');
			$table->string('transaction_id', 100)->default('')->comment('微信支付交易号');
			$table->boolean('is_comment')->default(0)->comment('是否已评价：0-未评价，1-已评价');
			$table->string('goods_name', 100)->default('')->comment('商品名称');
			$table->string('goods_cover', 200)->default('')->comment('商品封面图');
			$table->boolean('is_delete')->default(0)->comment('删除状态：0-正常，1-已删除');
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
		Schema::drop('ph_order');
	}

}
