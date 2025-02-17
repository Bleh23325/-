<?php
require_once '../../db/connect.php';

$backupDir = '../../backups/';
if (!is_dir($backupDir)) {
    mkdir($backupDir, 0777, true);
}

$backupFile = $backupDir . 'backup_' . date('Y-m-d_H-i-s') . '.sql';
$host = "localhost";
$user = "root"; 
$password = ""; 
$dbname = "tz3";

$command = "mysqldump --user={$user} --password={$password} --host={$host} {$dbname} > {$backupFile}";

exec($command, $output, $returnVar);

if ($returnVar === 0) {
    echo json_encode(["success" => true, "file" => $backupFile]);
} else {
    echo json_encode(["success" => false, "error" => "Ошибка создания бэкапа"]);
}
?>
