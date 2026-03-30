<?php

class EssaisPompage extends \Eloquent {
	protected $table = 't_essaispompage';
	public $timestamps = false;
		
	protected $primaryKey = 'IDEssaispompage';
	protected $guarded = array();
	
	public function ouvrage() 
	{
      	return $this->belongsTo('Ouvrage');
    }
}