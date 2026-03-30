<?php

class Panne extends \Eloquent {
	protected $table = 't_panne';
	public $timestamps = false;
		
	protected $primaryKey = 'IDPanne';
	protected $guarded = array();
		
	public function ouvrage()
    {
        return $this->belongsTo('Ouvrage', 'IDOuvrage'); 
    }
	
	public function agent()
	{
		return $this->belongsTo('Agent', 'IDAgent'); 
	}
	
	public function statut()
	{
		return $this->belongsTo('Statutpanne', 'StatutPanne'); 
	}
	
}