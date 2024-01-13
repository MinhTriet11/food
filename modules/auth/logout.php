<?php
if (!defined('_INCODE')) die('Access Deined...');
//file nay chua chuc nang dang xuat
if (isLogin()){
    $token=getSession('logintoken');
    delete('logintoken', "token='$token'");
    removeSession('logintoken');
    redirect('?module=auth&action=login');
}