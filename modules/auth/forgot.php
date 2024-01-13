<?php
if (!defined('_INCODE')) die('Access Deined...');
//file nay chua chac nang quen mk

$data=[
    'pageTitle'=>'Đặt lại mật khẩu',
];
layout('header-login', $data);

//kiem tra trang thai dang nhap

if (isLogin()){
    redirect('?module=users');
}


if(isPost()){
    $body=getBody();
    if (!empty(trim($body['email']))){
        //truy van
        $email=$body['email']; 
        $fogotQuery=firstRaw("SELECT * FROM users WHERE email='$email'");
        if (!empty($fogotQuery)){
            $userId=$fogotQuery['id'];
            // tao token fogot
            $token=sha1(uniqid().time());
            $updateFogot=[
                'forgotToken'=>$token
            ];
            $updateStatus=update('users', $updateFogot, 'id='.$userId );
            if ($updateStatus){ 
                // gui mail
                $link=_WEB_HOST_ROOT.'?module=auth&action=reset&token='.$token;
                $subject='Yêu cầu khôi phục mật khẩu';
                $content='Chào bạn: '. $body['fullname']. '<br/>';
                $content.='Vui lòng click vào link sau để khôi phục: '. $link;
                $sendMail=sendMail($email, $subject, $content);
                if ($sendMail){
                    setFalshData('msg', 'Vui lòng kiểm tra gmail');
                    setFalshData('msg_type', 'success');
                }else{
                    setFalshData('msg', 'Lỗi hệ thống');
                    setFalshData('msg_type', 'danger');
                }
                
            }else{
                setFalshData('msg', 'Lỗi hệ thống');
                setFalshData('msg_type', 'danger');
            }
        }else{
            setFalshData('msg', 'Email không tồn tại');
            setFalshData('msg_type', 'danger');
        }
    }else{
        setFalshData('msg', 'Vui lòng nhập địa chỉ email');
        setFalshData('msg_type', 'danger');
    }
    redirect('?module=auth&action=forgot');

}
$msg=getFalshData('msg');
$msg_type=getFalshData('msg_type');

?>
<div class="row">
    <div class="col-6" style="margin: 22px auto;">
        <h3 class="text-center text-uppercase"> Đặt lại mật khẩu </h3>
        <?php getMsg($msg, $msg_type); ?>
        <form action="" method="post">
            <div class="form-group">
                <label for="">Email</label>
                <input type="email" name="email" id="" class="form-control" placeholder="Địa chỉ email...">
            </div>


            <button type="submit" class="btn btn-primary btn-block">Xác nhận</button>
            <hr>
            <p class="text-center"><a href="?module=auth&action=forgot">Đăng nhập</a></p>
            <p class="text-center"><a href="?module=auth&action=register">Đăng ký tài khoản</a></p>
        </form>
    </div>
</div>
<?php

layout('footer-login');