<?php
/**
 * Created by PhpStorm.
 * User: andela
 * Date: 10/7/15
 * Time: 4:49 PM
 */

namespace Verem\Emoji\Api\Utils;

use Dotenv\Dotenv;

class EnvReader extends Dotenv {


	private $dotEnv;
	public function  __construct()
	{
		$this->dotEnv = parent::__construct(__DIR__.'/../');
	}

	public function loadEnv()
	{
		$this->dotEnv->load();
	}
}