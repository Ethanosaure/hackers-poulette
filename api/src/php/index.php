<?php
require 'access.php';

if (isset($_POST['button'])) {
    $name = $_POST['name'];
    $firstname = $_POST['firstname'];
    $email = $_POST['email'];
    $description = $_POST['description'];
    $honey = $_POST['honey'];
    $file = $_FILES['file'];
    

    if (!empty($_FILES['file']['tmp_name'])){
    $file_info = @getimagesize($_FILES['file']['tmp_name']);
    $width = $file_info[0];
    $height = $file_info[1];
    $file_allowed_image = array('png', 'jpg', 'gif');
    $file_extention = pathinfo($_FILES["file"]['name'], PATHINFO_EXTENSION);

    // check if valide extension file
    if(! in_array($file_extention, $file_allowed_image)){
        $response = array ('type' => 'error',
        "message" => "Upload valid images, Only PNG, JPG and GIF allowed.");
    // check if valide image size
    } else if (($_FILES['file']['size'] > 2097152)) {
        $response= array (
            "type" => 'error',
            "message" => 'Image size should be at max 2MB'
        );
        // check image dimensions
    } else if ($width > '300' || $height > "200") {
        $response = array(
            "type" => "error",
            "message" => "image dimensions should be within 300x200"
        );
    } else {
        // validation succes
        $target = "Desktop\Photos.Images" . basename($_FILES['file']["name"]);
        if (move_uploaded_file($_FILES['file']['tmp_name'], $target)) {
            $response = array (
                'type' => 'success',
                'message' => 'image uploaded succesfully.'
            );
            // uploading failed
        } else {
            $response = array (
                'type' => 'error',
                'message' => 'problem in uploading image file.'
            );
        }
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
    $to = $sanitized_email;
    $headers = 'From: the supercool site you come from';
    $email_subject = 'a supercool message';
    $email_body = "you just got a new message from the supercool site you come from";

    $check = true;
    if ($honey) {
        echo 'bien tenté le spam gros nullos';
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
                $req =
                    'INSERT INTO support (name, firstname, email, description, file) VALUES (?,?,?,?,?)';
                $query = $bdd->prepare($req);
                $query->bindParam(1, $sanitized_name);
                $query->bindParam(2, $sanitized_firstname);
                $query->bindParam(3, $sanitized_email);
                $query->bindParam(4, $sanitized_description);
                
                    if(!empty($_FILES['file']['tmp_name'])) {
                        $query->bindParam(5, $sanitized_file);
                    }else {
                        $sanitized_file = null;
                        $query->bindParam(5, $sanitized_file, PDO::PARAM_NULL);
                    }

                $query->execute();
                echo 'envoyé avec succes';
                mail($to, $email_subject, $email_body, $headers);
                echo 'check your mails!';
            } else {
                echo 'error: please enter your information correclty';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="..\css\style.css">
    <title>Contact Support</title>
</head>
<body>
    <form method='POST' enctype="multipart/form-data">
        <label for='name' id='name'>name:</label>
        <input type='text' name='name' value='' placeholder="name"> <br>
         <?php if (isset($_POST['button'])) {
             if ($sanitized_name !== $name) {
                 $check = false;
                 echo 'remove the special characters from your "name" input' .
                     "<br>";
             }
             if (empty($sanitized_name)) {
                 $check = false;
                 echo 'the "name" input should not be empty' . "<br>";
             }
         } ?>
        <label for='firstname'>firstname:</label>
        <input type='text' name='firstname' value='' placeholder='firstname'><br>
        <?php if (isset($_POST['button'])) {
            if ($sanitized_firstname !== $firstname) {
                $check = false;
                echo 'remove the special characters from your "firstname" input' .
                    "<br>";
            }
            if (empty($sanitized_firstname)) {
                $check = false;
                echo 'the "firstname" input should not be empty' . "<br>";
            }
        } ?>
        <label for='email'>email:</label>
        <input type ='email' name='email' value='' placeholder='email'><br>
        <?php if (isset($_POST['button'])) {
            if (!filter_var($sanitized_email, FILTER_VALIDATE_EMAIL)) {
                $check = false;
                echo 'email invalid' . "<br>";
            }
        } ?>
        <label for='description'>description:</label>
        <input type='text' name='description' value='' placeholder='max 1000 char.' class="description"><br>
        <?php if (isset($_POST['button'])) {
            if ($sanitized_description !== $description) {
                $check = false;
                echo 'remove the special characters from your "description" input' .
                    "<br>";
            }
            if (empty($sanitized_description)) {
                $check = false;
                echo 'the "description" input should not be empty' . "<br>";
            }
        } ?>
    <input type="File" name='file' maxlength="2097152" accept="image/jpg, image/png, image/gif">

    <input type="text" name='honey' class="honey" value=''>
        <button type='submit' name='button'>Send</button>
    </form>
    
</body>
</html>