<?php
include ('includes/connect.php');
include ('includes/session.php');

// Определение переменной $category_id
$category_id = isset($_GET['category']) ? $_GET['category'] : 0;
?>

<!doctype html>
<html lang="ru">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Новости</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="st.css">
</head>

<body>
  <?php include ('includes/header.php'); ?>

  <div class="container">
    <div class="row mt-5">
      <div class="col-6">
        <h1>Новости</h1>
      </div>
      <div class="col-6 text-end">
        <a href="#" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addNewsModal">Создать</a>
      </div>
    </div>
    <div class="row mt-3">
      <div class="col-md-4 mb-3">
        <form method="GET">
          <select class="form-select" name="category" onchange="this.form.submit()">
            <option value="0" <?= $category_id == 0 ? 'selected' : '' ?>>Все категории</option>
            <?php
            $news_categories = $conn->query("SELECT * FROM news_category")->fetchAll(PDO::FETCH_ASSOC);
            foreach ($news_categories as $news_category) { ?>
              <option value="<?= $news_category['id'] ?>" <?= $category_id == $news_category['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($news_category['category']) ?>
              </option>
            <?php } ?>
          </select>
        </form>
      </div>
    </div>


    <!-- HTML форма для добавления новости -->
    <div class="modal fade" id="addNewsModal" tabindex="-1" aria-labelledby="addNewsModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addNewsModalLabel">Добавить новость</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="addNewsForm" method="post" action="news_add.php">
              <div class="mb-3">
                <label for="title" class="form-label">Заголовок</label>
                <input type="text" class="form-control" id="title" name="title" required>
              </div>
              <div class="mb-3">
                <label for="descr" class="form-label">Описание</label>
                <textarea class="form-control" id="descr" name="descr" rows="3" required></textarea>
              </div>
              <div class="mb-3">
                <label for="category" class="form-label">Категория</label>
                <select class="form-select form-select-md" name="category_id" id="category">
                  <option value="0">Выберите категорию</option>
                  <?php
                  $news_categories = $conn->query("SELECT * FROM news_category")->fetchAll(PDO::FETCH_ASSOC);
                  foreach ($news_categories as $news_category) { ?>
                    <option value="<?= $news_category['id'] ?>"><?= $news_category['category'] ?></option>
                  <?php } ?>
                </select>
              </div>
              <input type="hidden" name="user_id" value="<?= $_SESSION['uid'] ?>">
              <button type="submit" name="add_news" class="btn btn-primary">Добавить</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <?php
      $user_id = $_SESSION['uid'];
      $sql = "SELECT news.*, users.name, news_category.category AS category_name 
          FROM news 
          JOIN users ON news.user_id = users.id 
          JOIN news_category ON news.category_id = news_category.id";
      if ($category_id != 0) {
        $sql .= " WHERE news.category_id = $category_id";
      }
      $sql .= " ORDER BY news.id DESC";
      $query = $conn->query($sql);
      while ($row = $query->fetch()) {
        ?>
        <div class="col-md-6 mb-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($row['title']) ?></h5>
              <p class="card-text"><?= isset($row['descr']) ? htmlspecialchars($row['descr']) : '' ?></p>
              <p class="card-text"><small class="text-muted">Категория:
                  <?= htmlspecialchars($row['category_name']) ?></small></p>
              <p class="card-text"><small class="text-muted">Автор: <?= htmlspecialchars($row['name']) ?></small></p>
              <?php if ($row['user_id'] == $user_id) { ?>
                        <a href="edit_news.php?id=<?= $row['id'] ?>" class="btn btn-primary">Редактировать</a>
              <?php } ?>
            </div>
          </div>
        </div>
      <?php } ?>
    </div>
  </div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>

</body>

</html>
