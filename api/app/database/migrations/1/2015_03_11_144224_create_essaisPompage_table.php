<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEssaisPompageTable extends Migration {

	public function up()
	{
		Schema::create('t_essaispompage', function($t) {
		 
          $t->increments('IDEssaispompage');
		  $t->integer('IDOuvrage')->unsigned();
		 		  
		  $t->date('Date');
		  $t->string('TypeEssai', 50);
		  $t->string('DureeEssai', 10);
		  $t->string('DebitMax', 20);		  
		  $t->string('Rabattement', 50);
		  $t->string('DebitCritique', 10);
		  $t->string('Transmissivite', 20);		  
		  $t->string('Emmagasinage', 50);		  
		  });
	}

	public function down()
	{
		Schema::drop('t_essaispompage');
	}

}
