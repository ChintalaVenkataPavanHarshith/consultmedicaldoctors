<?php
session_start();
include('db_config.php');

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login_signup.php");
    exit;
}

// Get booking_id from the URL
$booking_id = $_GET['booking_id'] ?? null;

if (!$booking_id) {
    header("Location: doctors.php");
    exit;
}

// Get the booking details from the database
$conn = getPDOConnection();
$stmt = $conn->prepare("SELECT * FROM bookings WHERE id = :booking_id");
$stmt->bindParam(':booking_id', $booking_id);
$stmt->execute();
$booking = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$booking) {
    header("Location: doctors.php");
    exit;
}

// Get the doctor details
$doctor_stmt = $conn->prepare("SELECT * FROM doctors WHERE id = :doctor_id");
$doctor_stmt->bindParam(':doctor_id', $booking['doctor_id']);
$doctor_stmt->execute();
$doctor = $doctor_stmt->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Details</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Your Appointment Details</h2>
        <p><strong>Doctor:</strong> <?php echo $doctor['name']; ?></p>
        <p><strong>Specialization:</strong> <?php echo $doctor['specialization']; ?></p>
        <p><strong>Designation:</strong> <?php echo $doctor['designation']; ?></p>
        <p><strong>Symptoms:</strong> <?php echo $booking['symptoms']; ?></p>
        <p><strong>Status:</strong> Confirmed</p>
        <p>Your appointment has been successfully booked. Thank you for choosing our service!</p>
    </div>
</body>
</html>
