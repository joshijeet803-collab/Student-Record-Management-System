<?php
require "config.php";
require "auth.php";

if (($_SESSION["user_role"] ?? "student") !== "admin") {
    header("Location: dashboard.php");
    exit;
}

$title = "Reply to Message";
$error = "";

if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

$messageId = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$messageId || $messageId < 1) {
    header("Location: messages.php");
    exit;
}

$stmt = $conn->prepare(
    "SELECT id, name, email, message, admin_reply, reply
     FROM contact_messages
     WHERE id = ?
     LIMIT 1"
);
$stmt->bind_param("i", $messageId);
$stmt->execute();
$result = $stmt->get_result();
$messageData = $result->fetch_assoc();
$stmt->close();

if (!$messageData) {
    header("Location: messages.php");
    exit;
}

$currentReply = $messageData["admin_reply"] ?: ($messageData["reply"] ?? "");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $csrfToken = $_POST["csrf_token"] ?? "";
    $replyText = trim($_POST["reply"] ?? "");

    if (!hash_equals($_SESSION["csrf_token"], $csrfToken)) {
        $error = "Invalid request. Please refresh the page and try again.";
    } elseif ($replyText === "") {
        $error = "Please enter a reply.";
    } elseif (strlen($replyText) > 10000) {
        $error = "Reply must not exceed 10000 characters.";
    } else {
        $stmt = $conn->prepare(
            "UPDATE contact_messages
             SET admin_reply = ?, reply = ?, replied_at = NOW(), status = 'Replied'
             WHERE id = ?"
        );
        $stmt->bind_param("ssi", $replyText, $replyText, $messageId);

        if ($stmt->execute()) {
            $stmt->close();
            header("Location: messages.php?replied=1");
            exit;
        }

        $error = "Unable to save the reply. Please try again.";
        $stmt->close();
    }

    $currentReply = $replyText;
}

include "includes/header.php";
?><style>
.reply-page {
    max-width: 850px;
    margin: 40px auto;
    padding: 0 20px;
}
.reply-card {
    background: #fff;
    border: 1px solid #e5ebf3;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 15px 40px rgba(16, 42, 67, .08);
}
.reply-header {
    padding: 25px 30px;
    color: #fff;
    background: linear-gradient(120deg, #102f55, #1769aa);
}
.reply-header h2 {
    margin: 0 0 7px;
    font-size: 27px;
    font-weight: 800;
}
.reply-header p {
    margin: 0;
    color: #e1edff;
}
.reply-body {
    padding: 30px;
}
.sender-info {
    padding: 18px;
    margin-bottom: 22px;
    border-radius: 12px;
    background: #f3f7fc;
}
.sender-info p {
    margin: 5px 0;
    overflow-wrap: anywhere;
}
.original-message {
    margin: 10px 0 24px;
    padding: 18px;
    border-left: 4px solid #1976d2;
    border-radius: 8px;
    background: #f8fafc;
    overflow-wrap: anywhere;
    white-space: normal;
}
.reply-label {
    display: block;
    margin-bottom: 10px;
    font-weight: 700;
    color: #243b53;
}
.reply-textarea {
    min-height: 180px;
    resize: vertical;
}
.reply-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    margin-top: 22px;
}
.reply-actions a {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 12px 20px;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 700;
    background: #edf2f7;
    color: #334e68;
}
@media(max-width: 600px) {
    .reply-page { padding: 0 12px; margin: 22px auto; }
    .reply-header, .reply-body { padding: 22px; }
    .reply-header h2 { font-size: 23px; }
}
</style><main class="reply-page">
    <div class="reply-card">
        <div class="reply-header">
            <h2><i class="bi bi-reply-fill"></i> Reply to Message</h2>
            <p>Respond to the student's enquiry.</p>
        </div>    <div class="reply-body">
        <?php if ($error !== ""): ?>
            <div class="alert-msg error">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <div class="sender-info">
            <p><strong>Message ID:</strong> #<?= (int)$messageData["id"] ?></p>
            <p><strong>Student:</strong> <?= htmlspecialchars($messageData["name"]) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($messageData["email"]) ?></p>
        </div>

        <label class="reply-label">Student's Message</label>
        <div class="original-message">
            <?= nl2br(htmlspecialchars($messageData["message"])) ?>
        </div>

        <form method="post">
            <input
                type="hidden"
                name="csrf_token"
                value="<?= htmlspecialchars($_SESSION["csrf_token"]) ?>"
            >

            <label class="reply-label" for="reply">Your Reply</label>
            <textarea
                class="form-control reply-textarea"
                id="reply"
                name="reply"
                maxlength="10000"
                placeholder="Write your reply to the student..."
                required
            ><?= htmlspecialchars($currentReply) ?></textarea>

            <div class="reply-actions">
                <button type="submit" class="full-btn">
                    <i class="bi bi-send-fill"></i>
                    Save Reply
                </button>

                <a href="messages.php">
                    <i class="bi bi-arrow-left"></i>
                    Back to Messages
                </a>
            </div>
        </form>
    </div>
</div>

</main><?php include "includes/footer.php"; ?>