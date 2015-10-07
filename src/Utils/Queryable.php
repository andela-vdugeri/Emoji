<?php
/**
 * Created by PhpStorm.
 * User: andela
 * Date: 10/7/15
 * Time: 5:18 PM
 */

namespace Verem\Emoji\Api\Utils;


interface Queryable
{


	public function find($id);

	public function where($column, $operand, $value);

	public function all();

	public function delete($id);

	//public function update($id);

	//public function save($object);
}