<?php
session_start();
include('db_config.php');

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login_signup.php");
    exit;
}

// Get the doctor ID, name, and symptoms from the URL
$doctor_id = $_GET['doctor_id'] ?? null;
$doctor_name = $_GET['doctor_name'] ?? null;
$symptoms = $_GET['symptoms'] ?? null;

if (!$doctor_id || !$doctor_name || !$symptoms) {
    header("Location: doctors.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Confirm Your Appointment</h2>
        <p><strong>Doctor:</strong> <?php echo $doctor_name; ?></p>
        <p><strong>Symptoms:</strong> <?php echo $symptoms; ?></p>
        
        <form action="confirmation.php" method="POST">
            <input type="hidden" name="doctor_id" value="<?php echo $doctor_id; ?>">
            <input type="hidden" name="doctor_name" value="<?php echo $doctor_name; ?>">
            <input type="hidden" name="symptoms" value="<?php echo $symptoms; ?>">
            
            <!-- Appointment Date and Time Picker -->
            <label for="appointment_date">Select Appointment Date:</label>
            <input type="date" name="appointment_date" id="appointment_date" required>
            
            <label for="appointment_time">Select Appointment Time:</label>
            <input type="time" name="appointment_time" id="appointment_time" required>

            <button type="submit">Confirm Appointment</button>
        </form>
    </div>
</body>
</html>