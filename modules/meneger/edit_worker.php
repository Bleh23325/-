<?php
require_once '../../db/connect.php';

function getDepartments($conn) {
    return $conn->query("SELECT id_departament, department FROM department")->fetch_all(MYSQLI_ASSOC);
}

function getJobTitles($conn) {
    return $conn->query("SELECT id_jt, Job_title FROM Job_title")->fetch_all(MYSQLI_ASSOC);
}

// Получение списка статусов
$statusResults = mysqli_query($conn, "SELECT id_dis, dismissed FROM Dismissed");
$statuses = mysqli_fetch_all($statusResults, MYSQLI_ASSOC);

$departments = getDepartments($conn);
$jobTitles = getJobTitles($conn);
$workers = [];

// Обновление данных сотрудника
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $id = $_POST['id'];
    $familia = $_POST['familia'];
    $ima = $_POST['ima'];
    $otchestvo = $_POST['otchestvo'];
    $phone = $_POST['phone'];
    $data_zachislenia = $_POST['data_zachislenia'];
    $passport_series = $_POST['passport_series'];
    $passport_number = $_POST['passport_number'];
    $passport_issued_by = $_POST['passport_issued_by'];
    $passport_issue_date = $_POST['passport_issue_date'];
    $street = $_POST['street'];
    $house = $_POST['house'];
    $apartment = $_POST['apartment'];
    $city = $_POST['city'];
    $department = $_POST['department'];
    $job_title = $_POST['job_title'];
    $status = $_POST['status'];
    $fst_date = $_POST['fst_date'];
    $last_date = $_POST['last_date'];

    // Обновление основной таблицы Worker
    $sql_worker = "UPDATE Worker 
                   SET Familia = ?, Ima = ?, Otchestvo = ?, data_zachislenia = ?, department = ?, jod_title = ? 
                   WHERE id_w = ?";
    $stmt = $conn->prepare($sql_worker);
    $stmt->bind_param("ssssiii", $familia, $ima, $otchestvo, $data_zachislenia, $department, $job_title, $id);
    $stmt->execute();
    $stmt->close();

    // Обновление контактных данных
    $sql_info = "UPDATE info_worker SET phone = ? WHERE Worker = ?";
    $stmt = $conn->prepare($sql_info);
    $stmt->bind_param("si", $phone, $id);
    $stmt->execute();
    $stmt->close();

    // Обновление паспортных данных
    $sql_passport = "UPDATE data_worker 
                     SET seria_pasporta = ?, nomer_pasporta = ?, who_issue = ?, when_issue = ? 
                     WHERE Worker = ?";
    $stmt = $conn->prepare($sql_passport);
    $stmt->bind_param("ssssi", $passport_series, $passport_number, $passport_issued_by, $passport_issue_date, $id);
    $stmt->execute();
    $stmt->close();

    // Обновление адреса
    $sql_address = "UPDATE address 
                    SET street = ?, house = ?, apartment = ?, city = ? 
                    WHERE Worker = ?";
    $stmt = $conn->prepare($sql_address);
    $stmt->bind_param("ssssi", $street, $house, $apartment, $city, $id);
    $stmt->execute();
    $stmt->close();

    // Обновление статуса и дат отсутствия
    $sql_status = "UPDATE time_of_absence SET statys = ?, fst_date = ?, last_date = ? WHERE worker = ?";
    $stmt = $conn->prepare($sql_status);
    $stmt->bind_param("sssi", $status, $fst_date, $last_date, $id);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Данные успешно обновлены!'); window.location.href='edit_worker.php';</script>";
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {
    $familia = trim($_POST['familia'] ?? '');
    $ima = trim($_POST['ima'] ?? '');
    $otchestvo = trim($_POST['otchestvo'] ?? '');

    if (empty($familia) || empty($ima) || empty($otchestvo)) {
        echo "<script>alert('Введите Фамилию, Имя и Отчество полностью!');</script>";
    } else {
        $sql = "SELECT w.id_w, w.Familia, w.Ima, w.Otchestvo, d.id_departament, d.department, 
               j.id_jt, j.Job_title, w.data_zachislenia, 
               i.phone, dw.seria_pasporta, dw.nomer_pasporta, dw.who_issue, dw.when_issue, 
               a.street, a.house, a.apartment, a.city, 
               t.statys, t.fst_date, t.last_date
        FROM Worker w
        LEFT JOIN department d ON w.department = d.id_departament
        LEFT JOIN Job_title j ON w.jod_title = j.id_jt
        LEFT JOIN info_worker i ON w.id_w = i.Worker
        LEFT JOIN data_worker dw ON w.id_w = dw.Worker
        LEFT JOIN address a ON w.id_w = a.Worker
        LEFT JOIN time_of_absence t ON w.id_w = t.worker
        WHERE w.Familia LIKE ? AND w.Ima LIKE ? AND w.Otchestvo LIKE ?
        ORDER BY w.data_zachislenia DESC
        LIMIT 1";

        $familia = "%$familia%";
        $ima = "%$ima%";
        $otchestvo = "%$otchestvo%";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $familia, $ima, $otchestvo);
        $stmt->execute();
        $workers = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактирование сотрудников</title>
    <link rel="stylesheet" href="/css/styleEdit.css">
</head>
<body>
<div class="header">
        <div class="logo">Учёт работников</div>
        <nav>
            <a href="/meneger.php">Главная</a>
            <a href="/modules/meneger/insert.php">Добавить работника</a>
            <a href="/modules/meneger/search_by_date.php">Умный поиск</a>
            <a href="/modules/meneger/edit_worker.php">Изменить работника</a>
        </nav>
    </div>
    <h2>Поиск сотрудников</h2>
        <form method="POST">
            <label>Фамилия: <input type="text" name="familia"></label>
            <label>Имя: <input type="text" name="ima"></label>
            <label>Отчество: <input type="text" name="otchestvo"></label>
            <button type="submit" name="search">Найти</button>
        </form>

    <?php if (!empty($workers)): ?>
        <h2>Редактирование сотрудников</h2>
        <?php foreach ($workers as $worker): ?>
            <form method="POST">
                <input type="hidden" name="id" value="<?= $worker['id_w'] ?>">
                <label>Фамилия: <input type="text" name="familia" value="<?= $worker['Familia'] ?>"></label>
                <label>Имя: <input type="text" name="ima" value="<?= $worker['Ima'] ?>"></label>
                <label>Отчество: <input type="text" name="otchestvo" value="<?= $worker['Otchestvo'] ?>"></label>
                <label>Телефон: <input type="text" name="phone" value="<?= $worker['phone'] ?>"></label>

                <label>Дата зачисления: 
                    <input type="date" name="data_zachislenia" value="<?= isset($worker['data_zachislenia']) ? date('Y-m-d', strtotime($worker['data_zachislenia'])) : '' ?>">
                </label>

                <label>Паспорт: 
                    <input type="text" name="passport_series" value="<?= $worker['seria_pasporta'] ?>"> 
                    <input type="text" name="passport_number" value="<?= $worker['nomer_pasporta'] ?>">
                </label>
                <label>Кем выдан: <input type="text" name="passport_issued_by" value="<?= $worker['who_issue'] ?>"></label>
                <label>Дата выдачи паспорта: 
                    <input type="date" name="passport_issue_date" value="<?= isset($worker['when_issue']) ? date('Y-m-d', strtotime($worker['when_issue'])) : '' ?>">
                </label>

                <label>Адрес: 
                    <input type="text" name="street" value="<?= $worker['street'] ?>" placeholder="Улица"> 
                    <input type="text" name="house" value="<?= $worker['house'] ?>" placeholder="Дом"> 
                    <input type="text" name="apartment" value="<?= $worker['apartment'] ?>" placeholder="Квартира"> 
                    <input type="text" name="city" value="<?= $worker['city'] ?>" placeholder="Город">
                </label>

                <label>Отдел:
                    <select name="department">
                        <?php foreach ($departments as $dept): ?>
                            <option value="<?= $dept['id_departament'] ?>" <?= $worker['id_departament'] == $dept['id_departament'] ? 'selected' : '' ?>><?= $dept['department'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>

                <label>Должность:
                    <select name="job_title">
                        <?php foreach ($jobTitles as $job): ?>
                            <option value="<?= $job['id_jt'] ?>" <?= $worker['id_jt'] == $job['id_jt'] ? 'selected' : '' ?>><?= $job['Job_title'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>

                <label>Статус: 
                    <select id="statusSelect" name="status">
                        <?php foreach ($statuses as $status): ?>
                            <option value="<?= $status['id_dis'] ?>" <?= ($worker['statys'] == $status['id_dis']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($status['dismissed']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label>Дата начала отсутствия:
                    <input type="date" name="fst_date" value="<?= isset($worker['fst_date']) ? date('Y-m-d', strtotime($worker['fst_date'])) : '' ?>">
                </label>

                <label>Дата окончания отсутствия:
                    <input type="date" name="last_date" value="<?= isset($worker['last_date']) ? date('Y-m-d', strtotime($worker['last_date'])) : '' ?>">
                </label>
                <button type="submit" name="update">Сохранить</button>
            </form>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>