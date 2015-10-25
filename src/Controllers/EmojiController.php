<?php
/**
 * Created by PhpStorm.
 * User: andela
 * Date: 10/19/15
 * Time: 6:47 PM
 */

namespace Verem\Emoji\Api;

use Slim\Slim;
use PDOException;
use Verem\Emoji\Api\DAO\EmojiManager;
use Verem\Emoji\Api\Exceptions\RecordNotFoundException;

class EmojiController
{
	 /**
	 * @param Slim $app
	 * @return mixed
	 */
     public static function findAll(Slim $app)
     {
		$response 	= static::getContentType($app);

        $manager    = new EmojiManager();

        try {
            $emojis = $manager->all();
            $result = $manager->toJson($emojis);
            $response->body($result);

            return $response;

        } catch (RecordNotFoundException $e) {
            $result= $manager->toJson([
              "status"  => 500,
              "message" => $e->getErrorMessage()
            ]);

            $response->body($result);

            return $response;
        }
     }

	 /**
	 * @param $id
	 * @param Slim $app
	 * @return mixed
	 */
     public static function find($id, Slim $app)
     {
        $response = static::getContentType($app);

        $manager  = new EmojiManager();
        try {
            $emoji = $manager->find($id);
            $response->body($manager->toJson($emoji));

            return $response;

        } catch (RecordNotFoundException $e) {
            $response->body($manager->toJson([
              'status' => 204,
              'message' => 'No record found'
            ]));

            return $response;
        }
     }

	 /**
	 * @param Slim $app
	 * @return \Slim\Http\Response
	 */
     public static function save(Slim $app)
     {
		$response  	= static::getContentType($app);

        $name        = $app->request->params('emojiname');
        $char        = $app->request->params('emojichar');
        $category    = $app->request->params('category');
        $createdBy   = $app->request->params('created_by');
        $createdAt   = date('Y-m-d H:i:s');
        $updatedAt   = date('Y-m-d H:i:s');
        $keywords    = $app->request->params('keywords');

        $emoji       = new Emoji($name, $char, $keywords, $category);

        $emoji->setUpdatedAt($updatedAt);
        $emoji->setCreatedAt($createdAt);
        $emoji->setCreatedBy($createdBy);

        $manager    = new EmojiManager();
        try {
            $isSaved = $manager->save($emoji);
            if ($isSaved) {
                $response->body(json_encode([
                  'status'  => 201,
                  'message' => 'Record created'
                ]));

				return $response;
            }

            $response->body(json_encode([
              'status'  => 500,
              'message' => 'An error occurred while fulfilling request.'
            ]));

			return $response;

        } catch (PDOException $e) {
            $response->body(json_encode([
              'status'  => 500,
              'message' => $e->getMessage()
            ]));

			return $response;
        }
     }

	 /**
	 * @param Slim $app
	 * @param $id
	 * @return mixed
	 */
	 public static function update(Slim $app, $id)
	 {
		$response 	= static::getContentType($app);

		$name 		= $app->request->params('emojiname');
		$char	 	= $app->request->params('emojichar');
		$category 	= $app->request->params('category');
		$updatedAt 	= date('Y-m-d H:i:s');
		$keywords 	= $app->request->params('keywords');

		$emoji 		= new Emoji($name, $char, $keywords, $category);


		$emoji->setUpdatedAt($updatedAt);
		$manager 	= new EmojiManager();
		try {
			$result = $manager->update($id, $emoji);
			if ($result) {
				$response->body(json_encode([
				  'status'  => 200,
				  'message' => 'Record modified'
				]));

				return $response;
			}

			$response->body(json_encode([
			  'status'  => '500',
			  'message' => 'An error occured while fulfilling request.'
			]));

			return $response;

		} catch (PDOException $e) {
			$response->body(json_encode([
			  'status'  => 304,
			  'message' => $e->getMessage()
			]));

			return $response;
		}
	 }


	 /**
	 * @param Slim $app
	 * @param $id
	 * @return mixed
	 */
	 public static function patch(Slim $app, $id)
	 {
		$response 	= static::getContentType($app);

		$name 		= $app->request->params('emojiname');
		$char 		= $app->request->params('emojichar');
		$category 	= $app->request->params('category');
		$updatedAt 	= date('Y-m-d H:i:s');
		$keywords 	= $app->request->params('keywords');

		$emoji 		= new Emoji($name, $char, $keywords, $category);

		$emoji->setUpdatedAt($updatedAt);

		$manager 	= new EmojiManager();

		try {
			$result = $manager->update($id, $emoji);
			if ($result) {
				$response->body(json_encode([
				  'status' => 200,
				  'message' => 'Record modified'
				]));

				return $response;
			}

			$response->body(json_encode([
			  'status' => '500',
			  'message' => 'An error occured while fulfilling request.'
			]));

			return $response;

		} catch (PDOException $e) {
			$response->body(json_encode([
			  'status' => 304,
			  'message' => $e->getMessage()
			]));

			return $response;
		}
	 }

	 /**
	 * @param Slim $app
	 * @param $id
	 * @return mixed
	 */
	 public static function delete(Slim $app, $id)
	 {
		$response 	=  static::getContentType($app);

		$manager 	= new EmojiManager();

		try {
			$result = $manager->delete($id);
			if ($result === true) {
				$response->body(json_encode([
				  'status'  => 200,
				  'message' => 'Record deleted'
				]));

				return $response;

			} else {
				$response->body(json_encode([
				  'status'  => 304,
				  'message' => 'Record not found'
				]));

				return $response;
			}

		} catch (PDOException $e) {
			$response->body($manager->toJson([
			  'status'  => 500,
			  'message' => $e->getMessage()
			]));

			return $response;
		}
	 }

	/**
	 * @param $app
	 * @return mixed
	 */
	private function getContentType($app)
	{
		$response   = $app->response();
		$response->header("Content-type", "application/json");

		return $response;
	}
}
