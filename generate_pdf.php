<?php
require_once('tcpdf/tcpdf.php');

if (isset($_POST['tableData'])) {
    $tableData = json_decode($_POST['tableData'], true);

    $pdf = new TCPDF();
    $pdf->AddPage();

    $html = '<h1>Library Attendance</h1>';
    $html .= '<table border="1" cellpadding="5">';
    $html .= '<tr><th>No.</th><th>Student Number</th><th>Name</th><th>Course</th><th>Date</th><th>Time In</th><th>Time Out</th></tr>';

    foreach ($tableData as $row) {
        $html .= '<tr>';
        $html .= '<td>' . $row[0] . '</td>';
        $html .= '<td>' . $row[1] . '</td>';
        $html .= '<td>' . $row[2] . '</td>';
        $html .= '<td>' . $row[3] . '</td>';
        $html .= '<td>' . $row[4] . '</td>';
        $html .= '<td>' . $row[5] . '</td>';
        $html .= '<td>' . $row[6] . '</td>';
        $html .= '</tr>';
    }

    $html .= '</table>';

    $pdf->writeHTML($html);
    $pdf->Output('library_attendance.pdf', 'D');
}
?>
