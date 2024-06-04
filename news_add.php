<?php
include ('includes/connect.php');
include ('includes/session.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_news'])) {
    $title = $_POST['title'];
    $descr = $_POST['descr'];
    $category_id = $_POST['category_id'];
    $user_id = $_POST['user_id'];

    $sql = "INSERT INTO news (title, descr, category_id, user_id) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$title, $descr, $category_id, $user_id]);

    header("Location: news.php");
    exit();
}
?>