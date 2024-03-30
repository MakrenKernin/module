<?php
// Подключение к базе данных
require_once 'connect.php';

// Обработка отправленной формы
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Получение данных из формы
    $course_name = $_POST['course_name'];
    $description = $_POST['description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // SQL запрос для вставки данных в таблицу курсов
    $sql = "INSERT INTO Courses (Course_Name, Description, Start_Date, End_Date) 
            VALUES (:course_name, :description, :start_date, :end_date)";
    $stmt = $pdo->prepare($sql);

    // Выполнение запроса с передачей данных через параметры
    $stmt->execute([
        'course_name' => $course_name,
        'description' => $description,
        'start_date' => $start_date,
        'end_date' => $end_date
    ]);

    // Перенаправление на страницу администратора после успешного добавления курса
    header("Location: admin_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить Курс</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Добавить Курс</h2>
        <form action="add_courses.php" method="post">
            <input type="text" name="course_name" placeholder="Название курса" required><br><br>
            <textarea name="description" placeholder="Описание курса" rows="4" cols="50"></textarea><br><br>
            <label for="start_date">Дата начала курса:</label>
            <input type="date" name="start_date" id="start_date" required><br><br>
            <label for="end_date">Дата окончания курса:</label>
            <input type="date" name="end_date" id="end_date" required><br><br>
            <button type="submit" name="submit">Добавить</button>
        </form>
        <a href="admin_dashboard.php" class="back-btn">Назад</a>
    </div>
</body>
</html>
