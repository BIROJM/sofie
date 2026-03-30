<?php

class SuiviPhysicoChimique extends \Eloquent {
	protected $table = 't_suiviphysicochimique';
	public $timestamps = false;
		
	protected $primaryKey = 'IDSuiviphysicochimique';
	protected $guarded = array();
	
	public function ouvrage() {
        
		return $this->belongsTo('Ouvrage');
    }
}