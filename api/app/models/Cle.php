<?php

class Cle extends \Eloquent {
	
	protected $table = 't_cle';
	public $timestamps = false;
	
	protected $primaryKey = 'IDCle';
	protected $guarded = array();
}