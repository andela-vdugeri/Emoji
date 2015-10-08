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
            //TODO: allow access
        }

        //TODO: deny access
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
}
