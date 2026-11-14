<?php
require "config.php";
require "auth.php";

$title = "Messages";

$userId = (int)$_SESSION["user_id"];
$userRole = $_SESSION["user_role"] ?? "student";
$isAdmin = ($userRole === "admin");

if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

if ($isAdmin) {
    $stmt = $conn->prepare(
        "SELECT id, user_id, name, email, message, status,
                admin_reply, reply, replied_at, created_at
         FROM contact_messages
         ORDER BY id DESC"
    );
} else {
    $stmt = $conn->prepare(
        "SELECT id, user_id, name, email, message, status,
                admin_reply, reply, replied_at, created_at
         FROM contact_messages
         WHERE user_id = ?
         ORDER BY id DESC"
    );
    $stmt->bind_param("i", $userId);
}

$stmt->execute();
$result = $stmt->get_result();

include "includes/header.php";
?><style>
.messages-wrap {
    max-width: 1400px;
    margin: 35px auto;
    padding: 0 22px;
}
.messages-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    flex-wrap: wrap;
    margin-bottom: 25px;
}
.messages-heading h2 {
    font-size: 30px;
    font-weight: 800;
    margin: 0 0 6px;
    color: #102a43;
}
.messages-heading p {
    margin: 0;
    color: #718096;
}
.messages-count {
    background: #e8f1ff;
    color: #1458b5;
    padding: 10px 17px;
    border-radius: 12px;
    font-weight: 700;
}
.messages-panel {
    background: #fff;
    border: 1px solid #e8edf4;
    border-radius: 18px;
    box-shadow: 0 12px 35px rgba(16,42,67,.07);
    overflow: hidden;
}
.messages-panel-top {
    padding: 20px 24px;
    border-bottom: 1px solid #edf1f6;
    font-weight: 700;
    color: #243b53;
}
.messages-table {
    margin: 0;
    min-width: 1100px;
    vertical-align: middle;
}
.messages-table thead th {
    background: #f5f8fc;
    color: #526579;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: .6px;
    padding: 17px 15px;
    border-bottom: 1px solid #e7edf5;
}
.messages-table tbody td {
    padding: 17px 15px;
    border-color: #edf1f6;
    color: #334e68;
}
.messages-table tbody tr:hover {
    background: #f8fbff;
}
.message-text {
    min-width: 180px;
    max-width: 280px;
    white-space: normal;
    overflow-wrap: anywhere;
}
.reply-box {
    min-width: 200px;
    max-width: 280px;
    padding: 12px;
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-radius: 10px;
    white-space: normal;
    overflow-wrap: anywhere;
}
.reply-date {
    display: block;
    margin-top: 7px;
    font-size: 11px;
    color: #64748b;
}
.status-pill {
    display: inline-block;
    padding: 6px 11px;
    border-radius: 30px;
    font-size: 12px;
    font-weight: 700;
}
.status-new {
    color: #175cd3;
    background: #eaf2ff;
}
.status-read {
    color: #8a5700;
    background: #fff3d6;
}
.status-replied {
    color: #087443;
    background: #e4f8ed;
}
.message-actions {
    display: flex;
    gap: 7px;
    align-items: center;
    flex-wrap: wrap;
}
.message-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 8px 11px;
    border: 0;
    border-radius: 8px;
    text-decoration: none;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    white-space: nowrap;
}
.edit-btn {
    color: #175cd3;
    background: #eaf2ff;
}
.reply-btn {
    color: #087443;
    background: #e4f8ed;
}
.delete-btn {
    color: #c62828;
    background: #fff0f0;
}
.empty-messages {
    text-align: center;
    padding: 65px 20px;
    color: #718096;
}
.empty-messages i {
    font-size: 45px;
    color: #a5b4c5;
}
.empty-messages h5 {
    color: #243b53;
    margin-top: 15px;
    font-weight: 700;
}
@media (max-width: 768px) {
    .messages-wrap {
        padding: 0 12px;
        margin: 22px auto;
    }
    .messages-heading h2 {
        font-size: 25px;
    }
    .messages-panel-top {
        padding: 17px;
    }
}
</style><main class="messages-wrap"><div class="messages-heading">
    <div>
        <h2>
            <i class="bi bi-chat-square-text-fill"></i>
            <?= $isAdmin ? "All Messages" : "My Messages" ?>
        </h2>
        <p>
            <?= $isAdmin
                ? "Review messages and reply to students."
                : "View your submitted messages and admin replies." ?>
        </p>
    </div>

    <div class="messages-count">
        <i class="bi bi-envelope-fill"></i>
        <?= $result->num_rows ?> Messages
    </div>
