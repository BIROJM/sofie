<?php

class Reparateur extends \Eloquent {
	protected $table = 't_reparateur';
	public $timestamps = false;
		
	protected $primaryKey = 'IDReparateur';
	protected $guarded = array();
	
	public function getReparateurFullNameAttribute()
	{
		return $this->attributes['NomRep'] .' '. $this->attributes['PrenomsRep'];
	}
	
	public function ouvrages() 
	{
		return $this->hasMany('Ouvrage', 'IDOuvrage');
	}
	
	public function numeroAppel() 
	{
		return $this->belongsTo('NumAppel' , 'IDNumAppel');
	}
}