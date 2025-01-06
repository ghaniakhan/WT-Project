<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "edoc";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch specialties for dropdown
$specialtiesQuery = "SELECT * FROM specialties";
$specialtiesResult = $conn->query($specialtiesQuery);

// Handle specialization selection
$doctors = [];
if (isset($_POST['speciality'])) {
    $specialityId = intval($_POST['speciality']);
    $doctorsQuery = "
        SELECT * FROM doctor 
        WHERE specialties = $specialityId 
        ORDER BY RAND() 
        LIMIT 3";
    $doctorsResult = $conn->query($doctorsQuery);

    if ($doctorsResult) {
        while ($row = $doctorsResult->fetch_assoc()) {
            $doctors[] = $row;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Recommendation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            width: 50%;
            margin: 20px auto;
        }
        .dropdown, .submit-btn {
            display: block;
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            font-size: 16px;
        }
        .doctor-card {
            border: 1px solid #ccc;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
        }
        .doctor-card h3 {
            margin: 0;
            font-size: 18px;
        }
        .doctor-card p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Select Specialization</h1>
        <form method="POST">
            <select name="speciality" class="dropdown" required>
                <option value="" disabled selected>Select a Specialization</option>
                <?php
                if ($specialtiesResult->num_rows > 0) {
                    while ($row = $specialtiesResult->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['sname'] . "</option>";
                    }
                }
                ?>
            </select>
            <button type="submit" class="submit-btn">Show Doctors</button>
        </form>

        <?php if (!empty($doctors)): ?>
            <h2>Recommended Doctors</h2>
            <?php foreach ($doctors as $doctor): ?>
                <div class="doctor-card">
                    <h3><?php echo htmlspecialchars($doctor['docname']); ?></h3>
                    <p>Email: <?php echo htmlspecialchars($doctor['docemail']); ?></p>
                    <p>Phone: <?php echo htmlspecialchars($doctor['doctel']); ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
