<?php namespace Lib\Validation;

interface ValidatorInterface {

    public function fails($id = null);
	public function errors();

}