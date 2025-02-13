<?php
require_once './db/connect.php';

// Получение списка отделов
$departmentResults = mysqli_query($conn, "SELECT id_departament, department FROM department");
$departments = mysqli_fetch_all($departmentResults, MYSQLI_ASSOC);

// Получение списка должностей
$jobTitleResults = mysqli_query($conn, "SELECT id_jt, Job_title FROM Job_title");
$jobTitles = mysqli_fetch_all($jobTitleResults, MYSQLI_ASSOC);

// Получение списка статусов
$statusResults = mysqli_query($conn, "SELECT id_dis, dismissed FROM Dismissed");
$statuses = mysqli_fetch_all($statusResults, MYSQLI_ASSOC);

// Базовый SQL-запрос
$query = "SELECT w.*, a.*, iw.*, dw.*, d.department, d.id_departament, jt.Job_title, dis.dismissed, ta.fst_date, ta.last_date, id_dis
    FROM Worker w
    JOIN address a ON w.id_w = a.Worker
    JOIN data_worker dw ON w.id_w = dw.Worker
    JOIN department d ON w.department = d.id_departament
    JOIN info_worker iw ON w.id_w = iw.Worker
    JOIN Job_title jt ON w.jod_title = jt.id_jt
    LEFT JOIN time_of_absence ta ON w.id_w = ta.worker
    LEFT JOIN Dismissed dis ON ta.statys = dis.id_dis
    WHERE 1=1";

// Получение параметров фильтрации
$selectedDepartment = isset($_GET['department']) ? $_GET['department'] : '';
$selectedJobTitle = isset($_GET['job_title']) ? $_GET['job_title'] : '';
$selectedStatus = isset($_GET['status']) ? $_GET['status'] : '';
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// Добавляем фильтрацию по ОБЯЗАТЕЛЬНЫМ условиям: отдел, должность и статус
if ($selectedDepartment !== '') {
    $query .= " AND w.department = " . intval($selectedDepartment);
}
if ($selectedJobTitle !== '') {
    $query .= " AND w.jod_title = " . intval($selectedJobTitle);
}
if ($selectedStatus !== '') {
    $query .= " AND dis.id_dis = " . intval($selectedStatus);
}

// Добавляем необязательные параметры
if ($startDate && $endDate) {
    $query .= " AND (ta.fst_date BETWEEN '$startDate' AND '$endDate' OR ta.last_date BETWEEN '$startDate' AND '$endDate')";
}
if ($searchTerm) {
    $query .= " AND (w.Familia LIKE '%$searchTerm%' OR w.Ima LIKE '%$searchTerm%' OR w.Otchestvo LIKE '%$searchTerm%')";
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Поиск сотрудников</title>
    <link rel="stylesheet" href="/css/styleSearch.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<div class="header">
    <div class="logo">Учёт работников</div>
    <nav>
        <a href="/meneger.php">Главная</a>
        <a href="/insert.php">Добавить работника</a>
        <a href="/search_by_date.php">Умный поиск</a>
    </nav>
</div>

<h3>Фильтр сотрудников</h3>
<div class="filter-form">
    <form id="searchForm">
        <div class="input-container">
            <p>Начальная дата</p>
            <input type="date" id="startDate" name="start_date">
        </div>

        <div class="input-container">
            <p>Конечная дата</p>
            <input type="date" id="endDate" name="end_date">
        </div>

        <div class="input-container">
            <p>Статус</p>
            <select id="statusSelect" name="status">
            <option value="">Все статусы</option>
            <?php foreach ($statuses as $status): ?>
                <option value="<?= $status['id_dis'] ?>"><?= htmlspecialchars($status['dismissed']) ?></option>
            <?php endforeach; ?>
        </select>
        </div>

        <div class="input-container">
            <p>Отдел</p>
            <select id="departmentFilter" name="department">
            <option value="">Все отделы</option>
            <?php foreach ($departments as $department): ?>
                <option value="<?= $department['id_departament'] ?>"><?= htmlspecialchars($department['department']) ?></option>
            <?php endforeach; ?>
        </select>
        </div>

        <div class="input-container">
            <p>Должность</p>
            <select id="jobTitleFilter" name="job_title">
            <option value="">Все должности</option>
            <?php foreach ($jobTitles as $jobTitle): ?>
                <option value="<?= $jobTitle['id_jt'] ?>"><?= htmlspecialchars($jobTitle['Job_title']) ?></option>
            <?php endforeach; ?>
        </select>
        </div>

        <button type="submit">Найти</button>
    </form>
</div>

<h3 id="tableTitle" style="display: none;">Список сотрудников</h3>

<!-- Фильтр поиска по ФИО -->
<div id="fioSearchContainer" style="display: none;">
    <input type="text" id="fioSearch" placeholder="Поиск по ФИО">
</div>

<div class="tabel" id="workersTableContainer" style="display: none;">
    <table id="workersTable">
        <thead>
            <tr>
                <th>ФИО</th>
                <th>Должность</th>
                <th>Отдел</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<script>
$(document).ready(function() {
    $('#searchForm').submit(function(event) {
        event.preventDefault();
        fetchWorkers();
    });

    function fetchWorkers() {
        let search = $('#searchInput').val() || '';
        let startDate = $('#startDate').val() || '';
        let endDate = $('#endDate').val() || '';
        let status = $('#statusSelect').val() || '';
        let department = $('#departmentFilter').val() || '';
        let jobTitle = $('#jobTitleFilter').val() || '';

        // Проверяем, что хотя бы одно из полей "дата" или "статус" заполнено
        if (!startDate && !endDate && !status) {
            alert('Пожалуйста, выберите дату или статус для поиска.');
            return; // Прерываем выполнение, если оба поля пустые
        }

        let tableBody = $('#workersTable tbody');
        tableBody.empty(); // Очищаем таблицу перед новым поиском

        $.getJSON('fetch_workers.php', { search, start_date: startDate, end_date: endDate, status, department, job_title: jobTitle }, function(data) {
            if (!data || data.length === 0) {
                tableBody.append('<tr><td colspan="3" style="text-align:center;">Нет данных</td></tr>');
                $('#tableTitle').fadeIn();
                $('#workersTableContainer').fadeIn();
                $('#fioSearchContainer').hide();
            } else {
                data.forEach(worker => {
                    tableBody.append(`
                        <tr>
                            <td class="fio-column">${worker.Familia} ${worker.Ima} ${worker.Otchestvo}</td>
                            <td>${worker.Job_title}</td>
                            <td>${worker.department}</td>
                        </tr>
                    `);
                });

                $('#tableTitle').fadeIn();
                $('#workersTableContainer').fadeIn();
                $('#fioSearchContainer').fadeIn();
            }
        }).fail(function() {
            tableBody.append('<tr><td colspan="3" style="text-align:center;">Ошибка загрузки данных</td></tr>');
            $('#tableTitle').fadeIn();
            $('#workersTableContainer').fadeIn();
        });
    }

    // Поиск по ФИО внутри загруженной таблицы
    $('#fioSearch').on('input', function() {
        let searchValue = $(this).val().toLowerCase();

        $('#workersTable tbody tr').each(function() {
            let fullName = $(this).find('.fio-column').text().toLowerCase();
            let nameParts = fullName.split(' ');

            let matches = nameParts.some(part => part.includes(searchValue));
            $(this).toggle(matches);
        });
    });
});
</script>
</body>
</html>