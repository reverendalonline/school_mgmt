<?php
require_once "../config.php";
include "../header.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='alert alert-danger'>Invalid student ID.</div>";
    include "../footer.php";
    exit;
}

$student_id = (int) $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM students WHERE student_id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<div class='alert alert-warning'>Student not found.</div>";
    include "../footer.php";
    exit;
}

$student = $result->fetch_assoc();
?>

<h2 class="mt-4">Student Details</h2>

<ul class="list-group mb-4">
    <li class="list-group-item"><strong>Student ID:</strong> <?= $student['student_id'] ?></li>
    <li class="list-group-item"><strong>Full Name:</strong> <?= htmlspecialchars($student['first_name'] . " " . $student['last_name']) ?></li>
    <li class="list-group-item"><strong>Date of Birth:</strong> <?= htmlspecialchars($student['date_of_birth']) ?></li>
    <li class="list-group-item"><strong>Gender:</strong> <?= htmlspecialchars($student['gender']) ?></li>
    <li class="list-group-item"><strong>Class Level:</strong> <?= htmlspecialchars($student['class_level']) ?></li>
    <li class="list-group-item"><strong>Admission Date:</strong> <?= htmlspecialchars($student['admission_date']) ?></li>
</ul>

<div class="mb-3">
    <a href="edit_student.php?id=<?= $student['student_id'] ?>" class="btn btn-warning">Edit Student</a>
    <a href="list_students.php" class="btn btn-secondary">Back to List</a>
</div>

<div class="mt-4">
    <h5>Marks</h5>
    <p>You can enter or view termly marks for this student from the <strong>Marks</strong> section.</p>
</div>

<?php include "../footer.php"; ?>
