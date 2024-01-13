 <?php
if (!defined('_INCODE')) die('Access Deined...');
//file nay chua chuc nang dang ky
$data=[
    'pageTitle'=>'Đăng ký tài khoản'
];
layout('header-login', $data);
//xu ly dang ky
if (isPost()){
    $body=getBody();
    $errors=[];
    //validate ho ten: bat buoc nhap , >=5 ky tu
    if (empty(trim($body['fullname']))){
        $errors['fullname']['required']='Họ tên không được để trống';
    }else{
        if (strlen(trim($body['fullname']))<=5){
            $errors['fullname']['min']='Họ tên phải lớn hơn 5 ký tự';
        }
    }
    //validate so dien thoai: bat buoc nhap, dinh dang so dt

    if (empty(trim($body['phone']))){
        $errors['phone']['required']='Số điện thoại không được để trống';
    }else{
        if (!isPhone(trim($body['phone']))){
            $errors['phone']['isPhone']='Số điện thoại không hợp lệ';
        }
    }

    //validate email: bat buoc phai nhap, dinh dang email, email phai la duy nhat

    if (empty(trim($body['email']))){
        $errors['email']['required']='Email không được để trống';
    }else{
        if(!isEmail(trim($body['email']))){
            $errors['email']['isEmail']='Email không hợp lệ';
        }else{
            //kiem tra email co ton tai ko
            $email=trim($body['email']);
            $sql="SELECT id FROM users WHERE email='$email'";
            if (getRows($sql)>0){
                $errors['email']['unique']='Địa chỉ email đã tồn tại';
            }
        }
    }

    //validate mat khau: bat buoc phai nhap , >= 8 ký tự
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

    // echo '<pre>';
    // print_r($errors);
    // echo '</pre>';

    //kiem tra mang errors
    if (empty($errors)){
        //khong co loi
        // setFalshData('msg', 'Đăng ký thành công');
        // setFalshData('msg_type', 'success');
        // redirect('?module=auth&action=register'); 
        $activeToken=sha1(uniqid().time());
        $dataInsert=[
            'fullname'=>$body['fullname'],
            'email'=>$body['email'],
            'phone'=>$body['phone'],
            'password'=>password_hash($body['password'], PASSWORD_DEFAULT),
            'activeToken'=>$activeToken,
            'createAt'=> date("Y.m.d H:i:s"),
        ];
        $statusInsert=insert('users', $dataInsert);
        if ($statusInsert){
            //tao link
            $linkActive=_WEB_HOST_ROOT.'?module=auth&action=active&token='.$activeToken;
            $subject='Vui lòng kích hoạt tài khoản của '.$body['fullname'].'';
            $content='Chào bạn '.$body['fullname'].'<br/>';
            $content.='Vui lòng click vào link: '. $linkActive;
            $statusEmail=sendMail($body['email'], $subject, $content );
            if ($statusEmail){
                setFalshData('msg', 'Đăng ký thành công, vui lòng kiểm tra email');
                setFalshData('msg_type', 'success');
            }else{
                setFalshData('msg', 'Hệ thống gặp sự cố ,vui lòng thử lại sau');
                setFalshData('msg_type', 'danger');
            }
        }else{
            setFalshData('msg', 'Hệ thống gặp sự cố ,vui lòng thử lại sau');
            setFalshData('msg_type', 'danger');
        }

        redirect('?module=auth&action=register'); 
        
    }else{
        //co loi xay ra
        setFalshData('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
        setFalshData('msg_type', 'danger');
        setFalshData('errors', $errors);
        setFalshData('old', $body);
        redirect('?module=auth&action=register'); //load lai trang dang ky
    }
}
$msg=getFalshData('msg');
$msgType=getFalshData('msg_type');
$errors=getFalshData('errors');
$old=getFalshData('old');

?>
<div class="row">
    <div class="col-6" style="margin: 22px auto;">
        <h3 class="text-center text-uppercase"> Đăng ký tài khoản </h3>
        
        <?php
            getMsg($msg, $msgType);
        ?>
        <form action="" method="post">
            <div class="form-group">
                <label for="">Họ và tên</label>
                <input type="text" name="fullname" id="" class="form-control" value="<?php echo oldData($old, 'fullname');?>" placeholder="Họ và tên...">
                <?php echo from_error('fullname', $errors, '<span class="error">', '</span>');  ?>
                
            </div>

            <div class="form-group">
                <label for="">Điện thoại</label>
                <input type="text" name="phone" id="" class="form-control" value="<?php echo oldData($old, 'phone');?>" placeholder="Địa thoại...">
                <?php echo from_error('phone', $errors, '<span class="error">', '</span>');  ?>
            </div>

            <div class="form-group">
                <label for="">Email</label>
                <input type="text" name="email" id="" class="form-control" value="<?php echo oldData($old, 'email');?>" placeholder="Địa chỉ email...">
                <?php echo from_error('email', $errors, '<span class="error">', '</span>');  ?>
            </div>

            <div class="form-group">
                <label for="">Mật khẩu</label>
                <input type="password" name="password" id="" class="form-control" placeholder="Mật khẩu...">
                <?php echo from_error('password', $errors, '<span class="error">', '</span>'); ?>
            </div>

            <div class="form-group">
                <label for="">Nhập lại mật khẩu</label>
                <input type="password" name="confirm_password" id="" class="form-control" placeholder="Nhập lại mật khẩu...">
                <?php echo from_error('confirm_password', $errors, '<span class="error">', '</span>');  ?>
            </div>


            <button type="submit" class="btn btn-primary btn-block">Đăng ký</button>
            <hr>

            <p class="text-center"><a href="?module=auth&action=login">Đăng nhập hệ thống</a></p>
        </form>
    </div>
</div>
<?php
layout('footer-login');
