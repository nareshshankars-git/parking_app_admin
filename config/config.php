<?php
if(!isset($_SESSION))
        session_start();
	date_default_timezone_set("Asia/Calcutta");
	$server="localhost";
	$username="root";
	$password="root";
	$db="parking_app";
	global $conn;
	$conn = new PDO('mysql:host='.$server.';dbname='.$db.';charset=utf8mb4', $username, $password);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
