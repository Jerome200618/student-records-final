<?php
// Debug (remove these 2 lines after everything works)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include DB using an absolute path
// If your folder is named "includes", change to '/includes/db.php'
require_once __DIR__ . '/include/db.php';

// Validate and get ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header('Location: index.php');
    exit;
}

// Fetch existing student
$sel = mysqli_prepare($conn, "SELECT id, name, course, year_level, email FROM students WHERE id = ?");
if (!$sel) {
    die('Prepare failed (select): ' . htmlspecialchars(mysqli_error($conn)));
}
mysqli_stmt_bind_param($sel, 'i', $id);
mysqli_stmt_execute($sel);
$res = mysqli_stmt_get_result($sel);
$student = $res ? mysqli_fetch_assoc($res) : null;

if (!$student) {
    header('Location: index.php');
    exit;
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name       = trim($_POST['name'] ?? '');
    $course     = trim($_POST['course'] ?? '');
    $year_level = trim($_POST['year_level'] ?? '');
    $email      = trim($_POST['email'] ?? '');

    if ($name === '' || $course === '' || $year_level === '' || $email === '') {
        die('All fields are required.');
    }

    $upd = mysqli_prepare($conn, "UPDATE students SET name = ?, course = ?, year_level = ?, email = ? WHERE id = ?");
    if (!$upd) {
        die('Prepare failed (update): ' . htmlspecialchars(mysqli_error($conn)));
    }
    mysqli_stmt_bind_param($upd, 'ssssi', $name, $course, $year_level, $email, $id);

    if (!mysqli_stmt_execute($upd)) {
        die('Execute failed (update): ' . htmlspecialchars(mysqli_error($conn)));
    }

    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Edit Student</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="edit-container">
    <h2 class="center">Edit Student</h2>

    <form method="POST" class="edit-form" autocomplete="off">
        <div class="form-row">
            <label>Name:</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($student['name']); ?>" required>
        </div>
        <div class="form-row">
            <label>Course:</label>
            <input type="text" name="course" value="<?php echo htmlspecialchars($student['course']); ?>" required>
        </div>
        <div class="form-row">
            <label>Year Level:</label>
            <input type="text" name="year_level" value="<?php echo htmlspecialchars($student['year_level']); ?>" required>
        </div>
        <div class="form-row">
            <label>Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>" required>
        </div>

        <input type="submit" name="submit" value="Save">
    </form>
</div>

</body>
</html>