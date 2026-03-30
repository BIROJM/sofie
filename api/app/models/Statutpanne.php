<?php

class Statutpanne extends \Eloquent {
	protected $table = 't_statutpanne';
	public $timestamps = false;
		
	protected $primaryKey = 'id';
	
	public function ouvrages() 
	{
		return $this->hasMany('Ouvrage', 'StatutPanne');
	}
}