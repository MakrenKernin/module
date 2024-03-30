<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель администратора</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Добро пожаловать, Админ!</h1>
        <nav>
            <ul>
                <li><a href="add_users.php">Добавить Пользователей</a></li>
                <li><a href="add_courses.php">Добавить Курсы</a></li>
				<li><a href="add_groups.php">Добавить Группы</a></li>
            </ul>
        </nav>
        <p>Это ваша панель администратора. Здесь вы можете управлять пользователями, курсами и другими административными задачами.</p>
        <a href="logout.php" class="logout-btn">Выйти</a>
    </div>
</body>
</html>
