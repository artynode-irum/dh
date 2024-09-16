<?php
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
function sendVerificationEmail($title, $firstname, $lastname, $email, $token)
{
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = 'mail.trixum.net';                    // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                                 // Enable SMTP authentication
        $mail->Username = 'tester@trixum.net';                  // SMTP username
        $mail->Password = 'F0RyCluPkk}q';                       // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;          //Enable implicit TLS encryption
        $mail->Port = 465;                                  // TCP port to connect to

        //Recipients
        $mail->setFrom('tester@trixum.net', 'Welcome to DoctorHelp');
        $mail->addAddress($email);

        //Content
        $mail->isHTML(true);
        $mail->Subject = 'Verify Your Email';
        $mail->Body = '<!DOCTYPE html>
                        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Welcome to Doctor Help</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 0;
                    background-color: #f4f4f4;
                }
                .email-container {
                    width: 100%;
                    max-width: 600px;
                    margin: 0 auto;
                    background-color: #ffffff;
                    padding: 20px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }
                .header {
                    background-color: #c3263f;
                    color: #ffffff;
                    text-align: center;
                    padding: 20px;
                    border-radius: 8px 8px 0 0;
                }
                .header h1 {
                    margin: 0;
                    font-size: 24px;
                }
                .content {
                    padding: 20px;
                    text-align: left;
                }
                .content h2 {
                    color: #333333;
                    font-size: 20px;
                    margin-top: 0;
                }
                .content p {
                    color: #666666;
                    font-size: 16px;
                    line-height: 1.5;
                }
                .button {
                    display: inline-block;
                    margin-top: 20px;
                    padding: 15px 25px;
                    background-color: #c3263f;
                    color: #ffffff;
                    text-decoration: none;
                    border-radius: 4px;
                    font-size: 16px;
                }
                .footer {
                    text-align: center;
                    color: #999999;
                    font-size: 14px;
                    padding: 20px;
                    border-top: 1px solid #eeeeee;
                    margin-top: 20px;
                }
                .footer p {
                    margin: 0;
                }
                @media only screen and (max-width: 600px) {
                    .email-container {
                        padding: 10px;
                    }
                    .header h1 {
                        font-size: 20px;
                    }
                    .content h2 {
                        font-size: 18px;
                    }
                    .content p, .button {
                        font-size: 14px;
                    }
                    .button {
                        padding: 10px 20px;
                    }
                }
            </style>
        </head>
        <body>
            <div class="email-container">
                <div class="header">
                    <h1>Welcome to Doctor Help</h1>
                </div>
                <div class="content">
                    <h2>Hello '  . $lastname . $firstname . '</h2>
                    <p>
                        Thank you for signing up for Doctor Help. We\'re excited to have you on board! Our platform is designed to make it easier for you to connect with medical professionals and get the help you need.
                    </p>
                    <p>
                        Please confirm your email address by clicking the button below. Once confirmed, you\'ll be able to access all the features we offer, including booking appointments, accessing your medical records, and more.
                    </p>
                 
                    <a href="http://lcoalhost/dh/email_verify.php?token="' . $token . 'class="button">Confirm Email</a>
                </div>
                <div class="footer">
                    <p>If you didn\'t sign up for this account, please ignore this email.</p>
                    <p>&copy; 2024 Doctor Help. All rights reserved.</p>
                </div>
            </div>
        </body>
    </html>';
        // "Click the link to verify your email: <a href='https://multiplepromosolutions.com/dh/email_verify.php?token=$token'>Verify Email</a>";
        // $mail->Body = "Click the link to verify your email: <a href='http://localhost/dh/email_verify.php?token=$token'>Verify Email</a>";

        $mail->send();
        echo 'Verification email sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

sendVerificationEmail($title, $firstname, $lastname, $email, $token);

?>