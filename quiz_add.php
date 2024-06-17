<?php
include ('includes/connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quiz_title = $_POST['quiz_title'];
    $category_id = $_POST['category_id'];
    $user_id = $_POST['user_id'];
    $questions = $_POST['questions'];
    $answers = $_POST['answers'];
    $correct_answers = $_POST['correct_answers'];

    try {
        $conn->beginTransaction();

        $stmt = $conn->prepare("INSERT INTO quizzes (title, category_id, user_id) VALUES (?, ?, ?)");
        $stmt->execute([$quiz_title, $category_id, $user_id]);
        $quiz_id = $conn->lastInsertId();

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
        header("Location: quiz.php");

        echo "Quiz успешно добавлен!";
    } catch (Exception $e) {
        $conn->rollBack();
        echo "Ошибка: " . $e->getMessage();
    }
} else {
    echo "Неверный метод запроса.";
}
?>
