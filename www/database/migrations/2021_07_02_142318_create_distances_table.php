<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateDistancesTable.
 */
class CreateDistancesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('distances', function(Blueprint $table) {
            $table->increments('id');
			$table->string('postcode_origin')->nullable();
			$table->string('postcode_destiny')->nullable();
			$table->longText('calculated_distance')->nullable();
            $table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('distances');
	}
}
