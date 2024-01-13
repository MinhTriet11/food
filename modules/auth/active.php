<?php
if (!defined('_INCODE')) die('Access Deined...');
//file nay chua chuc nang kich hoat tai khoan
layout('header-login');
$token=getBody()['token'];
if (!empty($token)){
    //truy van kiem tra token
    $tokenQuery=firstRaw("SELECT id, fullname, email FROM users WHERE activeToken='$token'");
    if (!empty($tokenQuery)){
        $userId=$tokenQuery['id'];
        $dataUpdate=[
            'status'=>1,
            'activeToken'=>null
        ];
        $userUpdate=update('users', $dataUpdate, "id=$userId");
        if ($userUpdate){
            //tao link
            $link=_WEB_HOST_ROOT.'?modules=auth&action=login';
            $subject='Kích hoạt tài khoản thành công';
            $content='Chúc mừng '.$tokenQuery['fullname'].'<br/>';
            $content.='Vui lòng đăng nhập để sử dụng web <br/>';
            $content.='Link đăng nhập: '.$link;
            sendMail($tokenQuery['email'], $subject, $content);
            setFalshData('msg', 'Kích hoạt tài khoản thành công! Vui lòng đăng nhập tài khoản');
            setFalshData('msg_type', 'success');
        }else{
            setFalshData('msg', 'Kích hoạt tài khoản không thành công!');
            setFalshData('msg_type', 'danger');
        }
    redirect('?module=auth&action=login');
    }else{
        getMsg('Liên kết tồn tài hoặc đã hết hạn', 'danger');
    }
}else {
    getMsg('Liên kết tồn tài hoặc đã hết hạn', 'danger');
}
layout('footer-login');