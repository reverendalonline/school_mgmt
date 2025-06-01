<?php
require_once "../config.php";
include "../header.php";

// Handle add subject form
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['subject_name'])) {
    $name = trim($_POST['subject_name']);
    $code = trim($_POST['subject_code']);

    $stmt = $conn->prepare("INSERT INTO subjects (subject_name, subject_code) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $code);
    if ($stmt->execute()) {
        $message = "<div class='alert alert-success'>Subject added successfully.</div>";
    } else {
        $message = "<div class='alert alert-danger'>Error adding subject.</div>";
    }
}

// Fetch all subjects
$result = $conn->query("SELECT * FROM subjects ORDER BY subject_name ASC");
?>

<div class="container-fluid">
    <h2 class="mb-4">Manage Subjects</h2>
    <?= $message ?>

    <div class="row">
        <!-- Subject Form -->
        <div class="col-md-5">
            <form method="POST" class="card p-3 shadow-sm">
                <h5>Add New Subject</h5>
                <div class="mb-2">
                    <label class="form-label">Subject Name</label>
                    <input type="text" name="subject_name" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label class="form-label">Subject Code (optional)</label>
                    <input type="text" name="subject_code" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary">Add Subject</button>
            </form>
        </div>

        <!-- Subject List -->
        <div class="col-md-7">
            <div class="card p-3 shadow-sm">
                <h5>All Subjects</h5>
                <table class="table table-bordered table-striped mt-2">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Subject Name</th>
                            <th>Code</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= htmlspecialchars($row['subject_name']) ?></td>
                                <td><?= htmlspecialchars($row['subject_code']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include "../footer.php"; ?>
