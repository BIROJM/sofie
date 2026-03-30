<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoupeGeologiqueTable extends Migration {

	public function up()
	{
		 Schema::create('t_coupegeologique', function($t) {
		 
          $t->increments('IDCoupegeologique');
		  $t->integer('IDOuvrage')->unsigned();
		  
		  $t->string('CoteSup', 20);
		  $t->string('CoteInf', 20);
		  $t->string('Lithographie', 20);		  
		  $t->string('Description', 50);		 
		  });
	}

	public function down()
	{
		Schema::drop('t_coupegeologique');
	}

}
