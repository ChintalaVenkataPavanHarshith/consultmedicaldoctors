<?php
session_start();
include('db_config.php');

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login_signup.php");
    exit;
}

// Get doctor ID from URL parameter
$doctor_id = isset($_GET['doctor_id']) ? filter_var($_GET['doctor_id'], FILTER_SANITIZE_NUMBER_INT) : null;

if (!$doctor_id) {
    // Redirect to doctors page if no doctor is selected
    header("Location: doctors.php");
    exit;
}

// Get the doctor's details from the database
$conn = getPDOConnection();
$stmt = $conn->prepare("SELECT * FROM doctors WHERE id = :doctor_id");
$stmt->bindParam(':doctor_id', $doctor_id);
$stmt->execute();
$doctor = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$doctor) {
    // If the doctor doesn't exist, redirect to doctors page
    header("Location: doctors.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Symptoms</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Describe Your Symptoms</h2>
        <form action="booking.php" method="GET">
            <input type="hidden" name="doctor_id" value="<?php echo $doctor['id']; ?>">
            <input type="hidden" name="doctor_name" value="<?php echo $doctor['name']; ?>">
            
            <label for="symptoms">Describe Your Symptoms:</label><br>
            <textarea name="symptoms" id="symptoms" placeholder="Describe your symptoms..." required></textarea><br>

            <!-- Appointment time selection -->
            <label for="appointment_time">Select Appointment Time:</label>
            <select name="appointment_time" id="appointment_time" required>
                <option value="09:00 AM">09:00 AM</option>
                <option value="11:00 AM">11:00 AM</option>
                <option value="01:00 PM">01:00 PM</option>
                <option value="03:00 PM">03:00 PM</option>
            </select><br>

            <button type="submit">Proceed to Book</button>
        </form>
    </div>
</body>
</html>

