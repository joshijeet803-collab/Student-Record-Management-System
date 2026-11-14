<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$title = $title ?? "Student Record Management System";
$isLoggedIn = isset($_SESSION["user_id"]);
$isAdmin = isset($_SESSION["user_role"]) && $_SESSION["user_role"] === "admin";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?> | SRMS</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">

    <style>
        .admin-nav-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 8px 13px;
            border-radius: 9px;
            color: #fff !important;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .2px;
            background: linear-gradient(135deg, #173b70, #087cf0);
            border: 1px solid rgba(255,255,255,.2);
            box-shadow: 0 3px 10px rgba(0,65,150,.2);
            transition: all .25s ease;
        }

        .admin-nav-btn:hover {
            color: #fff !important;
            background: linear-gradient(135deg, #0d5ac2, #19a4ff);
            transform: translateY(-2px);
            box-shadow: 0 5px 14px rgba(0,110,255,.3);
        }

        .admin-nav-btn i {
            font-size: 13px;
            transition: transform .25s ease;
        }

        .admin-nav-btn:hover i {
            transform: scale(1.12);
        }

        @media (max-width: 768px) {
            .admin-nav-btn {
                padding: 7px 10px;
                font-size: 11px;
            }
        }
    </style>
</head>

<body>

<header class="topbar">

    <a href="<?= $isLoggedIn ? 'dashboard.php' : 'index.php' ?>" class="brand">
        <i class="bi bi-mortarboard-fill"></i> SRMS
    </a>

    <?php if ($isLoggedIn && $isAdmin): ?>

        <nav class="main-nav">
            <a href="dashboard.php">Dashboard</a>
            <a href="students.php">Students</a>
            <a href="add_student.php">Add Student</a>
            <a href="messages.php">All Messages</a>
            <a href="logout.php" class="nav-btn">Logout</a>
        </nav>

    <?php elseif ($isLoggedIn): ?>

        <nav class="main-nav">
            <a href="dashboard.php">Dashboard</a>
            <a href="messages.php">My Messages</a>
            <a href="contact.php">Contact</a>
            <a href="logout.php" class="nav-btn">Logout</a>
        </nav>

    <?php else: ?>

        <nav class="main-nav">
            <a href="index.php">Home</a>
            <a href="about.php">About</a>
            <a href="contact.php">Contact</a>
            <a href="login.php" class="nav-btn light">Login</a>
            <a href="register.php" class="nav-btn">Register</a>

            <a href="admin_login.php" class="admin-nav-btn">
                <i class="bi bi-shield-lock-fill"></i>
                <span>Admin</span>
            </a>
        </nav>

    <?php endif; ?>

</header>