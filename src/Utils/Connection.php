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
	/**
	 * @var string $dsn The data source name for database connection
	 */
    private $dsn;

	/**
	 * @var string $host The database connection host
	 */
	private $host;

	/**
	 * @var string $dbName Database name
	 */
	private $dbName;

	/**
	 * @var string $username Database username
	 */
	private $username;

	/**
	 * @var string $password Database password
	 */
	private $password;

	/**
	 * @var string $connection the connection driver
	 */
    private $connection;

	/**
	 * @return null|PDO
	 *
	 * Try to get a connection to the database. Fail with
	 * an error is connection is not successful.
	 */
    public function getConnection()
    {
        //initialize database configurations
        $this->initConfigs();
        //try to get a pdo connection, fail if an error occurs
        $connection = null;
        try {
            $connection = new PDO($this->dsn, $this->username, $this->password);
        } catch (PDOException $e) {
            echo "Error: ".$e->getMessage();
        }

        return $connection;
    }

	/**
	 * initialise database connection configurations & settings
	 */
    public function initConfigs()
    {
        //load the env file
        $this->loadEnv();

        //read the environment variables
        $this->host        =    getenv('DB_HOST');
        $this->dbName      =    getenv('DB_NAME');
        $this->password    =    getenv('DB_PASSWORD');
        $this->username    =    getenv('DB_USERNAME');
        $this->connection  =    getenv('DB_CONNECTION');

        //construct the data source
        $this->dsn = $this->connection.':host='.$this->host.';dbname='.$this->dbName;

    }
}
