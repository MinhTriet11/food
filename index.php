<?php
session_start();
require_once 'config.php';
require_once 'includes/phpmailer/PHPMailer.php';
require_once 'includes/phpmailer/SMTP.php';
require_once 'includes/phpmailer/Exception.php';
require_once 'includes/function.php';
require_once 'includes/connect.php';
require_once 'includes/session.php';
require_once 'includes/database.php';
$module =_MODULE_DEFAULT;
$action=_ACTION_DEFAULT;
if (!empty($_GET['module'])){
    if (is_string($_GET['module'])){
    $module=trim($_GET['module']);
    }
}

if (!empty($_GET['action'])){
    if (is_string($_GET['action'])){
    $action=trim($_GET['action']);
    }
}
$path='modules/'.$module.'/'.$action.'.php';

if (file_exists($path)){
    require_once $path;
}else{
    require_once 'modules/errors/404.php';
}

