<?php
require 'database.php';
require 'Student.php';
require 'Section.php';

session_start();

$studentModel = new Student($pdo);
$sectionModel = new Section($pdo);



$currentTab = $_GET['tab'] ?? 'students';
$students = $studentModel->getAll();
$sections = $sectionModel->getAll();

if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    if ($currentTab === 'students' && $studentModel->delete($delete_id)) {
        $_SESSION['success'] = "Student deleted successfully";
    } elseif ($currentTab === 'sections' && $sectionModel->delete($delete_id)) {
        $_SESSION['success'] = "Section deleted successfully";
    } else {
        $_SESSION['error'] = "Failed to delete record";
    }
    header('Location: admin.php?tab='.$currentTab);
    exit;
}
require_once 'export.php'; 
$sort_column = isset($_GET['sort_column']) ? $_GET['sort_column'] : '';
$sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : '';
$order_by = ($sort_column && $sort_order) ? "ORDER BY $sort_column $sort_order" : '';

?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="header">
        <h1>Admin Dashboard</h1>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="tabs">
        <a href="?tab=students" class="tab <?= $currentTab === 'students' ? 'active' : '' ?>">Students</a>
        <a href="?tab=sections" class="tab <?= $currentTab === 'sections' ? 'active' : '' ?>">Sections</a>
    </div>



    <?php if ($currentTab === 'students'): ?>
        <h2>Students Management</h2>
        <a href="student_form.php" class="action-btn btn-primary add-btn">Add Student</a>
        <div class="export-buttons" style="margin: 10px 0; text-align: left;">
            <button class="action-btn btn-primary" id="exportCsv">Export CSV</button>
            <button class="action-btn btn-primary" id="exportPdf">Export PDF</button>
            <button class="action-btn btn-primary" id="exportExcel">Export Excel</button>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID
                        <a href='?tab=students&sort_column=id&sort_order=asc' class='sort-btn'>&#8645;</a>
                    </th>
                    <th>Name
                        <a href='?tab=students&sort_column=Name&sort_order=asc' class='sort-btn'>&#8645;</a>
                    </th>
                    <th>Birthdate
                        <a href='?tab=students&sort_column=birthday&sort_order=asc' class='sort-btn'>&#8645;</a>
                    </th>
                    <th>Section
                        <a href='?tab=students&sort_column=section_name&sort_order=asc' class='sort-btn'>&#8645;</a>
                    </th>
                    <th>Details</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student): ?>
                <tr>
                    <td><?= htmlspecialchars($student['id']) ?></td>
                    <td><?= htmlspecialchars($student['Name']) ?></td>
                    <td><?= htmlspecialchars($student['birthday']) ?></td>
                    <td><?= htmlspecialchars($student['section_name']) ?></td>
                    <td><a href='detailEtudiant.php?id=<?= $student['id'] ?>' class='details-link'>></a></td>
                    <td>
                        <a href="student_form.php?id=<?= $student['id'] ?>" class="action-btn btn-primary">Edit</a>
                        <a href="?tab=students&delete_id=<?= $student['id'] ?>" class="action-btn btn-danger" 
                           onclick="return confirm('Are you sure you want to delete this student?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <h2>Sections Management</h2>
        <a href="section_form.php" class="action-btn btn-primary add-btn">Add Section</a>
        <div class="export-buttons" style="margin: 10px 0; text-align: left;">
            <button class="action-btn btn-primary" id="exportCsv">Export CSV</button>
            <button class="action-btn btn-primary" id="exportPdf">Export PDF</button>
            <button class="action-btn btn-primary" id="exportExcel">Export Excel</button>
        </div>

        <table>
                <tr>
                    <th>ID
                        <a href='?tab=sections&sort_column=id&sort_order=asc' class='sort-btn'>&#8645;</a>
                    </th>
                    <th>Designation
                        <a href='?tab=sections&sort_column=designation&sort_order=asc' class='sort-btn'>&#8645;</a>
                    </th>
                    <th>Description
                        <a href='?tab=sections&sort_column=description&sort_order=asc' class='sort-btn'>&#8645;</a>
                    </th>
                    <th>Actions</th>
                </tr>
            <tbody>
                <?php foreach ($sections as $section): ?>
                <tr>
                    <td><?= htmlspecialchars($section['id']) ?></td>
                    <td><?= htmlspecialchars($section['designation']) ?></td>
                    <td><?= htmlspecialchars($section['description']) ?></td>
                    <td>
                        <a href="section_form.php?id=<?= $section['id'] ?>" class="action-btn btn-primary">Edit</a>
                        <a href="?tab=sections&delete_id=<?= $section['id'] ?>" class="action-btn btn-danger"
                           onclick="return confirm('Are you sure you want to delete this section?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <script>
        $(document).ready(function(){
            $('#exportCsv').click(function(){
                exportData('csv');
            });

            $('#exportPdf').click(function(){
                exportData('pdf');
            });

            $('#exportExcel').click(function(){
                exportData('excel');
            });

            function exportData(type) {
                var tab = '<?= $currentTab ?>';
                $.ajax({
                    url: 'admin.php',
                    type: 'GET',
                    data: {
                        export: true,
                        type: type,
                        tab: tab
                    },
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function(response, status, xhr) {
                        var blob = response;
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = tab + '_export.' + type;
                        link.click();
                    },
                    error: function() {
                        alert('Error exporting data');
                    }
                });
            }
        });
    </script>
</body>
</html>
