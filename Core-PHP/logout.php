<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$_SESSION=[];
session_destroy();
$title="Logout"; include "includes/header.php";
?>
<main class="page">
<div class="form-card text-center">
<div class="logout-icon"><i class="bi bi-check-circle-fill"></i></div>
<h3>You have been logged out.</h3>
<p class="text-muted">Thank you for using SRMS.</p>
<a class="btn-main" href="login.php">Login Again</a>
</div>
</main>
<?php include "includes/footer.php"; ?>
