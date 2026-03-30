<?php

class NumAppel extends \Eloquent {
	protected $table = 't_numeroappel';
	public $timestamps = false;
	
	protected $primaryKey = 'IDNumAppel';
	protected $guarded = array();
}