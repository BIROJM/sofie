<?php

class Groupe extends Eloquent {

	protected $table = 't_groupe';
	public $timestamps = false;
		
	protected $primaryKey = 'id';
	protected $guarded = array();

}
