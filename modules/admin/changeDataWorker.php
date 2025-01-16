<?php
// Подключение к базе данных
$host = 'localhost';
$db = 'tz2';
$user = 'root';
$pass = '';

$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// Обработка поиска
$searchFamilia = isset($_POST['searchFamilia']) ? $_POST['searchFamilia'] : '';
$searchIma = isset($_POST['searchIma']) ? $_POST['searchIma'] : '';
$searchOtchestvo = isset($_POST['searchOtchestvo']) ? $_POST['searchOtchestvo'] : '';

// Инициализация переменной для результатов
$workers = [];

// Подготовка SQL-запроса с фильтрацией, если есть поисковые параметры
if ($searchFamilia || $searchIma || $searchOtchestvo) {
    $sql = "SELECT w.id_w, w.Familia, w.Ima, w.Otchestvo, a.street, a.house, iw.phone, dw.seria_pasporta, dw.nomer_pasporta 
            FROM Worker w
            JOIN address a ON w.id_w = a.Worker
            JOIN info_worker iw ON w.id_w = iw.Worker
            JOIN data_worker dw ON w.id_w = dw.Worker
            WHERE 1=1 AND w.dismissed!='1'";

    $params = [];
    if ($searchFamilia) {
        $sql .= " AND w.Familia LIKE ?";
        $params[] = "%$searchFamilia%";
    }
    if ($searchIma) {
        $sql .= " AND w.Ima LIKE ?";
        $params[] = "%$searchIma%";
    }
    if ($searchOtchestvo) {
        $sql .= " AND w.Otchestvo LIKE ?";
        $params[] = "%$searchOtchestvo%";
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $workers = $stmt->fetchAll();
}

// Обработка формы редактирования
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    foreach ($_POST['id'] as $key => $id) {
        $street = $_POST['street'][$id];
        $house = $_POST['house'][$id];
        $phone = $_POST['phone'][$id];
        $seria_pasporta = $_POST['seria_pasporta'][$id];
        $nomer_pasporta = $_POST['nomer_pasporta'][$id];

        // Обновление данных в таблицах
        $stmt1 = $pdo->prepare("UPDATE address SET street = ?, house = ? WHERE Worker = ?");
        $stmt1->execute([$street, $house, $id]);

        $stmt2 = $pdo->prepare("UPDATE info_worker SET phone = ? WHERE Worker = ?");
        $stmt2->execute([$phone, $id]);

        $stmt3 = $pdo->prepare("UPDATE data_worker SET seria_pasporta = ?, nomer_pasporta = ? WHERE Worker = ?");
        $stmt3->execute([$seria_pasporta, $nomer_pasporta, $id]);
    }

    echo "Данные успешно обновлены!";
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Изменение данных работников</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
    <script>
    $(document).ready(function() {
        // Маска для телефона
        $('input[name^="phone"]').inputmask({
            mask: "+7 (999) 999-99-99",
            placeholder: " "
        });
        
        // Маска для серии паспорта
        $('input[name^="seria_pasporta"]').inputmask({
            mask: "9999",
            placeholder: " "
        });

        // Маска для номера паспорта
        $('input[name^="nomer_pasporta"]').inputmask({
            mask: "999999",
            placeholder: " "
        });
    });
    </script>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>
    <div class="navigation">
        <a href="/admin.php">Главная</a>
        <a href="Insert.php">Добавить работника</a>
        <a href="dataWorker.php">Добавить данные работников</a>
        <a href="dataDepartment.php">Добавить отдел</a>
        <a href="JobTitle.php">Добавить должность</a>
        <a href="changeWorker.php">Изменить работника</a>
        <a href="FindeWorker.php">Найти работника</a>
        <a href="changeDataWorker.php">Изменить данные работника</a>
    </div>
    <h1>Изменение данных работников</h1>

    <!-- Форма поиска -->
    <form method="POST">
        <label for="searchFamilia">Фамилия:</label>
        <input type="text" name="searchFamilia" value="<?php echo htmlspecialchars($searchFamilia); ?>">
        
        <label for="searchIma">Имя:</label>
        <input type="text" name="searchIma" value="<?php echo htmlspecialchars($searchIma); ?>">
        
        <label for="searchOtchestvo">Отчество:</label>
        <input type="text" name="searchOtchestvo" value="<?php echo htmlspecialchars($searchOtchestvo); ?>">
        
        <input type="submit" value="Поиск">
    </form>

    <!-- Таблица с результатами -->
    <?php if (!empty($workers)): ?>
        <form method="POST">
            <table>
                <tr>
                    <th>Фамилия</th>
                    <th>Имя</th>
                    <th>Отчество</th>
                    <th>Улица</th>
                    <th>Дом</th>
                    <th>Телефон</th>
                    <th>Серия паспорта</th>
                    <th>Номер паспорта</th>
                    <th>Действия</th>
                </tr>
                <?php foreach ($workers as $worker): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($worker['Familia']); ?></td>
                        <td><?php echo htmlspecialchars($worker['Ima']); ?></td>
                        <td><?php echo htmlspecialchars($worker['Otchestvo']); ?></td>
                        <td><input type="text" name="street[<?php echo $worker['id_w']; ?>]" value="<?php echo htmlspecialchars($worker['street']); ?>"></td>
                        <td><input type="number" name="house[<?php echo $worker['id_w']; ?>]" value="<?php echo htmlspecialchars($worker['house']); ?>"></td>
                        <td><input type="text" name="phone[<?php echo $worker['id_w']; ?>]" value="<?php echo htmlspecialchars($worker['phone']); ?>"></td>
                        <td><input type="text" name="seria_pasporta[<?php echo $worker['id_w']; ?>]" value="<?php echo htmlspecialchars($worker['seria_pasporta']); ?>"></td>
                        <td><input type="text" name="nomer_pasporta[<?php echo $worker['id_w']; ?>]" value="<?php echo htmlspecialchars($worker['nomer_pasporta']); ?>"></td>
                        <td>
                            <input type="hidden" name="id[]" value="<?php echo htmlspecialchars($worker['id_w']); ?>">
                            <input type="submit" value="Изменить">
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </form>
    <?php else: ?>
    <?php endif; ?>
</body>
</html>
