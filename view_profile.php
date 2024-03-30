<?php
// Подключение к базе данных
require_once 'connect.php';

// Проверка, авторизован ли пользователь
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Получение данных о пользователе из базы данных
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM Users JOIN UserProfiles ON Users.User_ID = UserProfiles.User_ID WHERE Users.User_ID = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Проверка, была ли отправлена форма для обновления профиля
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Получение данных из формы
    $birth_date = $_POST['birth_date'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $interests = $_POST['interests'];

    // Проверка, есть ли уже данные профиля пользователя в таблице UserProfiles
    $sql_check = "SELECT * FROM UserProfiles WHERE User_ID = :user_id";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute(['user_id' => $user_id]);
    $profile_exists = $stmt_check->fetch();

    if ($profile_exists) {
        // Если данные уже существуют, выполнить UPDATE
        $sql_update = "UPDATE UserProfiles SET Birth_Date = :birth_date, Address = :address, Phone = :phone, Interests = :interests WHERE User_ID = :user_id";
        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->execute(['birth_date' => $birth_date, 'address' => $address, 'phone' => $phone, 'interests' => $interests, 'user_id' => $user_id]);
    } else {
        // Если данных нет, выполнить INSERT
        $sql_insert = "INSERT INTO UserProfiles (User_ID, Birth_Date, Address, Phone, Interests) VALUES (:user_id, :birth_date, :address, :phone, :interests)";
        $stmt_insert = $pdo->prepare($sql_insert);
        $stmt_insert->execute(['user_id' => $user_id, 'birth_date' => $birth_date, 'address' => $address, 'phone' => $phone, 'interests' => $interests]);
    }

    // Перенаправление на страницу просмотра профиля
    header("Location: view_profile.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Просмотр профиля</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Просмотр профиля</h2>
        <!-- Отображение данных профиля -->
        <p><strong>Дата рождения:</strong> <?php echo $user['Birth_Date']; ?></p>
        <p><strong>Адрес:</strong> <?php echo $user['Address']; ?></p>
        <p><strong>Телефон:</strong> <?php echo $user['Phone']; ?></p>
        <p><strong>Интересы:</strong> <?php echo $user['Interests']; ?></p>
        
        <!-- Форма для редактирования профиля -->
        <h3>Редактировать профиль</h3>
        <form action="view_profile.php" method="post">
            <label for="birth_date">Дата рождения:</label>
            <input type="date" name="birth_date" id="birth_date" value="<?php echo $user['Birth_Date']; ?>" required><br><br>
            <label for="address">Адрес:</label>
            <input type="text" name="address" id="address" value="<?php echo $user['Address']; ?>" required><br><br>
            <label for="phone">Телефон:</label>
            <input type="tel" name="phone" id="phone" value="<?php echo $user['Phone']; ?>" required><br><br>
            <label for="interests">Интересы:</label><br>
            <textarea name="interests" id="interests" rows="4" cols="50"><?php echo $user['Interests']; ?></textarea><br><br>
            <button type="submit" name="submit">Обновить профиль</button>
        </form>
        <a href="index.php" class="back-btn">Назад</a>
    </div>
</body>
</html>
