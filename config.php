<?php
//file nay chua cac hang so cau hinh
date_default_timezone_set('Asia/Ho_Chi_Minh');
const _MODULE_DEFAULT='home';
const _ACTION_DEFAULT='lists';

const _INCODE=true; // nganw chan hanh vi truy cap truc tiep vao file

//thiet lap host
define('_WEB_HOST_ROOT', 'http://'.$_SERVER['HTTP_HOST'].'/hocphp/users_manager'); //dia chi trang chu

define('_WEB_HOST_TEMPLATE', _WEB_HOST_ROOT.'/templates');

//thiet lap path
define('_WEB_PATH_ROOT',__DIR__);
define('_WEB_PATH_TEMPLATE', _WEB_PATH_ROOT.'/templates');

//Kết nối database

//Thông tin kết nối
const _HOST = 'localhost';
const _USER = 'root';
const _PASS = ''; //Xampp => pass='';
const _DB = 'quanlynguoidung1';
const _DRIVER = 'mysql';

//so luong ban ghi tren 1 trang
const _PER_PAGE=5;