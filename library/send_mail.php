<?php
    require_once('topup_config.php');
    require_once('CSendMail.php');
    $email = $_POST['email'];
    $body = "Please reset password  <a href='".$rootpath."/password_reset.php?email=".$email."' target='_blank'> Reset Password </a>";
    $mail_class = new CSendMail($email, $body);
    $mail_class->sendMailPassword();
?>