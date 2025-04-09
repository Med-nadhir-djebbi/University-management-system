<?php
include('database.php');

$student_id = intval($_GET['id']);

$query = "
    SELECT 
        e.id AS student_id,
        e.Name,
        e.birthday,
        s.designation,
        s.description
    FROM etudiant e
    JOIN section s ON e.section_id = s.id
    WHERE e.id = :id
";

$stmt = $pdo->prepare($query);
$stmt->execute(['id' => $student_id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Details</title>
    <link rel="stylesheet" href="detailStyle.css">
</head>
<body>
    <?php if ($student): ?>
        <div class="student-card">
            <div class="student-name"><?= htmlspecialchars($student['Name']) ?></div>
            <div class="section-badge"><?= htmlspecialchars($student['designation']) ?></div>
            <div class="section-info"><?= htmlspecialchars($student['description']) ?></div>
            <div class="student-info">
                <div><strong>ID:</strong> <?= htmlspecialchars($student['student_id']) ?></div>
                <div><strong>Birthdate:</strong> <?= htmlspecialchars($student['birthday']) ?></div>
            </div>
            <a href="javascript:history.back()" class="back-link">← Back to list</a>
        </div>
    <?php else: ?>
        <div class="student-card">
            <p>Student not found</p>
            <a href="javascript:history.back()" class="back-link">← Back to list</a>
        </div>
    <?php endif; ?>
</body>
</html>