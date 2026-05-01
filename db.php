<?php
$dbHost = '127.0.0.1';
$dbUser = 'root';
$dbPass = '';
$dbName = 'apex_task1';
$dbError = '';

// Disable mysqli exceptions so pages can render actionable setup guidance.
mysqli_report(MYSQLI_REPORT_OFF);

$conn = @mysqli_connect($dbHost, $dbUser, $dbPass);

if (!$conn) {
    $dbError = 'Database server connection failed: ' . mysqli_connect_error();
    return;
}

if (!mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS `$dbName`")) {
    $dbError = 'Unable to create database: ' . mysqli_error($conn);
    return;
}

if (!mysqli_select_db($conn, $dbName)) {
    $dbError = 'Unable to select database: ' . mysqli_error($conn);
    return;
}

mysqli_set_charset($conn, 'utf8mb4');

$createUsers = "
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB
";

$createProfiles = "
CREATE TABLE IF NOT EXISTS profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    password_length INT NOT NULL,
    role VARCHAR(50) NOT NULL,
    gender VARCHAR(20) NOT NULL,
    bio TEXT,
    interests VARCHAR(255),
    newsletter TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_profiles_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB
";

mysqli_query($conn, $createUsers);
mysqli_query($conn, $createProfiles);
?>