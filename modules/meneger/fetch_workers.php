<?php
require_once '../../db/connect.php';

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

// Флаг наличия фильтров по отделу, должности и статусу
$filtersApplied = false;

// Если указаны отдел, должность или статус, они должны соответствовать
if (!empty($selectedDepartment) || !empty($selectedJobTitle) || !empty($selectedStatus)) {
    $filtersApplied = true;
    
    $conditions = [];
    if (!empty($selectedDepartment)) {
        $conditions[] = "w.department = " . intval($selectedDepartment);
    }
    if (!empty($selectedJobTitle)) {
        $conditions[] = "w.jod_title = " . intval($selectedJobTitle);
    }
    if (!empty($selectedStatus)) {
        $conditions[] = "dis.id_dis = " . intval($selectedStatus);
    }

    // Объединяем условия
    if (!empty($conditions)) {
        $query .= " AND (" . implode(" AND ", $conditions) . ")";
    }
}

// Добавляем необязательные параметры (ФИО, даты)
if (!empty($startDate) && !empty($endDate)) {
    $query .= " AND (ta.fst_date BETWEEN '$startDate' AND '$endDate' OR ta.last_date BETWEEN '$startDate' AND '$endDate')";
}
if (!empty($searchTerm)) {
    $query .= " AND (w.Familia LIKE '%$searchTerm%' OR w.Ima LIKE '%$searchTerm%' OR w.Otchestvo LIKE '%$searchTerm%')";
}

// Выполнение запроса
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Если были выбраны отдел, должность или статус, но данных нет — показываем "Нет данных"
if ($filtersApplied && empty($data)) {
    echo json_encode([]);
} else {
    echo json_encode($data);
}
?>