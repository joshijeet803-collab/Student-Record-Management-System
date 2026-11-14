<?php
require "auth.php";
require "config.php";

$search = trim($_GET["search"] ?? "");
$courseFilter = trim($_GET["course"] ?? "");

$sql = "SELECT id, name, roll_no, email, course, year, phone FROM students WHERE 1=1";
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

$sql .= " ORDER BY id DESC";

$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

header("Content-Type: text/csv; charset=UTF-8");
header('Content-Disposition: attachment; filename="student_records.csv"');

$output = fopen("php://output", "w");

fwrite($output, "\xEF\xBB\xBF");

fputcsv($output, [
    "ID",
    "Student Name",
    "Roll Number",
    "Email",
    "Course",
    "Year",
    "Phone"
]);

while ($student = $result->fetch_assoc()) {
    fputcsv($output, [
        $student["id"],
        $student["name"],
        $student["roll_no"],
        $student["email"],
        $student["course"],
        $student["year"],
        $student["phone"]
    ]);
}

fclose($output);
$stmt->close();
exit;