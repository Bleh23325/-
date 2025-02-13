<?php
session_start();

// Подключение к базе данных
$host = 'localhost';
$dbname = 'tz3';
$dbuser = 'root';
$dbpass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $dbuser, $dbpass);
} catch (PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        // Проверка пользователя
        $stmt = $pdo->prepare("SELECT * FROM users WHERE name = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && $user['password'] === $password) {
            // Сохранение данных пользователя в сессию
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['name'];
            $_SESSION['role'] = $user['roots'];

            if ($user['roots'] == 1) {
                header("Location: admin.php");
            } elseif ($user['roots'] == 2) {
                header("Location: meneger.php");
            } else {
                echo "У вас недостаточно прав для входа.";
            }
        } else {
            $error = "Неверные имя пользователя или пароль.";
        }
    } else {
        $error = "Пожалуйста, заполните все поля.";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация</title>
    <link rel="stylesheet" href="/css/registre.css">
</head>
<body>
    <div class="login-container">
        <h1>Вход</h1>
        <?php if (isset($error)) echo "<p>$error</p>"; ?>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Логин" required>
            <input type="password" name="password" placeholder="Пароль" required>
            <button type="submit">Войти</button>
        </form>
    </div>
</body>
</html>
