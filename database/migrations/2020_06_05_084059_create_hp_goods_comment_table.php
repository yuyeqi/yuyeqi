<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHpGoodsCommentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('hp_goods_comment', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->default(0)->primary()->comment('商品评论id');
			$table->integer('user_id')->unsigned();
			$table->string('comment_content', 200)->default('')->comment('评论内容');
			$table->boolean('is_delete')->default(0)->comment('删除状态：0-正常，1-删除');
			$table->integer('sort')->unsigned()->default(0)->comment('排序');
			$table->boolean('is_top')->default(0)->comment('制定状态：0-正常，1-置顶');
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
		Schema::drop('hp_goods_comment');
	}

}
