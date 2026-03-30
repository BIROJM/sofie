<?php

class EquipementForage extends \Eloquent {
	protected $table = 't_equipementforage';
	public $timestamps = false;
		
	protected $primaryKey = 'IDEquipementforage';
	protected $guarded = array();
	
	public function ouvrage() 
	{
      	return $this->belongsTo('Ouvrage');
    }
}