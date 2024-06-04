<?php
header('Content-Type: application/json');
require_once ("includes/connect.php");

// Получаем данные в формате JSON
$data = json_decode(file_get_contents("php://input"), true);

// Проверяем, является ли текущий пользователь автором новости
function isNewsAuthor($conn, $newsId, $userId)
{
    $sql = "SELECT user_id FROM news WHERE id = :news_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':news_id', $newsId);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result && $result['user_id'] == $userId;
}

// Проверяем, что данные запроса содержат все необходимые параметры
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($data["news_id"]) && isset($data["title"])) {
    $newsId = intval($data['news_id']);
    $title = $data['title'];
    $userId = $_SESSION['uid']; // Получаем идентификатор текущего пользователя из сессии

    // Проверяем, является ли текущий пользователь автором новости
    if (isNewsAuthor($conn, $newsId, $userId)) {
        $sql = "UPDATE news SET title = :title WHERE id = :news_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':news_id', $newsId);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Изменения сохранены успешно.']);
        } else {
            $errorInfo = $stmt->errorInfo();
            echo json_encode(['status' => 'error', 'message' => 'Ошибка при сохранении изменений: ' . $errorInfo[2]]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Вы не являетесь автором этой новости.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Невозможно обработать запрос.']);
}
?>