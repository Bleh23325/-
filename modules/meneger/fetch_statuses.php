<?php
require_once '/db/connect.php';

$query = "SELECT id_dis, dismissed FROM Dismissed";
$result = mysqli_query($conn, $query);

$statuses = [];
while ($row = mysqli_fetch_assoc($result)) {
    $statuses[] = $row;
}

echo json_encode($statuses);
?>
