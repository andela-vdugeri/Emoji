<?php
/**
 * Created by PhpStorm.
 * @author : Verem Dugeri
 * Date: 10/7/15
 * Time: 4:49 PM
 *
 * This class is responsible for loading the contents
 * of the .env file found in the root directory
 * of this project.
 */

namespace Verem\Emoji\Api\Utils;

use Dotenv\Dotenv;

class EnvReader extends Dotenv
{
	 /**
	 * create an instance of the class
	 */
     public function __construct()
     {
        parent::__construct(__DIR__ .'/../..');
     }

	 /**
	 * load the environment file.
	 */
     public function loadEnv()
     {
        $this->load();
     }
}
