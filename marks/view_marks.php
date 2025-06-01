<?php
require_once "../config.php";
include "../header.php";

$students = $conn->query("SELECT student_id, first_name, last_name, class_level FROM students ORDER BY last_name");

$marks = [];
$selected_student = null;
$term = "";
$year = "";

function calculate_grade($score) {
    if ($score >= 85) return "A";
    if ($score >= 75) return "B";
    if ($score >= 60) return "C";
    if ($score >= 50) return "D";
    return "F";
}

// When form is submitted
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['student_id'], $_GET['term'], $_GET['year'])) {
    $student_id = (int) $_GET['student_id'];
    $term = $_GET['term'];
    $year = $_GET['year'];

    // Get student info
    $stmt = $conn->prepare("SELECT * FROM students WHERE student_id = ?");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $selected_student = $res->fetch_assoc();

    // Get marks
    $stmt = $conn->prepare("SELECT m.*, s.subject_name FROM marks m JOIN subjects s ON m.subject_id = s.subject_id WHERE m.student_id = ? AND m.term = ? AND m.academic_year = ?");
    $stmt->bind_param("iss", $student_id, $term, $year);
    $stmt->execute();
    $marks = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
?>

<h2 class="mt-4">View Student Marks</h2>

<form method="GET" class="row g-3 mb-4">
    <div class="col-md-4">
        <label class="form-label">Student</label>
        <select name="student_id" class="form-select" required>
            <option value="">-- Select Student --</option>
            <?php while ($row = $students->fetch_assoc()): ?>
                <option value="<?= $row['student_id'] ?>" <?= isset($student_id) && $student_id == $row['student_id'] ? "selected" : "" ?>>
                    <?= htmlspecialchars($row['last_name'] . ", " . $row['first_name']) ?> (<?= $row['class_level'] ?>)
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="col-md-3">
        <label class="form-label">Term</label>
        <select name="term" class="form-select" required>
            <option value="">-- Select Term --</option>
            <option value="Term 1" <?= $term == "Term 1" ? "selected" : "" ?>>Term 1</option>
            <option value="Term 2" <?= $term == "Term 2" ? "selected" : "" ?>>Term 2</option>
            <option value="Term 3" <?= $term == "Term 3" ? "selected" : "" ?>>Term 3</option>
        </select>
    </div>

    <div class="col-md-3">
        <label class="form-label">Academic Year</label>
        <input type="text" name="year" class="form-control" placeholder="e.g. 2024/25" value="<?= htmlspecialchars($year) ?>" required>
    </div>

    <div class="col-md-2 d-flex align-items-end">
        <button type="submit" class="btn btn-primary w-100">View Marks</button>
    </div>
</form>

<?php if ($selected_student): ?>
    <h5>Results for <?= htmlspecialchars($selected_student['first_name'] . " " . $selected_student['last_name']) ?> — <?= htmlspecialchars($term) ?> <?= htmlspecialchars($year) ?></h5>

    <?php if (count($marks) > 0): ?>
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th>Subject</th>
                    <th>CAT 1</th>
                    <th>CAT 2</th>
                    <th>Exam</th>
                    <th>Total (100)</th>
                    <th>Grade</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($marks as $mark): 
                    $total = $mark['cat1'] + $mark['cat2'] + $mark['exam'];
                    $grade = calculate_grade($total);
                ?>
                    <tr>
                        <td><?= htmlspecialchars($mark['subject_name']) ?></td>
                        <td><?= number_format($mark['cat1'], 1) ?></td>
                        <td><?= number_format($mark['cat2'], 1) ?></td>
                        <td><?= number_format($mark['exam'], 1) ?></td>
                        <td><strong><?= number_format($total, 1) ?></strong></td>
                        <td><?= $grade ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="add_marks.php?student_id=<?= $selected_student['student_id'] ?>&term=<?= urlencode($term) ?>&year=<?= urlencode($year) ?>" class="btn btn-warning">Edit Marks</a>
    <?php else: ?>
        <div class="alert alert-info">No marks found for this student in the selected term/year.</div>
        <a href="add_marks.php?student_id=<?= $selected_student['student_id'] ?>&term=<?= urlencode($term) ?>&year=<?= urlencode($year) ?>" class="btn btn-success">Add Marks</a>
    <?php endif; ?>
<?php endif; ?>

<?php include "../footer.php"; ?>