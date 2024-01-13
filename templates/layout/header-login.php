<?php
if (!defined('_INCODE')) die('Access Deined...');
autoRemovetoken();
?>
<html>
<head>
    <title><?php echo !empty($data['pageTitle']) ? $data['pageTitle']:'Unicode'; ?></title>
    <meta charset="utf-8"/>
    <link type="text/css" rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATE; ?>/css/bootstrap.min.css"/>
    <link type="text/css" rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATE; ?>/css/font-awesome.min.css"/>
    <link type="text/css" rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATE; ?>/css/style.css?ver=<?php echo rand(); ?>"/>
</head>
<body>
<?php
