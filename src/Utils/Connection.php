<?php
/**
 * Created by PhpStorm.
 * User: andela
 * Date: 10/7/15
 * Time: 4:48 PM
 */

namespace Verem\Emoji\Api\Utils;

use PDO;
use PDOException;

class Connection extends EnvReader
{
    private $dsn;
    private $username;
    private $password;
    private $dbName;
    private $host;
    private $connection;
    public function getConnection()
    {
        //try to get a pdo connection, fail if an error occurs
		$connection = null;
        try {
			$connection = new PDO($this->dsn, $this->username, $this->password);
        } catch (PDOException $e) {
			echo "Error: ".$e->getMessage();

        }

		return $connection;
    }

    public function initConfigs()
    {
        //load the env file
        $this->loadEnv();

        //read the environment variables
        $this->host        =    getenv('DB_HOST');
        $this->dbName        =    getenv('DB_NAME');
        $this->password    =    getenv('DB_PASSWORD');
        $this->username    =    getenv('DB_USERNAME');
        $this->connection    =    getenv('DB_CONNECTIOn');

        //construct the data source
        $this->dsn = $this->connection.':host='.$this->host.';dbname='.$this->dbName;
    }
}
