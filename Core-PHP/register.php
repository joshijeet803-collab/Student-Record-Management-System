<?php
require "config.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION["user_id"])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirm = $_POST["confirm"] ?? "";

    if ($name === "" || $email === "" || $password === "" || $confirm === "") {
        $error = "Please fill all required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $error = "Email already exists.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare(
                "INSERT INTO users (name, email, password, role)
                 VALUES (?, ?, ?, 'student')"
            );

            $stmt->bind_param("sss", $name, $email, $hash);

            if ($stmt->execute()) {
                header("Location: login.php?registered=1");
                exit;
            } else {
                $error = "Registration failed. Please try again.";
            }

            $stmt->close();
        }

        $check->close();
    }
}

$title = "Register";
include "includes/header.php";
?>

<main class="page">
    <div class="form-card">
        <h2>Create Account</h2>
        <div class="sub">Register to access the system</div>

        <?php if ($error !== ""): ?>
            <div class="alert-msg error">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <input
                class="form-control"
                name="name"
                value="<?= htmlspecialchars($_POST["name"] ?? "") ?>"
                placeholder="Full Name"
                required
            >

            <input
                class="form-control"
                type="email"
                name="email"
                value="<?= htmlspecialchars($_POST["email"] ?? "") ?>"
                placeholder="Email"
                required
            >

            <input
                class="form-control"
                type="password"
                name="password"
                placeholder="Password"
                required
            >

            <input
                class="form-control"
                type="password"
                name="confirm"
                placeholder="Confirm Password"
                required
            >

            <button type="submit" class="full-btn">Register</button>
        </form>

        <p class="text-center small mt-3 mb-0">
            Already have an account?
            <a href="login.php">Login</a>
        </p>
    </div>
</main>

<?php include "includes/footer.php"; ?>