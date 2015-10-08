<?php
/**
 * Created by PhpStorm.
 * User: andela
 * Date: 10/8/15
 * Time: 9:30 AM
 */

require_once('vendor/autoload.php');

use Slim\Slim;
use Verem\Emoji\Api\Emoji;
use Verem\Emoji\Api\Authenticate;
use Verem\Emoji\Api\DAO\EmojiManager;

$app = new Slim();


$app->get('/', function () {
    $auth = new Authenticate('danver', 'password');
    $token = $auth->login();
    echo $token;
});

$app->get('/login', function () {
    $auth = new Authenticate('danverem', 'password');
    $token = $auth->login();
    echo $token;
});

$app->post('/auth/login', function () use ($app) {
	$username = $app->request->params('username');
	$password = $app->request->params('password');
    $auth = new Authenticate($username, $password);
    $token = $auth->login();
    echo $token;
});

$app->get('/emojis', function () {
    $manager = new EmojiManager();
    $emojis = $manager->all();
    echo $manager->toJson($emojis);
});

$app->get('/emojis/:id', function ($id) {
    $manager = new EmojiManager();
    $emoji = $manager->find($id);
    echo $manager->toJson($emoji);
});

/**
 * Create an emoji
 */
$app->post('/emojis', function () use ($app) {

    $name =  $app->request->params('emojiname');
    $char = $app->request->params('emojichar');
    $category = $app->request->params('category');
    $createdBy = $app->request->params('created_by');
    $createdAt = $app->request->params('created_at');
    $updatedAt = $app->request->params('updated_at');
    $keywords = $app->request->params('keywords');

    $emoji = new Emoji($name, $char, $keywords, $category);

    $emoji->setUpdatedAt($updatedAt);
    $emoji->setCreatedAt($createdAt);
    $emoji->setCreatedBy($createdBy);
    
    $manager = new EmojiManager();
    $manager->save($emoji);

});

/**
 * Update an emoji matching the specified id
 */
$app->put('/emojis/:id', function($id){

});


/**
 * delete an emoji by the specified id
 */
$app->delete('/emojis/:id', function($id){

	$manager = new EmojiManager();
	try{
		$result = $manager->delete($id);
		if($result === true) {
			echo json_encode([
			  	'statusCode ' 	=> 	200,
				'message' 		=> 	'Record deleted'
			]);
		} else {
			echo json_encode([
			  	'statusCode' 	=>	304,
				'message'		=> 	'Record not found'
			]);
		}
	} catch(PDOException $e) {
		echo json_encode(['message' => $e->getMessage()]);
	}
});


$app->run();
