<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAverageScoreToUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('qa_users', function($table)
		{
			$table->integer('tests_done')->default(0);
		    $table->float('average_score');
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
		    $table->dropColumn('average_score');
		    $table->dropColumn('tests_done');
		});
	}

}
