<?php
session_start();
require_once 'connect.php';

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Получаем идентификатор пользователя из сессии
$user_id = $_SESSION['user_id'];

// Обработка отправленной формы
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Проверяем, сколько курсов уже выбрано пользователем
    $sql_count = "SELECT COUNT(*) FROM UserCourseRegistration WHERE User_ID = :user_id";
    $stmt_count = $pdo->prepare($sql_count);
    $stmt_count->execute(['user_id' => $user_id]);
    $course_count = $stmt_count->fetchColumn();

    // Если пользователь уже выбрал 5 курсов, выдаем сообщение об ошибке
    if ($course_count >= 5) {
        echo "<p>Вы уже выбрали максимальное количество курсов.</p>";
    } else {
        // Получаем данные из формы
        $selected_course_id = $_POST['course'];

        // Проверяем, выбрал ли пользователь уже этот курс
        $sql_check = "SELECT COUNT(*) FROM UserCourseRegistration WHERE User_ID = :user_id AND Course_ID = :course_id";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->execute(['user_id' => $user_id, 'course_id' => $selected_course_id]);
        $already_selected = $stmt_check->fetchColumn();

        // Если пользователь уже выбрал этот курс, выдаем сообщение об ошибке
        if ($already_selected) {
            echo "<p>Вы уже выбрали этот курс.</p>";
        } else {
            // Регистрируем пользователя на выбранный курс
            $sql_register = "INSERT INTO UserCourseRegistration (User_ID, Course_ID, Registration_Date) VALUES (:user_id, :course_id, CURDATE())";
            $stmt_register = $pdo->prepare($sql_register);
            $stmt_register->execute(['user_id' => $user_id, 'course_id' => $selected_course_id]);
            echo "<p>Курс успешно добавлен!</p>";

            // После успешного добавления курса делаем редирект на эту же страницу
            header("Location: select_courses.php");
            exit();
        }
    }
}

// Получаем список всех курсов
$sql_courses = "SELECT Course_ID, Course_Name FROM Courses";
$stmt_courses = $pdo->query($sql_courses);
$all_courses = $stmt_courses->fetchAll(PDO::FETCH_ASSOC);

// Получаем список курсов, выбранных пользователем
$sql_user_courses = "SELECT Courses.Course_ID, Courses.Course_Name FROM UserCourseRegistration 
                    JOIN Courses ON UserCourseRegistration.Course_ID = Courses.Course_ID 
                    WHERE UserCourseRegistration.User_ID = :user_id";
$stmt_user_courses = $pdo->prepare($sql_user_courses);
$stmt_user_courses->execute(['user_id' => $user_id]);
$user_courses = $stmt_user_courses->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Выбор курсов</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Выберите курсы</h2>
        <form action="select_courses.php" method="post">
            <select name="course">
                <?php foreach ($all_courses as $course) : ?>
                    <option value="<?php echo $course['Course_ID']; ?>"><?php echo $course['Course_Name']; ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" name="submit">Добавить курс</button>
        </form>

        <h2>Выбранные курсы</h2>
        <ul>
            <?php foreach ($user_courses as $user_course) : ?>
                <li><?php echo $user_course['Course_Name']; ?></li>
            <?php endforeach; ?>
        </ul>
        <a href="index.php" class="back-btn">Назад</a>
    </div>
</body>
</html>
