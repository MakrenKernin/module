<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить Пользователя</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Добавить Пользователя</h2>
        <form action="add_users.php" method="post">
            <input type="text" name="first_name" placeholder="Имя" required><br><br>
            <input type="text" name="last_name" placeholder="Фамилия" required><br><br>
            <input type="email" name="email" placeholder="Email" required><br><br>
            <input type="password" name="password" placeholder="Пароль" required><br><br>
            <select name="role">
                <option value="admin">admin</option>
                <option value="student">student</option>
            </select><br><br>
            <select name="group_id">
                <?php
                // Подключение к базе данных
                require_once 'connect.php';

                // Получение списка групп пользователей из базы данных
                $sql = "SELECT Group_ID, Group_Name FROM UserGroups";
                $stmt = $pdo->query($sql);

                // Вывод списка групп в выпадающий список
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='" . $row['Group_ID'] . "'>" . $row['Group_Name'] . "</option>";
                }
                ?>
            </select><br><br>
            <button type="submit" name="submit">Добавить</button>
        </form>
        <?php
        // Обработка отправленной формы
        if (isset($_POST['submit'])) {
            // Получение данных из формы
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $role = $_POST['role'];
            $group_id = $_POST['group_id'];

            // SQL запрос для вставки данных в таблицу пользователей
            $sql = "INSERT INTO Users (First_Name, Last_Name, Email, Password, Role, Registration_Date, Group_ID) 
                    VALUES (:first_name, :last_name, :email, :password, :role, CURDATE(), :group_id)";
            $stmt = $pdo->prepare($sql);

            // Выполнение запроса с передачей данных через параметры
            $stmt->execute([
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'password' => $password,
                'role' => $role,
                'group_id' => $group_id
            ]);

            // После успешного добавления пользователя перенаправляем обратно на страницу добавления пользователей
            header("Location: add_users.php?success=true");
            exit();
        }

        // Проверка наличия параметра success в URL, чтобы выводить сообщение только после успешного добавления пользователя
        if (isset($_GET['success']) && $_GET['success'] == 'true') {
            echo "<p>Пользователь успешно добавлен!</p>";
        }
        ?>
        <a href="admin_dashboard.php" class="back-btn">Назад</a>
    </div>
</body>
</html>
