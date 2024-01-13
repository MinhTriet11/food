<?php
if (!defined('_INCODE')) die('Access Deined...');
//file nay chua chuc nang dat lai mk
$data=[
    'pageTitle'=>'Đặt lại mật khẩu '
];
layout('header-login', $data );
echo '<div class="container text-center"> <br/>';
$token=getBody()['token'];
if (!empty($token)){
    $tokenQuery=firstRaw("SELECT id, fullname, email FROM users WHERE forgotToken='$token'");
    if (!empty($tokenQuery)){
        $userId=$tokenQuery['id'];
        $email=$tokenQuery['email'];
        if (isPost()){
            $body=getBody();
            $errors=[];
            if (empty(trim($body['password']))){
                $errors['password']['required']='Mật khẩu không được để trống';
            }else {
                if(strlen(trim($body['password']))>=8){
                    $errors['password']['isPass']='Mật khẩu phải nhỏ hơn 8 ký tự';
                }
            }
        
            if (empty(trim($body['confirm_password']))){
                $errors['confirm_password']['required']='Xác nhận mật khẩu không được để trống';
            }else{
                if (trim($body['password']) != trim($body['confirm_password'])){
                    $errors['confirm_password']['confirm']='Mật khẩu không đúng';
                }
            }
            if (empty($errors)){
                //xu ly update matj khau
                $passwordHash=password_hash($body['password'], PASSWORD_DEFAULT);
                $updataPass=[
                    'password'=>$passwordHash,
                    'forgotToken'=>null,
                    'updateAt'=>date("Y:m:d H:i:s")
                ];
                $updateStatus=update('users', $updataPass, 'id='.$userId);
                if ($updateStatus){
                    setFalshData('msg', 'Thay đổi mật khẩu thành công');
                    setFalshData('msg_type','success');
                    //gui mail thong bao
                    $subject='Bạn vừa đổi mật khẩu';
                    $content='Chúc mừng bạn đã đổi mật khẩu thành công!';
                    sendMail($email, $subject, $content);
                    redirect('?module=auth&action=login');
                }else{
                    setFalshData('msg', 'Lỗi hệ thống');
                    setFalshData('msg_type', 'danger');
                    redirect('?module=auth&action=reset&token='.$token); 
                }

            }else{
                setFalshData('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
                setFalshData('msg_type', 'danger');
                setFalshData('errors', $errors);
                redirect('?module=auth&action=reset&token='.$token); 
            }
            }
            $msg=getFalshData('msg');
            $msgType=getFalshData('msg_type');
            $errors=getFalshData('errors');

        ?>
        
        <div class="row text-left">
            <div class="col-6" style="margin: 22px auto;">
                <h3 class="text-center text-uppercase"> Đặt lại mật khẩu </h3>
                <?php getMsg($msg, $msgType); ?>
        <form action="" method="post">
            <div class="form-group">
                <label for="">Mật khẩu mới</label>
                <input type="password" name="password" id="" class="form-control" placeholder="Địa chỉ email...">
                <?php echo from_error('password', $errors, '<span class="error">', '</span>'); ?>
            </div>

            <div class="form-group">
                <label for="">Xác nhận mật khẩu mới</label>
                <input type="password" name="confirm_password" id="" class="form-control" placeholder="Mật khẩu...">
                <?php echo from_error('confirm_password', $errors, '<span class="error">', '</span>'); ?>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Xác nhận</button>
            <hr>
            <p class="text-center"><a href="?module=auth&action=login">Đăng nhập</a></p>
            <p class="text-center"><a href="?module=auth&action=register">Đăng ký tài khoản</a></p>
            <input type="hidden" name="token" value="<?php echo $token; ?> ">
        </form>
    </div>
</div>

        <?php

    }else{
        getMsg('Liên kết tồn tài hoặc đã hết hạn', 'danger');
    }
}else{
    getMsg('Liên kết tồn tài hoặc đã hết hạn', 'danger');
}
echo '</div>';
layout('footer-login');
