<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEquipementForageTable extends Migration {

	public function up()
	{
		Schema::create('t_equipementforage', function($t) {
		 
          $t->increments('IDEquipementforage');
		  $t->integer('IDOuvrage')->unsigned();
		  
		  $t->string('Nature', 50);
		  $t->string('ProfSup', 20);
		  $t->string('ProfInf', 20);		  
		  $t->string('Diamètre', 50);		 
		  });
	}

	public function down()
	{
		Schema::drop('t_equipementforage');
	}
}
