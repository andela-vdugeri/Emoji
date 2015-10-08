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
use Verem\Emoji\Api\DAO\UserManager;
use Verem\Emoji\Api\DAO\EmojiManager;
use Verem\Emoji\Api\Exceptions\RecordNotFoundException;

$app = new Slim();

//route middleware
$authenticator = function () use ($app) {
    //determine if the user has authorization.

    $authorization = $app->request->headers->get('Authorization');

    if (! is_null($authorization)) {
        //check token expiry
        $manager = new UserManager();
        try {
            $user = $manager->where('token', '=', $authorization);
            if ($user['token_expire'] < date('Y-m-d H:i:s')) {
                $app->stop();
            }
        } catch (RecordNotFoundException $e) {
            $app->response->status(401);
			$app->stop();
        }
    } else {
        $app->response()->status(401);
        $app->stop();
    }
};


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

    $data = json_decode($token, true);

    $result = null;

    if (array_key_exists('token', $data)) {

        //update the user table
        $manager = new UserManager();

        $result = $manager->updateToken(
          $data['expiry'], $data['token'], $username);
    }

    if (json_decode($result, true)['statusCode'] == 200) {
        $response = $app->response();
        $response['authorization'] = $data['token'];
    }
});

$app->get('/emojis', $authenticator, function () {
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
$app->put('/emojis/:id', function ($id) {

});


/**
 * delete an emoji by the specified id
 */
$app->delete('/emojis/:id', function ($id) {

    $manager = new EmojiManager();
    try {
        $result = $manager->delete($id);
        if ($result === true) {
            echo json_encode([
                'statusCode '    =>    200,
                'message'        =>    'Record deleted'
            ]);
        } else {
            echo json_encode([
                'statusCode'    =>    304,
                'message'        =>    'Record not found'
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode(['message' => $e->getMessage()]);
    }
});


$app->run();
