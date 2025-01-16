<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
    <form method="POST" action="">
    <h1>Добавить отел</h1>
    <p>Отдел:</p>
     <input type="text" name="department" required><br>
     <input type="submit" value="Добавить">
</form>
</body>
</html>
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tz2";

$conn = new mysqli($servername, $username, $password, $dbname);

// Получение данных из формы
if ($_SERVER["REQUEST_METHOD"] == "POST") {
$department = $_POST['department'];
// Добавление данных в таблицу data_worker
$sql = "INSERT INTO department (department) VALUES ('$department')";
$conn->query($sql);

// Получение ID последней добавленной записи
$id_dw = $conn->insert_id;
echo "Данные успешно добавлены в базу данных.";
}
$conn->close();

?>