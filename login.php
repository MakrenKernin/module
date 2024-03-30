<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <h2>Добро пожаловать в модуль персонального трека обучения</h2>
        <form action="login.php" method="post">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Войти</button>
        </form>
    </div>
</body>
</html>
<?php
session_start();
require_once 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Запрос к базе данных для проверки существования пользователя
    $sql = "SELECT * FROM Users WHERE Email = :email AND Password = :password";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email, 'password' => $password]);
    $user = $stmt->fetch();

    if ($user) {
        // Проверка роли пользователя
        if ($user['Role'] == 'admin') {
            // Если пользователь - администратор, перенаправление на страницу администратора
            $_SESSION['user_id'] = $user['User_ID']; // Сохраняем ID пользователя в сессии
            header("Location: admin_dashboard.php");
            exit();
        } else {
            // Если пользователь - студент или другая роль, перенаправление на главную страницу
            $_SESSION['user_id'] = $user['User_ID']; // Сохраняем ID пользователя в сессии
            header("Location: index.php");
            exit();
        }
    } else {
        // Вывод ошибки авторизации
        echo "<p>Invalid email or password. Please try again.</p>";
    }
}
?>

