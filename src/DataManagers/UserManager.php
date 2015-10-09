<?php
/**
 * Created by PhpStorm.
 * User: andela
 * Date: 10/7/15
 * Time: 5:35 PM
 */

namespace Verem\Emoji\Api\DAO;

use PDO;
use PDOException;
use Verem\Emoji\Api\User;
use InvalidArgumentException;
use Verem\Emoji\Api\Utils\Queryable;
use Verem\Emoji\Api\Utils\Connection;
use Verem\Emoji\Api\Exceptions\RecordNotFoundException;

class UserManager extends Connection implements Queryable
{
    public function find($id)
    {
        //create sql statement
        $sql = "SELECT * FROM users WHERE id = ?";

        //get a connection instance
        $connection = $this->getConnection();

        //prepare a statement
        $statement = $connection->prepare($sql);

        //bind params
        $statement->bindParam(1, $id);

        //get the result
        $result = $statement->execute();

        //if result is not empty, return it
        if (! empty($result)) {
            return $result;
        }

        throw new RecordNotFoundException("The user does not exist in the database");
    }

    public function where($column, $operand, $value)
    {
        //construct a sql query
        $sql = "SELECT * FROM users WHERE $column $operand ?";

        //create a connection to the database
        $connection = $this->getConnection();

        //prepare a statement
        $statement = $connection->prepare($sql);

        //bind params

        $statement->bindParam(1, $value);

        //execute query;

        $statement->execute();

		$result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($statement->rowCount() > 0) {
            return $result;
        }

        throw new RecordNotFoundException("The record does not exist");
    }

    public function all()
    {
        //construct a sql query
        $sql = "SELECT * FROM users";

        //get a connection instance
        $connection = $this->getConnection();

        //make a database query
        $statement = $connection->query($sql);

        //fetch result
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        //if result is not empty return it, else throw an exception

        if (! empty($result)) {
            return $result;
        }

        throw new RecordNotFoundException('No Records found');
    }

    public function delete($id)
    {
        //construct a sql query
        $sql = "DELETE FROM users WHERE id = ?";

        //create a connection to the database
        $connection = $this->getConnection();

        //prepare a statement
        $statement = $connection->prepare($sql);

        //bind params

        $statement->bindParam(1, $id);

        //execute the statement
        $statement->execute();

        //check if rows were affected
        if ($statement->rowCount() > 0) {
            return true;
        }

        return new PDOException("Unable to delete record");
    }

    public function update($id)
    {
    }

    public function save(User $user)
    {
        //construct a sql statement
        $sql = 'INSERT INTO users (username, password, usernames,token, token_expire) VALUES(?,?,?,?,?)';

        //connect to the database
        $connection = $this->getConnection();

        //prepare a statement
        $statement = $connection->prepare($sql);

        //bind params to statement
        $statement->bindParam(1, $user->getUsername());
        $statement->bindParam(2, $user->getPassword());
        $statement->bindParam(3, $user->getNames());
        $statement->bindParam(4, $user->getToken());
        $statement->bindParam(5, $user->getTokenExpire());

        //execute query
        $statement->execute();

        //check to see if user was saved
        if ($statement->rowCount() > 0) {
            return true;
        }

        throw new PDOException("Error:  Record not saved. Please try again");
    }

    public function toJson(array $object)
    {
        if (! is_array($object)) {
            throw new InvalidArgumentException("Argument must be of type array");
        }
        return json_encode($object);
    }

	public function updateToken($expiryDate,$token, $username)
	{
		//construct a sql
		$sql = "UPDATE users SET token_expire = ?, token = ? WHERE username = ?";

		//create a connection
		$connection = $this->getConnection();

		//prepare a sql statement
		$statement = $connection->prepare($sql);

		//bind statement parameters
		$statement->bindParam(1, $expiryDate);
		$statement->bindParam(2, $token);
		$statement->bindParam(3, $username);

		//execute the statement
		$statement->execute();

		//return true if a row is affected
		if($statement->rowCount() > 0) {
			return  $this->toJson([
			    'statusCode' =>  200,
				'message' 	 =>  'token expiry set'
			]);
		}

		return $this->toJson([
		  	'statusCode' => '304',
			'message'	 => 'Unable to set token expiry date'
		]);

	}

	/**
	 * @param $token
	 * @return string
	 *
	 * Delete the token and related token content from the database
	 */
	public function invalidateSession($token)
	{
		//construct a sql query
		$sql = "UPDATE users SET token = NULL, token_expire = NULL WHERE token = ?";

		//get a connection
		$connection = $this->getConnection();

		//prepare a statement
		$statement = $connection->prepare($sql);

		//bind params
		$statement->bindParam(1, $token);

		//execute the statement
		$statement->execute();

		//check to see if the row was affected
		if($statement->rowCount() > 0) {
			return json_encode([
			  'statusCode' 	=> 	200,
			  'message'		=>  'row updated'
			]);
		}

		return json_encode([
			'statusCode' => 304,
			'message'	 => 'No rows affected'
		]);

	}

}
