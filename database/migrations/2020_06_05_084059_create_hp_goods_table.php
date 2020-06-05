<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHpGoodsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('hp_goods', function(Blueprint $table)
		{
			$table->increments('id')->comment('商品id');
			$table->string('goods_no', 100)->default('')->comment('商品编码');
			$table->string('goods_name', 100)->default('')->comment('商品名称');
			$table->string('goods_cover', 200)->default('')->comment('商品主图');
			$table->decimal('good_price', 10, 0)->unsigned()->default(0)->comment('商品价格');
			$table->decimal('line_price', 10, 0)->unsigned()->default(0)->comment('商品划线价');
			$table->integer('score')->unsigned()->default(0)->comment('购买赠送积分');
			$table->decimal('book_price', 10, 0)->unsigned()->default(0)->comment('订金');
			$table->integer('stock_num')->unsigned()->default(0)->comment('商品库存');
			$table->integer('sales_initial')->unsigned()->nullable()->default(0)->comment('初始销量');
			$table->integer('sales_actual')->unsigned()->default(0)->comment('商品实际销量');
			$table->boolean('cate_id')->default(0)->comment('商品所属分类');
			$table->boolean('is_news')->default(0)->comment('是否新品：0-是，1-不是');
			$table->boolean('is_hot')->default(0)->comment('是否热门：0-不是，1-是');
			$table->boolean('is_recommend')->default(0)->comment('是否推荐：0-是，1-不是');
			$table->integer('sort')->unsigned()->default(0)->comment('排序：越小越靠前');
			$table->boolean('goods_status')->default(10)->comment('商品状态：10-正常，20-下架');
			$table->string('goods_desc', 200)->default('')->comment('商品简介');
			$table->text('goods_content', 65535)->comment('商品详情');
			$table->integer('comment_num')->unsigned()->default(0)->comment('商品评论数');
			$table->boolean('is_delete')->default(0)->comment('删除状态：0-正常，1-删除');
			$table->integer('admin_id')->unsigned()->default(0)->comment('更新人id');
			$table->integer('update_time')->unsigned()->default(0)->comment('更新时间');
			$table->integer('create_time')->unsigned()->default(0)->comment('更新时间');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('hp_goods');
	}

}
