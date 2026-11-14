<?php
require "auth.php";
require "config.php";

$id = (int)($_GET["id"] ?? $_POST["id"] ?? 0);

$result = mysqli_query($conn, "SELECT * FROM students WHERE id=$id");
$student = mysqli_fetch_assoc($result);

if (!$student) {
    header("Location: students.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"] ?? "");
    $roll = trim($_POST["roll_no"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $course = trim($_POST["course"] ?? "");
    $year = trim($_POST["year"] ?? "");
    $phone = trim($_POST["phone"] ?? "");

    if ($name == "" || $roll == "" || $email == "" || $course == "" || $year == "") {
        $error = "Please fill all required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email.";
    } else {

        $name = mysqli_real_escape_string($conn, $name);
        $roll = mysqli_real_escape_string($conn, $roll);
        $email = mysqli_real_escape_string($conn, $email);
        $course = mysqli_real_escape_string($conn, $course);
        $year = mysqli_real_escape_string($conn, $year);
        $phone = mysqli_real_escape_string($conn, $phone);

        $query = "UPDATE students SET
        name='$name',
        roll_no='$roll',
        email='$email',
        course='$course',
        year='$year',
        phone='$phone'
        WHERE id=$id";

        if (mysqli_query($conn, $query)) {
            header("Location: students.php?updated=1");
            exit;
        } else {
            $error = "Unable to update student. Roll number may already exist.";
        }

        $student["name"] = $name;
        $student["roll_no"] = $roll;
        $student["email"] = $email;
        $student["course"] = $course;
        $student["year"] = $year;
        $student["phone"] = $phone;
    }
}

$title = "Edit Student";
include "includes/header.php";
?>

<div class="admin-page">

    <aside class="sidebar">

        <a href="dashboard.php">
            <i class="bi bi-house-fill"></i> Dashboard
        </a>

        <a href="add_student.php">
            <i class="bi bi-person-plus-fill"></i> Add Student
        </a>

        <a class="active" href="students.php">
            <i class="bi bi-people-fill"></i> View Students
        </a>

        <a href="logout.php">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>

    </aside>

    <main class="admin-content">

        <div class="admin-card admin-form">

            <h2>Edit Student</h2>

            <?php if ($error): ?>
                <div class="alert-msg error">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="post">

                <input type="hidden" name="id" value="<?= $id ?>">

                <input
                    class="form-control"
                    name="name"
                    value="<?= htmlspecialchars($student["name"]) ?>"
                    placeholder="Full Name"
                    required
                >

                <input
                    class="form-control"
                    name="roll_no"
                    value="<?= htmlspecialchars($student["roll_no"]) ?>"
                    placeholder="Roll Number"
                    required
                >

                <input
                    class="form-control"
                    type="email"
                    name="email"
                    value="<?= htmlspecialchars($student["email"]) ?>"
                    placeholder="Email"
                    required
                >

                <input
                    class="form-control"
                    name="course"
                    value="<?= htmlspecialchars($student["course"]) ?>"
                    placeholder="Course"
                    required
                >

                <select class="form-select" name="year" required>
                    <option value="">Select Year</option>
                    <option <?= $student["year"] == "1st Year" ? "selected" : "" ?>>1st Year</option>
                    <option <?= $student["year"] == "2nd Year" ? "selected" : "" ?>>2nd Year</option>
                    <option <?= $student["year"] == "3rd Year" ? "selected" : "" ?>>3rd Year</option>
                    <option <?= $student["year"] == "4th Year" ? "selected" : "" ?>>4th Year</option>
                </select>

                <input
                    class="form-control"
                    name="phone"
                    value="<?= htmlspecialchars($student["phone"]) ?>"
                    placeholder="Phone"
                >

                <button type="submit" class="full-btn">
                    Update Student
                </button>

            </form>

        </div>

    </main>

</div>

<?php include "includes/footer.php"; ?>