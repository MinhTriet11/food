<?php
if (!defined('_INCODE')) die('Access Deined...');
//file nay dung de xoa nguoi dung
$body=getBody();
if (!empty($body['id'])){
    $userId=$body['id'];
    $userDetail=getRows("SELECT id FROM users WHERE id='$userId'");
    if ($userDetail>0){
        //xoa token cua login token
        $deleteToken=delete('logintoken', "userId=$userId");
        if ($deleteToken){
            //xoa users
            $deleteUser=delete('users', "id=$userId");
            if ($deleteUser){
                setFalshData('msg', 'Xóa người dùng thành công');
                setFalshData('msg_type', 'success');
            }else{
                setFalshData('msg', 'Lỗi hệ thống! Vui lòng thử lại sau');
                setFalshData('msg_type', 'danger');
            }
        }
    }else{
        setFalshData('msg', 'Liên kết không tồn tại');
        setFalshData('msg_type', 'danger');
    }
}else{
    setFalshData('msg', 'Liên kết không tồn tại');
    setFalshData('msg_type', 'danger');
}
redirect('?module=users');