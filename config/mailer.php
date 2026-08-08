<?php
// config/mailer.php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Require the core files manually
require_once __DIR__ . '/../vendor/PHPMailer/Exception.php';
require_once __DIR__ . '/../vendor/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../vendor/PHPMailer/SMTP.php';

function sendOTP($recipientEmail, $otpCode) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();                                            
        $mail->Host       = 'smtp.gmail.com';                     
        $mail->SMTPAuth   = true;                                   
        $mail->Username   = $_ENV['SMTP_USER'];                     
        $mail->Password   = $_ENV['SMTP_PASS'];                               
        $mail->SMTPSecure = 'tls';            
        $mail->Port       = 587;                                    

        $mail->setFrom($_ENV['SMTP_USER'], 'Study Buddy Admin');
        $mail->addAddress($recipientEmail);     

        $mail->isHTML(true);                                  
        $mail->Subject = 'Your Study Buddy Security Code';
        $mail->Body    = 'Your secure verification code is: <b>' . $otpCode . '</b>';

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}
?>