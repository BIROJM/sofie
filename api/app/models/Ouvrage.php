<?php

class Ouvrage extends \Eloquent 
{
	protected $table = 't_ouvrage';
	public $timestamps = false;
	protected $primaryKey = 'IDOuvrage';
	protected $guarded = array();
	
	public function localite() 
	{
		return $this->belongsTo('Localite' , 'IDLocalite');
	}
	
	public function region() 
	{
		return $this->belongsTo('Region' , 'IDRegion');
	}
	
	public function comite() 
	{
		return $this->belongsTo('Comite' , 'IDComite');
	}
	
	public function reparateur() 
	{
		return $this->belongsTo('Reparateur' , 'IDReparateur');
	}
	
	public function agentForma() 
	{
		return $this->belongsTo('Agent' , 'IDAgentForma');
	}
	/*
	public function statutPanne() 
	{
		return $this->belongsTo('Panne' , 'StatutPanne');
	}
	*/
	public function collecte() 
	{
		return $this->hasMany('Collecte', 'IDCollecte');
	}
	
	public function pannes() 
	{
		return $this->hasMany('Panne', 'IDOuvrage');
	}
	
	public function coupeGeologique()
    {
        return $this->hasOne('CoupeGeologique', 'IDOUvrage'); 
    }
		
	public function essaisPompage()
    {
        return $this->hasOne('EssaisPompage', 'IDOUvrage'); 
    }
	
	public function equipementForage()
    {
        return $this->hasOne('EquipementForage', 'IDOUvrage'); 
    }
	
	public function suiviPhysicoChimique()
    {
        return $this->hasOne('SuiviPhysicoChimique', 'IDOUvrage'); 
    }
	
	public function venuEauPrincipale()
    {
        return $this->hasOne('VenuEauPrincipale', 'IDOUvrage'); 
    }
}