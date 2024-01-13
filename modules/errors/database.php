<?php if (!defined('_INCODE')) die('Access Deined...'); ?>
<div class="" style="width: 600px; padding: 20px 30px; text-align:center; margin: 0 auto;">
    <h2 style="text-transform: uppercase;">Lỗi liên quan đến CSDL</h2>
    <hr>
    <p> <?php echo $exception->getMessage();  ?></p>
    <p>File: <?php echo $exception->getFile(); ?> </p>
    <p>Line: <?php echo $exception->getLine(); ?> </p>
</div>