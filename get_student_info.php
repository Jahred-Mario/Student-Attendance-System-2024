<?php
require('connect.php');

if (isset($_GET['booking_code'])) {
    $booking_code = trim($_GET['booking_code']);

    error_log("Booking code received: '$booking_code'");

    $stmt = $conn2->prepare("SELECT student_number FROM bookings WHERE booking_code = ?");
    $stmt->bind_param("s", $booking_code);

    $stmt->execute();
    $result_booking = $stmt->get_result();

    if ($result_booking->num_rows > 0) {
        $booking = $result_booking->fetch_assoc();
        $student_number = $booking['student_number'];

        // Debug student number
        error_log("Found student_number: $student_number");

        $stmt2 = $conn1->prepare("SELECT first_name, middle_name, last_name, course, studentnumber FROM students WHERE studentnumber = ?");
        $stmt2->bind_param("s", $student_number);
        $stmt2->execute();
        $result_student = $stmt2->get_result();

        if ($result_student->num_rows > 0) {
            $student = $result_student->fetch_assoc();
            echo json_encode(['success' => true, 'student' => $student]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Student not found']);
        }

    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid booking code']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Booking code not provided']);
}

$conn1->close();
$conn2->close();
?>
