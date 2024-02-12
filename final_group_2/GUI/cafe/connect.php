<?php
session_start();


$conn = new mysqli('localhost:3306','root','','project_sem1');
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
