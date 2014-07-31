<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdditionalFieldsToUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('qa_users', function($table)
		{
			$table->string('username', 30);
			$table->integer('section_id');
			$table->integer('title_id');
			$table->integer('category_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('qa_users', function($table)
		{
		    $table->dropColumn('username');
		    $table->dropColumn('section_id');
		    $table->dropColumn('title_id');
		    $table->dropColumn('category_id');
		});
	}

}
