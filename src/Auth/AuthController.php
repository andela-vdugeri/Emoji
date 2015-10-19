<?php
/**
 * Created by PhpStorm.
 * User: andela
 * Date: 10/19/15
 * Time: 6:29 PM
 */

namespace Verem\Emoji\Api;

use Slim\Slim;
use Verem\Emoji\Api\DAO\UserManager;

class AuthController
{
    public static function login(Slim $app)
    {
		$response = $app->response();
		$response->header("Content-type", "application/json");

        $username    = $app->request->params('username');
        $password    = $app->request->params('password');

        $auth        = new Authenticate($username, $password);

        $token        = $auth->login();

        $data        = json_decode($token, true);

        $result    = null;
        //if user token exists
        if (array_key_exists('token', $data)) {

            //update the user table
            $manager = new UserManager();
            $result  = $manager->updateToken(
              $data['expiry'], $data['token'], $username);
			$response->body($result);
        }

        if (json_decode($result, true)['status'] == 200) {
            $response->header('Authorization', $data['token']);
        }

		return $response;
    }

	public static function logout(Slim $app)
	{
		$response = $app->response();

		$response->header("Content-type", "application/json");

		//remove token from session
		$token 	 = $app->request->headers->get('Authorization');
		//remove token and expiry time from database
		$manager = new UserManager();
		$manager->invalidateSession($token);
		//set authorization headers to null;
		$response = $app->response();
		$response['Authorization'] = null;

		$response->body(json_encode([
			'status'  => 200,
			'message' => 'token unset'
		]));

		return $response;
	}
}
