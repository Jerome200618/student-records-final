<?php
// Debug (remove these 2 lines after everything works)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include DB using an absolute path
// If your folder is named "includes", change to '/includes/db.php'
require_once __DIR__ . '/include/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name       = trim($_POST['name'] ?? '');
    $course     = trim($_POST['course'] ?? '');
    $year_level = trim($_POST['year_level'] ?? '');
    $email      = trim($_POST['email'] ?? '');

    // Basic validation
    if ($name === '' || $course === '' || $year_level === '' || $email === '') {
        // Show a clear message while debugging; later you can render this in the page.
        die('All fields are required.');
    }

    // Prepared statement
    $stmt = mysqli_prepare($conn, "INSERT INTO students (name, course, year_level, email) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        die('Prepare failed: ' . htmlspecialchars(mysqli_error($conn)));
    }

    mysqli_stmt_bind_param($stmt, 'ssss', $name, $course, $year_level, $email);

    if (!mysqli_stmt_execute($stmt)) {
        // For example, duplicate email if you add a UNIQUE constraint
        die('Execute failed: ' . htmlspecialchars(mysqli_error($conn)));
    }

    // Redirect back to list
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Add Student</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="add-container">
    <h2 class="center">Add New Student</h2>

    <form method="POST" class="add-form" autocomplete="off">
        <div class="form-row">
            <label>Name:</label>
            <input type="text" name="name" required>
        </div>
        <div class="form-row">
            <label>Course:</label>
            <input type="text" name="course" required>
        </div>
        <div class="form-row">
            <label>Year Level:</label>
            <input type="text" name="year_level" required>
        </div>
        <div class="form-row">
            <label>Email:</label>
            <input type="email" name="email" required>
        </div>

        <input type="submit" name="submit" value="Add Student">
    </form>
</div>

</body>
</html>