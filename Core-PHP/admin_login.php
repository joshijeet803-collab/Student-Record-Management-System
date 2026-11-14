<?php
session_start();

if (isset($_SESSION["user_id"]) &&
    ($_SESSION["user_role"] ?? "") === "admin") {
    header("Location: dashboard.php");
    exit;
}

if (isset($_SESSION["user_id"])) {
    header("Location: logout.php");
    exit;
}

header("Location: login.php?admin=1");
exit;
?>