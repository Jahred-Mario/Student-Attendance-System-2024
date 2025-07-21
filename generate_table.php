<?php
require('connect.php');

$data = json_decode(file_get_contents("php://input"), true);
$tableData = $data['tableData'];

// Group records by date
$recordsByDate = [];
foreach ($tableData as $row) {
    $date = str_replace('/', '_', $row[4]); // Convert date format to MM_DD_YYYY
    if (!isset($recordsByDate[$date])) {
        $recordsByDate[$date] = [];
    }
    $recordsByDate[$date][] = $row;
}

foreach ($recordsByDate as $date => $records) {
    $tableName = $date;

    $sql = "CREATE TABLE IF NOT EXISTS `$tableName` (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        student_number VARCHAR(20) NOT NULL,
        name VARCHAR(100) NOT NULL,
        course VARCHAR(100) NOT NULL,
        time_in VARCHAR(20) NOT NULL,
        time_out VARCHAR(20) NOT NULL
    )";

    if ($conn->query($sql) === TRUE) {
        $stmt = $conn->prepare("INSERT INTO `$tableName` (student_number, name, course, time_in, time_out) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $student_number, $name, $course, $time_in, $time_out); // Corrected parameter types

        foreach ($records as $row) {
            // Format time to include AM/PM
            $time_in = date("h:i A", strtotime($row[5]));
            $time_out = date("h:i A", strtotime($row[6]));

            // Debug: Log each row being inserted
            error_log(print_r($row, true));

            $student_number = $row[1];
            $name = $row[2];
            $course = $row[3];
            $stmt->execute();
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
        $conn->close();
        exit();
    }
}

echo json_encode(['success' => true]);
$conn->close();
?>
