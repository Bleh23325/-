<?php
require_once '../../db/connect.php';

// Добавление должности
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_job_title'])) {
    $jobTitle = trim($_POST['job_title_name']);
    if (!empty($jobTitle)) {
        $stmt = $conn->prepare("INSERT INTO Job_title (Job_title) VALUES (?)");
        $stmt->bind_param("s", $jobTitle);
        $stmt->execute();
        $stmt->close();
    }
}

// Обновление должности
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_job_title'])) {
    $id = intval($_POST['job_title_id']);
    $newName = trim($_POST['new_job_title_name']);
    if (!empty($newName)) {
        $stmt = $conn->prepare("UPDATE Job_title SET Job_title = ? WHERE id_jt = ?");
        $stmt->bind_param("si", $newName, $id);
        $stmt->execute();
        $stmt->close();
    }
}

// Удаление должности
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_job_title'])) {
    $id = intval($_POST['job_title_id']);
    $stmt = $conn->prepare("DELETE FROM Job_title WHERE id_jt = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Получение списка должностей
$jobTitles = mysqli_query($conn, "SELECT * FROM Job_title")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление должностями</title>
    <link rel="stylesheet" href="/css/styleMDJ.css">
    <script>
        // Функция для поиска в таблице
        function searchJobTitles() {
            let searchValue = document.getElementById("searchInput").value.toLowerCase();
            let table = document.getElementById("jobTitleTable");
            let rows = table.getElementsByTagName("tr");

            for (let i = 1; i < rows.length; i++) { // начинаем с 1, чтобы пропустить заголовок таблицы
                let cells = rows[i].getElementsByTagName("td");
                let jobTitle = cells[0].textContent.toLowerCase(); // Название должности

                // Если должность соответствует поисковому запросу, показываем строку, иначе скрываем
                if (jobTitle.indexOf(searchValue) !== -1) {
                    rows[i].style.display = "";
                } else {
                    rows[i].style.display = "none";
                }
            }
        }
    </script>
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

<h2>Должности</h2>

<!-- Форма поиска -->
<div class="search-container">
    <input type="text" id="searchInput" placeholder="Поиск должности..." onkeyup="searchJobTitles()">
</div>

<!-- Форма добавления должности -->
<form method="POST">
    <input type="text" name="job_title_name" placeholder="Название должности" required>
    <button type="submit" name="add_job_title">Добавить</button>
</form>

<!-- Таблица с должностями -->
<div id="tableContainer">
    <table border="1" id="jobTitleTable">
        <tr>
            <th>Название</th>
            <th>Действия</th>
        </tr>
        <?php foreach ($jobTitles as $jobTitle): ?>
            <tr>
                <td><?= htmlspecialchars($jobTitle['Job_title']) ?></td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="job_title_id" value="<?= $jobTitle['id_jt'] ?>">
                        <input type="text" name="new_job_title_name" placeholder="Новое название" required>
                        <button type="submit" name="edit_job_title">Изменить</button>
                    </form>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="job_title_id" value="<?= $jobTitle['id_jt'] ?>">
                        <button type="submit" name="delete_job_title" onclick="return confirm('Удалить должность?')">Удалить</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

</body>
</html>
