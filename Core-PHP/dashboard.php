<?php
require "auth.php";
require "config.php";

$total = 0;
$courses = 0;
$recentStudents = [];

$result = $conn->query("SELECT COUNT(*) AS total FROM students");
if ($result) {
    $total = (int)$result->fetch_assoc()["total"];
}

$result = $conn->query("SELECT COUNT(DISTINCT course) AS total FROM students");
if ($result) {
    $courses = (int)$result->fetch_assoc()["total"];
}

$result = $conn->query("SELECT id, name, roll_no, course FROM students ORDER BY id DESC LIMIT 5");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $recentStudents[] = $row;
    }
}

$title = "Dashboard";
include "includes/header.php";
?>

<style>
.dashboard-container {
    max-width: 1300px;
    margin: 0 auto;
    padding: 45px 30px;
}

.dashboard-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    margin-bottom: 38px;
}

.dashboard-heading h1 {
    font-size: 36px;
    font-weight: 400;
    margin-bottom: 8px;
    color: #172b40;
}

.dashboard-heading p {
    color: #6c757d;
    margin: 0;
}

.dashboard-btn {
    background: #087bf5;
    color: #fff;
    text-decoration: none;
    padding: 14px 22px;
    border-radius: 6px;
    white-space: nowrap;
}

.dashboard-btn:hover {
    background: #0668d0;
    color: #fff;
}

.dashboard-stats {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 22px;
    margin-bottom: 24px;
}

.dashboard-stat,
.recent-card {
    background: #fff;
    border-radius: 10px;
    padding: 24px;
    box-shadow: 0 5px 22px rgba(30, 60, 90, 0.06);
}

.dashboard-stat small {
    color: #747b83;
    font-size: 15px;
}

.dashboard-stat h2 {
    font-size: 30px;
    margin: 8px 0;
    color: #202b36;
}

.dashboard-stat a {
    color: #4779b5;
    text-decoration: none;
}

.recent-card {
    padding: 25px;
    margin-top: 24px;
}

.recent-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    margin-bottom: 8px;
}

.recent-header h3 {
    font-size: 25px;
    margin: 0;
    color: #263442;
}

.recent-subtitle {
    color: #777;
    margin-bottom: 18px;
}

.dashboard-table {
    width: 100%;
    border-collapse: collapse;
}

.dashboard-table th {
    background: #f2f5fa;
    font-size: 12px;
    padding: 12px 10px;
    text-align: left;
}

.dashboard-table td {
    padding: 12px 10px;
    border-bottom: 1px solid #e1e5ea;
    font-size: 13px;
}

.view-btn {
    display: inline-block;
    background: #218653;
    color: #fff;
    padding: 6px 13px;
    border-radius: 4px;
    text-decoration: none;
}

.view-btn:hover {
    background: #176b40;
    color: #fff;
}

@media (max-width: 700px) {
    .dashboard-container {
        padding: 25px 15px;
    }

    .dashboard-heading {
        align-items: flex-start;
        flex-direction: column;
    }

    .dashboard-heading h1 {
        font-size: 29px;
    }

    .dashboard-stats {
        grid-template-columns: 1fr;
    }

    .recent-card {
        padding: 16px;
    }

    .recent-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .dashboard-table th,
    .dashboard-table td {
        padding: 10px 7px;
    }
}
</style>

<main class="dashboard-container">

    <div class="dashboard-heading">
        <div>
            <h1>Welcome, <?= htmlspecialchars($_SESSION["user_name"]) ?>!</h1>
            <p>Student Record Management System Dashboard</p>
        </div>

        <a href="add_student.php" class="dashboard-btn">
            + Add Student
        </a>
    </div>

    <div class="dashboard-stats">

        <div class="dashboard-stat">
            <small>Total Students</small>
            <h2><?= $total ?></h2>
            <a href="students.php">View all students →</a>
        </div>

        <div class="dashboard-stat">
            <small>Total Courses</small>
            <h2><?= $courses ?></h2>
            <a href="students.php">Manage records →</a>
        </div>

    </div>

    <section class="recent-card">

        <div class="recent-header">
            <h3>Recently Added Students</h3>

            <a href="students.php" class="btn btn-outline-primary">
                View All Students
            </a>
        </div>

        <p class="recent-subtitle">Your latest student records.</p>

        <div class="table-responsive">
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Student Name</th>
                        <th>Roll Number</th>
                        <th>Course</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (count($recentStudents) > 0): ?>
                        <?php foreach ($recentStudents as $student): ?>
                            <tr>
                                <td><?= (int)$student["id"] ?></td>
                                <td><?= htmlspecialchars($student["name"]) ?></td>
                                <td><?= htmlspecialchars($student["roll_no"]) ?></td>
                                <td><?= htmlspecialchars($student["course"]) ?></td>
                                <td>
                                    <a class="view-btn" href="view_student.php?id=<?= (int)$student["id"] ?>">
                                        View
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                No student records found.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </section>

</main>

<?php include "includes/footer.php"; ?>