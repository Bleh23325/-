<?php

require_once './db/connect.php';

// Получение списка отделов
$departmentResults = mysqli_query($conn, "SELECT id_departament, department FROM department");
$departments = mysqli_fetch_all($departmentResults, MYSQLI_ASSOC);

// Получение списка должностей
$jobTitleResults = mysqli_query($conn, "SELECT id_jt, Job_title FROM Job_title");
$jobTitles = mysqli_fetch_all($jobTitleResults, MYSQLI_ASSOC);

// Получение списка статусов увольнения
$dismissedResults = mysqli_query($conn, "SELECT id_dis, dismissed FROM Dismissed");
$Dismissed = mysqli_fetch_all($dismissedResults, MYSQLI_ASSOC);

// Обработка фильтрации
$selectedDepartment = isset($_GET['department']) ? $_GET['department'] : '';
$selectedJobTitle = isset($_GET['job_title']) ? $_GET['job_title'] : '';
$selectedDismissed = isset($_GET['dismissed']) ? $_GET['dismissed'] : '';
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Формирование основного запроса
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

// Поиск работников по ФИО
$searchingByName = !empty($searchTerm);
if ($searchingByName) {
    $searchTerm = mysqli_real_escape_string($conn, $searchTerm);
    $searchWords = explode(' ', $searchTerm);
    $searchConditions = [];

    foreach ($searchWords as $word) {
        $searchConditions[] = "(w.Familia LIKE '%$word%' OR w.Ima LIKE '%$word%' OR w.Otchestvo LIKE '%$word%')";
    }

    if (!empty($searchConditions)) {
        $query .= " AND (" . implode(" AND ", $searchConditions) . ")";
    }
}

if (!$searchingByName) {
    if ($selectedDepartment) {
        $query .= " AND w.department = " . intval($selectedDepartment);
    }

    if ($selectedJobTitle) {
        $query .= " AND w.jod_title = " . intval($selectedJobTitle);
    }

    if ($selectedDismissed) {
        // Фильтруем по конкретному статусу
        $query .= " AND dis.id_dis = " . intval($selectedDismissed);
    } else {
        // Исключаем уволенных, если статус не выбран
        $query .= " AND (dis.id_dis IS NULL OR dis.dismissed != 'Уволен')";
    }
}

// Выполнение запроса
$results = mysqli_query($conn, $query);

if (!$results) {
    die("Ошибка запроса: " . mysqli_error($conn));
}

