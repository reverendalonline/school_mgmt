<?php
require_once "../config.php";
include "../header.php";

$result = $conn->query("SELECT * FROM students ORDER BY last_name, first_name");
?>

<h2 class="mt-4">Students</h2>

<a href="add_student.php" class="btn btn-primary mb-3">+ Add New Student</a>

<?php if ($result->num_rows > 0): ?>
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Class Level</th>
                    <th>Date of Birth</th>
                    <th>Gender</th>
                    <th>Admission Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $count = 1; ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $count++ ?></td>
                        <td><?= htmlspecialchars($row['last_name'] . ", " . $row['first_name']) ?></td>
                        <td><?= htmlspecialchars($row['class_level']) ?></td>
                        <td><?= htmlspecialchars($row['date_of_birth']) ?></td>
                        <td><?= htmlspecialchars($row['gender']) ?></td>
                        <td><?= htmlspecialchars($row['admission_date']) ?></td>
                        <td>
                            <a href="student_details.php?id=<?= $row['student_id'] ?>" class="btn btn-sm btn-info">View</a>
                            <a href="edit_student.php?id=<?= $row['student_id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="alert alert-info">No students found. Add one now.</div>
<?php endif; ?>

<?php include "../footer.php"; ?>
