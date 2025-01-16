

<?php
// Подключение к бд
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tz2";

$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка подключения
if ($conn->connect_error) {
    die("Ошибка: " . $conn->connect_error);
}

// Обработка данных
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Familia = $_POST['Familia'];
    $Ima = $_POST['Ima'];
    $Otchestvo = $_POST['Otchestvo'];
    $department = $_POST['department'];
    $jod_title = $_POST['jod_title'];
    $data_rojdenia = $_POST['data_rojdenia'];
    $zarplata = $_POST['zarplata'];
    $data_zachislenia = $_POST['data_zachislenia'];

    // запрос для вставки данных в таблицу Worker
    $sql = "INSERT INTO Worker (Familia, Ima, Otchestvo, department, jod_title, data_rojdenia, zarplata, data_zachislenia) 
            VALUES ('$Familia', '$Ima', '$Otchestvo', '$department', '$jod_title', '$data_rojdenia', '$zarplata', '$data_zachislenia')";

    if ($conn->query($sql) === TRUE) {
        echo "Новая запись успешно добавлена";
    } else {
        echo "Ошибка: " . $sql . "<br>" . $conn->error;
    }
}

// Получение данных для выпадающих списков
$departments = $conn->query("SELECT id_departament, department FROM department");
$jod_titles = $conn->query("SELECT id_jt, Job_title FROM Job_title");
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вставка работника</title>
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
    <h1>Добавить работника</h1>
    <form method="POST" action="">
        <label for="Familia">Фамилия:</label>
        <input type="text" name="Familia" required><br>

        <label for="Ima">Имя:</label>
        <input type="text" name="Ima" required><br>

        <label for="Otchestvo">Отчество:</label>
        <input type="text" name="Otchestvo" required><br>

        <label for="department">Отдел:</label>
        <select name="department">
            <?php while ($row = $departments->fetch_assoc()) { ?>
                <option value="<?php echo $row['id_departament']; ?>"><?php echo $row['department']; ?></option>
            <?php } ?>
        </select><br>

        <label for="jod_title">Должность:</label>
        <select name="jod_title">
            <?php while ($row = $jod_titles->fetch_assoc()) { ?>
                <option value="<?php echo $row['id_jt']; ?>"><?php echo $row['Job_title']; ?></option>
            <?php } ?>
        </select><br>

        <label for="data_rojdenia">Дата рождения:</label>
        <input type="date" name="data_rojdenia" required><br>

        <label for="zarplata">Зарплата:</label>
        <input type="number" name="zarplata" required><br>

        <label for="data_zachislenia">Дата зачисления:</label>
        <input type="date" name="data_zachislenia" required><br>
        

        <input type="submit" value="Добавить работника">
    </form>
</body>
</html>

<?php
$conn->close();
?>
</body>
</html>