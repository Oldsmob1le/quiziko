<?php
    try{
        $conn = new PDO ("mysql:host=localhost;dbname=REG",'root','');
    }catch(PDOException $e){
        echo $e;
    }
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>