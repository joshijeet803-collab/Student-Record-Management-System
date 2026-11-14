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

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"] ?? "");

    if ($email == "") {

        $error = "Please enter your email.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Please enter a valid email.";

    } else {

        $email = mysqli_real_escape_string($conn, $email);

        $query = "SELECT id, name, email FROM users WHERE email='$email'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {

            $account = mysqli_fetch_assoc($result);

            $_SESSION["reset_user_id"] = $account["id"];

            header("Location: reset_password.php");
            exit;

        } else {

            $error = "Account not found. Please check your email.";
        }
    }
}

$title = "Forgot Password";
include "includes/header.php";
?>

<main class="page">

    <div class="form-card">

        <h2>Forgot Password?</h2>

        <div class="sub">
            Enter your email to reset your password.
        </div>

        <?php if ($error): ?>

            <div class="alert-msg error">
                <?= htmlspecialchars($error) ?>
            </div>

        <?php endif; ?>

        <form method="post">

            <input
                class="form-control"
                type="email"
                name="email"
                placeholder="Email"
                value="<?= htmlspecialchars($_POST["email"] ?? "") ?>"
                required
            >

            <button type="submit" class="full-btn">
                Continue
            </button>

        </form>

        <p class="text-center small mt-3 mb-0">
            <a href="login.php">Back to Login</a>
        </p>

    </div>

</main>

<?php include "includes/footer.php"; ?>