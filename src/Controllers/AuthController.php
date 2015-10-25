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
	 /**
	 * @param Slim $app
	 * @return mixed
	 */
     public static function login(Slim $app)
     {

		$response = static::getResponse($app);

        $username   = $app->request->params('username');
        $password   = $app->request->params('password');

        $auth       = new Authenticate($username, $password);

        $token      = $auth->login();

        $data       = json_decode($token, true);

        $result     = null;

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

	 /**
	 * @param Slim $app
	 * @return mixed
	 */
	 public static function logout(Slim $app)
	 {
		$response = static::getResponse($app);

		//remove token from session
		$token 	 = $app->request->headers->get('Authorization');

		//remove token and expiry time from database
		$manager = new UserManager();

		$manager->invalidateSession($token);

		//set authorization headers to null;

		$response['Authorization'] = null;

		$response->body(json_encode([
			'status'  => 200,
			'message' => 'token unset'
		]));

		return $response;
	 }


	public function registerUser(Slim $app)
	{
		$response = $this->getResponse($app);

		$username = $app->request->params('username');
		$password = $app->request->params('password');
		$names 	  = $app->request->params('names');

		$user = new User($username, $password, $names);

		//get token
		$auth  = new Authenticate($username, $password);
		$tokenResponse = $auth->getToken();
		$token = $this->jsonDecode($tokenResponse)['token'];

		$tokenExpire = $this->jsonDecode($tokenResponse)['expiry'];

		//set user token and expiry
		$user->setToken($token);
		$user->setTokenExpire($tokenExpire);

		$manager = new UserManager();

		$manager->save($user);

		$token =  $auth->login();

		$response->body($token);

		return $response;
	}

	 /**
	 * @param $app
	 * @return mixed
	 */
	 private function getResponse(Slim $app)
	 {
		$response = $app->response();
		$response->header("Content-type", "application/json");

		return $response;
	 }

	private function jsonDecode($response)
	{
		return json_decode($response, true);
	}
}
