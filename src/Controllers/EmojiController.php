<?php
/**
 * Created by PhpStorm.
 * User: andela
 * Date: 10/19/15
 * Time: 6:47 PM
 */

namespace Verem\Emoji\Api;


use Slim\Slim;
use Verem\Emoji\Api\DAO\EmojiManager;
use Verem\Emoji\Api\Exceptions\RecordNotFoundException;

class EmojiController {




	public static function findAll(Slim $app)
	{
		$response   = $app->response();
		$response->header("Content-type", "application/json");
		$manager 	= new EmojiManager();
		$emojis 	= $manager->all();
		try{
			$result = $manager->toJson($emojis);
			$response->body($result);
			return $response;
		} catch(RecordNotFoundException $e) {
			$result= $manager->toJson([
			  "status"  => 500,
			  "message" => $e->getErrorMessage()]);
			$response->body($result);

			return $response;
		}

	}


}