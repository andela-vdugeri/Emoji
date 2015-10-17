<?php
/**
 * Created by PhpStorm.
 * @author :Verem Dugeri
 * Date: 10/7/15
 * Time: 5:50 PM
 */

namespace Verem\Emoji\Api\Exceptions;

use Exception;

class RecordNotFoundException extends Exception {

	 /**
	 * @var string $message
	 */
	 protected $message;

	 /**
	 * Construct the class instance
	 *
	 * @param string $message
	 */
	 public function __construct($message)
	 {
		parent::__construct($message);
	 }

	 /**
	 * Return an error message
	 *
	 * @return string
	 */
	 public function getErrorMessage()
	 {
		return $this->message;
	 }
}