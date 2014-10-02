<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPointsHistoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('qa_history', function($table)
		{
    		$table->dropColumn('points');
		});

		Schema::table('qa_history', function($table)
		{
    		$table->float('points');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('qa_history', function($table)
		{
    		$table->dropColumn('points');
		});
		Schema::table('qa_history', function($table)
		{
    		$table->integer('points');
		});
	}

}
