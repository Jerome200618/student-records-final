<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/include/db.php';

$sql = "SELECT id, name, course, year_level, email FROM students ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
if (!$result) {
    die("Query Failed: " . htmlspecialchars(mysqli_error($conn)));
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Student Records</title>
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
</head>
<body>
<div class="container">
    <h2>Student Records</h2>

    <div class="top-controls">
        <a href="add.php" class="add-button">Add New Student</a>
        <input type="text" id="searchInput" placeholder="Search students...">
    </div>

    <table id="studentsTable">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Course</th>
            <th>Year Level</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr data-id="<?php echo (int)$row['id']; ?>">
                    <td><?php echo (int)$row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['course']); ?></td>
                    <td><?php echo htmlspecialchars($row['year_level']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td class="actions">
                        <a href="edit.php?id=<?php echo (int)$row['id']; ?>" class="edit">Edit</a>
                        <button class="delete" data-id="<?php echo (int)$row['id']; ?>">Delete</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6">No students found</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const table = document.querySelector("#studentsTable tbody");
    const searchInput = document.getElementById('searchInput');

    // SEARCH
    searchInput.addEventListener('input', function() {
        const q = this.value.toLowerCase();
        table.querySelectorAll('tr').forEach(tr => {
            tr.style.display = tr.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    });

    // SORTING
    const headers = document.querySelectorAll("table th");
    headers.forEach((header, index) => {
        header.addEventListener("click", function() {
            const rowsArray = Array.from(table.querySelectorAll("tr"));
            const asc = !header.asc;
            header.asc = asc;

            rowsArray.sort((a, b) => {
                const aText = a.children[index].textContent.trim();
                const bText = b.children[index].textContent.trim();
                return !isNaN(aText) && !isNaN(bText)
                    ? asc ? aText - bText : bText - aText
                    : asc ? aText.localeCompare(bText) : bText.localeCompare(aText);
            });

            rowsArray.forEach(row => table.appendChild(row));
        });
    });

    // DELETE
    table.querySelectorAll(".delete").forEach(btn => {
        btn.addEventListener("click", function() {
            const studentId = this.dataset.id;
            const row = this.closest("tr");
            const studentName = row.querySelector("td:nth-child(2)").textContent;

            if (confirm(`Are you sure you want to delete ${studentName}?`)) {
                fetch('delete.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: `id=${studentId}`
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        row.remove();
                    } else {
                        alert('Failed to delete the student.');
                    }
                })
                .catch(() => alert('Error occurred while deleting.'));
            }
        });
    });
});
</script>
</body>
</html>
