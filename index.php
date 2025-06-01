<?php
require_once "config.php";
include "header.php";

// Get total counts
$total_students = $conn->query("SELECT COUNT(*) AS total FROM students")->fetch_assoc()['total'];
$total_receipts = $conn->query("SELECT COUNT(*) AS total FROM receipts")->fetch_assoc()['total'];
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>

    <!-- Content Row -->
    <div class="row">

        <!-- Students Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Students</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_students ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Receipts Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Receipts Issued</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_receipts ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-receipt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add More Cards If Needed -->
    </div>

    <!-- Optional: Add links to key pages -->
    <div class="row">
        <div class="col-md-4">
            <a href="students/list_students.php" class="btn btn-outline-primary btn-block">Manage Students</a>
        </div>
        <div class="col-md-4">
            <a href="marks/view_marks.php" class="btn btn-outline-success btn-block">View Marks</a>
        </div>
        <div class="col-md-4">
            <a href="settings.php" class="btn btn-outline-secondary btn-block">School Settings</a>
        </div>
    </div>

</div>
<!-- End of Page Content -->

<?php include "footer.php"; ?>
