<?php
include ('includes/connect.php');
include ('includes/session.php');

$category_id = isset($_GET['category']) ? $_GET['category'] : 0;
?>

<!doctype html>
<html lang="ru">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>QUIZIKOs</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="st.css">
</head>

<body>
  <?php include ('includes/header.php'); ?>

  <div class="container">
    <div class="row mt-5">
      <div class="col-6">
        <h1>Quiz</h1>
      </div>
      <div class="col-6 text-end">
        <a href="#" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addQuizModal">Создать</a>
      </div>
    </div>
    <div class="row mt-3">
      <div class="col-md-4 mb-3">
        <form method="GET">
          <select class="form-select" name="category" onchange="this.form.submit()">
            <option value="0" <?= $category_id == 0 ? 'selected' : '' ?>>Все категории</option>
            <?php
            $quiz_categories = $conn->query("SELECT * FROM quiz_category")->fetchAll(PDO::FETCH_ASSOC);
            foreach ($quiz_categories as $quiz_category) { ?>
              <option value="<?= $quiz_category['id'] ?>" <?= $category_id == $quiz_category['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($quiz_category['category']) ?>
              </option>
            <?php } ?>
          </select>
        </form>
      </div>
    </div>


<!-- HTML форма для добавления quiz -->
<div class="modal fade" id="addQuizModal" tabindex="-1" aria-labelledby="addQuizModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addQuizModalLabel">Добавить Quiz</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addQuizForm" method="post" action="quiz_add.php">
          <div class="mb-3">
            <label for="quizTitle" class="form-label">Название Quiz</label>
            <input type="text" class="form-control" id="quizTitle" name="quiz_title" required>
          </div>
          <div class="mb-3">
            <label for="quizCategory" class="form-label">Категория</label>
            <select class="form-select form-select-md" name="category_id" id="quizCategory">
              <option value="0">Выберите категорию</option>
              <?php
              $quiz_categories = $conn->query("SELECT * FROM quiz_category")->fetchAll(PDO::FETCH_ASSOC);
              foreach ($quiz_categories as $quiz_category) { ?>
                <option value="<?= $quiz_category['id'] ?>"><?= $quiz_category['category'] ?></option>
              <?php } ?>
            </select>
          </div>
          <div id="questionsContainer">
            <div class="question-item mb-3">
              <label for="question_1" class="form-label">Вопрос 1</label>
              <input type="text" class="form-control" id="question_1" name="questions[]" required>
              <div class="answers-container mt-2">
                <label class="form-label">Ответы</label>
                <div class="input-group mb-2">
                  <input type="text" class="form-control" name="answers[1][]" placeholder="Ответ 1" required>
                  <div class="input-group-text">
                    <input type="radio" name="correct_answers[1]" value="0" required>
                  </div>
                </div>
                <div class="input-group mb-2">
                  <input type="text" class="form-control" name="answers[1][]" placeholder="Ответ 2" required>
                  <div class="input-group-text">
                    <input type="radio" name="correct_answers[1]" value="1">
                  </div>
                </div>
                <div class="input-group mb-2">
                  <input type="text" class="form-control" name="answers[1][]" placeholder="Ответ 3" required>
                  <div class="input-group-text">
                    <input type="radio" name="correct_answers[1]" value="2">
                  </div>
                </div>
                <div class="input-group mb-2">
                  <input type="text" class="form-control" name="answers[1][]" placeholder="Ответ 4" required>
                  <div class="input-group-text">
                    <input type="radio" name="correct_answers[1]" value="3">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <button type="button" class="btn btn-secondary" id="addQuestionButton">Добавить вопрос</button>
          <input type="hidden" name="user_id" value="<?= $_SESSION['uid'] ?>">
          <button type="submit" name="add_quiz" class="btn btn-primary">Добавить</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
document.getElementById('addQuestionButton').addEventListener('click', function () {
  const questionsContainer = document.getElementById('questionsContainer');
  const questionCount = questionsContainer.querySelectorAll('.question-item').length + 1;
  const questionItem = document.createElement('div');
  questionItem.classList.add('question-item', 'mb-3');
  questionItem.innerHTML = `
    <label for="question_${questionCount}" class="form-label">Вопрос ${questionCount}</label>
    <input type="text" class="form-control" id="question_${questionCount}" name="questions[]" required>
    <div class="answers-container mt-2">
      <label class="form-label">Ответы</label>
      <div class="input-group mb-2">
        <input type="text" class="form-control" name="answers[${questionCount}][]" placeholder="Ответ 1" required>
        <div class="input-group-text">
          <input type="radio" name="correct_answers[${questionCount}]" value="0" required>
        </div>
      </div>
      <div class="input-group mb-2">
        <input type="text" class="form-control" name="answers[${questionCount}][]" placeholder="Ответ 2" required>
        <div class="input-group-text">
          <input type="radio" name="correct_answers[${questionCount}]" value="1">
        </div>
      </div>
      <div class="input-group mb-2">
        <input type="text" class="form-control" name="answers[${questionCount}][]" placeholder="Ответ 3" required>
        <div class="input-group-text">
          <input type="radio" name="correct_answers[${questionCount}]" value="2">
        </div>
      </div>
      <div class="input-group mb-2">
        <input type="text" class="form-control" name="answers[${questionCount}][]" placeholder="Ответ 4" required>
        <div class="input-group-text">
          <input type="radio" name="correct_answers[${questionCount}]" value="3">
        </div>
      </div>
    </div>
  `;
  questionsContainer.appendChild(questionItem);
});
</script>




    <div class="row">
      <?php
      $user_id = $_SESSION['uid'];
      $sql = "SELECT quizzes.*, users.name, quiz_category.category AS category_name 
      FROM quizzes 
      JOIN users ON quizzes.user_id = users.id 
      JOIN quiz_category ON quizzes.category_id = quiz_category.id";
      if ($category_id != 0) {
        $sql .= " WHERE quizzes.category_id = $category_id";
      }
      $sql .= " ORDER BY quizzes.id DESC";
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
