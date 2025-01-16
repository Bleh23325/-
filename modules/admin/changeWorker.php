<?php
// Настройки подключения к базе данных
$host = 'localhost';
$db   = 'tz2';
$user = 'root'; // Измените на ваше имя пользователя
$pass = ''; // Измените на ваш пароль
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// Поиск данных по введенной информации
$workers = [];
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {
    $familia = $_POST['familia'] ?? '';
    $ima = $_POST['ima'] ?? '';
    $otchestvo = $_POST['otchestvo'] ?? '';

    $stmt = $pdo->prepare("SELECT w.*, a.street, a.house, dw.seria_pasporta, dw.nomer_pasporta, d.department, iw.phone, jt.Job_title
        FROM Worker w
        JOIN address a ON w.id_w = a.Worker
        JOIN data_worker dw ON w.id_w = dw.Worker
        JOIN department d ON w.department = d.id_departament
        JOIN info_worker iw ON w.id_w = iw.Worker
        JOIN Job_title jt ON w.jod_title = jt.id_jt WHERE Familia LIKE ? AND Ima LIKE ? AND Otchestvo LIKE ? AND dismissed!='1'");
    $stmt->execute(["%$familia%", "%$ima%", "%$otchestvo%"]);
    $workers = $stmt->fetchAll();
}

// Обновление записи
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $id = $_POST['id_w'];
    $familia = $_POST['new_familia'];
    $ima = $_POST['new_ima'];
    $otchestvo = $_POST['new_otchestvo'];
    $department = $_POST['new_department'];
    $jod_title = $_POST['new_jod_title'];
    $data_rojdenia = $_POST['data_rojdenia'];
    $zp= $_POST['zarplata'];
    $dz = $_POST['data_zachislenia'];
    $dis = $_POST['new_dismissed'];

    $stmt = $pdo->prepare("UPDATE Worker SET Familia = ?, Ima = ?, Otchestvo = ?, department = ?, jod_title = ?, data_rojdenia = ?, zarplata = ?,data_zachislenia = ?,dismissed = ? WHERE id_w = ?");
    $stmt->execute([$familia, $ima, $otchestvo, $department, $jod_title,$data_rojdenia,$zp,$dz,$dis, $id]);

    echo "Запись успешно обновлена!";
}

// Получение данных для выпадающих списков
$departments = $pdo->query("SELECT * FROM department")->fetchAll();
$jod_titles = $pdo->query("SELECT * FROM Job_title")->fetchAll();
$addresses = $pdo->query("SELECT * FROM address")->fetchAll();
$telefon = $pdo->query("SELECT * FROM info_worker")->fetchAll();
$passport = $pdo->query("SELECT * FROM data_worker")->fetchAll();
$dismissed = $pdo->query("SELECT * FROM Dismissed")->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Поиск и изменение работников</title>
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
<h1>Поиск работников</h1>
<form method="POST">
    <input type="text" name="familia" placeholder="Фамилия" required>
    <input type="text" name="ima" placeholder="Имя" required>
    <input type="text" name="otchestvo" placeholder="Отчество" required>
    <input type="submit" name="search" value="Поиск">
</form>

<?php if($workers): ?>
    <h2>Результаты поиска:</h2>
    <div class="tabel">
        <div class="title">
    <table >
            <?php foreach ($workers as $worker): ?>
                <tr>
                        <td><form method="POST">
                            <p>ФИО: </p>
                            <input type="hidden" name="id_w" value="<?= $worker['id_w'] ?>">
                            <input type="text" name="new_familia" value="<?= $worker['Familia'] ?>"><br>
                            <input type="text" name="new_ima" value="<?= $worker['Ima'] ?>"><br>
                            <input type="text" name="new_otchestvo" value="<?= $worker['Otchestvo'] ?>"></td>
                            <td> <p>Отдел: </p>
                            <select name="new_department">
                                <?php foreach($departments as $department): ?>
                                    <option value="<?= $department['id_departament'] ?>" <?= $department['id_departament'] == $worker['department'] ? 'selected' : '' ?>>
                                        <?= $department['department'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select></td>
                            <td> <p>Должность: </p>
                                 <select name="new_jod_title">
                                <?php foreach($jod_titles as $jod_title): ?>
                                    <option value="<?= $jod_title['id_jt'] ?>" <?= $jod_title['id_jt'] == $worker['jod_title'] ? 'selected' : '' ?>>
                                        <?= $jod_title['Job_title'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select></td>
                            <td> <p>Дата рождения: </p>
                             <input type="date" name="data_rojdenia" value="<?= $worker['data_rojdenia'] ?>">
                            </td>
                            <td> <p>Зарплата: </p>
                             <input type="text" name="zarplata" value="<?= $worker['zarplata'] ?>">
                            </td>
                            <td> <p>Дата зачисления: </p>
                             <input type="date" name="data_zachislenia" value="<?= $worker['data_zachislenia'] ?>">
                            </td>
                            </select></td>
                            <td><p>Статус работника: </p>
                                <select name="new_dismissed">
                                <?php foreach($dismissed as $Dismissed): ?>
                                    <option value="<?= $Dismissed['id_dis'] ?>" <?= $Dismissed['id_dis'] == $worker['dismissed'] ? 'selected' : '' ?>>
                                        <?= $Dismissed['dismissed'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select></td>
                            </tr>
                            <input type="submit" name="update" value="Обновить">
                            
                        </form>
                </tr>
            <?php endforeach; ?>
    </table>
    </div>
    </div>
<?php endif; ?>

</body>
</html>