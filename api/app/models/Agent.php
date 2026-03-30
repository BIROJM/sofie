<?php

class Agent extends \Eloquent {
	protected $table = 't_agent';
	public $timestamps = false;
	
	protected $primaryKey = 'IDAgent';
	protected $guarded = array();
	
	public function getAgentFullNameAttribute()
	{
		return $this->attributes['NomAgent'] .' '. $this->attributes['PrenomsAgent'];
	}
	
	public function numeroAppel() 
	{
		return $this->belongsTo('NumAppel' , 'IDNumAppel');
	}
}