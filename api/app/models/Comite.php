<?php

class Comite extends \Eloquent {
	protected $table = 't_comite';
	public $timestamps = false;
	
	protected $primaryKey = 'IDComite';
	protected $guarded = array();
	
	public function numeroAppel() 
	{
		return $this->belongsTo('NumAppel' , 'IDNumAppel');
	}
	
}