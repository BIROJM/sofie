<?php

class CoupeGeologique extends \Eloquent {
	protected $table = 't_coupegeologique';
	public $timestamps = false;
		
	protected $primaryKey = 'IDCoupegeologique';
	protected $guarded = array();
	
	public function ouvrage() 
	{
      	return $this->belongsTo('Ouvrage');
    }
}