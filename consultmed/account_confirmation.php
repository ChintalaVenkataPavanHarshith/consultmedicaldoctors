<?php
session_start();
include('db_config.php');

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login_signup.php");
    exit;
}

// Fetch booking details from the database
$user_id = $_SESSION['user_id'];
$conn = getPDOConnection();
$stmt = $conn->prepare("SELECT * FROM bookings WHERE user_id = :user_id ORDER BY id DESC LIMIT 1");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$booking = $stmt->fetch(PDO::FETCH_ASSOC);

if ($booking) {
    $doctor_id = $booking['doctor_id'];
    $symptoms = $booking['symptoms'];
    $appointment_datetime = $booking['appointment_datetime'];

    // Fetch doctor name based on doctor_id
    $stmt = $conn->prepare("SELECT name FROM doctors WHERE id = :doctor_id");
    $stmt->bindParam(':doctor_id', $doctor_id);
    $stmt->execute();
    $doctor = $stmt->fetch(PDO::FETCH_ASSOC);

    $doctor_name = $doctor['name'];
}
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
    <div class="container appointment-details">
        <h3>Your Appointment Details</h3>
        <p><strong>Doctor:</strong> <?php echo $doctor_name; ?></p>
        <p><strong>Symptoms:</strong> <?php echo $symptoms; ?></p>
        <p><strong>Appointment Date and Time:</strong> <?php echo $appointment_datetime; ?></p>
        <p class="status">Your appointment is successfully booked. Thank you for using our service. Visit again!</p>
    </div>
</body>
</html>
