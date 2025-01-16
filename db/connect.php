<?php
$servername = "localhost";
$username = "root";
$password = "";
$db = "tz2";

$conn = new mysqli($servername, $username, $password,$db);

if ($conn->connect_error) {
  die("Ошибка подключения: " . $conn->connect_error);
}
?>