<?php

class Profile extends \Eloquent {
	protected $table = 't_profile';
	public $timestamps = false;
	
	protected $primaryKey = 'IDProfile';
	protected $guarded = array();
}