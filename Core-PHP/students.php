<?php
require "auth.php";
require "config.php";

$search = trim($_GET["search"] ?? "");
$courseFilter = trim($_GET["course"] ?? "");

$courses = [];
$courseResult = $conn->query(
    "SELECT DISTINCT course FROM students
     WHERE course IS NOT NULL AND course != ''
     ORDER BY course"
);

if ($courseResult) {
    while ($row = $courseResult->fetch_assoc()) {
        $courses[] = $row["course"];
    }
}

$sql = "SELECT * FROM students WHERE 1=1";
$params = [];
$types = "";

if ($search !== "") {
    $sql .= " AND (name LIKE ? OR roll_no LIKE ?)";
    $term = "%" . $search . "%";
    $params[] = $term;
    $params[] = $term;
    $types .= "ss";
}

if ($courseFilter !== "") {
    $sql .= " AND course = ?";
    $params[] = $courseFilter;
    $types .= "s";
}

$sql .= " ORDER BY id ASC";

$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$title = "View Students";
include "includes/header.php";
?>

<style>
.students-container {
    max-width: 1350px;
    margin: 0 auto;
    padding: 40px 25px;
}

.students-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    margin-bottom: 25px;
}

.students-heading h2 {
    color: #172b40;
    margin-bottom: 5px;
}

.students-heading p {
    color: #6c757d;
    margin: 0;
}

.students-card {
    background: #fff;
    padding: 24px;
    border-radius: 10px;
    box-shadow: 0 5px 22px rgba(30, 60, 90, 0.06);
}

.student-filters {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 22px;
}

.student-filters input,
.student-filters select {
    min-width: 180px;
    flex: 1;
    padding: 10px 12px;
    border: 1px solid #d8e0e8;
    border-radius: 6px;
}

.students-table {
    width: 100%;
    border-collapse: collapse;
}

.students-table th {
    background: #f2f5fa;
    padding: 13px 10px;
    font-size: 12px;
    text-align: left;
    white-space: nowrap;
}

.students-table td {
    padding: 13px 10px;
    border-bottom: 1px solid #e5e9ef;
    font-size: 13px;
}

.action-buttons {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
}

.btn-view,
.btn-edit,
.btn-delete {
    display: inline-block;
    padding: 6px 10px;
    border-radius: 4px;
    text-decoration: none;
    color: #fff;
    font-size: 12px;
    border: 0;
}

.btn-view {
    background: #218653;
}

.btn-edit {
    background: #087bf5;
}

.btn-delete {
    background: #dc3545;
}

.btn-view:hover,
.btn-edit:hover,
.btn-delete:hover {
    color: #fff;
    opacity: 0.88;
}

@media (max-width: 700px) {
    .students-container {
        padding: 25px 12px;
    }

    .students-heading {
        align-items: flex-start;
        flex-direction: column;
    }

    .students-card {
        padding: 14px;
    }
}
</style>

<main class="students-container">

    <div class="students-heading">
        <div>
            <h2>Student List</h2>
            <p>Search, filter and manage student records.</p>
        </div>

        <a class="btn btn-primary" href="add_student.php">
            + Add Student
        </a>
    </div>

    <?php if (isset($_GET["added"])): ?>
        <div class="alert alert-success">Student added successfully.</div>
    <?php endif; ?>

    <?php if (isset($_GET["updated"])): ?>
        <div class="alert alert-success">Student updated successfully.</div>
    <?php endif; ?>

    <?php if (isset($_GET["deleted"])): ?>
        <div class="alert alert-success">Student deleted successfully.</div>
    <?php endif; ?>

    <section class="students-card">

        <form method="GET" action="students.php" class="student-filters">

            <input
                type="text"
                name="search"
                placeholder="Search name or roll number"
                value="<?= htmlspecialchars($search) ?>"
            >

            <select name="course">
                <option value="">All Courses</option>

                <?php foreach ($courses as $course): ?>
                    <option
                        value="<?= htmlspecialchars($course) ?>"
                        <?= $courseFilter === $course ? "selected" : "" ?>
                    >
                        <?= htmlspecialchars($course) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn btn-primary">
                Search / Filter
            </button>

            <a href="students.php" class="btn btn-outline-secondary">
                Clear
            </a>

            <a
                href="export_students.php?search=<?= urlencode($search) ?>&course=<?= urlencode($courseFilter) ?>"
                class="btn btn-success"
            >
                <i class="bi bi-download"></i> Export CSV
            </a>

        </form>

        <div class="table-responsive">
            <table class="students-table">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Student Name</th>
                        <th>Roll Number</th>
                        <th>Email</th>
                        <th>Course</th>
                        <th>Year</th>
                        <th>Phone</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                <?php
                $displayId = 1;

                if ($result && $result->num_rows > 0):
                    while ($student = $result->fetch_assoc()):
                ?>
                    <tr>
                        <td><?= $displayId++ ?></td>
                        <td><?= htmlspecialchars($student["name"]) ?></td>
                        <td><?= htmlspecialchars($student["roll_no"]) ?></td>
                        <td><?= htmlspecialchars($student["email"]) ?></td>
                        <td><?= htmlspecialchars($student["course"]) ?></td>
                        <td><?= htmlspecialchars((string)$student["year"]) ?></td>
                        <td><?= htmlspecialchars($student["phone"] ?? "") ?></td>

                        <td>
                            <div class="action-buttons">

                                <a
                                    class="btn-view"
                                    href="view_student.php?id=<?= (int)$student["id"] ?>"
                                >View</a>

                                <a
                                    class="btn-edit"
                                    href="edit_student.php?id=<?= (int)$student["id"] ?>"
                                >Edit</a>

                                <a
                                    class="btn-delete"
                                    href="delete_student.php?id=<?= (int)$student["id"] ?>"
                                    onclick="return confirm('Are you sure you want to delete this student?')"
                                >Delete</a>

                            </div>
                        </td>
                    </tr>

                <?php
                    endwhile;
                else:
                ?>
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            No student records found.
                        </td>
                    </tr>
                <?php endif; ?>

                </tbody>
            </table>
        </div>

    </section>

</main>

<?php
$stmt->close();
include "includes/footer.php";
?>