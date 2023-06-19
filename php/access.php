<?php
try{

    $bdd = new PDO('mysql:localhost;dbname=contact;charset=utf8', 'root', '');
}
catch(Exception $e)
{
    die('Erreur : ' .$e->getMessage());
}

?>