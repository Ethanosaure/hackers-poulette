<?php
require 'access.php';





if(isset($_POST['button'])){
    $name=$_POST['name'];
    $firstname=$_POST['firstname'];
    $email=$_POST['email'];
    $description=$_POST['description'];

    $sanitized_name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
    $sanitized_firstname = htmlspecialchars($firstname, ENT_QUOTES, 'UTF-8');
    $sanitized_email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $sanitized_description = htmlspecialchars($description, ENT_QUOTES, 'UTF-8');

    $check = true;
    if ($sanitized_name !== $name ){
        $check = false;
        echo 'remove the special characters from your "name" input'."<br>";
    }
    if (empty($sanitized_name)){
        $check = false;
        echo 'the "name" input should not be empty'."<br>";
    }


     if ($sanitized_firstname !== $firstname ){
        $check = false;
        echo 'remove the special characters from your "firstname" input'."<br>";
    }
    if (empty($sanitized_firstname)){
        $check = false;
        echo 'the "firstname" input should not be empty'."<br>";
    }


     if ($sanitized_description !== $description ){
        $check = false;
        echo 'remove the special characters from your "description" input'."<br>" ;
    }
    if (empty($sanitized_description)){
        $check = false;
        echo 'the "description" input should not be empty'."<br>" ;
    


    if (!filter_var($sanitized_email, FILTER_VALIDATE_EMAIL)) {
        $check = false;
        echo 'email invalid'."<br>";
    }

    if ($check){
        // $req = 'INSERT INTO support (name, firstname, email, description) VALUES (?,?,?,?)';
        // $query = $bdd->prepare($req);
        // $query->bindParam(1, $sanitized_name);
        // $query->bindParam(2, $sanitized_firstname);
        // $query->bindParam(3, $sanitized_email);
        // $query->bindParam(4, $sanitized_description);
        // $query->execute();
        echo 'envoyé avec succes';
        } else {
            echo 'error';
        }
}
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Support</title>
</head>
<body>
    <form method='POST'>
        <label for='name'>name</label>
        <input type='text' name='name' value='' placeholder="name"> <br>
        <label for='firstname'>firstname</label>
        <input type='text' name='firstname' value='' placeholder='firstname'><br>
        <label for='email'>email</label>
        <input type ='email' name='email' value='' placeholder='email'><br>
        <label for='description'>description</label>
        <input type='text' name='description' value='' placeholder='explain your problem'><br>
        <button type='submit' name='button'>Send</button>
    </form>
    
</body>
</html>