<?php
session_start();
include('db_config.php');

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login_signup.php");
    exit;
}

// Get the doctor's details from the POST request
$doctor_id = $_POST['doctor_id'] ?? null;
$doctor_name = $_POST['doctor_name'] ?? null;
$symptoms = $_POST['symptoms'] ?? null;
$appointment_date = $_POST['appointment_date'] ?? null;
$appointment_time = $_POST['appointment_time'] ?? null;

if (!$doctor_id || !$doctor_name || !$symptoms || !$appointment_date || !$appointment_time) {
    header("Location: doctors.php");
    exit;
}

// Combine the date and time into a single datetime field
$appointment_datetime = $appointment_date . ' ' . $appointment_time;

// Insert the booking details into the database
$conn = getPDOConnection();
$stmt = $conn->prepare("INSERT INTO bookings (user_id, doctor_id, symptoms, appointment_datetime) VALUES (:user_id, :doctor_id, :symptoms, :appointment_datetime)");
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->bindParam(':doctor_id', $doctor_id);
$stmt->bindParam(':symptoms', $symptoms);
$stmt->bindParam(':appointment_datetime', $appointment_datetime);
$stmt->execute();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Appointment Confirmed</h2>
        <p>Your appointment with Dr. <?php echo $doctor_name; ?> has been confirmed.</p>
        <p><strong>Symptoms:</strong> <?php echo $symptoms; ?></p>
        <p><strong>Appointment Date and Time:</strong> <?php echo $appointment_datetime; ?></p>
        <p>We will notify you about your appointment details shortly.</p>
        
        <a href="account_confirmation.php" class="btn-success">Go to Account</a>
    </div>
</body>
</html>
