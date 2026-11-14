<?php
require "config.php";
require "auth.php";

$userId = (int)$_SESSION["user_id"];
$userRole = $_SESSION["user_role"] ?? "student";
$isAdmin = ($userRole === "admin");
$error = "";

if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
}

if (!$id || $id < 1) {
    http_response_code(400);
    exit("Invalid message ID.");
}

if ($isAdmin) {
    $stmt = $conn->prepare(
        "SELECT id, user_id, name, email, message, status
         FROM contact_messages WHERE id = ?"
    );
    $stmt->bind_param("i", $id);
} else {
    $stmt = $conn->prepare(
        "SELECT id, user_id, name, email, message, status
         FROM contact_messages WHERE id = ? AND user_id = ?"
    );
    $stmt->bind_param("ii", $id, $userId);
}

$stmt->execute();
$messageData = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$messageData) {
    http_response_code(403);
    exit("You are not allowed to edit this message.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = $_POST["csrf_token"] ?? "";

    if (!hash_equals($_SESSION["csrf_token"], $token)) {
        exit("Invalid request token. Please go back and try again.");
    }

    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $message = trim($_POST["message"] ?? "");
    $status = $_POST["status"] ?? "New";

    if ($name === "" || $email === "" || $message === "") {
        $error = "Please fill in all required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif ($isAdmin && !in_array($status, ["New", "Read", "Replied"], true)) {
        $error = "Invalid message status.";
    } else {
        if ($isAdmin) {
            $stmt = $conn->prepare(
                "UPDATE contact_messages
                 SET name = ?, email = ?, message = ?, status = ?
                 WHERE id = ?"
            );
            $stmt->bind_param("ssssi", $name, $email, $message, $status, $id);
        } else {
            $stmt = $conn->prepare(
                "UPDATE contact_messages
                 SET message = ?
                 WHERE id = ? AND user_id = ?"
            );
            $stmt->bind_param("sii", $message, $id, $userId);
        }

        if ($stmt->execute() && $stmt->affected_rows >= 0) {
            $stmt->close();
            header("Location: messages.php?updated=1");
            exit;
        }

        $stmt->close();
        $error = "Unable to update the message. Please try again.";
    }

    $messageData["name"] = $name;
    $messageData["email"] = $email;
    $messageData["message"] = $message;
    $messageData["status"] = $status;
}

$title = "Edit Message";
include "includes/header.php";
?>

<main class="page">
    <div class="form-card">
        <h2>Edit Message</h2>
        <div class="sub">Update your message details below.</div>

        <?php if ($error !== ""): ?>
            <div class="alert-msg error">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <input type="hidden" name="id" value="<?= (int)$id ?>">
            <input
                type="hidden"
                name="csrf_token"
                value="<?= htmlspecialchars($_SESSION["csrf_token"]) ?>"
            >

            <label class="form-label">Name</label>
            <input
                class="form-control mb-3"
                name="name"
                value="<?= htmlspecialchars($messageData["name"]) ?>"
                <?= $isAdmin ? "" : "readonly" ?>
                required
            >

            <label class="form-label">Email</label>
            <input
                class="form-control mb-3"
                type="email"
                name="email"
                value="<?= htmlspecialchars($messageData["email"]) ?>"
                <?= $isAdmin ? "" : "readonly" ?>
                required
            >

            <label class="form-label">Message</label>
            <textarea
                class="form-control mb-3"
                name="message"
                rows="5"
                required
            ><?= htmlspecialchars($messageData["message"]) ?></textarea>

            <?php if ($isAdmin): ?>
                <label class="form-label">Status</label>
                <select class="form-select mb-3" name="status">
                    <?php foreach (["New", "Read", "Replied"] as $option): ?>
                        <option
                            value="<?= $option ?>"
                            <?= $messageData["status"] === $option ? "selected" : "" ?>
                        >
                            <?= $option ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>

            <button type="submit" class="full-btn">Save Changes</button>

            <a href="messages.php" class="btn btn-outline-secondary w-100 mt-2">
                Cancel
            </a>
        </form>
    </div>
</main>

<?php include "includes/footer.php"; ?>