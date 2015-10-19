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
use Verem\Emoji\Api\Utils\Queryable;
use Verem\Emoji\Api\Utils\Connection;
use Verem\Emoji\Api\Exceptions\RecordNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;

class EmojiManager extends Connection implements Queryable
{
    /**
     * find an emoji matching the specified id.
     *
     * @param $id
     * @return mixed
     * @throws RecordNotFoundException
     */
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
        $statement->execute();

        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $result = $statement->fetch();

        //if result is not empty
        if ($result) {
            return $result;
        }

        throw new RecordNotFoundException("The emoji does not exist in the database");
     }

     /**
     * Search for an  emoji by column name
     *
     * @param $column
     * @param $operand
     * @param $value
     * @return string
     * @throws RecordNotFoundException
     */
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
            return $statement->fetchAll(PDO::FETCH_ASSOC)[1];
        }

        throw new RecordNotFoundException("The record does not exist");
     }

     /**
     * Fetch all emojis from the database
     *
     * @return array
     * @throws RecordNotFoundException
     */
     public function all()
     {
        //construct a sql query
        $sql = "SELECT * FROM emojis";

        //get a connection instance
        $connection = $this->getConnection();

        //make a database query
        $statement = $connection->query($sql);

        //fetch result
        $result = $statement->fetchAll(PDO::FETCH_CLASS);


        //if result is not empty return it, else throw an exception

        if (! empty($result)) {
            return $result;
        }

        throw new RecordNotFoundException('No Records found');
     }

     /**
     * Delete an emoji that matches the specified id.
     *
     * @param $id
     * @return bool|PDOException
     */
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
        if ($statement->rowCount() > 0) {
            return true;
        }

         return new PDOException("Unable to delete record");
     }

     /**
	 * Update an emoji matching the specified id
	 * with the object record
	 *
     * @param $id
     * @param Emoji $emoji
     * @return bool
     */
     public function update($id, Emoji $emoji)
     {
         //construct a sql statement
        $sql = "UPDATE emojis SET emojiname = ?, emojichar = ?, category = ?, updated_at = ? WHERE id = ?";

        // get a connection to the database
        $connection = $this->getConnection();

        //prepare a statement;
        $statement = $connection->prepare($sql);

        //bind params
        $statement->bindParam(1, $emoji->getName());
	    $statement->bindParam(2, $emoji->getChar());
	    $statement->bindParam(3, $emoji->getCategory());
	    $statement->bindParam(4, $emoji->getUpdatedAt());
	    $statement->bindParam(5, $id);


        //execute the statement
        $statement->execute();


        //if statement executed successfully, return true, fail otherwise

        if ($statement->rowCount() > 0) {
            return true;
        }
         throw new PDOException("Unable to update record");
     }

	 /**
	 * Save a newly created Emoji to database
	 *
	 * @param Emoji $emoji
	 * @return bool
	 */
     public function save(Emoji $emoji)
     {
        //construct a sql statement
        $sql = "INSERT INTO emojis (emojiname, emojichar, keywords, category, created_at, updated_at, created_by) VALUES(?,?,?,?,?,?,?)";

        //get a database connection
        $connection = $this->getConnection();
        //prepare a statement
        $statement = $connection->prepare($sql);

		/*
		 * I have to get the object properties here because, apparently
		 * PHP does not allow passing of objects by reference, sad
		 * stuff.
		 */
		 $name 		= $emoji->getName();
		 $char		= $emoji->getChar();
		 $keywords 	= $emoji->getKeywords();
		 $category 	= $emoji->getCategory();
		 $createdAt = $emoji->getCreatedAt();
		 $updatedAt = $emoji->getUpdatedAt();
		 $createdBy = $emoji->getCreatedBy();

        //bind params

        $statement->bindParam(1, $name);
        $statement->bindParam(2, $char);
        $statement->bindParam(3, $keywords);
        $statement->bindParam(4, $category);
        $statement->bindParam(5, $createdAt);
        $statement->bindParam(6, $updatedAt);
        $statement->bindParam(7, $createdBy);

        //execute statement
        $statement->execute();

        //check to see if record has been saved, if it isn't
        //throw an exception
        if ($statement->rowCount() > 0) {
            return true;
        }

        throw new PDOException("Error: Unable to save emoji");
     }

	 /**
	 * convert an array to a json object
	 *
	 * @param array $object
	 * @return string
	 */
	 public function toJson(array $object)
     {
        if (! is_array($object)) {
            throw new InvalidArgumentException(json_encode(["message" =>"Argument must be of type array"]));
        }
        return json_encode($object);
     }
}
