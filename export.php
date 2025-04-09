<?php
if (isset($_GET['export'])) {
    $type = $_GET['type'] ?? 'excel';
    $currentTab = $_GET['tab'] ?? 'students';
    
    
    include('database.php');
    
    // 
    $sort_column = $_GET['sort_column'] ?? '';
    $sort_order = $_GET['sort_order'] ?? '';
    $order_by = ($sort_column && $sort_order) ? "ORDER BY $sort_column $sort_order" : '';
    
    if ($currentTab === 'students') {
        $query = "SELECT e.id, e.Name, e.birthday, s.designation as section_name 
                 FROM etudiant e   
                 JOIN section s ON e.section_id = s.id $order_by";
        $filename = "students_export";
    } else {
        $query = "SELECT id, designation, description FROM section $order_by";
        $filename = "sections_export";
    }
    
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($type === 'csv') {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="'.$filename.'.csv"');
        $output = fopen('php://output', 'w');
        
        if ($currentTab === 'students') {
            fputcsv($output, array('ID', 'Name', 'Birthday', 'Section'));
            foreach ($data as $row) {
                fputcsv($output, array(
                    $row['id'],
                    $row['Name'],
                    $row['birthday'],
                    $row['section_name']
                ));
            }
        } else {
            fputcsv($output, array('ID', 'Designation', 'Description'));
            foreach ($data as $row) {
                fputcsv($output, array(
                    $row['id'],
                    $row['designation'],
                    $row['description']
                ));
            }
        }
        fclose($output);
        exit;
    } elseif ($type === 'pdf') {
        require_once('tcpdf/tcpdf.php');
        $pdf = new TCPDF();
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 12);
        
        $html = '<h2>'.ucfirst($currentTab).' List</h2>';
        $html .= '<table border="1" cellpadding="4">';
        
        if ($currentTab === 'students') {
            $html .= '<tr><th>ID</th><th>Name</th><th>Birthday</th><th>Section</th></tr>';
            foreach ($data as $row) {
                $html .= '<tr>';
                $html .= '<td>'.$row['id'].'</td>';
                $html .= '<td>'.$row['Name'].'</td>';
                $html .= '<td>'.$row['birthday'].'</td>';
                $html .= '<td>'.$row['section_name'].'</td>';
                $html .= '</tr>';
            }
        } else {
            $html .= '<tr><th>ID</th><th>Designation</th><th>Description</th></tr>';
            foreach ($data as $row) {
                $html .= '<tr>';
                $html .= '<td>'.$row['id'].'</td>';
                $html .= '<td>'.$row['designation'].'</td>';
                $html .= '<td>'.$row['description'].'</td>';
                $html .= '</tr>';
            }
        }
        
        $html .= '</table>';
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output($filename.'.pdf', 'D');
        exit;
    } else { 
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="'.$filename.'.xls"');
        
        echo '<table border="1">';
        if ($currentTab === 'students') {
            echo '<tr><th>ID</th><th>Name</th><th>Birthday</th><th>Section</th></tr>';
            foreach ($data as $row) {
                echo '<tr>';
                echo '<td>'.$row['id'].'</td>';
                echo '<td>'.$row['Name'].'</td>';
                echo '<td>'.$row['birthday'].'</td>';
                echo '<td>'.$row['section_name'].'</td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><th>ID</th><th>Designation</th><th>Description</th></tr>';
            foreach ($data as $row) {
                echo '<tr>';
                echo '<td>'.$row['id'].'</td>';
                echo '<td>'.$row['designation'].'</td>';
                echo '<td>'.$row['description'].'</td>';
                echo '</tr>';
            }
        }
        echo '</table>';
        exit;
    }
}
?>