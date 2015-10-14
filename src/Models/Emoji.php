<?php
/**
 * Created by PhpStorm.
 * @author : Verem
 * Date: 10/7/15
 * Time: 4:21 PM
 *
 *
 * class Emoji The model for the emoji image.
 * Contains all properties and methods for
 * manipulating an Emoji object.
 */

namespace Verem\Emoji\Api;

class Emoji
{
    /**
     * @var string $name The emoji name
     */
     private $name;

     /**
     * @var string $char The hex-code representing
     * the emoji
     */
     private $char;

     /**
     * @var array keywords The array of keywords describing
     * the object
     */
     private $keywords;

     /**
     * @var string $category The category the emoji belongs to
     */
     private $category;

     /**
     * @var $createdAt
     */
     private $createdAt;

     /**
     * @var $createdAt
     */
     private $updatedAt;

     /**
     * @var int $createdBy The user id of the creator.
     */
     private $createdBy;


     /**
     * @param $name
     * @param $char
     * @param string $keywords
     * @param $category
     *
     * Create an Emoji instance
     */
     public function __construct($name, $char, $keywords, $category)
     {
         $this->name    =    $name;
         $this->char    =    $char;
         $this->category =    $category;
         $this->keywords =    $keywords;
     }

     /**
     * @param $createdAt
     *
     * Set the date the emoji was created
     */
     public function setCreatedAt($createdAt)
     {
         $this->createdAt = $createdAt;
     }

     /**
     * @param $updatedAt
     *
     * set the date the emoji was updated on.
     */
     public function setUpdatedAt($updatedAt)
     {
         $this->updatedAt = $updatedAt;
     }

     /**
     * @param $createdBy
     *
     * Set the id of the user that created the emoji
     */
     public function setCreatedBy($createdBy)
     {
         $this->createdBy = $createdBy;
     }

     /**
     * Set the keywords that describe the Emoji
     *
     * @param $keywords
     */
     public function setKeywords($keywords)
     {
         $this->keywords = $keywords;
     }

     /**
     * @return string
     *
     * return the name of the emoji
     */
     public function getName()
     {
         return $this->name;
     }

     /**
     * @return string
     *
     * return the hex-code representing the emoji
     */
     public function getChar()
     {
        return $this->char;
     }

     /**
     * @return string
     *
     * return the category the emoji belongs to
     */
     public function getCategory()
     {
        return $this->category;
     }

     /**
     * @return array
     *
     * get the keywords that describe the emoji
     */
     public function getKeywords()
     {
        return $this->keywords;
     }

     /**
     * @return mixed
     *
     * get the date at which the emoji was created
     */
     public function getCreatedAt()
     {
        return $this->createdAt;
     }

     /**
     * @return mixed
     *
     * get the date the emoji was updated on.
     */
     public function getUpdatedAt()
     {
        return $this->updatedAt;
     }

     /**
     * @return int
     *
     * get the id of the user that created the emoji
     */
     public function getCreatedBy()
     {
        return $this->createdBy;
     }
}