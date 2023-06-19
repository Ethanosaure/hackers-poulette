<?php
require 'access.php';





if(isset($_POST['button'])){
    $_POST['name']=$name;
    $_POST['firstname']=$firstname;
    $_POST['email']=$email;
    $_POST['description']=$description;

    $req = 'INSERT INTO support (name, firstname, email, description) VALUES (?,?,?,?)';
$query = $bdd->prepare([$name, $firstname, $email, $desciption]);
$query->execute();
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