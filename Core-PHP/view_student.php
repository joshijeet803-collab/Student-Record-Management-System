<?php
require "auth.php";
require "config.php";

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: students.php");
    exit;
}

$id = (int) $_GET["id"];

$stmt = $conn->prepare(
    "SELECT id, name, roll_no, email, course, year, phone
     FROM students
     WHERE id = ?"
);

$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

if (!$student) {
    header("Location: students.php");
    exit;
}

$title = "Student Profile";
include "includes/header.php";
?>

<style>
.profile-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 40px 20px;
}

.profile-card {
    background: #fff;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 5px 22px rgba(30, 60, 90, 0.07);
}

.profile-title {
    color: #172b40;
    font-size: 28px;
    font-weight: 600;
}

.profile-subtitle {
    color: #6c757d;
    margin-bottom: 28px;
}

.profile-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 20px;
}

.profile-item {
    padding: 17px;
    background: #f4f8fc;
    border-radius: 8px;
    overflow-wrap: anywhere;
}

.profile-item small {
    display: block;
    color: #6c757d;
    margin-bottom: 7px;
}

.profile-item strong {
    color: #172b40;
    font-size: 16px;
}

.profile-actions {
    margin-top: 28px;
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

@media (max-width: 600px) {
    .profile-grid {
        grid-template-columns: 1fr;
    }

    .profile-card {
        padding: 20px;
    }
}
</style>

<main class="profile-container">

    <div class="profile-card">

        <h2 class="profile-title">Student Profile</h2>
        <p class="profile-subtitle">
            Complete information about the selected student.
        </p>

        <div class="profile-grid">

            <div class="profile-item">
                <small>Student ID</small>
                <strong><?= (int)$student["id"] ?></strong>
            </div>

            <div class="profile-item">
                <small>Student Name</small>
                <strong><?= htmlspecialchars($student["name"]) ?></strong>
            </div>

            <div class="profile-item">
                <small>Roll Number</small>
                <strong><?= htmlspecialchars($student["roll_no"]) ?></strong>
            </div>

            <div class="profile-item">
                <small>Email Address</small>
                <strong><?= htmlspecialchars($student["email"]) ?></strong>
            </div>

            <div class="profile-item">
                <small>Course</small>
                <strong><?= htmlspecialchars($student["course"]) ?></strong>
            </div>

            <div class="profile-item">
                <small>Academic Year</small>
                <strong><?= htmlspecialchars((string)$student["year"]) ?></strong>
            </div>

            <div class="profile-item">
                <small>Phone Number</small>
                <strong><?= htmlspecialchars($student["phone"]) ?></strong>
            </div>

        </div>

        <div class="profile-actions">
            <a href="students.php" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left"></i> Back to Students
            </a>

            <a href="dashboard.php" class="btn btn-primary">
                Dashboard
            </a>
        </div>

    </div>

</main>

<?php include "includes/footer.php"; ?>