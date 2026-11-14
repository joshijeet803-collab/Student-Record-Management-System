<?php
require "config.php";
require "auth.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: messages.php");
    exit;
}

$userId = (int)$_SESSION["user_id"];
$userRole = $_SESSION["user_role"] ?? "student";
$isAdmin = ($userRole === "admin");

$token = $_POST["csrf_token"] ?? "";

if (
    empty($_SESSION["csrf_token"]) ||
    !hash_equals($_SESSION["csrf_token"], $token)
) {
    http_response_code(403);
    exit("Invalid request token. Please try again.");
}

$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    http_response_code(400);
    exit("Invalid message ID.");
}

if ($isAdmin) {
    $stmt = $conn->prepare(
        "DELETE FROM contact_messages WHERE id = ?"
    );
    $stmt->bind_param("i", $id);
} else {
    $stmt = $conn->prepare(
        "DELETE FROM contact_messages WHERE id = ? AND user_id = ?"
    );
    $stmt->bind_param("ii", $id, $userId);
}

$stmt->execute();
$deleted = $stmt->affected_rows;
$stmt->close();

if ($deleted > 0) {
    header("Location: messages.php?deleted=1");
    exit;
}

http_response_code(404);
exit("Message not found or you do not have permission to delete it.");