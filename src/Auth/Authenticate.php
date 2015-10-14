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

class Authenticate
{
    private $username;
    private $password;

    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    public function login()
    {
        if ($this->isValid($this->username, $this->password) === true) {
            $token = $this->getToken();
            return $token;
        } else {
            return json_encode(['Error: '=>'User authentication failed']);
        }
    }

    public function isValid($username, $password)
    {
        $userManager = new UserManager();

        try {
            $user = $userManager->where('username', '=', $username);
            if (!empty($user)) {
                if ($user['password'] === $password) {
                    return true;
                } else {
                    return json_encode(['message'=>'Invalid username or password']);
                }
            } else {
                return json_encode(['message' => 'User account does not exist']);
            }
        } catch (RecordNotFoundException $e) {
            return json_encode(['Error' => "Invalid username or password"]);
        }
    }

    public function getToken()
    {
        $token = bin2hex(openssl_random_pseudo_bytes(16));
		$tokenExpire = date('Y-m-d H:i:s', strtotime('+ 1 hour'));
        return json_encode([
		  'expiry'=>$tokenExpire,
		  'token' => $token
		]);
    }
}
