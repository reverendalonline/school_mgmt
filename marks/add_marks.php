<?php
require_once "../config.php";
include "../header.php";

if (!isset($_GET['student_id'], $_GET['term'], $_GET['year'])) {
    echo "<div class='alert alert-danger'>Missing required parameters.</div>";
    include "../footer.php";
    exit;
}

$student_id = (int) $_GET['student_id'];
$term = $_GET['term'];
$year = $_GET['year'];

$message = "";

// Get subjects
$subjects = [];
$res = $conn->query("SELECT * FROM subjects ORDER BY subject_name");
while ($row = $res->fetch_assoc()) {
    $subjects[$row['subject_id']] = $row['subject_name'];
}

// Get existing marks
$existing = [];
$stmt = $conn->prepare("SELECT * FROM marks WHERE student_id = ? AND term = ? AND academic_year = ?");
$stmt->bind_param("iss", $student_id, $term, $year);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $existing[$row['subject_id']] = $row;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    foreach ($_POST['cat1'] as $subject_id => $cat1) {
        $cat2 = $_POST['cat2'][$subject_id] ?? null;
        $exam = $_POST['exam'][$subject_id] ?? null;

        $cat1 = $cat1 !== '' ? floatval($cat1) : null;
        $cat2 = $cat2 !== '' ? floatval($cat2) : null;
        $exam = $exam !== '' ? floatval($exam) : null;

        if (!is_null($cat1) || !is_null($cat2) || !is_null($exam)) {
            if (isset($existing[$subject_id])) {
                $stmt = $conn->prepare("UPDATE marks SET cat1 = ?, cat2 = ?, exam = ? WHERE mark_id = ?");
                $stmt->bind_param("dddi", $cat1, $cat2, $exam, $existing[$subject_id]['mark_id']);
                $stmt->execute();
            } else {
                $stmt = $conn->prepare("INSERT INTO marks (student_id, subject_id, term, academic_year, cat1, cat2, exam) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("iissddd", $student_id, $subject_id, $term, $year, $cat1, $cat2, $exam);
                $stmt->execute();
            }
        }
    }

    $message = "<div class='alert alert-success'>Marks saved successfully.</div>";
    // Refresh existing marks
    $existing = [];
    $stmt = $conn->prepare("SELECT * FROM marks WHERE student_id = ? AND term = ? AND academic_year = ?");
    $stmt->bind_param("iss", $student_id, $term, $year);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $existing[$row['subject_id']] = $row;
    }
}
?>

<h2 class="mt-4">Enter Marks</h2>
<?= $message ?>

<p><strong>Term:</strong> <?= htmlspecialchars($term) ?> |
<strong>Academic Year:</strong> <?= htmlspecialchars($year) ?></p>

<form method="POST" class="table-responsive">
    <table class="table table-bordered align-middle">
        <thead class="table-light">
            <tr>
                <th>Subject</th>
                <th>CAT 1</th>
                <th>CAT 2</th>
                <th>Exam</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($subjects as $id => $name): 
                $cat1 = $existing[$id]['cat1'] ?? "";
                $cat2 = $existing[$id]['cat2'] ?? "";
                $exam = $existing[$id]['exam'] ?? "";
                $total = is_numeric($cat1) && is_numeric($cat2) && is_numeric($exam)
                        ? $cat1 + $cat2 + $exam
                        : "";
            ?>
                <tr>
                    <td><?= htmlspecialchars($name) ?></td>
                    <td><input type="number" name="cat1[<?= $id ?>]" class="form-control" step="0.01" value="<?= $cat1 ?>"></td>
                    <td><input type="number" name="cat2[<?= $id ?>]" class="form-control" step="0.01" value="<?= $cat2 ?>"></td>
                    <td><input type="number" name="exam[<?= $id ?>]" class="form-control" step="0.01" value="<?= $exam ?>"></td>
                    <td><strong><?= $total !== "" ? number_format($total, 2) : "" ?></strong></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <button type="submit" class="btn btn-success">Save Marks</button>
</form>

<p class="mt-3">
    <a href="../students/student_details.php?id=<?= $student_id ?>" class="btn btn-secondary">Back to Student</a>
</p>

<?php include "../footer.php"; ?>