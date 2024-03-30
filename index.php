<?php
session_start();
require_once 'connect.php';

// Получение данных о пользователе из базы данных
$user_id = $_SESSION['user_id']; // Предполагается, что у вас уже есть сессия пользователя
$sql = "SELECT * FROM Users WHERE User_ID = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Получение названия группы пользователя
$group_id = $user['Group_ID'];
$sql_group = "SELECT Group_Name FROM UserGroups WHERE Group_ID = :group_id";
$stmt_group = $pdo->prepare($sql_group);
$stmt_group->execute(['group_id' => $group_id]);
$group = $stmt_group->fetchColumn();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <nav>
            <ul>
                <li><a href="view_progress.php" class="menu-item">Просмотр прогресса</a></li>
                <li><a href="view_profile.php" class="menu-item">Просмотр профиля</a></li>
				<li><a href="select_courses.php" class="menu-item">Выбор курсов и просмотр</a></li>
                <li><a href="logout.php" class="logout-btn">Выйти</a></li>
            </ul>
        </nav>
        <h1>Добро пожаловать, <?php echo $user['First_Name']; ?>!</h1>
        <p>Вы находитесь в личном кабинете.</p>
        <p>Имя: <?php echo $user['First_Name']; ?></p>
        <p>Фамилия: <?php echo $user['Last_Name']; ?></p>
        <p>Email: <?php echo $user['Email']; ?></p>
        <p>Группа: <?php echo $group; ?></p>
    </div>
</body>
</html>
