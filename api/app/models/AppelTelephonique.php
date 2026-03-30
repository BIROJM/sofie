<?php

class AppelTelephonique extends \Eloquent {

	protected $table = 't_appeltelephonique';
	public $timestamps = false;		
	protected $primaryKey = 'IDAPPEL';
	protected $guarded = array();
	
}