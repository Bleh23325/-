<?php
// Подключение к базе данных
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tz2";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Получаем данные для выпадающих списков
$workers = $conn->query("SELECT id_w, Familia, Ima FROM Worker");
$addresses = $conn->query("SELECT id_adres, street, house FROM address");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Обработка формы
    // Вставка данных в таблицы
    $street = $_POST['street'];
    $house = $_POST['house'];
    $worker_id = $_POST['worker_id'];
    $seria_pasporta = $_POST['seria_pasporta'];
    $nomer_pasporta = $_POST['nomer_pasporta'];
    $phone = $_POST['phone'];

    // Вставка в address
    $conn->query("INSERT INTO address (street, house, Worker) VALUES ('$street', $house, $worker_id)");

    // Получаем последний добавленный id_adres
    $last_address_id = $conn->insert_id;

    // Вставка в data_worker
    $conn->query("INSERT INTO data_worker (seria_pasporta, nomer_pasporta, Worker) VALUES ($seria_pasporta, $nomer_pasporta, $worker_id)");

    // Получаем последний добавленный id_dw
    $last_data_worker_id = $conn->insert_id;

    // Вставка в info_worker
    $conn->query("INSERT INTO info_worker (phone, Worker) VALUES ('$phone', $worker_id)");

    echo "Данные успешно добавлены!";
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Форма добавления данных</title>
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
    <h1>Добавить данные работника</h1>
    <form method="post">
        <label for="street">Улица:</label>
        <input type="text" id="street" name="street" required> <br>

        <label for="house">Номер дома:</label>
        <input type="number" id="house" name="house" required><br>        

        <label for="seria_pasporta">Серия паспорта:</label>
        <input type="number" id="seria_pasporta" name="seria_pasporta" required> <br>

        <label for="nomer_pasporta">Номер паспорта:</label>
        <input type="number" id="nomer_pasporta" name="nomer_pasporta" required><br>

        <label for="phone">Телефон:</label>
        <input type="text" id="phone" name="phone" required><br>

        <label for="worker_id">Работник:</label>
        <select id="worker_id" name="worker_id" required>
            <?php while ($row = $workers->fetch_assoc()): ?>
                <option value="<?= $row['id_w'] ?>"><?= $row['Familia'] . ' ' . $row['Ima'] ?></option>
            <?php endwhile; ?>
        </select><br>

        <button type="submit">Добавить</button>
    </form>
</body>
</html>

<?php
$conn->close();
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script>
        $(document).ready(function(){
            $('#phone').mask('+7 (999) 999-99-99');
            $('#seria_pasporta').mask('9999');
            $('#nomer_pasporta').mask('999999');
        });
    </script>
