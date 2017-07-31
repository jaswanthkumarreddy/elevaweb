<?php
require 'PHPMailerAutoload.php';

$mail = new PHPMailer(); // create a new object
$mail->IsSMTP(); // enable SMTP
$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
$mail->SMTPAuth = true; // authentication enabled
//$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
$mail->Host = "mail.youmewebs.com";
$mail->Port = 587; // or 587
$mail->IsHTML(true);
$mail->Username = "elevaweb@youmewebs.com";
$mail->Password = "Pratik@123";
$mail->SetFrom("elevaweb@youmewebs.com");
$mail->Subject = "Test";
$mail->Body = "hello";
$mail->AddAddress("pratiknyk@gmail.com");

 if(!$mail->Send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
 } else {
    echo "Message has been sent";
 }
?>