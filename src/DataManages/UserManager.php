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

		//if affected rows is more than one, return the result;
		if($statement->rowCount() > 0) {
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

		//if affected rows is more than one, return the result;
		if($statement->rowCount() > 0) {
			return true;
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

		if(! empty($result)) {
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
		if($statement->rowCount() > 0) {
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
		if($statement->rowCount() > 0) {
			return true;
		}

		throw new PDOException("Error:  Record not saved. Please try again");
	}
}