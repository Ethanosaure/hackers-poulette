<?php
require 'access.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';
require 'vendor/phpmailer/phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

if (isset($_POST['button'])) {
    $name = $_POST['name'];
    $firstname = $_POST['firstname'];
    $email = $_POST['email'];
    $description = $_POST['description'];
    $honey = $_POST['honey'];
    $file = $_FILES['file'];

    if (!empty($_FILES['file']['tmp_name'])) {
        $file_info = @getimagesize($_FILES['file']['tmp_name']);
        $width = $file_info[0];
        $height = $file_info[1];
        $file_allowed_image = array('png', 'jpg', 'gif');
        $file_extension = pathinfo($_FILES["file"]['name'], PATHINFO_EXTENSION);
        $target_dir = './images';
        $filename = basename($_FILES['file']['name']);
        $target_path = $target_dir . $filename;

        // check if valid extension file
        if (!in_array($file_extension, $file_allowed_image)) {
            $response = array(
                'type' => 'error',
                'message' => 'Upload valid images, only PNG, JPG, and GIF allowed.'
            );
        }
        // check if valid image size
        else if ($_FILES['file']['size'] > 2097152) {
            $response = array(
                'type' => 'error',
                'message' => 'Image size should be at max 2MB.'
            );
        }
        // check image dimensions
        else if ($width > 300 || $height > 200) {
            $response = array(
                'type' => 'error',
                'message' => 'Image dimensions should be within 300x200.'
            );
        } 
        move_uploaded_file($_FILES['file']['tmp_name'], $target_path);
        }
    }

    $sanitized_file = htmlspecialchars($_FILES['file']['name'], ENT_QUOTES, 'UTF-8');
    $sanitized_name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
    $sanitized_firstname = htmlspecialchars($firstname, ENT_QUOTES, 'UTF-8');
    $sanitized_email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $sanitized_description = str_replace(
        ['<', '>', '$', '#', '[', ']', '}', '{'],
        '',
        $description
    );

    $check = true;
    if ($honey) {
        echo '<p>' . 'bien tenté le spam gros nullos'.'</p>';
        exit();
    } else {
        if ($sanitized_firstname !== $firstname) {
            $check = false;
        }
        if (empty($sanitized_firstname)) {
            $check = false;
        }
        if (isset($_POST['button'])) {
            if ($sanitized_name !== $name) {
                $check = false;
            }
            if (empty($sanitized_name)) {
                $check = false;
            }
            if (!filter_var($sanitized_email, FILTER_VALIDATE_EMAIL)) {
                $check = false;
            }
            if ($sanitized_description !== $description) {
                $check = false;
            }
            if (empty($sanitized_description)) {
                $check = false;
            }

            if ($check) {
                $req = 'INSERT INTO support (name, firstname, email, description, file) VALUES (?,?,?,?,?)';
                $query = $bdd->prepare($req);
                $query->bindParam(1, $sanitized_name);
                $query->bindParam(2, $sanitized_firstname);
                $query->bindParam(3, $sanitized_email);
                $query->bindParam(4, $sanitized_description);

                if (!empty($_FILES['file']['tmp_name'])) {
                    $query->bindParam(5, $sanitized_file);
                } else {
                    $sanitized_file = null;
                    $query->bindParam(5, $sanitized_file, PDO::PARAM_NULL);
                }

                $query->execute();
                echo '<span>' . 'envoyé avec succes'.'</span>' ;
                sendmail();
            } else {
                echo '<span>'.'error: please enter your information correctly'.'</span>' ;
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="api/src/css\style.css">
    <title>Contact Support</title>
</head>
<body>
    <form method='POST' enctype="multipart/form-data">
        <h1>Contact Support</h1>

        <label for='name' id='name'>name:</label>
        <input type='text' name='name' value='' placeholder="name" maxlength='255'> <br>
         <?php if (isset($_POST['button'])) {
             if ($sanitized_name !== $name) {
                 $check = false;
                 echo '<p>' . 'remove the special characters from your "name" input' .'</p>' .
                     "<br>";
             }
             if (empty($sanitized_name)) {
                 $check = false;
                 echo '<p>' . 'the "name" input should not be empty'.'</p>' . "<br>";
             }
         } ?>
        <label for='firstname'>firstname:</label>
        <input type='text' name='firstname' value='' placeholder='firstname' maxlength='255'><br>
        <?php if (isset($_POST['button'])) {
            if ($sanitized_firstname !== $firstname) {
                $check = false;
                echo '<p>'.'remove the special characters from your "firstname" input'.'</p>' .
                    "<br>";
            }
            if (empty($sanitized_firstname)) {
                $check = false;
                echo '<p>' . 'the "firstname" input should not be empty' .'</p>' . "<br>";
            }
        } ?>
        <label for='email'>email:</label>
        <input type ='email' name='email' value='' placeholder='email' maxlength='255'><br>
        <?php if (isset($_POST['button'])) {
            if (!filter_var($sanitized_email, FILTER_VALIDATE_EMAIL)) {
                $check = false;
                echo '<p>'.'email invalid'.'</p>' . "<br>";
            }
        } ?>
        <label for='description'>description:</label>
        <textarea name='description' value='' placeholder='max 1000 char.' class="description" maxlength='1000'></textarea><br>
        <?php if (isset($_POST['button'])) {
            if ($sanitized_description !== $description) {
                $check = false;
                echo '<p>'.'remove the special characters from your "description" input' .'</p>' .
                    "<br>";
            }
            if (empty($sanitized_description)) {
                $check = false;
                echo '<p>'.'the "description" input should not be empty'.'</p>' . "<br>";
            }
        } ?>
    <input type="File" name='file' maxlength="2097152" accept="image/jpg, image/png, image/gif">

    <input type="text" name='honey' class="honey" value=''>
        <input type='submit' name='button'>
        <?php
        ?>
    </form>
    
</body>
</html>