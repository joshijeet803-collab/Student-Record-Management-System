<?php
require "config.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["reset_user_id"])) {
    header("Location: forgot_password.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $password = $_POST["password"] ?? "";
    $confirm = $_POST["confirm"] ?? "";

    if (strlen($password) < 6) {

        $error = "Password must be at least 6 characters.";

    } elseif ($password != $confirm) {

        $error = "Passwords do not match.";

    } else {

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $id = (int)$_SESSION["reset_user_id"];

        $query = "UPDATE users SET password='$hash' WHERE id=$id";

        if (mysqli_query($conn, $query)) {

            unset($_SESSION["reset_user_id"]);

            header("Location: login.php?reset=1");
            exit;

        } else {

            $error = "Password could not be changed.";
        }
    }
}

$title = "Reset Password";
include "includes/header.php";
?>

<main class="page">

    <div class="form-card">

        <h2>Reset Password</h2>

        <div class="sub">
            Create a new password for your account.
        </div>

        <?php if ($error): ?>

            <div class="alert-msg error">
                <?= htmlspecialchars($error) ?>
            </div>

        <?php endif; ?>

        <form method="post">

            <input
                class="form-control"
                type="password"
                name="password"
                placeholder="New Password"
                required
            >

            <input
                class="form-control"
                type="password"
                name="confirm"
                placeholder="Confirm Password"
                required
            >

            <button type="submit" class="full-btn">
                Update Password
            </button>

        </form>

    </div>

</main>

<?php include "includes/footer.php"; ?>