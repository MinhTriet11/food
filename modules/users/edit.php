<?php
if (!defined('_INCODE')) die('Access Deined...');
//file nay dung de sua nguoi dung

$data=[
    'pageTitle'=>'Sửa người dùng'
];
layout('header', $data );
//lay du lieu cu
$body=getBody();
if (!empty($body['id'])){
    $userId=$body['id'];

    //kiem tra userid co ton tai trong data hay khong
    //neu ton tai lay thong tin nguoc lai chuyen huong ve trang list
    $userDetail=firstRaw("SELECT * FROM users WHERE id=$userId");
    if (!empty($userDetail)){
        //gan gia tri userDetail vao flashdata
        setFalshData('userDatail', $userDetail);
    }else{
        redirect('?module=users');
    }
}else{
    redirect('?module=users');
}

//xu ly sửa nguoi dung
if (isPost()){
    $body=getBody();
    $errors=[];
    //name
    if (empty(trim($body['fullname']))){
        $errors['fullname']['required']='Họ tên không được để trống';
    }else{
        if (strlen(trim($body['fullname']))<5){
            $errors['fullname']['min']='Họ tên phải trên 5 ký tự';
        }
    }

    //phone
    if (empty(trim($body['phone']))){
        $errors['phone']['required']='Số điện thoại không được để trống';
    }else{
        if (trim(!isPhone($body['phone']))){
            $errors['phone']['isPhone']='Số điện thoại không hợp lệ';
        }
    }

    //email
    if (empty(trim($body['email']))){
        $errors['email']['required']='Email không được để trống';
    }else{
        if (!isEmail(trim($body['email']))){
            $errors['email']['isEmail']='Email không hợp lệ';
        }else{
            //kiem tra email co ton tai ko
            $email=trim($body['email']);
            $sql="SELECT id FROM users WHERE email='$email' AND id<>$userId";
            if (getRows($sql)>0){
                $errors['email']['unique']='Địa chỉ email đã tồn tại';
            }
        }
    }
    if (!empty(trim($body['password']))){
    //chi validate neu password dc nhap
    //confirm
    if (empty(trim($body['confirm-password']))){
        $errors['confirm-password']['required']='Nhập lại mật khẩu không được để trống';
    }else{
        if(trim($body['password'])!=trim($body['confirm-password'])){
            $errors['confirm-password']['confirm']='Mật khẩu không đúng';
        }
    }
}

    //kiem tra mang errors
    if (empty($errors)){
        $dataUpdate=[
            'fullname'=>$body['fullname'],
            'email'=>$body['email'],
            'phone'=>$body['phone'],
            'status'=>$body['status'],
            'updateAt'=> date("Y.m.d H:i:s"),
        ];
        if (!empty(trim($body['password']))){
            $dataUpdate['password']=password_hash($body['password'], PASSWORD_DEFAULT);
        }
        $condition="id=$userId";
        $updateStatus=update('users', $dataUpdate, $condition );
        if ($updateStatus){
                setFalshData('msg', 'Sửa người dùng thành công');
                setFalshData('msg_type', 'success');
                
            }else{
                setFalshData('msg', 'Hệ thống gặp sự cố ,vui lòng thử lại sau');
                setFalshData('msg_type', 'danger');
                
            }

    }else{
        //co loi xay ra
        setFalshData('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
        setFalshData('msg_type', 'danger');
        setFalshData('errors', $errors);
        setFalshData('old', $body);
        
    }
    redirect('?module=users&action=edit&id='.$userId);
}
$msg = getFalshData('msg');
$msgType = getFalshData('msg_type');
$errors = getFalshData('errors');
$old = getFalshData('old');
$userDetail=getFalshData('userDatail');
if (!empty($userDetail)){
    $old=$userDetail;
}

?>
<div class="container">
    <hr/>
    <h3><?php echo $data['pageTitle']; ?></h3>
        <?php
                getMsg($msg, $msgType);
        ?>
    <form action="" method="post">
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="">Họ tên</label>
                    <input type="text" name="fullname" class="form-control" id="" value="<?php echo oldData($old,'fullname'); ?>" placeholder="Họ tên..."> 
                    <?php echo from_error('fullname', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for="">Số điện thoại</label>
                    <input type="text" name="phone" class="form-control" id=""  value="<?php echo oldData($old,'phone'); ?>" placeholder="Điện thoại..."> 
                    <?php echo from_error('phone', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for="">Email</label>
                    <input type="text" name="email" class="form-control" id=""  value="<?php echo oldData($old,'email'); ?>" placeholder="Email..."> 
                    <?php echo from_error('email', $errors, '<span class="error">', '</span>'); ?>
                </div>

            </div>

            <div class="col">
                <div class="form-group">
                    <label for="">Mật khẩu</label>
                    <input type="password" name="password" class="form-control" id="" placeholder="Mật khẩu (không nhập nếu không thay đổi)..."> 
                    <?php echo from_error('password', $errors, '<span class="error">', '</span>'); ?>
                </div>

             <div class="form-group">
                    <label for="">Nhập lại mật khẩu</label>
                    <input type="password" name="confirm-password" class="form-control" id="" placeholder="Nhập lại mật khẩu (không nhập nếu không thay đổi)..."> 
                    <?php echo from_error('confirm-password', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                     <label for="">Trạng thái</label>
                    <select name="status" id="" class="form-control">
                            <option value="0" <?php echo (oldData($old, 'status')==0)?'selected':false; ?>>Chưa kích hoạt</option>
                            <option value="1" <?php echo (oldData($old, 'status')==1)?'selected':false; ?>>Kích hoạt</option>
                    </select>
                </div>
            </div>
        </div>
        <hr/>
        <button type="submit" class="btn btn-primary">Cập nhật người dùng</button>
        <a href="?module=users" class="btn btn-success">Quay lại</a>
        <input type="hidden" name="id" value="<?php echo $userId; ?>">
    </form>
</div>
<?php 
layout('footer');