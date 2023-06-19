<?php
require 'access.php';





if(isset($_POST['button'])){
    $_POST['name']=$name;
    $_POST['firstname']=$firstname;
    $_POST['email']=$email;
    $_POST['description']=$description;
    $sanitized_name=filter_var($name, FILTER_SANITIZE_NAME);
    $sanitized_firstname=filter_var($firstname, FILTER_SANITIZE_FIRSTNAME);
    $sanitized_email=filter_var($email, FILTER_SANITIZE_EMAIL);
    $sanitized_descripion=filter_var($descripion, FILTER_SANITIZE_DESCRIPTION);
    if(filter_var($sanitized_name, FILTER_VALIDATE_NAME) 
    && filter_var($sanitized_firstname, FILTER_VALIDATE_FIRSTNAME) 
    && filter_var($sanitized_email, FILTER_VALIDATE_EMAIL)
    && filter_var($sanitized_descripion, FILTER_VALIDATE_DESCRIPTION)){
    $req = 'INSERT INTO support (name, firstname, email, description) VALUES (?,?,?,?)';
    $query = $bdd->prepare([$sanitized_name, $sanitized_firstname, $sanitized_email, $sanitized_descripion]);
    $query->execute();

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