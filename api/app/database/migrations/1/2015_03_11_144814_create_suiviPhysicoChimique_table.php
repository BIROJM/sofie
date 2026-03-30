<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuiviPhysicoChimiqueTable extends Migration {

	public function up()
	{
		Schema::create('t_suiviphysicochimique', function($t) {
		 
          $t->increments('IDSuiviphysicochimique');
		  $t->integer('IDOuvrage')->unsigned();
		 		  
		  $t->date('Date');
		  $t->string('ph', 5);
		  $t->string('Cond', 10);
		  $t->string('ResSec', 20);		  
		  $t->string('Ca', 10);
		  $t->string('Mg', 10);
		  $t->string('Na', 10);		  
		  $t->string('K', 10);

		  $t->string('Cl', 10);
		  $t->string('No2', 10);
		  $t->string('No3', 10);		  
		  $t->string('So4', 10);
		  
		  $t->string('Hco3', 10);
		  $t->string('FeTot', 10);
		  $t->string('F', 10);		  
		  $t->string('As', 10);
		  
		  });
	}

	public function down()
	{
		Schema::drop('t_suiviphysicochimique');
	}

}
