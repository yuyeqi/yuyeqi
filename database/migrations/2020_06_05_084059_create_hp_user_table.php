<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHpUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('hp_user', function(Blueprint $table)
		{
			$table->increments('id')->comment('用户id');
			$table->string('open_id', 150)->default('')->comment('微信openid(唯一标示)');
			$table->string('nick_name', 100)->default('')->comment('微信昵称');
			$table->string('avatar_url')->default('')->comment('微信图像');
			$table->string('phone', 20)->default('')->comment('用户电话');
			$table->string('user_name', 100)->default('')->comment('用户姓名');
			$table->boolean('sex')->default(0)->comment('性别：0-未知，1-男，2-女');
			$table->string('position_name', 100)->default('')->comment('职称');
			$table->string('org_name', 100)->default('')->comment('单位名称');
			$table->string('birthday', 100)->default('')->comment('生日');
			$table->string('user_brand', 200)->default('')->comment('导购所属品牌-user_type=2用');
			$table->string('province', 100)->default('')->comment('省');
			$table->string('city', 100)->default('')->comment('市');
			$table->string('area', 100)->default('')->comment('区');
			$table->string('address')->default('')->comment('详细地址');
			$table->integer('deliver_id')->unsigned()->default(0)->comment('默认收货地址');
			$table->boolean('user_type')->comment('用户类别：1-设计师，2-异业，3-用户，4-员工，5-其他');
			$table->integer('parent_id')->unsigned()->default(0)->comment('推荐人id');
			$table->boolean('audit_status')->default(0)->comment('审核状态：0-审核中，1-审核通过，3-拒绝');
			$table->boolean('status')->default(0)->comment('账户状态：0-正常，1-禁用');
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
		Schema::drop('hp_user');
	}

}