// Обработка результатов
$tabl = [];
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
</head>
<body>
    <div class="header">
        <div class="logo">Учёт работников</div>
        <nav>
            <a href="/admin.php">Главная</a>
            <a href="./modules/admin/insert.php">Добавить работника</a>
            <a href="./modules/admin//search_by_date.php">Умный поиск</a>
            <a href="./modules/admin//edit_worker.php">Изменить работника</a>
            <a href="./modules/admin/job_title.php">Должности</a>
            <a href="./modules/admin/department.php">Отделы</a>
        </nav>
    </div>
    <h3>Работники: </h3>
    <div class="tabel">
        <div class="title">
            <div class="fined">
                <form method="GET" action="">
                    <input type="text" name="search" placeholder="Поиск по ФИО" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" />
                    <input type="submit" value="Найти" />
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
                <td>
                    <form method="GET" action="">
                        <select name="dismissed" onchange="this.form.submit()">
                            <option value="">Все статусы</option>
                            <?php foreach ($Dismissed as $dismissedItem): ?>
                                <option value="<?= $dismissedItem['id_dis'] ?>" <?= $selectedDismissed == $dismissedItem['id_dis'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($dismissedItem['dismissed']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                </td>

                <td>Персональные данные</td>
            </tr>
                <?php foreach ($tabl as $data): ?>             
                <tr>
                    <td><?= $data['Familia']. " ". $data['Ima']. " ". $data['Otchestvo'] ?></td>
                    <td><?= $data['department']?></td>                    
                    <td><?= $data['Job_title']?></td>                    
                    <td>
                        <div class="hidden-data-container">
                            <span class="hidden-icon">Показать</span>
                            <span class="hidden-data"><?= htmlspecialchars($data['zarplata']) ?> ₽</span>
                        </div>
                    </td>                   
                    <td><?= $data['data_zachislenia']?></td>
                    <td>
                        <button onclick="showStatusDetails('<?= $data['dismissed'] ?>', <?= htmlspecialchars(json_encode($data['absences']), ENT_QUOTES, 'UTF-8') ?>)">
                            Посмотреть
                        </button>
                    </td>
                    <td>
                        <button onclick="showContactDetails(
                            '<?= $data['seria_pasporta'] ?>', 
                            '<?= $data['nomer_pasporta'] ?>', 
                            '<?= $data['who_issue'] ?>', 
                            '<?= $data['when_issue'] ?>', 
                            '<?= $data['phone'] ?>', 
                            '<?= $data['data_rojdenia'] ?>', 
                            '<?= $data['city'] ?>',
                            '<?= $data['street'] ?>', 
                            '<?= $data['house'] ?>',
                            '<?= $data['apartment'] ?>'
                        )">Посмотреть</button>
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
    <div id="contactDetailsModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeContactDetailsModal()">&times;</span>
            <h3>Контактные данные сотрудника</h3>
            <p id="passportInfo"></p>
            <p id="passportIssuedInfo"></p>
            <p id="phoneInfo"></p>
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
                absences.sort((a, b) => new Date(b.end || b.start) - new Date(a.end || a.start));

                const lastAbsence = absences[0];

                const p = document.createElement('p');
                if (lastAbsence.end === null || lastAbsence.end === "" || typeof lastAbsence.end === "undefined") {
                    p.textContent = `С ${lastAbsence.start}`;
                } else {
                    p.textContent = `С ${lastAbsence.start} по ${lastAbsence.end}`;
                }
                absenceInfoDiv.appendChild(p);
            } else {
                absenceInfoDiv.textContent = "Нет данных об отсутствии.";
            }

            document.getElementById('statusModal').style.display = 'flex';
        }

        function closeStatusModal() {
            document.getElementById('statusModal').style.display = 'none';
        }

        function showContactDetails(passportSeria, passportNumber, passportIssuedBy, passportIssuedDate, phone, birthDate, city, street, house, apartment) {
            document.getElementById('passportInfo').textContent = `Паспорт: Серия ${passportSeria}, Номер ${passportNumber}`;
            document.getElementById('passportIssuedInfo').textContent = `Выдан: ${passportIssuedBy}, ${passportIssuedDate}`;
            document.getElementById('phoneInfo').textContent = `Телефон: ${phone}`;
            document.getElementById('birthDateInfo').textContent = `Дата рождения: ${birthDate}`;
            document.getElementById('addressInfo').textContent = `Адрес: ${city}, ${street}, дом ${house}, кв. ${apartment}`;
            
            document.getElementById('contactDetailsModal').style.display = 'flex';
        }

        function closeContactDetailsModal() {
            document.getElementById('contactDetailsModal').style.display = 'none';
        }

            function showEditModal(id, familia, ima, otchestvo, department, jobTitle, passportSeria, passportNumber, phone, birthDate, addressStreet, addressHouse, dismissed) {
        document.getElementById('editWorkerId').value = id;
        document.getElementById('editFamilia').value = familia;
        document.getElementById('editIma').value = ima;
        document.getElementById('editOtchestvo').value = otchestvo;
        document.getElementById('editDepartment').value = department;
        document.getElementById('editJobTitle').value = jobTitle;
        document.getElementById('editPassportSeria').value = passportSeria;
        document.getElementById('editPassportNumber').value = passportNumber;
        document.getElementById('editPhone').value = phone;
        document.getElementById('editBirthDate').value = birthDate;
        document.getElementById('editAddressStreet').value = addressStreet;
        document.getElementById('editAddressHouse').value = addressHouse;
        document.getElementById('editDismissed').value = dismissed;
        document.getElementById('editModal').style.display = 'flex';
    }

    function closeEditModal() {
        document.getElementById('editModal').style.display = 'none';
    }
    document.getElementById('editForm').addEventListener('submit', function(event) {
        event.preventDefault();
        
        const formData = new FormData(this); 

        fetch('edit_worker.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json()) 
        .then(data => {
            if (data.success) {
                alert('Данные успешно сохранены!');
                location.reload(); 
            } else {
                alert('Ошибка сохранения данных: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Ошибка:', error);
            alert('Произошла ошибка при сохранении данных.');
        });

        closeEditModal(); 
    });
    </script>
</body>
</html>