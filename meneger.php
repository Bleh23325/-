<?php

require_once './db/connect.php';

// Получение списка отделов
$departmentResults = mysqli_query($conn, "SELECT id_departament, department FROM department");
$departments = mysqli_fetch_all($departmentResults, MYSQLI_ASSOC);

// Получение списка должностей
$jobTitleResults = mysqli_query($conn, "SELECT id_jt, Job_title FROM Job_title");
$jobTitles = mysqli_fetch_all($jobTitleResults, MYSQLI_ASSOC);

// Обработка фильтрации
$selectedDepartment = isset($_GET['department']) ? $_GET['department'] : '';
$selectedJobTitle = isset($_GET['job_title']) ? $_GET['job_title'] : '';
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

$query = "SELECT w.*, a.*, iw.*, dw.*, d.department, jt.Job_title, dis.dismissed, ta.fst_date, ta.last_date 
    FROM Worker w
    JOIN address a ON w.id_w = a.Worker
    JOIN data_worker dw ON w.id_w = dw.Worker
    JOIN department d ON w.department = d.id_departament
    JOIN info_worker iw ON w.id_w = iw.Worker
    JOIN Job_title jt ON w.jod_title = jt.id_jt
    JOIN Dismissed dis ON w.dismissed = dis.id_dis
    LEFT JOIN time_of_absence ta ON w.id_w = ta.Worker
    WHERE 1=1";

if ($selectedDepartment) {
    $query .= " AND w.department = " . intval($selectedDepartment);
}

if ($selectedJobTitle) {
    $query .= " AND w.jod_title = " . intval($selectedJobTitle);
}

if ($searchTerm) {
    $searchTerm = mysqli_real_escape_string($conn, $searchTerm);
    $query .= " AND (w.Familia LIKE '%$searchTerm%' OR w.Ima LIKE '%$searchTerm%' OR w.Otchestvo LIKE '%$searchTerm%')";
}

$results = mysqli_query($conn, $query);

if (!$results) {
    die("Ошибка запроса: " . mysqli_error($conn));
}

$tabl = array();
while ($row = mysqli_fetch_assoc($results)) {
    $workerId = $row['id_w'];
    if (!isset($tabl[$workerId])) {
        $tabl[$workerId] = $row;
        $tabl[$workerId]['absences'] = [];
    }
    if ($row['fst_date'] && $row['last_date']) {
        $tabl[$workerId]['absences'][] = ['start' => $row['fst_date'], 'end' => $row['last_date']];
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Учёт работников</title>
    <link rel="stylesheet" href="/css/style.css">
    <style>
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            width: 400px;
            max-width: 90%;
            text-align: center;
            position: relative;
        }

        .modal-content .close {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 24px;
            cursor: pointer;
            color: #333;
            transition: color 0.3s ease;
        }

        .modal-content .close:hover {
            color: #6a11cb;
        }
    </style>
</head>
<body>
    <h3>Работники: </h3>
    <div class="tabel">
        <div class="title">
            <div class="fined">
                <form method="GET" action="">
                    <input type="text" name="search" placeholder="Поиск по ФИО" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" />
                    
                    
                    <input type="submit" value="Фильтровать" />
                </form>
            </div>
            <table>
            <tr>
                <td>ФИО</td>
                <td>
                    <form method="GET" action="">
                        <select name="department" onchange="this.form.submit()">
                            <option value="">Все отделы</option>
                            <?php foreach ($departments as $department): ?>
                                <option value="<?= $department['id_departament'] ?>" <?= $selectedDepartment == $department['id_departament'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($department['department']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                </td>
                <td>
                    <form method="GET" action="">
                        <select name="job_title" onchange="this.form.submit()">
                            <option value="">Все должности</option>
                            <?php foreach ($jobTitles as $jobTitle): ?>
                                <option value="<?= $jobTitle['id_jt'] ?>" <?= $selectedJobTitle == $jobTitle['id_jt'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($jobTitle['Job_title']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                </td>
                <td>Размер зарплаты</td>
                <td>Дата принятия на работу</td>
                <td>Статус работника</td>
                <td>Персональные данные</td>
                <td>Личные данные</td> 
            </tr>
                <?php foreach ($tabl as $data): ?>             
                <tr>
                    <td><?= $data['Familia']. " ". $data['Ima']. " ". $data['Otchestvo'] ?></td>
                    <td><?= $data['department']?></td>                    
                    <td><?= $data['Job_title']?></td>                    
                    <td><?= $data['zarplata']?></td>                    
                    <td><?= $data['data_zachislenia']?></td>
                    <td>
                        <button onclick="showStatusDetails('<?= $data['dismissed'] ?>', <?= htmlspecialchars(json_encode($data['absences']), ENT_QUOTES, 'UTF-8') ?>)">
                            Посмотреть
                        </button>
                    </td>
                    <td>
                        <button onclick="showDetails('<?= $data['seria_pasporta']. " ". $data['nomer_pasporta'] ?>', '<?= $data['phone'] ?>')">
                            Посмотреть
                        </button>
                    </td>
                    <td>
                        <button onclick="showPersonalData('<?= $data['data_rojdenia']?>', '<?= $data['street']. " ". $data['house'] ?>')">
                            Посмотреть
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?> 
            </table>
        </div> 
    </div>
    <div id="statusModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeStatusModal()">&times;</span>
            <h3>Статус сотрудника</h3>
            <p id="statusInfo"></p>
            <div id="absenceDateInfo"></div>
        </div>
    </div>
    <div id="detailsModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3>Персональные данные сотрудника</h3>
            <p id="passportInfo"></p>
            <p id="phoneInfo"></p>
        </div>
    </div>

    <div id="personalDataModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closePersonalDataModal()">&times;</span>
            <h3>Личные данные сотрудника</h3>
            <p id="birthDateInfo"></p>
            <p id="addressInfo"></p>
        </div>
    </div>

    <script>
        function showStatusDetails(status, absences) {
            document.getElementById('statusInfo').textContent = "Статус: " + status;
            const absenceInfoDiv = document.getElementById('absenceDateInfo');
            absenceInfoDiv.innerHTML = "";

            if (absences.length > 0) {
                absences.forEach(absence => {
                    const p = document.createElement('p');
                    p.textContent = `Отсутствовал с ${absence.start} по ${absence.end}`;
                    absenceInfoDiv.appendChild(p);
                });
            } else {
                absenceInfoDiv.textContent = "Нет данных об отсутствии.";
            }

            document.getElementById('statusModal').style.display = 'flex';
        }

        function closeStatusModal() {
            document.getElementById('statusModal').style.display = 'none';
        }
        function showDetails(passport, phone) {
            document.getElementById('passportInfo').textContent = "Паспорт: " + passport;
            document.getElementById('phoneInfo').textContent = "Телефон: " + phone;
            document.getElementById('detailsModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('detailsModal').style.display = 'none';
        }

        function showPersonalData(birthDate, address) {
            document.getElementById('birthDateInfo').textContent = "Дата рождения: " + birthDate;
            document.getElementById('addressInfo').textContent = "Адрес: " + address;
            document.getElementById('personalDataModal').style.display = 'flex';
        }

        function closePersonalDataModal() {
            document.getElementById('personalDataModal').style.display = 'none';
        }
    </script>
</body>
</html>