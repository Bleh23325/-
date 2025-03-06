<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tz3";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получаем данные из формы
    $surname = $_POST['surname'];
    $name = $_POST['name'];
    $patronymic = $_POST['patronymic'];
    $department = $_POST['department'];
    $job_title = $_POST['job_title'];
    $salary = $_POST['salary'];
    $street = $_POST['street'];
    $house = $_POST['house'];
    $city = $_POST['city'];
    $apartment = $_POST['apartment'];
    $passport_series = $_POST['passport_series'];
    $passport_number = $_POST['passport_number'];
    $passport_issued_by = $_POST['passport_issued_by'];
    $passport_issued_date = $_POST['passport_issued_date'];
    $phone = $_POST['phone'];
    $birth_date = $_POST['birth_date'];
    $hire_date = $_POST['hire_date'];

    // Проверяем, есть ли уже такой сотрудник в базе (по ФИО)
    $stmt = $conn->prepare("SELECT COUNT(*) FROM Worker WHERE Familia = ? AND Ima = ? AND Otchestvo = ?");
    $stmt->bind_param("sss", $surname, $name, $patronymic);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        echo "Сотрудник с таким ФИО уже существует.";
    } else {
        // Вставляем данные в таблицу Worker
        $stmt = $conn->prepare("INSERT INTO Worker (Familia, Ima, Otchestvo, department, jod_title, data_rojdenia, zarplata, data_zachislenia) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $surname, $name, $patronymic, $department, $job_title, $birth_date, $salary, $hire_date);
        
        if ($stmt->execute()) {
            $worker_id = $stmt->insert_id;
            $stmt->close();

            // Вставляем данные в таблицу address
            $stmt = $conn->prepare("INSERT INTO address (street, house, apartment, city, Worker) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sissi", $street, $house, $apartment, $city, $worker_id);
            $stmt->execute();
            $stmt->close();

            // Вставляем данные в таблицу data_worker (паспорт)
            $stmt = $conn->prepare("INSERT INTO data_worker (seria_pasporta, nomer_pasporta, who_issue, when_issue, Worker) 
                                    VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssi", $passport_series, $passport_number, $passport_issued_by, $passport_issued_date, $worker_id);
            $stmt->execute();
            $stmt->close();

            // Вставляем контактные данные в таблицу info_worker
            $stmt = $conn->prepare("INSERT INTO info_worker (phone, Worker) VALUES (?, ?)");
            $stmt->bind_param("si", $phone, $worker_id);
            $stmt->execute();
            $stmt->close();

            echo "Сотрудник добавлен успешно!";
        } else {
            echo "Ошибка: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавление сотрудника</title>
    <link rel="stylesheet" href="../../css/styleInsert.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
</head>
<body>
<div class="header">
        <div class="logo">Учёт работников</div>
        <nav>
            <a href="/admin.php">Главная</a>
            <a href="/modules/admin/insert.php">Добавить работника</a>
            <a href="/modules/admin/search_by_date.php">Умный поиск</a>
            <a href="/modules/admin/edit_worker.php">Изменить работника</a>
            <a href="/modules/admin/job_title.php">Должности</a>
            <a href="/modules/admin/department.php">Отделы</a>
        </nav>
    </div>

    <div class="form-container">
        <h2>Добавление нового сотрудника</h2>
        <form method="POST">
            <div class="form-group">
                <label>Фамилия:</label>
                <input type="text" name="surname" placeholder="Введите фамилию" required autocomplete="family-name">
            </div>
            <div class="form-group">
                <label>Имя:</label>
                <input type="text" name="name" placeholder="Введите имя" required autocomplete="given-name">
            </div>
            <div class="form-group">
                <label>Отчество:</label>
                <input type="text" name="patronymic" placeholder="Введите отчество" required>
            </div>
            <div class="form-group">
                <label>Дата рождения:</label>
                <input type="date" name="birth_date" required>
            </div>
            <div class="form-group">
                <label>Дата зачисления:</label>
                <input type="date" name="hire_date" required>
            </div>
            <div class="form-group">
                <label>Зарплата (₽):</label>
                <input type="number" name="salary" placeholder="Введите сумму" required min="10000">
            </div>
            <div class="form-group">
                <label>Паспорт:</label>
                <input type="text" name="passport_series" id="passport_series" placeholder="Серия (4 цифры)" required>
                <input type="text" name="passport_number" id="passport_number" placeholder="Номер (6 цифр)" required>
                <input type="text" name="passport_issued_by" placeholder="Кем выдан" required>
                <input type="date" name="passport_issued_date" placeholder="Дата выдачи" required>
            </div>
            <div class="form-group">
                <label>Телефон:</label>
                <input type="tel" name="phone" id="phone" placeholder="+7 (___) ___-__-__" required>
            </div>
            <div class="form-group">
                <label>Адрес:</label>
                <input type="text" name="city" placeholder="Город" required>
                <input type="text" name="street" placeholder="Улица" required>
                <input type="number" name="house" placeholder="Дом" required>
                <input type="number" name="apartment" placeholder="Квартира" required>
            </div>
            <div class="form-group">
                <label>Департамент:</label>
                <select name="department" required>
                    <?php
                    $result = $conn->query("SELECT id_departament, department FROM department");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id_departament'] . "'>" . $row['department'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>Должность:</label>
                <select name="job_title" required>
                    <?php
                    $result = $conn->query("SELECT id_jt, Job_title FROM Job_title");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id_jt'] . "'>" . $row['Job_title'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit">Добавить</button>
        </form>
    </div>

    <script>
        $(document).ready(function(){
            $('#passport_series').mask('0000');
            $('#passport_number').mask('000000');
            $('#phone').mask('+7 (000) 000-00-00');
        });
    </script>
</body>
</html>