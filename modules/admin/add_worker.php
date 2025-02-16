<?php
$servername = "localhost";
$username = "root"; // Укажите имя пользователя MySQL
$password = ""; // Укажите пароль, если есть
$dbname = "tz3"; // Имя вашей базы данных

// Подключение к базе данных
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка подключения
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Проверяем, пришли ли данные в POST-запросе
$required_fields = ['Familia', 'Ima', 'Otchestvo', 'department', 'job_title', 'data_rojdenia', 'zarplata', 'data_zachislenia', 'street', 'house', 'phone', 'seria_pasporta', 'nomer_pasporta'];
foreach ($required_fields as $field) {
    if (!isset($_POST[$field])) {
        die("Ошибка: отсутствует поле $field");
    }
}

// Получаем данные из формы (или из JSON)
$Familia = $_POST['Familia'];
$Ima = $_POST['Ima'];
$Otchestvo = $_POST['Otchestvo'];
$department = (int)$_POST['department'];
$job_title = (int)$_POST['job_title'];
$data_rojdenia = $_POST['data_rojdenia'];
$zarplata = (int)$_POST['zarplata'];
$data_zachislenia = $_POST['data_zachislenia'];
$dismissed = isset($_POST['dismissed']) ? (int)$_POST['dismissed'] : 2; // Если нет, ставим по умолчанию 2

$street = $_POST['street'];
$house = (int)$_POST['house'];
$phone = $_POST['phone'];

$seria_pasporta = (int)$_POST['seria_pasporta'];
$nomer_pasporta = (int)$_POST['nomer_pasporta'];

// Подготовленный запрос для вызова хранимой процедуры
$sql = "CALL add_worker(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

// Проверка, создалась ли подготовленная инструкция
if (!$stmt) {
    die("Ошибка подготовки запроса: " . $conn->error);
}

// Привязка параметров
$stmt->bind_param("sssiiisiissii", 
    $Familia, $Ima, $Otchestvo, 
    $department, $job_title, 
    $data_rojdenia, $zarplata, 
    $data_zachislenia, $dismissed, 
    $street, $house, $phone, 
    $seria_pasporta, $nomer_pasporta
);

// Выполнение запроса
if ($stmt->execute()) {
    echo "Сотрудник успешно добавлен!";
} else {
    echo "Ошибка выполнения запроса: " . $stmt->error;
}

// Закрываем соединение
$stmt->close();
$conn->close();
?>
