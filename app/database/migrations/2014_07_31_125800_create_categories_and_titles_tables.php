<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesAndTitlesTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Create
		Schema::create('categories', function($table)
		{
			$table->increments('id');
			$table->string('name', 50)->unique();
		});

		Schema::create('titles', function($table)
		{
			$table->increments('id');
			$table->string('name', 50)->unique();
		});


		// Seed
		DB::table('titles')->insert(
        	array(
				array('name' => '[Non specificato]'),
				array('name' => 'Arbitro Effettivo'),
				array('name' => 'Assistente Arbitrale'),
				array('name' => 'Osservatore Arbitrale'),
				array('name' => 'Arbitro Benemerito'),
				array('name' => 'Altro'),            
			)
		);

		DB::table('categories')->insert(
        	array(
				array('name' => '[Non specificato]'),
				array('name' => 'Esordienti'),
				array('name' => 'Giovanissimi'),
				array('name' => 'Allievi'),
				array('name' => 'Juniores'),
				array('name' => 'Terza categoria'),
				array('name' => 'Seconda categoria'),
				array('name' => 'Prima categoria'),
				array('name' => 'Promozione'),
				array('name' => 'Eccellenza'),
				array('name' => 'C.A.I. (Scambi)'),
				array('name' => 'Serie D'),
				array('name' => 'Lega Pro'),
				array('name' => 'Serie B'),
				array('name' => 'Serie A'),
			)
		);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('categories');
		Schema::drop('titles');
	}

}
