<?php
use PHPMailer\PHPMailer\PHPMailer;
require 'vendor/autoload.php';

$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'uthmhealthcentre@gmail.com';
$mail->Password = 'tjpx qyev tyvi ydnt';
$mail->SMTPSecure = 'tls';
$mail->Port = 587;
$mail->setFrom('uthmhealthcentre@gmail.com');
$mail->addAddress('ai230087@student.uthm.edu.my');
$mail->Subject = 'Test';
$mail->Body = 'Test email from PKU UTHM';
$mail->send();
echo 'Email sent!';
?>