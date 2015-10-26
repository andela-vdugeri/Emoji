<?php
/**
 * Created by PhpStorm.
 * @author : Verem Dugeri
 * Date: 10/8/15
 * Time: 9:30 AM
 */

require_once('vendor/autoload.php');

use Slim\Slim;
use Verem\Emoji\Api\AuthController;
use Verem\Emoji\Api\EmojiController;
use Verem\Emoji\Api\DAO\UserManager;
use Verem\Emoji\Api\Exceptions\RecordNotFoundException;

$app = new Slim([
	'templates.path' => 'templates/',
	'debug'			 => true
]);

// Prepare view
$app->view(new \Slim\Views\Twig());
$app->view->parserOptions = array(
  'charset' 	=> 'utf-8',
  'cache' 		=> realpath('templates/cache'),
  'auto_reload' => true,
  'strict_variables' => false,
  'autoescape' 	=> true,
);
$app->view->parserExtensions = array(new \Slim\Views\TwigExtension());

//route middleware
$authenticator = function () use ($app) {
	$response = $app->response();
	$response->header("Content-type", "application/json");

    //determine if the user has authorization.
    $authorization = $app->request->headers->get('Authorization');

    if (! is_null($authorization)) {
        //check token expiry
        $manager = new UserManager();
        try {
            $user = $manager->where('token', '=', $authorization);
            if ($user['token_expire'] < date('Y-m-d H:i:s')) {
                $response->body(json_encode([
                  'status' => 401,
                  'message' => 'You have no authorization'
                ]));
				$response->status(401);
				$app->stop();
				return $response;
            }

            $app->response->header('Authorization', $authorization);

        } catch (RecordNotFoundException $e) {
            $response->body(json_encode([
              'status' => 401,
              'message' => 'You have no authorization'
            ]));

			$response->status(401);
			$app->stop();
			return $response;
        }
    } else {
        $response->body(json_encode([
          'status' => 401,
          'message' => 'You have no authorization'
        ]));

		$response->status(401);
		$app->stop();
		return $response;
    }
};

$app->post('/register', function() use ($app){
	$auth = new AuthController();
	$auth->registerUser($app);
});

/**
 * Homepage route
 */

$app->get('/', function() use ($app) {
	$app->render('index.html');
});
/**
 * The login route for the post method
 */

$app->post('/auth/login', function () use ($app) {
	AuthController::login($app);
});

/**
 * Log out of the application
 */
$app->get('/auth/logout', $authenticator, function () use ($app) {
	AuthController::logout($app);
});

/**
 * Fetch all emojis from the database
 */
$app->get('/emojis', function () use ($app) {
	EmojiController::findAll($app);
});

/**
 * Get an emoji from the database matching the
 * particular id
 */
$app->get('/emojis/:id', function ($id) use ($app) {
	EmojiController::find($id, $app);
});

/**
 * Create an emoji
 */
$app->post('/emojis', $authenticator, function () use ($app) {
	EmojiController::save($app);
});

/**
 * Update an emoji matching the specified id
 */
$app->put('/emojis/:id', $authenticator, function ($id) use ($app) {
	EmojiController::update($app, $id);
});

/**
 * Do a partial update of an emoji
 */
$app->patch('/emojis/:id', $authenticator, function ($id) use ($app) {
	EmojiController::patch($app, $id);
});


/**
 * delete an emoji by the specified id
 */
$app->delete('/emojis/:id', $authenticator, function ($id) use ($app) {
	EmojiController::delete($app, $id);
});


$app->run();
