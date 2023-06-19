<?php
require 'access.php';





if(isset($_POST['button'])){
    $name=$_POST['name'];
    $firstname=$_POST['firstname'];
    $email=$_POST['email'];
    $description=$_POST['description'];
    $honey = $_POST['honey'];

    $sanitized_name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
    $sanitized_firstname = htmlspecialchars($firstname, ENT_QUOTES, 'UTF-8');
    $sanitized_email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $sanitized_description = str_replace(['<', '>', '$', '#' , '[', ']', '}', '{'], '', $description);

    
    $check = true;
    if ($honey) {
    echo 'bien tenté le spam gros nullos';
    exit();
    }else {
     if ($sanitized_firstname !== $firstname ){
        $check = false;
    }
    if (empty($sanitized_firstname)){
        $check = false;
    }
     if (isset($_POST['button'])){
        if ($sanitized_name !== $name ){
        $check = false;
    }
    if (empty($sanitized_name)){
        $check = false;
    }
    if (!filter_var($sanitized_email, FILTER_VALIDATE_EMAIL)) {
        $check = false;
    }
    if ($sanitized_description !== $description ){
        $check = false;
       
    }
    if (empty($sanitized_description)){
        $check = false;
      
    }
   

    if ($check){
        $req = 'INSERT INTO support (name, firstname, email, description) VALUES (?,?,?,?)';
        $query = $bdd->prepare($req);
        $query->bindParam(1, $sanitized_name);
        $query->bindParam(2, $sanitized_firstname);
        $query->bindParam(3, $sanitized_email);
        $query->bindParam(4, $sanitized_description);
        $query->execute();
        echo 'envoyé avec succes';
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
    <link rel="stylesheet" href="style.css">
    <title>Contact Support</title>
</head>
<body>
    <form method='POST'>
        <label for='name'>name:</label>
        <input type='text' name='name' value='' placeholder="name"> <br>
         <?php
        if (isset($_POST['button'])){
        if ($sanitized_name !== $name ){
        $check = false;
        echo 'remove the special characters from your "firstname" input'."<br>";
        }
        if (empty($sanitized_name)){
        $check = false;
        echo 'the "name" input should not be empty'."<br>";
         }
        }
        ?>
        <label for='firstname'>firstname:</label>
        <input type='text' name='firstname' value='' placeholder='firstname'><br>
        <?php
        if (isset($_POST['button'])){
          if ($sanitized_firstname !== $firstname ){
        $check = false;
        echo 'remove the special characters from your "firstname" input'."<br>";
        }
        if (empty($sanitized_firstname)){
        $check = false;
        echo 'the "firstname" input should not be empty'."<br>";
            }
    }
            ?>
        <label for='email'>email:</label>
        <input type ='email' name='email' value='' placeholder='email'><br>
        <?php
        if (isset($_POST['button'])){
    if (!filter_var($sanitized_email, FILTER_VALIDATE_EMAIL)) {
        $check = false;
        echo 'email invalid'."<br>";
        }
        }
    ?>
        <label for='description'>description:</label>
        <input type='text' name='description' value='' placeholder='max 1000 char.'><br>
        <?php
        if (isset($_POST['button'])){
    if ($sanitized_description !== $description ){
        $check = false;
        echo 'remove the special characters from your "description" input'."<br>" ;
    }
    if (empty($sanitized_description)){
        $check = false;
        echo 'the "description" input should not be empty'."<br>" ;
    }
    }
    ?>
    <input type="text" name='honey' class="honey" value=''><br>
        <button type='submit' name='button'>Send</button>
    </form>
    
</body>
</html>