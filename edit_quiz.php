<?php
include ('includes/connect.php');
include ('includes/session.php');

if (!isset($_SESSION['uid'])) {
    echo '<script>document.location.href="/"</script>';
    exit();
}

$quiz_id = $_GET['id'];
$user_id = $_SESSION['uid'];

$query = $conn->prepare("SELECT * FROM quizzes WHERE id = ? AND user_id = ?");
$query->execute([$quiz_id, $user_id]);
$quiz = $query->fetch();

if (!$quiz) {
    echo "Нет прав!";
    header("Location: quiz.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['quiz_title'];
    $category_id = $_POST['category_id'];
    $questions = $_POST['questions'];
    $answers = $_POST['answers'];
    $correct_answers = $_POST['correct_answers'];

    if (empty($title) || empty($category_id)) {
        echo "Заполните все поля!";
        exit();
    }

    try {
        $conn->beginTransaction();

        $update_query = $conn->prepare("UPDATE quizzes SET title = ?, category_id = ? WHERE id = ?");
        $update_query->execute([$title, $category_id, $quiz_id]);

        $questions_query = $conn->prepare("SELECT id FROM questions WHERE quiz_id = ?");
        $questions_query->execute([$quiz_id]);
        $question_ids = $questions_query->fetchAll(PDO::FETCH_COLUMN);

        if (!empty($question_ids)) {
            $placeholders = implode(',', array_fill(0, count($question_ids), '?'));
            $delete_answers_query = $conn->prepare("DELETE FROM answers WHERE question_id IN ($placeholders)");
            $delete_answers_query->execute($question_ids);

            $delete_questions_query = $conn->prepare("DELETE FROM questions WHERE id IN ($placeholders)");
            $delete_questions_query->execute($question_ids);
        }

        $stmt_question = $conn->prepare("INSERT INTO questions (quiz_id, question_text) VALUES (?, ?)");
        $stmt_answer = $conn->prepare("INSERT INTO answers (question_id, answer_text, is_correct) VALUES (?, ?, ?)");

        foreach ($questions as $index => $question) {
            $stmt_question->execute([$quiz_id, $question]);
            $question_id = $conn->lastInsertId();

            foreach ($answers[$index + 1] as $answer_index => $answer) {
                $is_correct = ($answer_index == $correct_answers[$index + 1]) ? 1 : 0;
                $stmt_answer->execute([$question_id, $answer, $is_correct]);
            }
        }

        $conn->commit();
        header('Location: quiz.php');
    } catch (Exception $e) {
        $conn->rollBack();
        echo "Ошибка: " . $e->getMessage();
    }
}

$categories_query = $conn->query("SELECT * FROM quiz_category");
$categories = $categories_query->fetchAll();

$questions_query = $conn->prepare("SELECT * FROM questions WHERE quiz_id = ?");
$questions_query->execute([$quiz_id]);
$questions = $questions_query->fetchAll(PDO::FETCH_ASSOC);

$answers = [];
foreach ($questions as $question) {
    $answers_query = $conn->prepare("SELECT * FROM answers WHERE question_id = ?");
    $answers_query->execute([$question['id']]);
    $answers[$question['id']] = $answers_query->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Редактировать Quiz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <?php include ("includes/header.php"); ?>

    <div class="container">
        <h1>Редактировать Quiz</h1>
        <form action="edit_quiz.php?id=<?= $quiz_id ?>" method="post">
            <div class="mb-3">
                <label for="quiz_title" class="form-label">Название Quiz</label>
                <input type="text" class="form-control" id="quiz_title" name="quiz_title"
                    value="<?= htmlspecialchars($quiz['title']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="category_id" class="form-label">Категория</label>
                <select class="form-control" id="category_id" name="category_id" required>
                    <?php foreach ($categories as $category) { ?>
                        <option value="<?= $category['id'] ?>" <?= $category['id'] == $quiz['category_id'] ? 'selected' : '' ?>><?= htmlspecialchars($category['category']) ?></option>
                    <?php } ?>
                </select>
            </div>
            <div id="questionsContainer">
                <?php foreach ($questions as $index => $question) { ?>
                    <div class="question-item mb-3">
                        <label for="question_<?= $index + 1 ?>" class="form-label">Вопрос <?= $index + 1 ?></label>
                        <input type="text" class="form-control" id="question_<?= $index + 1 ?>" name="questions[]"
                            value="<?= htmlspecialchars($question['question_text']) ?>" required>
                        <div class="answers-container mt-2">
                            <label class="form-label">Ответы</label>
                            <?php foreach ($answers[$question['id']] as $answer_index => $answer) { ?>
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" name="answers[<?= $index + 1 ?>][]"
                                        value="<?= htmlspecialchars($answer['answer_text']) ?>" required>
                                    <div class="input-group-text">
                                        <input type="radio" name="correct_answers[<?= $index + 1 ?>]"
                                            value="<?= $answer_index ?>" <?= $answer['is_correct'] ? 'checked' : '' ?> required>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <button type="button" class="btn btn-secondary" id="addQuestionButton">Добавить вопрос</button>
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </form>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>
</html>
