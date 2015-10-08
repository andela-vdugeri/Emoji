<?php
/**
 * Created by PhpStorm.
 * @author : Verem Dugeri
 * Date: 10/7/15
 * Time: 5:18 PM
 *
 * The queryable interface contains the methods
 * that communicate with the database.
 */

namespace Verem\Emoji\Api\Utils;

interface Queryable
{
    /**
     * @param $id
     * @return mixed
     *
     * find a record in the database
     */
    public function find($id);

    /**
     * @param $column
     * @param $operand
     * @param $value
     * @return mixed
     *
     * perform a where search in the database
     */
    public function where($column, $operand, $value);

    /**
     * @return mixed
     *
     * get all records in the database table
     */
    public function all();

    /**
     * @param $id
     * @return mixed
     *
     * Delete a record
     */
    public function delete($id);

    /**
     * @param array $object
     * @return mixed
     *
     * return the json object of the array passed in
     * as an argument
     */
    public function toJson(array $object);
}
