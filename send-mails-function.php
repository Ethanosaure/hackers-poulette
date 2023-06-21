<?php
function sendmail(){
/**
 * This example shows sending a message using PHP's mail() function.
 */

//Import the PHPMailer class into the global namespace
use PHPMailer\PHPMailer\PHPMailer;

require './vendor/autoload.php';
require 'index.php';

//Create a new PHPMailer instance
$mail = new PHPMailer();
//Set who the message is to be sent from
$mail->setFrom('diasmarquesethan@gmail.com', 'First Last');
//Set who the message is to be sent to
$mail->addAddress($_POST['email'], $_POST['firstname']);
//Set the subject line
$mail->Subject = 'PHPMailer mail() test';
//Replace the plain text body with one created manually
$mail->AltBody = 'a supercool message';

//send the message, check for errors
if (!$mail->send()) {
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message sent!';
}
}