</div>

<div class="messages-panel">
    <div class="messages-panel-top">
        <i class="bi bi-inbox-fill"></i>
        Message Records
    </div>

    <?php if ($result->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table messages-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <?php if ($isAdmin): ?>
                            <th>Name</th>
                            <th>Email</th>
                        <?php endif; ?>
                        <th>Message</th>
                        <th>Admin Reply</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <?php
                        $status = $row["status"] ?? "New";

                        $statusClass = match ($status) {
                            "Read" => "status-read",
                            "Replied" => "status-replied",
                            default => "status-new"
                        };

                        $adminReply = !empty($row["admin_reply"])
                            ? $row["admin_reply"]
                            : ($row["reply"] ?? "");
                        ?>
                        <tr>
                            <td>
                                <strong>#<?= (int)$row["id"] ?></strong>
                            </td>

                            <?php if ($isAdmin): ?>
                                <td><?= htmlspecialchars($row["name"]) ?></td>
                                <td><?= htmlspecialchars($row["email"]) ?></td>
                            <?php endif; ?>

                            <td class="message-text">
                                <?= nl2br(htmlspecialchars($row["message"])) ?>
                            </td>

                            <td>
                                <?php if ($adminReply !== ""): ?>
                                    <div class="reply-box">
                                        <?= nl2br(htmlspecialchars($adminReply)) ?>
                                        <?php if (!empty($row["replied_at"])): ?>
                                            <span class="reply-date">
                                                Replied:
                                                <?= htmlspecialchars($row["replied_at"]) ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted">Not replied yet</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <span class="status-pill <?= $statusClass ?>">
                                    <?= htmlspecialchars($status) ?>
                                </span>
                            </td>

                            <td><?= htmlspecialchars($row["created_at"]) ?></td>

                            <td>
                                <div class="message-actions">
                                    <?php if ($isAdmin): ?>
                                        <a
                                            class="message-btn reply-btn"
                                            href="reply_message.php?id=<?= (int)$row["id"] ?>"
                                        >
                                            <i class="bi bi-reply-fill"></i>
                                            Reply
                                        </a>
                                    <?php endif; ?>

                                    <a
                                        class="message-btn edit-btn"
                                        href="edit_message.php?id=<?= (int)$row["id"] ?>"
                                    >
                                        <i class="bi bi-pencil-square"></i>
                                        Edit
                                    </a>

                                    <form
                                        method="post"
                                        action="delete_message.php"
                                        onsubmit="return confirm('Are you sure you want to delete this message?');"
                                    >
                                        <input type="hidden" name="id"
                                            value="<?= (int)$row["id"] ?>">
                                        <input type="hidden" name="csrf_token"
                                            value="<?= htmlspecialchars($_SESSION["csrf_token"]) ?>">

                                        <button class="message-btn delete-btn" type="submit">
                                            <i class="bi bi-trash3-fill"></i>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="empty-messages">
            <i class="bi bi-chat-square-dots"></i>
            <h5>No Messages Found</h5>
            <p class="mb-0">
                Contact messages will appear here when available.
            </p>
        </div>
    <?php endif; ?>
</div>

</main><?php
$stmt->close();
include "includes/footer.php";
?>