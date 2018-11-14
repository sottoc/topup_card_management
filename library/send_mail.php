<?php
    require_once('CSendMail.php');
    $email = $_POST['email'];
    $body = "Please reset password  <a href='http://butterflyportals.com:8090/topup_card_management' target='_blank'> Reset Password </a>";
    $mail_class = new CSendMail($email, $body);
    $mail_class->sendMailPassword();
?>