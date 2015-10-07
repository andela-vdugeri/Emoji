<?php
/**
 * Created by PhpStorm.
 * User: andela
 * Date: 10/7/15
 * Time: 4:08 PM
 */

namespace Verem\Emoji\Api;


class Authenticate {

	private $user ;
	public function __construct(User $user)
	{
		$this->user = $user;
	}

	public function Login()
	{
		if($this->isValid(
		  $this->user->getUsername(), $this->user->getPassword())) {
			//TODO: allow access
		}

		//TODO: deny access
	}

	public function isValid($username, $password)
	{
		return true;
	}
}