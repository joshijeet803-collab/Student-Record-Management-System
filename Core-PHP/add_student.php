<?php
require "auth.php";
require "config.php";

$error = "";

$name = "";
$roll = "";
$email = "";
$course = "";
$year = "";
$phone = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"] ?? "");
    $roll = trim($_POST["roll_no"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $course = trim($_POST["course"] ?? "");
    $year = trim($_POST["year"] ?? "");
    $phone = trim($_POST["phone"] ?? "");

    if ($name === "" || $roll === "" || $email === "" || $course === "" || $year === "") {
        $error = "Please fill all required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email.";
    } else {

        $stmt = $conn->prepare(
            "INSERT INTO students (name, roll_no, email, course, year, phone)
             VALUES (?, ?, ?, ?, ?, ?)"
        );

        if (!$stmt) {
            $error = "Unable to prepare student record.";
        } else {
            $stmt->bind_param(
                "ssssss",
                $name,
                $roll,
                $email,
                $course,
                $year,
                $phone
            );

            if ($stmt->execute()) {
                $stmt->close();
                header("Location: students.php?added=1");
                exit;
            } else {
                if ($stmt->errno === 1062) {
                    $error = "Roll number or email already exists.";
                } else {
                    $error = "Unable to add student. Please check the entered details.";
                }
            }

            $stmt->close();
        }
    }
}

$title = "Add Student";
include "includes/header.php";
?>

<div class="admin-page">

    <aside class="sidebar">
        <a href="dashboard.php">
            <i class="bi bi-house-fill"></i> Dashboard
        </a>

        <a class="active" href="add_student.php">
            <i class="bi bi-person-plus-fill"></i> Add Student
        </a>

        <a href="students.php">
            <i class="bi bi-people-fill"></i> View Students
        </a>

        <a href="logout.php">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </aside>

    <main class="admin-content">

        <div class="admin-card admin-form">

            <h2>Add New Student</h2>

            <?php if ($error !== ""): ?>
                <div class="alert-msg error">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="post">

                <input
                    class="form-control"
                    name="name"
                    placeholder="Full Name"
                    value="<?= htmlspecialchars($name) ?>"
                    required
                >

                <input
                    class="form-control"
                    name="roll_no"
                    placeholder="Roll Number"
                    value="<?= htmlspecialchars($roll) ?>"
                    required
                >

                <input
                    class="form-control"
                    type="email"
                    name="email"
                    placeholder="Email"
                    value="<?= htmlspecialchars($email) ?>"
                    required
                >

                <input
                    class="form-control"
                    name="course"
                    placeholder="Course"
                    value="<?= htmlspecialchars($course) ?>"
                    required
                >

                <select class="form-select" name="year" required>
                    <option value="">Select Year</option>
                    <?php foreach (["1st Year", "2nd Year", "3rd Year", "4th Year"] as $option): ?>
                        <option value="<?= $option ?>" <?= $year === $option ? "selected" : "" ?>>
                            <?= $option ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <input
                    class="form-control"
                    name="phone"
                    placeholder="Phone"
                    value="<?= htmlspecialchars($phone) ?>"
                >

                <button type="submit" class="full-btn">
                    Add Student
                </button>

            </form>

        </div>

    </main>

</div>

<?php include "includes/footer.php"; ?>