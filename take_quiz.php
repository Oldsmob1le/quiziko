<?php
include ('includes/connect.php');
include ('includes/session.php');

if (!isset($_GET['id'])) {
    die('Ошибка: ID квиза не задан.');
}

$quiz_id = (int)$_GET['id'];
$user_id = $_SESSION['uid'];

// Получаем вопросы и ответы
$sql = "SELECT questions.*, answers.id AS answer_id, answers.answer_text, answers.is_correct 
        FROM questions 
        LEFT JOIN answers ON questions.id = answers.question_id 
        WHERE questions.quiz_id = $quiz_id";
$query = $conn->query($sql);
$questions = $query->fetchAll(PDO::FETCH_ASSOC);

// Группируем ответы по вопросам
$grouped_questions = [];
foreach ($questions as $question) {
    $grouped_questions[$question['id']]['question_text'] = $question['question_text'];
    $grouped_questions[$question['id']]['answers'][] = [
        'answer_id' => $question['answer_id'],
        'answer_text' => $question['answer_text'],
        'is_correct' => $question['is_correct']
    ];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $answers = $_POST['answer'];
    $correct_count = 0;

    foreach ($grouped_questions as $question_id => $question) {
        foreach ($question['answers'] as $answer) {
            if ($answer['is_correct'] && $answer['answer_id'] == $answers[$question_id]) {
                $correct_count++;
                break;
            }
        }
    }
    // Перенаправляем на страницу результатов
    header("Location: result.php?correct_count=$correct_count&total_count=" . count($grouped_questions));
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Квиз</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mt-4">Квиз: <?= htmlspecialchars($quiz_id) ?></h1>
        <form method="post">
            <?php foreach ($grouped_questions as $question_id => $question): ?>
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($question['question_text']) ?></h5>
                        <?php foreach ($question['answers'] as $answer): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="answer[<?= $question_id ?>]" value="<?= $answer['answer_id'] ?>" id="answer<?= $answer['answer_id'] ?>">
                                <label class="form-check-label" for="answer<?= $answer['answer_id'] ?>">
                                    <?= htmlspecialchars($answer['answer_text']) ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            <button type="submit" class="btn btn-primary">Отправить ответы</button>
        </form>
    </div>
</body>
</html>