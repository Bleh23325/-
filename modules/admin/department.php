<?php
require_once '../../db/connect.php';

// Добавление отдела
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_department'])) {
    $departmentName = trim($_POST['department_name']);
    if (!empty($departmentName)) {
        $stmt = $conn->prepare("INSERT INTO department (department) VALUES (?)");
        $stmt->bind_param("s", $departmentName);
        $stmt->execute();
        $stmt->close();
    }
}

// Обновление отдела
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_department'])) {
    $id = intval($_POST['department_id']);
    $newName = trim($_POST['new_department_name']);
    if (!empty($newName)) {
        $stmt = $conn->prepare("UPDATE department SET department = ? WHERE id_departament = ?");
        $stmt->bind_param("si", $newName, $id);
        $stmt->execute();
        $stmt->close();
    }
}

// Удаление отдела
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_department'])) {
    $id = intval($_POST['department_id']);
    $stmt = $conn->prepare("DELETE FROM department WHERE id_departament = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Получение списка отделов
$departments = mysqli_query($conn, "SELECT * FROM department")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление отделами</title>
    <link rel="stylesheet" href="/css/styleMDJ.css">
    <script>
        // Функция для поиска в таблице
        function searchDepartments() {
            let searchValue = document.getElementById("searchInput").value.toLowerCase();
            let table = document.getElementById("departmentTable");
            let rows = table.getElementsByTagName("tr");

            for (let i = 1; i < rows.length; i++) { // начинаем с 1, чтобы пропустить заголовок таблицы
                let cells = rows[i].getElementsByTagName("td");
                let departmentName = cells[0].textContent.toLowerCase(); // Название отдела

                // Если название отдела соответствует поисковому запросу, показываем строку, иначе скрываем
                if (departmentName.indexOf(searchValue) !== -1) {
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

<h2>Отделы</h2>

<!-- Форма поиска -->
<div class="search-container">
    <input type="text" id="searchInput" placeholder="Поиск отдела..." onkeyup="searchDepartments()">
</div>

<!-- Форма добавления отдела -->
<form method="POST">
    <input type="text" name="department_name" placeholder="Название отдела" required>
    <button type="submit" name="add_department">Добавить</button>
</form>

<!-- Таблица с отделами -->
<div id="tableContainer">
    <table border="1" id="departmentTable">
        <tr>
            <th>Название</th>
            <th>Действия</th>
        </tr>
        <?php foreach ($departments as $department): ?>
            <tr>
                <td><?= htmlspecialchars($department['department']) ?></td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="department_id" value="<?= $department['id_departament'] ?>">
                        <input type="text" name="new_department_name" placeholder="Новое название" required>
                        <button type="submit" name="edit_department">Изменить</button>
                    </form>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="department_id" value="<?= $department['id_departament'] ?>">
                        <button type="submit" name="delete_department" onclick="return confirm('Удалить отдел?')">Удалить</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

</body>
</html>
