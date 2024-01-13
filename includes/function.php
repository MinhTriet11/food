<?php
if (!defined('_INCODE')) die('Access Deined...');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
function layout ($layoutName='header', $data=[]){
    
    if (file_exists(_WEB_PATH_TEMPLATE.'/layout/'.$layoutName.'.php')){
        require_once _WEB_PATH_TEMPLATE.'/layout/'.$layoutName.'.php';
    }
}

function sendMail ($to, $subject, $content) {
    //Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'lephamminhtriet.dmx2021@gmail.com';                     //SMTP username
    $mail->Password   = 'nmugnqatmwqzjkce';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('lephamminhtriet.dmx2021@gmail.com', 'Mailer');
    $mail->addAddress($to);     //Add a recipient
   // $mail->addReplyTo($to);
   // $mail->addCC('cc@example.com');
   // $mail->addBCC('bcc@example.com');

    //Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
   // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail ->CharSet = "UTF-8";
    $mail->Subject = $subject;
    $mail->Body    = $content;
    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );

    return $mail->send();
    
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
}

//kiem tra phuong thuc post
function isPost(){
    if ($_SERVER['REQUEST_METHOD']=='POST'){
        return true;
    }
    return false;
};

//kiem tra phuong thuc get
function isGet(){
    if ($_SERVER['REQUEST_METHOD']=='GET'){
        return true;
    }
    return false;
};

//lay gia tri phuong thuc post, get
function getBody(){
    $bodyArr=[];
    if(isGet()){
       //xu ly truoc khi hien thi ra
       //doc key cua mang $_GET
       if (!empty($_GET)){
        foreach ($_GET as $key=>$value){
            $key=strip_tags($key);
            if (is_array($value)){
                $bodyArr[$key]=filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
            }else{
                $bodyArr[$key]=filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
            
           }
       }
       
    }

    if (isPost()){
        //xu ly truoc khi hien thi ra
        //doc key cua mang $_POST
        if (!empty($_POST)){
         foreach ($_POST  as $key=>$value){
            $key=strip_tags($key);
            if (is_array($value)){
                $bodyArr[$key]=filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
            }else{
                $bodyArr[$key]=filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
            
           }
       }
    }
    return $bodyArr;
}

//kiem tra email
function isEmail($email){
    $check=filter_var($email, FILTER_VALIDATE_EMAIL);
    return $check;
}

function isNumberInt ($number, $range=[]){
    if (!empty($range)){
        $options=['options'=>$range];
        $checkNumber=filter_var($number, FILTER_VALIDATE_INT, $options);
    }else{
        $checkNumber=filter_var($number, FILTER_VALIDATE_INT);
    }

    return $checkNumber;
    

}

function isPhone($phone){
    $checkZero=false;
    if ($phone[0]==0){
        $checkZero=true;
        $phone = substr($phone, 1);
    }

    $checkNumberLast=false;
    if (isNumberInt($phone) && strlen($phone)==9){
        $checkNumberLast=true;
    }

    if ($checkNumberLast && $checkZero){
        return true;
    }

    return false;
}

//ham tao thong bao
function getMsg($msg, $type='success'){
    if (!empty($msg)){
    echo '<div class="alert alert-'.$type.'">';
    echo $msg;
    echo '</div>';
    }
}

//ham chuyen huong
function redirect($path='index.php'){
    header("location: $path");
    exit;
}

//ham thong bao loi
function from_error($fieldName, $errors, $befornHtml='', $afterHtml=''){
    return (!empty($errors[$fieldName])) ? $befornHtml.reset($errors[$fieldName]).$afterHtml:null; 
}

//ham giu du lieu cu
function oldData($old, $fieldName){
    return (!empty($old[$fieldName]))? $old[$fieldName] :null;
}

//kiem tra trang thai dang nhap 
function isLogin(){
    $checkLogin=false;
    if (getSession('logintoken')){
        $tokenLogin=getSession('logintoken');
        $queryToken=firstRaw("SELECT userId FROM logintoken WHERE token='$tokenLogin' ");
        if (!empty($queryToken)){
            $checkLogin=$queryToken;
        }else{
            removeSession('logintoken');
        }
    }
    return $checkLogin;
}

//tu dong xoa tokenlogin
function autoRemovetoken(){
    $allUsers=getRaw("SELECT * FROM users WHERE status=1");
    if (!empty($allUsers)){
        foreach($allUsers as $user){
            $now= date('Y-m-d H:i:s');
    
            $before= $user['lastActivity'];

            $diff= strtotime($now)-strtotime($before);
            $diff= floor($diff/60);
            
            if ($diff>=1){
                delete('logintoken', "userId=".$user['id']);
            }
        }
    }  
}

// luu lai thoi gian cuoi cung hoat dong

function saveActivity(){
    $userId=isLogin()['userId'];
    
    update('users', ['lastActivity' => date('Y-m-d H:i:s')], "id=$userId" );
}

//lay thong tin user
function getUserInfo($userId){
    $info = firstRaw("SELECT * FROM users WHERE id='$userId'");
    return $info;
}
