<?php
require 'config.php';
function sendmail(){
try {
                // SMTP configuration
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = $mailUsername;
                $mail->Password = $mailPassword;
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                // Sender and recipient
                $mail->setFrom($mailUsername, 'Support');
                $mail->addAddress($email, $name);

                // Email content
                $mail->Subject = 'Support';
                $mail->Body = "It's finally working";

                // Send the email
                $mail->send();

            } catch (Exception $e) {
                echo 'Email could not be sent. Error: ', $mail->ErrorInfo;
            }

         catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }
?>