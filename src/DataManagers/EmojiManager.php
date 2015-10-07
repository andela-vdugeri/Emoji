<?php
/**
 * Created by PhpStorm.
 * User: andela
 * Date: 10/7/15
 * Time: 9:00 PM
 */

namespace Verem\Emoji\Api\DAO;

use PDO;
use PDOException;
use Verem\Emoji\Api\Emoji;
use Verem\Emoji\Api\Utils\Connection;
use Verem\Emoji\Api\Utils\Queryable;
use Verem\Emoji\Api\Exceptions\RecordNotFoundException;

class EmojiManager extends Connection implements Queryable
{
    public function find($id)
    {
        //create sql statement
        $sql = "SELECT * FROM emojis WHERE id = ?";

        //get a connection instance
        $connection = $this->getConnection();

        //prepare a statement
        $statement = $connection->prepare($sql);

        //bind params
        $statement->bindParam(1, $id);

        //get the result
        $result = $statement->execute();

        //if affected rows is more than one, return the result;
        if ($statement->rowCount() > 0) {
            return $result;
        }

        throw new RecordNotFoundException("The user does not exist in the database");
    }

    public function where($column, $operand, $value)
    {
        //construct a sql query
        $sql = "SELECT * FROM emojis WHERE $column $operand ?";

        //create a connection to the database
        $connection = $this->getConnection();

        //prepare a statement
        $statement = $connection->prepare($sql);

        //bind params

        $statement->bindParam(1, $value);

        //execute query;

        $statement->execute();

        //if affected rows is more than one, return the result;
        if ($statement->rowCount() > 0) {
            return true;
        }

        throw new RecordNotFoundException("The record does not exist");
    }

    public function all()
    {
        //construct a sql query
        $sql = "SELECT * FROM emojis";

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
		$sql = "DELETE FROM emojis WHERE id = ?";

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

    public function update($id, Emoji $emoji)
    {

    }

	public function save(Emoji $emoji)
	{
		//construct a sql statement
		$sql = "INSERT INTO emojis (emojiname,emojichar, keywords,category, creted_at, updated_at, created_by) VALUES(?,?,?,?,?,?,?)";

		//get a database connection
		$connection = $this->getConnection();

		//prepare a statement
		$statement = $connection->prepare($sql);

		//bind params
		$statement->bindParam(1, $emoji->getName());
		$statement->bindParam(2, $emoji->getChar());
		$statement->bindParam(3, $emoji->getKeywords());
		$statement->bindParam(4, $emoji->getCategory());
		$statement->bindParam(5, $emoji->getCreatedAt());
		$statement->bindParam(6, $emoji->getUpdatedAt());
		$statement->bindParam(7, $emoji->getCreatedBy());

		//execute statement
		$statement->execute();

		//check to see if record has been saved, if it isn't
		//throw an exception

		if($statement->rowCount() > 0) {
			return true;
		}

		throw new PDOException("Error: Unable to save emoji");
	}
}
