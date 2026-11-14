<?php
require "auth.php";
require "config.php";

$id = (int)($_GET["id"] ?? 0);

$result = mysqli_query($conn, "SELECT name FROM students WHERE id=$id");
$student = mysqli_fetch_assoc($result);

if (!$student) {
    header("Location: students.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $query = "DELETE FROM students WHERE id=$id";

    if (mysqli_query($conn, $query)) {
        header("Location: students.php?deleted=1");
        exit;
    }
}

$title = "Delete Student";
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

        <div class="admin-card delete-box">

            <div class="delete-icon">
                <i class="bi bi-exclamation-circle-fill"></i>
            </div>

            <h3>Delete Student</h3>

            <p>
                Are you sure you want to delete
                <b><?= htmlspecialchars($student["name"]) ?></b>?
            </p>

            <form method="post">

                <a href="students.php" class="btn btn-light me-2">
                    Cancel
                </a>

                <button type="submit" class="btn btn-danger">
                    Delete
                </button>

            </form>

        </div>

    </main>

</div>

<?php include "includes/footer.php"; ?>