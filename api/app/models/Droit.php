<?php

class Droit extends Eloquent {

	protected $table = 't_droit';
	public $timestamps = false;
		
	protected $primaryKey = 'id';
	protected $guarded = array();

}
