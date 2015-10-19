<?php
/**
 * Created by PhpStorm.
 * @author : Verem Dugeri
 * Date: 10/8/15
 * Time: 9:30 AM
 */

require_once('vendor/autoload.php');

use Slim\Slim;
use Verem\Emoji\Api\Emoji;
use Verem\Emoji\Api\AuthController;
use Verem\Emoji\Api\EmojiController;
use Verem\Emoji\Api\DAO\UserManager;
use Verem\Emoji\Api\DAO\EmojiManager;
use Verem\Emoji\Api\Exceptions\RecordNotFoundException;

$app = new Slim();

//route middleware
$authenticator = function () use ($app) {
    //determine if the user has authorization.
    $authorization = $app->request->headers->get('Authorization');

    if (!is_null($authorization)) {
        //check token expiry
        $manager = new UserManager();
        try {
            $user = $manager->where('token', '=', $authorization);
            if ($user['token_expire'] < date('Y-m-d H:i:s')) {
                return json_encode([
                  'status' => 401,
                  'message' => 'You have no authorization'
                ]);
            }
            $app->response->header('Authorization', $authorization);
        } catch (RecordNotFoundException $e) {
            return json_encode([
              'status' => 401,
              'message' => 'You have no authorization'
            ]);
        }
    } else {
        return json_encode([
          'status' => 401,
          'message' => 'You have no authorization'
        ]);
    }
};


/**
 * Homepage route
 */

$app->get('/', function(){
	echo "<h2>Welcome to the naija emoji RESTful api</h2>";
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
$app->get('/emojis/:id', function ($id) {
    $manager = new EmojiManager();
    try {
        $emoji = $manager->find($id);
    } catch (RecordNotFoundException $e) {
		return $manager->toJson([
			'status' => 204,
			'message' => 'No record found'
		]);
    }

    echo $manager->toJson($emoji);
});

/**
 * Create an emoji
 */
$app->post('/emojis', $authenticator, function () use ($app) {

    $name 		= $app->request->params('emojiname');
    $char 		= $app->request->params('emojichar');
    $category 	= $app->request->params('category');
    $createdBy 	= $app->request->params('created_by');
    $createdAt 	= $app->request->params('created_at');
    $updatedAt 	= $app->request->params('updated_at');
    $keywords 	= $app->request->params('keywords');

    $emoji 		= new Emoji($name, $char, $keywords, $category);

    $emoji->setUpdatedAt($updatedAt);
    $emoji->setCreatedAt($createdAt);
    $emoji->setCreatedBy($createdBy);

    $manager 	= new EmojiManager();
    try {
        $isSaved = $manager->save($emoji);
        if ($isSaved) {
            return json_encode([
                'status'  => 201,
                'message' => 'Record created'
            ]);
        }
        return json_encode([
            'status'  => 500,
            'message' => 'An error occurred while fulfilling request.'
        ]);
    } catch (PDOException $e) {
        return json_encode([
            'status'  => 500,
            'message' => $e->getMessage()
        ]);
    }


});

/**
 * Update an emoji matching the specified id
 */
$app->put('/emojis/:id', $authenticator, function ($id) use ($app) {

    $name 		= $app->request->params('emojiName');
    $char	 	= $app->request->params('emojiChar');
    $category 	= $app->request->params('category');
    $updatedAt 	= $app->request->params('updatedAt');
    $keywords 	= $app->request->params('keywords');

    $emoji 		= new Emoji($name, $char, $keywords, $category);
    $emoji->setUpdatedAt($updatedAt);
    $manager 	= new EmojiManager();
    try {
        $result = $manager->update($id, $emoji);
        if ($result) {
            return json_encode([
              'status'  => 200,
              'message' => 'Record modified'
            ]);
        }
        return json_encode([
          'status'  => '500',
          'message' => 'An error occured while fulfilling request.'
        ]);
    } catch (PDOException $e) {
        return json_encode([
          'status'  => 304,
          'message' => 'Unable to update record'
        ]);
    }
});

/**
 * Do a partial update of an emoji
 */
$app->patch('/emojis/:id', $authenticator, function ($id) use ($app) {

    $name 		= $app->request->params('emojiName');
    $char 		= $app->request->params('emojiChar');
    $category 	= $app->request->params('category');
    $updatedAt 	= $app->request->params('updatedAt');
    $keywords 	= $app->request->params('keywords');

    $emoji 		= new Emoji($name, $char, $keywords, $category);
    $emoji->setUpdatedAt($updatedAt);
    $manager 	= new EmojiManager();
    try {
        $result = $manager->update($id, $emoji);
        if ($result) {
            return json_encode([
              'status' => 200,
              'message' => 'Record modified'
            ]);
        }
        return json_encode([
          'status' => '500',
          'message' => 'An error occured while fulfilling request.'
        ]);
    } catch (PDOException $e) {
        return json_encode([
          'status' => 304,
          'message' => 'Unable to update record'
        ]);
    }
});


/**
 * delete an emoji by the specified id
 */
$app->delete('/emojis/:id', $authenticator, function ($id) {

    $manager = new EmojiManager();
    try {
        $result = $manager->delete($id);
        if ($result === true) {
            echo json_encode([
              'status'  => 200,
              'message' => 'Record deleted'
            ]);
        } else {
            echo json_encode([
              'status'  => 304,
              'message' => 'Record not found'
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode(['message' => $e->getMessage()]);
    }
});


$app->run();
