<?php
include ('includes/connect.php');
include ('includes/session.php');

if (!isset($_SESSION['uid'])) {
    echo '<script>document.location.href="/"</script>';
    exit();
}

$news_id = $_GET['id'];
$user_id = $_SESSION['uid'];

$query = $conn->prepare("SELECT * FROM news WHERE id = ? AND user_id = ?");
$query->execute([$news_id, $user_id]);
$news = $query->fetch();

if (!$news) {
    echo "Нет прав!";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $descr = $_POST['descr'];
    $category_id = $_POST['category_id'];

    if (empty($title) || empty($descr) || empty($category_id)) {
        echo "Заполните все поля!";
        exit();
    }

    $update_query = $conn->prepare("UPDATE news SET title = ?, descr = ?, category_id = ? WHERE id = ?");
    $update_query->execute([$title, $descr, $category_id, $news_id]);

    header('Location: news.php');
}

$categories_query = $conn->query("SELECT * FROM news_category");
$categories = $categories_query->fetchAll();
?>

<!doctype html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Редактировать новость</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <?php include ("includes/header.php"); ?>

    <div class="container">
        <h1>Редактировать новость</h1>
        <form action="edit_news.php?id=<?= $news_id ?>" method="post">
            <div class="mb-3">
                <label for="title" class="form-label">Заголовок</label>
                <input type="text" class="form-control" id="title" name="title"
                    value="<?= htmlspecialchars($news['title']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="descr" class="form-label">Описание</label>
                <textarea class="form-control" id="descr" name="descr" rows="3"
                    required><?= htmlspecialchars($news['descr']) ?></textarea>
            </div>
            <div class="mb-3">
                <label for="category_id" class="form-label">Категория</label>
                <select class="form-control" id="category_id" name="category_id" required>
                    <?php foreach ($categories as $category) { ?>
                        <option value="<?= $category['id'] ?>" <?= $category['id'] == $news['category_id'] ? 'selected' : '' ?>><?= htmlspecialchars($category['category']) ?></option>
                    <?php } ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>