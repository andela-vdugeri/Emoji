<?php
/**
 * Created by PhpStorm.
 * @author: Verem Dugeri
 * Date: 10/7/15
 * Time: 4:03 PM
 *
 * Model class for the user object. The class embodies object
 * properties and method for returning the properties.
 */


namespace Verem\Emoji\Api;

class User
{
    /**
     * @var string $username
     */
     private $username;

     /**
     * @var string $name
     */
     private $name;

     /**
     * @var string $password
     */
     private $password;

     /**
     * @var string $token
     */
     private $token;

     /**
     * @var  $tokenExpire
     */
     private $tokenExpire;


     /**
     * @param $username
     * @param $password
     * @param $names
     *
     * Construct a user instance
     */
     public function __construct($username, $password, $names)
     {
         $this->username = $username;
         $this->password = $password;
         $this->name = $names;
     }

     /**
     * @return string
     *
     * Get the username for a user instance
     */
     public function getUsername()
     {
         return $this->username;
     }

     /**
     * @return string
     *
     * get the names of a user instance
     */
     public function getNames()
     {
         return $this->name;
     }

     /**
     * @return string
     *
     * get the password for a user instance
     */
     public function getPassword()
     {
         return $this->password;
     }

     /**
     * @param $token
     *
     * get the verification token for the user
     */
     public function setToken($token)
     {
         $this->token = $token;
     }

     /**
     * @param $tokenExpire
     *
     * set the token expiry time for a particular user token
     */
     public function setTokenExpire($tokenExpire)
     {
         $this->tokenExpire = $tokenExpire;
     }

     /**
     * @return string
     *
     * get the token for a user instance
     */
     public function getToken()
     {
         return $this->token;
     }

     /**
     * @return mixed
     *
     * get the expiry date for a particular token
     */
     public function getTokenExpire()
     {
         return $this->tokenExpire;
     }
}
