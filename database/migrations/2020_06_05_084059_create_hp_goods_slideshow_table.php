<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHpGoodsSlideshowTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('hp_goods_slideshow', function(Blueprint $table)
		{
			$table->increments('id')->comment('商品轮播图id');
			$table->integer('goods_id')->unsigned()->default(0)->comment('商品id');
			$table->string('slideshow_url', 200)->default('')->comment('轮播图地址');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('hp_goods_slideshow');
	}

}
