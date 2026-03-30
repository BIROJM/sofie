<?php

class Collecte extends \Eloquent {

	protected $table = 't_collecte';
	public $timestamps = false;
		
	protected $primaryKey = 'IDCollecte';
	protected $guarded = array();
	
	public function ouvrage() 
	{
		return $this->belongsTo('Ouvrage' , 'IDOuvrage');
	}
}