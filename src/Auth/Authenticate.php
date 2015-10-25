<?php
/**
 * Created by PhpStorm.
 * @author : Verem Dugeri
 * Date: 10/7/15
 * Time: 4:08 PM
 */

namespace Verem\Emoji\Api;

use Verem\Emoji\Api\DAO\UserManager;
use Verem\Emoji\Api\Exceptions\RecordNotFoundException;

class Authenticate
{
	 /**
	 * @var string $username
	 */
	 private $username;

	 /**
	 * @var string $username
	 */
	 private $password;

	 /**
	 * Construct the object instance
	 *
	 * @param $username
	 * @param $password
	 */
     public function __construct($username, $password)
     {
        $this->username = $username;
        $this->password = $password;
     }

     /**
     * Log a user into the system
     * @return string
     */
     public function login()
     {
        if ($this->isValid($this->username, $this->password) === true) {
            $token = $this->getToken();
            return $token;
        } else {
            return json_encode(['Error: '=>'User authentication failed']);
        }
     }

     /**
     * Check the validity of user credentials
     *
     * @param $username
     * @param $password
     * @return bool|null|string
     */
     public function isValid($username, $password)
     {
        $userManager = new UserManager();
        $message = null;

        try {
            $user = $userManager->where('username', '=', $username);
            if (!empty($user)) {
                if ($user['password'] === $password) {
                    $message  = true;
                } else {
                    $message = json_encode(['message'=>'Invalid username or password']);
                }
            } else {
                $message = json_encode(['message' => 'User account does not exist']);
            }
        } catch (RecordNotFoundException $e) {
            $message = json_encode(['Error' => "Invalid username or password"]);
        }

        return $message;
     }

     /**
     * Create and return a token
     *
     * @return string
     */
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
