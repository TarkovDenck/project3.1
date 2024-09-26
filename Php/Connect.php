<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$mysqli = new mysqli('localhost', 'root', '', 'pcprebuild', 3306); // you can omit the last argument
$mysqli->set_charset('utf8mb4'); // always set the charset
?>