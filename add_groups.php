<?php
// Подключение к базе данных
require_once 'connect.php';

// Обработка отправленной формы
if (isset($_POST['submit'])) {
    // Получение данных из формы
    $group_name = $_POST['group_name'];
    $description = $_POST['description'];

    // SQL запрос для вставки данных в таблицу групп пользователей
    $sql = "INSERT INTO UserGroups (Group_Name, Description) VALUES (:group_name, :description)";
    $stmt = $pdo->prepare($sql);

    // Выполнение запроса с передачей данных через параметры
    $stmt->execute(['group_name' => $group_name, 'description' => $description]);

    // Перенаправление пользователя на страницу администратора после успешного добавления группы
    header("Location: admin_dashboard.php");
    exit(); // Обязательно завершаем выполнение скрипта после перенаправления
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить Группу</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Добавить Группу</h2>
        <form action="add_groups.php" method="post">
            <input type="text" name="group_name" placeholder="Название группы" required><br><br>
            <textarea name="description" placeholder="Описание группы" rows="4" cols="50"></textarea><br><br>
            <button type="submit" name="submit">Добавить</button>
        </form>
        <a href="admin_dashboard.php" class="back-btn">Назад</a>
    </div>
</body>
</html>
