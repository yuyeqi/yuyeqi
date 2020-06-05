<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHpPersonTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('hp_person', function(Blueprint $table)
		{
			$table->integer('id')->primary();
			$table->boolean('cate_id')->default(0)->comment('私人定制分类id');
			$table->integer('user_id')->unsigned()->default(0)->comment('用户id');
			$table->string('person_name', 100)->default('')->comment('私人定制人姓名');
			$table->string('phone', 20)->default('')->comment('电话');
			$table->string('company', 200)->default('')->comment('公司名称');
			$table->string('ocupation', 100)->default('')->comment('职业');
			$table->string('person_info', 200)->default('')->comment('私人专属定制备注');
			$table->decimal('person_price', 10)->unsigned()->default(0.00)->comment('私人专属定制预算');
			$table->decimal('sales_price', 10)->unsigned()->default(0.00)->comment('私人专属销售额');
			$table->boolean('is_audit')->default(10)->comment('审核状态：10-审核中，20-审核通过，30-拒绝');
			$table->integer('is_delete')->unsigned()->default(0)->comment('删除状态：0-正常，1-删除');
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
		Schema::drop('hp_person');
	}

}
