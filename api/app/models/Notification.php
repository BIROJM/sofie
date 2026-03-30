<?php

class Notification extends \Eloquent {

	protected $table = 't_notification';
	public $timestamps = false;		
	protected $primaryKey = 'IDNotification';
	protected $guarded = array();
	
}