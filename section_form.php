<?php
include('database.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $designation = $_POST['designation'];
    $description = $_POST['description'];

    try {
        $stmt = $pdo->prepare("INSERT INTO section (id, designation, description) VALUES (?, ?, ?)");
        $stmt->execute([$id, $designation, $description]);
        
        header('Location: index.php?message=Section+added+successfully');
        exit;
    } catch (PDOException $e) {
        $error = "Error adding section: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New Section</title>
    <link rel="stylesheet" href="section_form.css">
</head>
<body>
    <div class="form-container">
        <h1>Add New Section</h1>
        
        <?php if (isset($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <form method="post">
            <div class="form-group">
                <label for="id">Section ID</label>
                <input type="number" id="id" name="id" required>
            </div>
            
            <div class="form-group">
                <label for="designation">Designation</label>
                <input type="text" id="designation" name="designation" maxlength="10" required >
            </div>
            
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" required></textarea>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn">Add Section</button>
                <a href="admin.php?tab=sections" class="cancel-link">Cancel</a>

            </div>
        </form>
    </div>

    <script>
        document.querySelectorAll('input, textarea').forEach(input => {
            const formGroup = input.closest('.form-group');
            
            input.addEventListener('focus', () => {
                formGroup.classList.add('focused');
            });
            
            input.addEventListener('blur', () => {
                if (!input.value) {
                    formGroup.classList.remove('focused');
                }
            });
            
            if (input.value) {
                formGroup.classList.add('focused');
            }
        });
    </script>
</body>
</html>