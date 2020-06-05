<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePhExchangeGoodsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ph_exchange_goods', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('goods_no', 30)->default('')->comment('商品编号');
			$table->string('goods_name', 100)->default('')->comment('商品名称');
			$table->string('goods_cover', 200)->default('')->comment('商品主图');
			$table->string('goods_desc', 200)->default('')->comment('商品简介');
			$table->text('content', 65535)->comment('商品内容');
			$table->integer('sales_score')->unsigned()->default(0)->comment('兑换积分');
			$table->integer('line_score')->unsigned()->default(0)->comment('划线积分');
			$table->integer('sales_num')->unsigned()->default(0)->comment('兑换数量');
			$table->integer('stock_num')->unsigned()->default(0)->comment('库存数量');
			$table->integer('sort')->unsigned()->default(0)->comment('排序');
			$table->boolean('status')->default(0)->comment('商品状态：10-正常，20-删除');
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
		Schema::drop('ph_exchange_goods');
	}

}
