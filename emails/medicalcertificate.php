<?php
require '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$doctorName = "Dr. John Doe"; // Replace with the assigned doctor's name
$certificateLink = "medical-certificate"; // Replace with the actual certificate link

$emailContent = '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Certificate Request Confirmation</title>
        <style>
        body,
        html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: Arial, sans-serif;
        }

        .background {
            background-color: #9b2942;
            height: 50%;
            position: relative;
            overflow: hidden;
            color: #FFFFFF;
        }

        .diagonal {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 0;
            height: 0;
            border-style: solid;
            border-width: 0 0 40vh 100vw;
            border-color: transparent transparent #FFFFFF transparent;
        }

        .icon-container {
            position: absolute;
            top: 60%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            border-radius: 50%;
            padding: 10px;
            box-shadow: 0 3px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .icon {
            width: 100px;
            height: 100px;
            background-color: #FFFFFF;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto;
        }

        .icon img {
            width: 70px;
            max-width: 100%;
            height: auto;
        }

        .email-container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            background-color: #ffffff;
            /* padding: 20px; */
            /* box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); */
        }

        .header {
            background-color: #0a143f;
            color: #ffffff;
            display: flex;
            align-items: center;
            padding: 20px;
            border-radius: 8px 8px 0 0;
        }

        h1 {
            margin: 0;
            font-size: 24px;
            text-align: center;
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
            background-color: #dc3545;
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

        /* Responsive styles */
        @media screen and (max-width: 768px) {
            .background {
                height: 40%;
            }

            .diagonal {
                border-width: 0 0 30vh 100vw;
            }

            .icon-container {
                top: 65%;
            }

            .icon {
                width: 80px;
                height: 80px;
            }

            .icon img {
                width: 55px;
            }
        }

        @media screen and (max-width: 480px) {
            .background {
                height: 25%;
            }

            .diagonal {
                border-width: 0 0 20vh 100vw;
            }

            .icon-container {
                top: 65%;
            }

            .icon {
                width: 60px;
                height: 60px;
            }

            .icon img {
                width: 45px;
            }

            .email-container {
                padding: 10px;
            }

            .header {
                flex-direction: column;
                align-items: flex-start;
            }

            h1 {
                font-size: 20px;
            }

            .content h2 {
                font-size: 18px;
            }

            .content p,
            .button {
                font-size: 14px;
            }

            .button {
                padding: 10px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="background">
        <div class="diagonal"></div>
        <div class="icon-container">
            <div class="icon">
                <img src="https://www.doctorhelp.com.au/wp-content/uploads/2023/08/cropped-Favicon-Dr-Help.png" alt="Doctor Help Icon" srcset="">
            </div>
        </div>
    </div>
    <div class="email-container">
        <h1>Medical Certificate Request</h1>
        <div class="content">
            <h2>Hello ' . $name . ',</h2>
            <p>
                Thank you for submitting your request for a medical certificate. Your request has been successfully received.
            </p>
            <p>
                Here\'s the login credentials for your portal.
            </p>
            <p><strong>Username: </strong> [ Your email address will be your username ]</p>
            <p><strong>Password: </strong> ' . $password . '</p>';

if ($doctorAssigned) {
    $emailContent .= '
            <h2>Certificate Details</h2>
            <p>
                <strong>Doctor:</strong> ' . $doctorName . '<br>
                Your medical certificate has been approved. You can access your certificate by clicking the link below:
            </p>
            <a href="' . $certificateLink . '" class="button">View Medical Certificate</a>';
} else {
    $emailContent .= '
            <h2>Certificate Details</h2>
            <p>
                We are currently working to assign a doctor to approve your medical certificate. You will receive a follow-up email with a link to access your certificate once it has been approved.
            </p>';
}

$emailContent .= '
<div class="footer">
    <p>If you have any questions or need assistance with your medical certificate, please contact our support team at <a href="mailto:info@doctorhelp.com.au">info@doctorhelp.com.au</a>.</p>
    <p>&copy; 2024 Doctor Help. All rights reserved.</p>
</div>
    </div>
</body>
</html>';

$mail = new PHPMailer(true);
try {
    //Server settings
    $mail->isSMTP();
    $mail->Host = 'gsydm1073.siteground.biz';               // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                                 // Enable SMTP authentication
    $mail->Username = 'web@doctorhelp.com.au';              // SMTP username
    $mail->Password = 'r?WScdbd6()fi';                        // SMTP password
    // $mail->Password = '1g2116~5n#$`';                       // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;        //Enable implicit TLS encryption
    $mail->Port = 465;                                      // TCP port to connect to

    //Recipients
    $mail->setFrom('web@doctorhelp.com.au', 'Welcom to DoctorHelp');
    $mail->addAddress($email);
    $mail->addBCC('info@doctorhelp.com.au');

    //Content
    $mail->isHTML(true);
    $mail->Subject = 'Medical Certificate Confirmation';
    $mail->Body = $emailContent;

    $mail->send();
    header('Location: ../emails/email_sent.html');
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>