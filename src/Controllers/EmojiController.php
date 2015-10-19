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

class EmojiController {




	public static function findAll(Slim $app)
	{
		$response = $app->response();
		$response->header("Content-type", "application/json");
		$manager 	= new EmojiManager();
		$emojis 	= $manager->all();
		$result = $manager->toJson($emojis);
		$response->body($result);

		return $response;
	}


}