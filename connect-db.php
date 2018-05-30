<?php

$server = 'localhost';
$user = 'root';
$pass = 'root';
$db = 'team';
$port = '3307';

$mysqli = new mysqli($server, $user, $pass, $db, $port);
mysqli_report(MYSQLI_REPORT_ERROR);
?>