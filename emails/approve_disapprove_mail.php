<?php
require '../../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$medical_certificate_link = 'http://localhost/dh/medical_certificate_pdf.php?id=' . $medical_certificate_id;
// $medical_certificate_link = 'https://consult.doctorhelp.com.au/medical_certificate_pdf.php?id=' . $medical_certificate_id;


$emailContent = '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Certificate Request Status</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif;">

    <div style="background-color: #9b2942; padding: 20px; color: #FFFFFF; text-align: center;">
        <div style="width: 100px; height: 100px; background-color: #FFFFFF; border-radius: 50%; margin: 0 auto;">
            <img src="https://www.doctorhelp.com.au/wp-content/uploads/2023/08/cropped-Favicon-Dr-Help.png" alt="Doctor Help Icon" style="width: 70px; height: auto; display: block; margin: 0 auto;">
        </div>
    </div>

    <div style="max-width: 800px; margin: 20px auto; background-color: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
        <h1 style="font-size: 24px; text-align: center; color: #0a143f;">Medical Certificate Request Status</h1>
        <div style="padding: 20px;">
            <h2 style="color: #333333; font-size: 20px;">Hello ' . $patient_name . ',</h2>
            <p style="color: #666666; font-size: 16px; line-height: 1.5;">';

if ($status == 'approved') {
    $emailContent .= '
                We are pleased to inform you that your request for a medical certificate has been approved by your doctor. You can download your medical certificate from our website or directly by clicking the link below:
            </p>
            <a href="' . $medical_certificate_link . '" style="display: inline-block; margin-top: 20px; padding: 15px 25px; background-color: #9b2942; color: #ffffff; text-decoration: none; border-radius: 4px; font-size: 16px;">Download Medical Certificate</a>';
} else {
    $emailContent .= '
                Unfortunately, your request for a medical certificate has been disapproved by your doctor due to the following reason(s):
            </p>
            <p style="color: #dc3545; font-size: 16px; font-weight: bold; line-height: 1.5;">'.$disapproval_reason.'</p>';
}

$emailContent .= '
            <p style="color: #666666; font-size: 16px; line-height: 1.5;">
                If you have any questions or require further assistance, please do not hesitate to contact our support team.
            </p>
        </div>
        <div style="text-align: center; color: #999999; font-size: 14px; padding: 20px; border-top: 1px solid #eeeeee; margin-top: 20px;">
            <p>If you have any questions, please contact our support team at <a href="mailto:info@doctorhelp.com.au">info@doctorhelp.com.au</a>.</p>
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
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;        //Enable implicit TLS encryption
    $mail->Port = 465;                                      // TCP port to connect to

    //Recipients
    $mail->setFrom('web@doctorhelp.com.au', 'Welcom to DoctorHelp');
    $mail->addAddress($email);
    $mail->addBCC('info@doctorhelp.com.au');

    //Content
    $mail->isHTML(true);
    $mail->Subject = 'Your Medical Certificate Request Has Been ' . $status;
    $mail->Body = $emailContent;

    $mail->send();
    header('Location: ../../emails/email_sent.html');
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

?>