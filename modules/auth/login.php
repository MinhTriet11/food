<?php
if (!defined('_INCODE')) die('Access Deined...');
//file nay chua chuc nang dang nhap
autoRemovetoken();
$data=[
    'pageTitle'=>'Đăng nhập hệ thống',
];
layout('header-login', $data);


//kiem tra trang thai dang nhap

if (isLogin()){
    redirect('?module=users');
}


$msg=getFalshData('msg');
$msg_type=getFalshData('msg_type');

if(isPost()){
    $body=getBody();
    if (!empty(trim($body['email'])) && !empty(trim($body['password']))){
        //kiem tra dang nhap
        $email=$body['email'];
        $password=$body['password'];
        //truy van
        $userQuery=firstRaw("SELECT id,password FROM users WHERE email='$email' AND status=1");
        if (!empty($userQuery)){
            $passwordHash=$userQuery['password'];
            $userId=$userQuery['id'];
            if (password_verify($password,$passwordHash)){
                //tao token login
                $tokenLogin=sha1(uniqid().time());
                //insert du lieu vao bang login token
                $dataToken=[
                    'userId'=>$userId,
                    'token'=>$tokenLogin,
                    'createAt'=>date("Y:m:d H:i:s")
                ];
                $insertTokenstatus=insert('logintoken', $dataToken);
                if ($insertTokenstatus){
                    //luu logintoken vao session
                    setSession('logintoken', $tokenLogin);
                    redirect('?module=users');
                
                }else{
                    setFalshData('msg', 'Lỗi hệ thống');
                    setFalshData('msg_type', 'danger');
                    //redirect('?module=auth&action=login');
                }
            }else{
                setFalshData('msg', 'Mật khẩu không chính xác');
                setFalshData('msg_type', 'danger');
                //redirect('?module=auth&action=login');
            }
        }else{
            setFalshData('msg', 'Email không tồn tại hoặc chưa được kích hoạt');
            setFalshData('msg_type', 'danger');
            //redirect('?module=auth&action=login');
        }
    }else{
        setFalshData('msg', 'Vui lòng nhập email và mật khẩu');
        setFalshData('msg_type', 'danger');
        //redirect('?module=auth&action=login');
    }
    redirect('?module=auth&action=login');
}

?>
<div class="row">
    <div class="col-6" style="margin: 22px auto;">
        <h3 class="text-center text-uppercase"> Đăng nhập hệ thống </h3>
        <?php getMsg($msg, $msg_type); ?>
        <form action="" method="post">
            <div class="form-group">
                <label for="">Email</label>
                <input type="email" name="email" id="" class="form-control" placeholder="Địa chỉ email...">
            </div>
            <div class="form-group">
                <label for="">Mật khẩu</label>
                <input type="password" name="password" id="" class="form-control" placeholder="Mật khẩu...">
            </div>

            <button type="submit" class="btn btn-primary btn-block">Đăng nhập</button>
            <hr>
            <p class="text-center"><a href="?module=auth&action=forgot">Quên mật khẩu</a></p>
            <p class="text-center"><a href="?module=auth&action=register">Đăng ký tài khoản</a></p>
        </form>
    </div>
</div>
<?php

layout('footer-login');