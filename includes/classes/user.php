<?php

class User{

    function checkAuthenticated(){
        if ( isset($_SESSION['authenticated']['status']) )
        {
            return array(
                    'status'=> $_SESSION['authenticated']['status'],
                    'username'=> $_SESSION['authenticated']['username'],
                    'user' => $_SESSION['authenticated']['user'],
                    'usertype' => $_SESSION['authenticated']['usertype']);
        }
        else
        {
            return array('status' => false, 'username' => false, 'user' => false, 'usertype'=> array('name' => false));
        }
    }

    function getUserName(){
        if ($this->checkAuthenticated()){
            return $_SESSION['authenticated']['username'];
        }else{
            return false;
        }
    }

    function getUserType(){
        if ($this->checkAuthenticated()){
            return isset($_SESSION['authenticated']['usertype']) ? $_SESSION['authenticated']['usertype'] : false;
        }else{
            return false;
        }
    }
}
