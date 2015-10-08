<?php
/**
 * Created by PhpStorm.
 * User: andela
 * Date: 10/7/15
 * Time: 5:50 PM
 */

namespace Verem\Emoji\Api\Exceptions;


use Exception;

class RecordNotFoundException extends Exception {


	protected $message;
	public function __construct($message)
	{
		parent::__construct($message);
	}

	public function getErrorMessage()
	{
		return $this->message;
	}
}