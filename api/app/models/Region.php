<?php

class Region extends \Eloquent {
	protected $table = 't_region';
	public $timestamps = false;
		
	protected $primaryKey = 'IDRegions';
	protected $guarded = array();
	
	public function ouvrages() 
	{
		return $this->hasMany('Ouvrage', 'IDOuvrage');
	}
}