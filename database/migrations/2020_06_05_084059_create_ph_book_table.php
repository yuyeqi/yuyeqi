<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePhBookTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ph_book', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned()->default(0)->comment('预约人id');
			$table->string('book_no', 30)->default('')->comment('预约码');
			$table->string('client_name', 50)->default('')->comment('客户姓名');
			$table->string('client_phone', 20)->default('')->comment('客户电话');
			$table->string('province', 50)->default('')->comment('省');
			$table->string('city', 100)->default('')->comment('市');
			$table->string('district', 100)->default('')->comment('区');
			$table->string('community', 100)->default('')->comment('小区名称');
			$table->string('house_name', 100)->default('')->comment('楼层地址');
			$table->boolean('sex')->default(0)->comment('性别：0-未知，1-男，2-女');
			$table->integer('arrive_time')->unsigned()->default(0)->comment('到店时间');
			$table->boolean('status')->default(10)->comment('预约状态：10-预约，20-到店，30-预算，40-成交');
			$table->integer('book_score')->unsigned()->default(0)->comment('预约赠送积分');
			$table->integer('deal_finshed_score')->unsigned()->default(0)->comment('交易完成赠送积分');
			$table->integer('actual_arrive_time')->unsigned()->default(0)->comment('实际到店时间');
			$table->integer('deal_finished_time')->unsigned()->default(0)->comment('交易完成时间');
			$table->integer('update_admin_id')->unsigned()->default(0)->comment('操作人id');
			$table->string('update_admin_name', 50)->default('')->comment('操作人姓名');
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
		Schema::drop('ph_book');
	}

}
