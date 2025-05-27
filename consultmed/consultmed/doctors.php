<?php
session_start();
include('db_config.php');

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login_signup.php");
    exit;
}

// Get list of doctors, their designations, and prescriptions
$conn = getPDOConnection();
$stmt = $conn->prepare("SELECT * FROM doctors");
$stmt->execute();
$doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctors</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Select a Doctor</h2>
        
        <!-- Doctor List (Now displayed side by side) -->
        <div class="doctor-list">
            <?php foreach ($doctors as $doctor): ?>
                <div class="doctor-item">
                    <h3><?php echo $doctor['name']; ?> - <?php echo $doctor['specialization']; ?></h3>
                    <p><strong>Designation:</strong> <?php echo $doctor['designation']; ?></p>
                    <p><strong>Prescription:</strong> <?php echo $doctor['prescription']; ?></p>
                    <a href="symptoms.php?doctor_id=<?php echo $doctor['id']; ?>">Select Doctor</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
