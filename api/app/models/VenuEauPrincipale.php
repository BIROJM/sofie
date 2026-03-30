<?php

class VenuEauPrincipale extends \Eloquent {
	protected $table = 't_venueauprincipale';
	public $timestamps = false;
		
	protected $primaryKey = 'IDVenueauprincipale';
	protected $guarded = array();
	
	public function ouvrage() 
	{
      	return $this->belongsTo('Ouvrage');
    }
}