<?php
include('database.php');

$currentTab = isset($_GET['tab']) ? $_GET['tab'] : 'students';

$sort_column = isset($_GET['sort_column']) ? $_GET['sort_column'] : '';
$sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : '';
$order_by = ($sort_column && $sort_order) ? "ORDER BY $sort_column $sort_order" : '';

if ($currentTab === 'students') {
    $query = "SELECT e.id, e.Name, e.birthday, s.designation as section_name 
              FROM etudiant e   
              JOIN section s ON e.section_id = s.id $order_by";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $query = "SELECT id, designation, description FROM section $order_by";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
require_once 'export.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>University Management</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="indexS.css">
</head>
<body>
    <h1>University Management System</h1>
    
    <div class="tabs">
        <div class="tab <?= $currentTab === 'students' ? 'active' : '' ?>">
            <a href="?tab=students">Students</a>
        </div>
        <div class="tab <?= $currentTab === 'sections' ? 'active' : '' ?>">
            <a href="?tab=sections">Sections</a>
        </div>
    </div>

    <?php if ($currentTab === 'students'): ?>
        <?php if (count($data) > 0): ?>
            <div class="export-buttons" style="margin: 10px 0; text-align: left;">
                <a href="export.php?export=1&type=csv&tab=<?= $currentTab ?><?= $sort_column ? '&sort_column='.$sort_column : '' ?><?= $sort_order ? '&sort_order='.$sort_order : '' ?>" class="action-btn btn-primary">Export CSV</a>
                <a href="export.php?export=1&type=pdf&tab=<?= $currentTab ?><?= $sort_column ? '&sort_column='.$sort_column : '' ?><?= $sort_order ? '&sort_order='.$sort_order : '' ?>" class="action-btn btn-primary">Export PDF</a>
                <a href="export.php?export=1&type=excel&tab=<?= $currentTab ?><?= $sort_column ? '&sort_column='.$sort_column : '' ?><?= $sort_order ? '&sort_order='.$sort_order : '' ?>" class="action-btn btn-primary">Export Excel</a>
            </div>
            <table>
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
                </tr>
                <?php foreach ($data as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['Name']) ?></td>
                        <td><?= htmlspecialchars($row['birthday']) ?></td>
                        <td><?= htmlspecialchars($row['section_name']) ?></td>
                        <td><a href='detailEtudiant.php?id=<?= $row['id'] ?>' class='details-link'>></a></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p class="no-data">No students found</p>
        <?php endif; ?>
    <?php else: ?>
        <?php if (count($data) > 0): ?>
            <div class="export-buttons" style="margin: 10px 0; text-align: left;">
                <a href="export.php?export=1&type=csv&tab=<?= $currentTab ?><?= $sort_column ? '&sort_column='.$sort_column : '' ?><?= $sort_order ? '&sort_order='.$sort_order : '' ?>" class="action-btn btn-primary">Export CSV</a>
                <a href="export.php?export=1&type=pdf&tab=<?= $currentTab ?><?= $sort_column ? '&sort_column='.$sort_column : '' ?><?= $sort_order ? '&sort_order='.$sort_order : '' ?>" class="action-btn btn-primary">Export PDF</a>
                <a href="export.php?export=1&type=excel&tab=<?= $currentTab ?><?= $sort_column ? '&sort_column='.$sort_column : '' ?><?= $sort_order ? '&sort_order='.$sort_order : '' ?>" class="action-btn btn-primary">Export Excel</a>
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
                </tr>
                <?php foreach ($data as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['designation']) ?></td>
                        <td><?= htmlspecialchars($row['description']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p class="no-data">No sections found</p>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>