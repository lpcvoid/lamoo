<?php
/**
 * Created by PhpStorm.
 * User: Swoosh
 * Date: 26.04.16
 * Time: 17:24
 */

class lmoLovooCredentials {

    private $_username;
    private $_password;

    function __construct($username, $pass){
        $this->_password = $pass;
        $this->_username = $username;
    }

    function GetUsername(){
        return $this->_username;
    }

    function GetPassword(){
        return $this->_password;
    }

} 