<?php

class Localite extends \Eloquent 
{

	protected $table = 't_localite';
	public $timestamps = false;
	protected $primaryKey = 'IDLocalite';
	protected $guarded = array();
	
	public function ouvrages() 
	{
		return $this->hasMany('Ouvrage', 'IDOuvrage');
	}
	
	public function agentForma() 
	{
		return $this->belongsTo('Agent' , 'IDAgentForma');
	}
	
	public function reparateur() 
	{
		return $this->belongsTo('Reparateur' , 'IDReparateur');
	}
	
}