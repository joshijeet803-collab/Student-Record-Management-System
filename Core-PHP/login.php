<?php
require "config.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$adminMode = (
    (isset($_GET["admin"]) && $_GET["admin"] === "1") ||
    (isset($_POST["admin"]) && $_POST["admin"] === "1")
);

if (isset($_SESSION["user_id"])) {
    if (($_SESSION["user_role"] ?? "student") === "admin") {
        header("Location: dashboard.php");
        exit;
    }

    if (!$adminMode) {
        header("Location: dashboard.php");
        exit;
    }

    session_unset();
    session_destroy();
    session_start();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($username === "" || $password === "") {
        $error = "Please enter your username and password.";
    } else {
        $stmt = $conn->prepare(
            "SELECT id, name, email, password, role
             FROM users
             WHERE name = ? OR email = ?
             LIMIT 1"
        );

        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            $validPassword = password_verify(
                $password,
                $user["password"]
            );

            if (!$validPassword && $password === $user["password"]) {
                $newPassword = password_hash(
                    $password,
                    PASSWORD_DEFAULT
                );

                $update = $conn->prepare(
                    "UPDATE users SET password = ? WHERE id = ?"
                );

                $update->bind_param("si", $newPassword, $user["id"]);
                $update->execute();
                $update->close();

                $validPassword = true;
            }

            if ($validPassword) {
                if ($adminMode && $user["role"] !== "admin") {
                    $error = "Access denied. Admin account required.";
                } else {
                    session_regenerate_id(true);

                    $_SESSION["user_id"] = $user["id"];
                    $_SESSION["user_name"] = $user["name"];
                    $_SESSION["user_email"] = $user["email"];
                    $_SESSION["user_role"] = $user["role"];

                    header("Location: dashboard.php");
                    exit;
                }
            } else {
                $error = "Invalid username or password.";
            }
        } else {
            $error = "Invalid username or password.";
        }

        $stmt->close();
    }
}

$title = $adminMode ? "Admin Login" : "Login";
include "includes/header.php";
?>

<main class="page">
    <div class="form-card">

        <h2>
            <?= $adminMode ? "Admin Login" : "Welcome Back" ?>
        </h2>

        <div class="sub">
            <?= $adminMode
                ? "Secure administrator access"
                : "Login to continue" ?>
        </div>

        <?php if (isset($_GET["registered"])): ?>
            <div class="alert-msg success">
                Registration successful. Please login.
            </div>
        <?php endif; ?>

        <?php if (isset($_GET["reset"])): ?>
            <div class="alert-msg success">
                Password changed successfully. Please login.
            </div>
        <?php endif; ?>

        <?php if ($error !== ""): ?>
            <div class="alert-msg error">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= $adminMode ? 'login.php?admin=1' : 'login.php' ?>">

            <?php if ($adminMode): ?>
                <input type="hidden" name="admin" value="1">
            <?php endif; ?>

            <input
                class="form-control"
                name="username"
                value="<?= htmlspecialchars($_POST["username"] ?? "") ?>"
                placeholder="Name or Email"
                autocomplete="username"
                required
            >

            <input
                class="form-control"
                type="password"
                name="password"
                placeholder="Password"
                autocomplete="current-password"
                required
            >

            <?php if (!$adminMode): ?>
                <div class="d-flex justify-content-between small mb-3">
                    <label>
                        <input type="checkbox" name="remember">
                        Remember me
                    </label>

                    <a href="forgot_password.php">Forgot Password?</a>
                </div>
            <?php endif; ?>

            <button type="submit" class="full-btn">
                <?= $adminMode ? "Login as Admin" : "Login" ?>
            </button>

        </form>

        <?php if (!$adminMode): ?>
            <p class="text-center small mt-3 mb-0">
                Don't have an account?
                <a href="register.php">Register</a>
            </p>
        <?php else: ?>
            <p class="text-center small mt-3 mb-0">
                <a href="login.php">Student Login</a>
            </p>
        <?php endif; ?>

    </div>
</main>

<?php include "includes/footer.php"; ?>