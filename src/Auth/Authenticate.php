<?php
/**
 * Created by PhpStorm.
 * User: andela
 * Date: 10/7/15
 * Time: 4:08 PM
 */

namespace Verem\Emoji\Api;

use Verem\Emoji\Api\DAO\UserManager;
use Verem\Emoji\Api\Exceptions\RecordNotFoundException;

class Authenticate extends UserManager
{
    private $user ;
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function Login()
    {
        if ($this->isValid(
          $this->user->getUsername(), $this->user->getPassword())) {
            $token = $this->getToken($this->$user->getUsername(),
			  $this->user->getPassword());

			return $token;
        } else {
			return "User authentication failed";
		}
    }

    public function isValid($username, $password)
    {
       try{
		   $user = $this->where('username', '=', $username);
		   if ($user['password'] === $password) {
			   return true;
		   } else{
			   return "Invalid username or password";
		   }
	   } catch(RecordNotFoundException $e) {
		   return $e->getMessage();
	   }

    }

	public function getToken($username, $password)
	{
		$token = bin2hex(openssl_random_pseudo_bytes(16));
		return json_encode([$username,$token]);
	}


}
