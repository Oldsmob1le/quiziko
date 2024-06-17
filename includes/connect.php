<?php
    try{
        $conn = new PDO ("mysql:host=localhost;dbname=reg",'root','');
    }catch(PDOException $e){
        echo $e;
    }
?>