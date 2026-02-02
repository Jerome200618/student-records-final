<?php
require_once __DIR__ . '/include/db.php';
header('Content-Type: application/json');

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id) { echo json_encode(['success'=>false]); exit; }

$stmt = mysqli_prepare($conn, "DELETE FROM students WHERE id=?");
mysqli_stmt_bind_param($stmt, 'i', $id);
$success = mysqli_stmt_execute($stmt) && mysqli_stmt_affected_rows($stmt) > 0;
mysqli_stmt_close($stmt);

echo json_encode(['success'=>$success]);
exit;
