<?php
if (!defined('_INCODE')) die('Access Deined...');
//chua cac ham lien quan den thao tac session

function setSession ($key, $value){
    if (!empty(session_id())){
        $_SESSION[$key]=$value;
        return true;
    }
}

//ham doc seesion
function getSession ($key=''){
    if (empty($key)){
        return $_SESSION;
    }else{
        if (isset($_SESSION[$key])){
            return $_SESSION[$key];
        }
    }
    return false;
}

//ham xoa 
function removeSession ($key=''){
    if (empty($key)){
        session_destroy();
    }else {
        if (isset($_SESSION[$key])){
            unset($_SESSION[$key]);
            return true;
        }
    }
    return false;
}

//ham gan flash data
function setFalshData($key, $value){
    $key='flash_'.$key;
    return setSession($key, $value);
}

//ham doc flash data
function getFalshData($key){
    $key='flash_'.$key;
    $data=getSession($key);
    removeSession($key);
    return $data;
}