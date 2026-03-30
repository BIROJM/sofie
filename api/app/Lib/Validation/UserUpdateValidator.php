<?php namespace Lib\Validation;

class UserUpdateValidator extends BaseValidator {

    public function __construct()
	{
		$this->regles = array(
			'name' => 'required|max:30|alpha|unique:users,name,id',
			'email' => 'required|email|unique:users,email,id'
		);
	}

}