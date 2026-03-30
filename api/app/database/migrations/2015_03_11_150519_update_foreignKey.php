<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysOuvrage extends Migration {

	public function up()
	{
		Schema::table('t_collecte', function(Blueprint $table)
		{
			$table->foreign('IDOuvrage', 't_IDOuvrage_fk')->references('IDOuvrage')->on('t_ouvrage');
		});
		
	}


	public function down()
	{/*
		Schema::table('t_comite', function(Blueprint $table)
		{
			$table->dropForeign('t_IDNumAppel_fk');
		});*/
	}

}
