<?php
// Запускаем сессию
session_start();

// Уничтожаем сессию
session_destroy();

// Перенаправляем пользователя на страницу авторизации
header("Location: login.php");
exit();
?>
