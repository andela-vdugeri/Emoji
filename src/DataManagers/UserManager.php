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
	 /**
	 * Find a user matching the specified id.
	 *
	 * @param $id
	 * @return bool
	 * @throws RecordNotFoundException
	 */
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
         $statement->execute();
		 $result = $statement->fetchAll();
        //if result is not empty, return it
        if (! empty($result)) {
            return $result[0];
        }

        throw new RecordNotFoundException("The user does not exist in the database");
     }

	 /**
	 * Find a user with a property matching the specified column
	 * @param $column
	 * @param $operand
	 * @param $value
	 * @return mixed
	 * @throws RecordNotFoundException
	 */
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

	 /**
	 * Get all users from the database
	 *
	 * @return array
	 * @throws RecordNotFoundException
	 */
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
            return $result[0];
        }

        throw new RecordNotFoundException('No Records found');
     }

	 /**
	 * Delete a user from the database with the specified id.
	 * @param $id
	 * @return bool|PDOException
	 */
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

	 /**
	 * Save a newly created user to database
	 *
	 * @param User $user
	 * @return bool
	 */
     public function save(User $user)
     {
        //construct a sql statement
        $sql = 'INSERT INTO users (username, password, usernames,token, token_expire) VALUES(?,?,?,?,?)';

        //connect to the database
        $connection = $this->getConnection();

        //prepare a statement
        $statement = $connection->prepare($sql);

		/*
		* I have to get the object properties here because, apparently
		* PDOStatement::bindParam() does not allow passing of objects by reference, sad
		* stuff.
		*/
	    $username    = $user->getUsername();
	    $password    = $user->getPassword();
	    $names 	  	 = $user->getNames();
	    $token 	     = $user->getToken();
	    $tokenExpire = $user->getTokenExpire();

		//bind params to statement
        $statement->bindParam(1, $username);
        $statement->bindParam(2, $password);
        $statement->bindParam(3, $names);
        $statement->bindParam(4, $token);
        $statement->bindParam(5, $tokenExpire);

        //execute query
        $statement->execute();

        //check to see if user was saved
        if ($statement->rowCount() > 0) {
            return true;
        }

        throw new PDOException("Error:  Record not saved. Please try again");
     }

	 /**
	 * return the json format of the array argument
	 *
	 * @param array $object
	 * @return string
	 */
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
				'status'  =>   200,
				'token'	  =>   $token,
				'message' =>   'token expiry set'
			]);
		}

		return $this->toJson([
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
			  'status' 	=> 	200,
			  'message'		=>  'Session invalidated'
			]);
		}

		return json_encode([
			'status' => 304,
			'message'	 => 'Unable to invalidate session'
		]);

	 }

}
