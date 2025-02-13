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

// Основной запрос на выборку данных
$query = "SELECT w.*, a.*, iw.*, dw.*, d.department, jt.Job_title, dis.dismissed, ta.fst_date, ta.last_date, ta.statys
    FROM Worker w
    JOIN address a ON w.id_w = a.Worker
    JOIN data_worker dw ON w.id_w = dw.Worker
    JOIN department d ON w.department = d.id_departament
    JOIN info_worker iw ON w.id_w = iw.Worker
    JOIN Job_title jt ON w.jod_title = jt.id_jt
    LEFT JOIN time_of_absence ta ON w.id_w = ta.worker
    LEFT JOIN Dismissed dis ON ta.statys = dis.id_dis";

$results = mysqli_query($conn, $query);
$workers = mysqli_fetch_all($results, MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактирование работников</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <h3>Список работников</h3>
    <table>
        <tr>
            <th>ФИО</th>
            <th>Отдел</th>
            <th>Должность</th>
            <th>Зарплата</th>
            <th>Дата зачисления</th>
            <th>Дата отсутствия (начало - конец)</th>
            <th>Статус увольнения</th>
            <th>Действия</th>
        </tr>
        <?php foreach ($workers as $worker): ?>
        <tr>
            <td><?= $worker['Familia'] . " " . $worker['Ima'] . " " . $worker['Otchestvo'] ?></td>
            <td><?= $worker['department'] ?></td>
            <td><?= $worker['Job_title'] ?></td>
            <td><?= $worker['zarplata'] ?> ₽</td>
            <td><?= $worker['data_zachislenia'] ?></td>
            <td><?= $worker['fst_date'] && $worker['last_date'] ? $worker['fst_date'] . " - " . $worker['last_date'] : "Нет данных" ?></td>
            <td><?= $worker['dismissed'] ?: "Не уволен" ?></td>
            <td><button onclick="editWorker(<?= htmlspecialchars(json_encode($worker), ENT_QUOTES, 'UTF-8') ?>)">Изменить</button></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <!-- Модальное окно для редактирования -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditModal()">&times;</span>
            <h3>Редактирование данных</h3>
            <form id="editForm" method="POST" action="edit_worker.php">
                <input type="hidden" name="id_w" id="editWorkerId">
                <label>Фамилия:</label>
                <input type="text" name="Familia" id="editFamilia">
                <label>Имя:</label>
                <input type="text" name="Ima" id="editIma">
                <label>Отчество:</label>
                <input type="text" name="Otchestvo" id="editOtchestvo">
                <label>Зарплата:</label>
                <input type="number" name="zarplata" id="editSalary">
                <label>Дата зачисления:</label>
                <input type="date" name="data_zachislenia" id="editHireDate">
                <label>Дата отсутствия (начало - конец):</label>
                <input type="date" name="fst_date" id="editAbsenceStart">
                <input type="date" name="last_date" id="editAbsenceEnd">
                <label>Статус увольнения:</label>
                <select name="dismissed" id="editDismissed">
                    <?php foreach ($Dismissed as $item): ?>
                        <option value="<?= $item['id_dis'] ?>"><?= htmlspecialchars($item['dismissed']) ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">Сохранить</button>
            </form>
        </div>
    </div>

    <script>
        function editWorker(worker) {
            document.getElementById('editWorkerId').value = worker.id_w;
            document.getElementById('editFamilia').value = worker.Familia;
            document.getElementById('editIma').value = worker.Ima;
            document.getElementById('editOtchestvo').value = worker.Otchestvo;
            document.getElementById('editSalary').value = worker.zarplata;
            document.getElementById('editHireDate').value = worker.data_zachislenia;
            document.getElementById('editAbsenceStart').value = worker.fst_date || '';
            document.getElementById('editAbsenceEnd').value = worker.last_date || '';
            document.getElementById('editDismissed').value = worker.statys || '';
            document.getElementById('editModal').style.display = 'flex';
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        document.getElementById('editForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);
            fetch('edit_worker.php', { method: 'POST', body: formData })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Данные обновлены!');
                        location.reload();
                    } else {
                        alert('Ошибка: ' + data.error);
                    }
                })
                .catch(error => alert('Ошибка запроса: ' + error));
            closeEditModal();
        });
    </script>
</body>
</html>