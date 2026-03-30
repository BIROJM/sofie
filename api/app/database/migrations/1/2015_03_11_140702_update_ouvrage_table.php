<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateOuvrageTable extends Migration {

	public function up()
	{
		Schema::table('t_ouvrage', function($t)
		{
			$t->string('NumIRH', 10);
			$t->string('AutreNumID', 10);
			$t->string('Designation', 20);
			$t->string('OperateurSaisie', 50);
			$t->date('DateSaisie', 50);
			$t->string('NumLocaliteProgress', 10);
			$t->string('TypeLocalite', 15);
			$t->string('EtatInitialCaptage', 30);
			
			$t->string('Propriete', 5);
			$t->string('Usage', 5);
			$t->string('NomDuProjet', 50);
			$t->string('Financement', 10);
			$t->string('IngenieurConseil', 50);
			$t->string('Entreprise', 50);
			$t->date('DateFinForation');
			$t->string('Debit', 6);
			$t->decimal('profondeurTotale', 5, 2);
			$t->decimal('ProfondeurEquipee', 5, 2);
			$t->decimal('NiveauStatique', 5, 2);
			$t->date('DateNs');
			
			$t->decimal('Geomorphologie', 5, 2);
			$t->decimal('EpaisseurAlteration', 5, 2);
						
			$t->string('NomAquifere', 50);
			$t->decimal('LithologieAquifere', 5, 2);
			$t->decimal('ProfondeurToit', 5, 2);
			$t->decimal('ProfondeurMur', 5, 2);
			
			$t->date('DatePrelevement');
			
			$t->decimal('Temperature', 5, 2);
			$t->decimal('Conductivite', 5, 2);
			$t->decimal('Ph', 5, 2);
			
			$t->decimal('FerTotal', 5, 2);
			$t->decimal('Nitrates', 5, 2);
						
			$t->string('Couleur', 20);
				
			$t->string('Turbidite', 30);
			$t->string('MarquePompe', 30);
			
			$t->date('DateInstallPompe');
			
			
			$t->decimal('ProfondeurInstallPompe', 5, 2);		
				
		});
	}

	public function down()
	{
		//
	}

}
