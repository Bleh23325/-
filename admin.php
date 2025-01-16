<?php

require_once './db/connect.php';

// Получение списка отделов
$departmentResults = mysqli_query($conn, "SELECT id_departament, department FROM department");
$departments = mysqli_fetch_all($departmentResults, MYSQLI_ASSOC);

// Получение списка должностей
$jobTitleResults = mysqli_query($conn, "SELECT id_jt, Job_title FROM Job_title");
$jobTitles = mysqli_fetch_all($jobTitleResults, MYSQLI_ASSOC);

// Обработка фильтрации
$selectedDepartment = isset($_POST['department']) ? $_POST['department'] : '';
$selectedJobTitle = isset($_POST['job_title']) ? $_POST['job_title'] : '';


$query = "SELECT w.*,a.*,iw.*,dw.*, a.street, a.house, dw.seria_pasporta, dw.nomer_pasporta, d.department, iw.phone, jt.Job_title,
    dis.dismissed 
    FROM Worker w
    JOIN address a ON w.id_w = a.Worker
    JOIN data_worker dw ON w.id_w = dw.Worker
    JOIN department d ON w.department = d.id_departament
    JOIN info_worker iw ON w.id_w = iw.Worker
    JOIN Job_title jt ON w.jod_title = jt.id_jt
    JOIN Dismissed dis ON w.dismissed = dis.id_dis WHERE 1=1";

if ($selectedDepartment) {
    $query .= " AND w.department = " . intval($selectedDepartment);
}

if ($selectedJobTitle) {
    $query .= " AND w.jod_title = " . intval($selectedJobTitle);
}

$results = mysqli_query($conn, $query);

if (!$results) {
    die("Ошибка запроса: " . mysqli_error($conn));
}

$tabl = array();
while ($row = mysqli_fetch_assoc($results)) {
    $tabl[] = $row;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Данные работников</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="navigation">
            <a href="admin.php">Главная</a>
            <a href="./modules/admin/Insert.php">Добавить работника</a>
            <a href="./modules/admin/dataWorker.php">Добавить данные работников</a>
            <a href="./modules/admin/dataDepartment.php">Добавить отдел</a>
            <a href="./modules/admin/JobTitle.php">Добавить должность</a>
            <a href="./modules/admin/changeWorker.php">Изменить работника</a>
            <a href="./modules/admin/FindeWorker.php">Найти работника</a>
            <a href="./modules/admin/changeDataWorker.php">Изменить данные работника</a>
        </div>
        <!-- Форма для фильтрации -->
         <h3>Сортировка: </h3>
        <form method="POST" action="">
            <select name="department">
                <option value="">Все отделы</option>
                <?php foreach ($departments as $department): ?>
                    <option value="<?= $department['id_departament'] ?>" <?= ($selectedDepartment == $department['id_departament']) ? 'selected' : '' ?>>
                        <?= $department['department'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        <select name="job_title">
            <option value="">Все должности</option>
            <?php foreach ($jobTitles as $jobTitle): ?>
                <option value="<?= $jobTitle['id_jt'] ?>" <?= ($selectedJobTitle == $jobTitle['id_jt']) ? 'selected' : '' ?>>
                    <?= $jobTitle['Job_title'] ?>
                </option>
            <?php endforeach; ?>
        </select>
    <button type="submit">Фильтровать</button>
    <h3>Работники: </h3>
</form>
    <div class="tabel">
        <div class="title">
            <table>
                <!-- заглавие -->
                <tr>
                    <td>ФИО</td>
                    <td>Дата рождения</td>
                    <td>Паспор</td>
                    <td>Телефон</td>
                    <td>Адрес проживания</td>
                    <td>Отдел</td>
                    <td>Должност</td>
                    <td>Размер зарплат</td>
                    <td>Дата принятия на работу</td>
                    <td>Статус работника</td>
                </tr>
            <!-- остальная информация -->
            <?php foreach ($tabl as $data): ?>             
            <tr>
                <td><?= $data['Familia']. " ". $data['Ima']. " ". $data['Otchestvo'] ?></td>
                <td><?= $data['data_rojdenia']?></td>
                <td><?= $data['seria_pasporta']. " ". $data['nomer_pasporta'] ?></td>                    
                <td><?= $data['phone']?></td>            
                <td><?= $data['street']. " ". $data['house'] ?></td>                      
                <td><?= $data['department']?></td>                    
                <td><?= $data['Job_title']?></td>                    
                <td><?= $data['zarplata']?></td>                    
                <td><?= $data['data_zachislenia']?></td>
                <td><?= $data['dismissed']?></td>
            </tr>
            <?php endforeach; ?> 
            </table>
        </div> 
    </div>
</body>
</html>