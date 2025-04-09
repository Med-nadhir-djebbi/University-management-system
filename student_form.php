<?php
require 'database.php';
require 'Student.php';
require 'Section.php';

$studentModel = new Student($pdo);
$sectionModel = new Section($pdo);

$id = $_GET['id'] ?? null;
$student = $id ? $studentModel->getById($id) : null;
$sections = $sectionModel->getAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'id' => $_POST['id'],
        'name' => $_POST['name'],
        'birthday' => $_POST['birthday'],
        'section_id' => $_POST['section_id']
    ];
    
    if ($id) {
        $studentModel->update($id, $data['name'], $data['birthday'], $data['section_id']);
    } else {
        $studentModel->create($data['id'], $data['name'], $data['birthday'], $data['section_id']);
    }
    
    header('Location: admin.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= $id ? 'Edit' : 'Add' ?> Student</title>
    <link rel="stylesheet" href="students_form.css">
</head>
<body>
    <div class="form-container">
        <h1><?= $id ? 'Edit' : 'Add' ?> Student</h1>
        
        <form method="post">
            <?php if (!$id): ?>
            <div class="form-group">
                <label for="id">Student ID</label>
                <input type="number" id="id" name="id" required>
            </div>
            <?php endif; ?>
            
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" 
                       value="<?= $student ? htmlspecialchars($student['Name']) : '' ?>" required>
            </div>
            
            <div class="form-group">
                <label for="birthday">Birthday</label>
                <input type="date" id="birthday" name="birthday" 
                       value="<?= $student ? htmlspecialchars($student['birthday']) : '' ?>" required>
            </div>
            
            <div class="form-group">
                <label for="section_id">Section</label>
                <select id="section_id" name="section_id" required>
                    <?php foreach ($sections as $section): ?>
                    <option value="<?= $section['id'] ?>" 
                        <?= $student && $student['section_id'] == $section['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($section['designation']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn">Save</button>
                <a href="admin.php?tab=students" class="cancel-link">Cancel</a>

            </div>
        </form>
    </div>
</body>
</html>