<?php
include 'connect.php'; // Ensure this includes the database connection script

// Use the connection to the `earist_database`
$conn = $conn1; // Assuming $conn1 is for `earist_database`

// Get data from POST request
$data = json_decode($_POST['tableData'], true);

if (count($data) === 0) {
    die("No data provided.");
}

// Determine the earliest date in the data
$earliestDate = min(array_column($data, 4)); // Assuming date is in the 5th column (index 4)
$table_name = str_replace('-', '_', $earliestDate); // Create a table name based on the date

// Create the SQL query to create a new table
$sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
    `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
    `student_number` VARCHAR(50),
    `name` VARCHAR(255),
    `course` VARCHAR(100),
    `time_in` VARCHAR(20),
    `time_out` VARCHAR(20)
)";

// Execute the query
if ($conn->query($sql) !== TRUE) {
    die("Error creating table: " . $conn->error);
}

// Prepare SQL for inserting data
$stmt = $conn->prepare("INSERT INTO `$table_name` (student_number, name, course, time_in, time_out) VALUES (?, ?, ?, ?, ?)");

foreach ($data as $row) {
    $stmt->bind_param("sssss", $row[1], $row[2], $row[3], $row[5], $row[6]);
    $stmt->execute();
}

// Close statement and connection
$stmt->close();
$conn->close();

echo "Data inserted successfully into $table_name";
?>
