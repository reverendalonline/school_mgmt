<style>
body {
    background-color: #f8f9fa;
    font-family: system-ui, sans-serif;
}
.card {
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 20px;
}
</style>
<?php
require_once "config.php";

// Fetch school settings (name, address, logo)
$school = [
    'name' => 'Cecile Preparatory School',
    'logo' => 'logo.png'
];

$sql = "SELECT school_name, logo_filename FROM settings WHERE id = 1 LIMIT 1";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    $school = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($school['name']) ?></title>
<!-- Link Bootstrap CSS -->
<link rel="stylesheet" href="/school_mgmt/css/bootstrap.min.css">

<!-- Navbar with logo -->
<img src="/school_mgmt/images/<?= htmlspecialchars($school['logo']) ?>" alt="Logo" style="height: 40px;">
    <style>
        body {
            padding-top: 70px;
        }
        .navbar-brand img {
            height: 40px;
            margin-right: 10px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="/school_mgmt/">
            <img src="/school_mgmt/images/<?= htmlspecialchars($school['logo']) ?>" alt="Logo">
            <?= htmlspecialchars($school['name']) ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="/school_mgmt/">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="/school_mgmt/students/list_students.php">Students</a></li>
                <li class="nav-item"><a class="nav-link" href="/school_mgmt/marks/view_marks.php">Marks</a></li>
                <li class="nav-item"><a class="nav-link" href="/school_mgmt/receipts/list_receipts.php">Receipts</a></li>
                <li class="nav-item"><a class="nav-link" href="/school_mgmt/settings.php">Settings</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
