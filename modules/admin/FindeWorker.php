<?php
// Настройки подключения к базе данных
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "tz2"; 

// Создание подключения
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка подключения
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Обработка формы
$searchFamilia = isset($_POST['familia']) ? $_POST['familia'] : '';
$searchIma = isset($_POST['ima']) ? $_POST['ima'] : '';
$searchOtchestvo = isset($_POST['otchestvo']) ? $_POST['otchestvo'] : '';

// Запрос на поиск
$sql = "SELECT w.*, a.street, a.house, dw.seria_pasporta, dw.nomer_pasporta, d.department, iw.phone, jt.Job_title,
    dis.dismissed 
        FROM Worker w
        JOIN address a ON w.id_w = a.Worker
        JOIN data_worker dw ON w.id_w = dw.Worker
        JOIN department d ON w.department = d.id_departament
        JOIN info_worker iw ON w.id_w = iw.Worker
        JOIN Job_title jt ON w.jod_title = jt.id_jt
        JOIN Dismissed dis ON w.dismissed = dis.id_dis WHERE Familia LIKE ? AND Ima LIKE ? AND Otchestvo LIKE ?";
$stmt = $conn->prepare($sql);
$likeFamilia = "%" . $searchFamilia . "%";
$likeIma = "%" . $searchIma . "%";
$likeOtchestvo = "%" . $searchOtchestvo . "%";
$stmt->bind_param("sss", $likeFamilia, $likeIma, $likeOtchestvo);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Поиск сотрудников</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<div class="navigation">
            <a href="/admin.php">Главная</a>
            <a href="Insert.php">Добавить работника</a>
            <a href="dataWorker.php">Добавить данные работников</a>
            <a href="dataDepartment.php">Добавить отдел</a>
            <a href="JobTitle.php">Добавить должность</a>
            <a href="changeWorker.php">Изменить работника</a>
            <a href="FindeWorker.php">Найти работника</a>
            <a href="changeDataWorker.php">Изменить данные работника</a>
        </div>
    <h1>Поиск сотрудников</h1>
    <form method="post">
        <label for="familia">Фамилия:</label>
        <input type="text" id="familia" name="familia">
        <label for="ima">Имя:</label>
        <input type="text" id="ima" name="ima">
        <label for="otchestvo">Отчество:</label>
        <input type="text" id="otchestvo" name="otchestvo">
        <input type="submit" value="Поиск">
    </form>

    <div class="tabel">
        <div class="title">
    <table>
        <tr>
            <td>ФИО</td>
            <td>Телефон</td>
            <td>Паспорт</td>
            <td>Дата рождения</td>
            <td>Адрес проживания</td>
            <td>Отдел</td>
            <td>Должност</td>
            <td>Размер зарплат</td>
            <td>Дата принятия на работу</td>
            <td>Статус работника</td>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['Familia']} {$row['Ima']} {$row['Otchestvo']}</td>
                        <td>{$row['phone']}</td>
                        <td>{$row['seria_pasporta']} {$row['nomer_pasporta']}</td>
                        <td>{$row['data_rojdenia']}</td>
                        <td>{$row['street']} {$row['house']}</td>
                        <td>{$row['department']}</td>
                        <td>{$row['Job_title']}</td>
                        <td>{$row['zarplata']}</td>
                        <td>{$row['data_zachislenia']}</td>
                        <td>{$row['dismissed']}</td>
                    </tr>";
            }
        } else {
            echo "<tr><td colspan='6'>Нет результатов</td></tr>";
        }
        ?>
    </table>
    </div>
    </div>

    <?php
    // Закрытие соединения
    $stmt->close();
    $conn->close();
    ?>
</body>
</html>
