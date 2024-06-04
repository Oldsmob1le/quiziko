<?php
include ('connect.php');

session_start();
if(isset($_SESSION['uid'])){
    $USER_ID = $_SESSION['uid'];
    $sql = "SELECT * FROM users WHERE id=:user_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['user_id' => $USER_ID]);
    $SIGNIN_USER = $stmt->fetch(PDO::FETCH_ASSOC);
}
if(isset($_GET['do'])){
    if($_GET['do'] == 'exit'){
        session_unset();
        echo '<script>document.location.href="index.php"</script>';
    }
}
?>
