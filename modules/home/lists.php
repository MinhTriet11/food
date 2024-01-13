<?php
if (!defined('_INCODE')) die('Access Deined...');
$data=[
    'pageTitle'=>'Hệ thống quản trị'
];
layout('header-login', $data );

?>
<html>

    <head>
        
    </head>
    <body>
<div class="container" style="display: flex; flex-direction:column ; justify-content: center; height:100%">
    <h1 class="text-center">HỆ THỐNG QUẢN TRỊ</h1>
    <p class="text-center"><a href="?module=auth&action=login" class="btn btn-success btn-lg">Vào hệ thống</a></p>
</div>
<script type="text/javascript" src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script tyle="text/javascript" src="<?php echo _WEB_HOST_TEMPLATE; ?>/js/bootstrap.min.js"></script>
<script tyle="text/javascript" src="<?php echo _WEB_HOST_TEMPLATE; ?>/js/custom.js"></script>
</body>
</html>
    </body>
</html>
