<?php
include ('includes/connect.php');
include ('includes/session.php');

if (!isset($_GET['correct_count']) || !isset($_GET['total_count'])) {
    die('Ошибка: Недостаточно данных для отображения результата.');
}

$correct_count = (int)$_GET['correct_count'];
$total_count = (int)$_GET['total_count'];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Bootstrap demo</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <?php include ('includes/header.php'); ?>

    <div class="container text-center mt-5 pt-5">
        <h1 class="mt-4">Результаты квиза</h1>
        <p>Вы ответили правильно на <?= $correct_count ?> из <?= $total_count ?> вопросов.</p>
        <a href="download_certificate.php?correct_count=<?= $correct_count ?>&total_count=<?= $total_count ?>" class="btn btn-success">Скачать сертификат</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
</body>
</html>
