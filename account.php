<?php include ('includes/connect.php'); ?>
<?php include ('includes/session.php'); ?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>

    <?php
    session_start();
    if (!isset($_SESSION['uid'])) {
        echo '<script>document.location.href="index.php"</script>';
    }
    ?>

    <?php include ('includes/header.php'); ?>

    <section class="main container mt-5 pt-5 text-center">
        <div class="m-5">
            <h2>Привет, <?= $SIGNIN_USER['name'] ?></h2>

            <h4 class="text-primary">Профиль</h4>
            <p><?= $SIGNIN_USER['email'] ?></p>
            <p>
                <?php
                if ($SIGNIN_USER['role'] == 1) {
                    echo '<span class="badge text-bg-primary">Пользователь</span>';
                } elseif ($SIGNIN_USER['role'] == 2) {
                    echo '<span class="badge text-bg-danger">Администратор</span>';
                } elseif ($SIGNIN_USER['role'] == 0) {
                    echo '<span class="badge text-bg-danger">Заблокирован</span>
            <br> Инструкция для разблокировки <a href="#">Читать</a>
            ';
                } else {
                    echo 'Гость';
                }
                ?>
            </p>
        </div>

        <?php
        if ($SIGNIN_USER['role'] == 2) {
            echo '<a class="btn btn-outline-danger" href="user.php" role="button">Admin Panel</a>';
        }
        ?>

        <div class="container mt-5">
            <h3>Ваши новости</h3>
            <div class="row">
                <?php
                // Запрос на получение новостей пользователя
                $user_id = $_SESSION['uid'];
                $query = "SELECT * FROM news WHERE user_id = :user_id";
                $statement = $conn->prepare($query);
                $statement->bindParam(':user_id', $user_id);
                $statement->execute();
                $news = $statement->fetchAll();

                // Отображение новостей пользователя
                foreach ($news as $item) {
                    echo '<div class="col-md-4 mt-4">';
                    echo '<div class="card">';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title">' . htmlspecialchars($item['title']) . '</h5>';
                    echo '<p class="card-text">' . htmlspecialchars($item['descr']) . '</p>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </section>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>