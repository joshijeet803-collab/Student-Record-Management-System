<?php
require "config.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$title = "Contact";
$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $message = trim($_POST["message"] ?? "");

    if ($name === "" || $email === "" || $message === "") {
        $msg = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "Please enter a valid email address.";
    } elseif (
        strlen($name) > 100 ||
        strlen($email) > 100 ||
        strlen($message) > 5000
    ) {
        $msg = "Please check the length of your entered details.";
    } else {
        $userId = isset($_SESSION["user_id"])
            ? (int)$_SESSION["user_id"]
            : null;

        $stmt = $conn->prepare(
            "INSERT INTO contact_messages (user_id, name, email, message)
             VALUES (?, ?, ?, ?)"
        );

        $stmt->bind_param("isss", $userId, $name, $email, $message);

        if ($stmt->execute()) {
            $stmt->close();
            header("Location: contact.php?sent=1");
            exit;
        } else {
            $msg = "Unable to send your message. Please try again.";
            $stmt->close();
        }
    }
}

include "includes/header.php";
?>

<main class="page">
    <div class="section-title">
        <h2>Contact Us</h2>
        <p>Feel free to reach out for any queries or support.</p>
    </div>

    <?php if (isset($_GET["sent"])): ?>
        <div class="alert-msg success" style="max-width:900px;margin:0 auto 20px">
            Your message has been sent successfully.
        </div>
    <?php endif; ?>

    <?php if ($msg !== ""): ?>
        <div class="alert-msg error" style="max-width:900px;margin:0 auto 20px">
            <?= htmlspecialchars($msg) ?>
        </div>
    <?php endif; ?>

    <div class="contact-grid">
        <div>
            <div class="contact-info">
                <i class="bi bi-geo-alt-fill"></i>
                <div>
                    <h6>Address</h6>
                    <p>Marwadi University, Rajkot</p>
                </div>
            </div>

            <div class="contact-info">
                <i class="bi bi-envelope-fill"></i>
                <div>
                    <h6>Email</h6>
                    <p>support@srms.com</p>
                </div>
            </div>

            <div class="contact-info">
                <i class="bi bi-telephone-fill"></i>
                <div>
                    <h6>Phone</h6>
                    <p>+91 98765 43210</p>
                </div>
            </div>

            <div class="contact-info">
                <i class="bi bi-clock-fill"></i>
                <div>
                    <h6>Working Hours</h6>
                    <p>Mon - Sat, 9:00 AM - 5:00 PM</p>
                </div>
            </div>
        </div>

        <div class="card-box">
            <form method="post">
                <input
                    class="form-control mb-3"
                    name="name"
                    placeholder="Your Name"
                    maxlength="100"
                    value="<?= htmlspecialchars($_SESSION["user_name"] ?? "") ?>"
                    required
                >

                <input
                    class="form-control mb-3"
                    type="email"
                    name="email"
                    placeholder="Your Email"
                    maxlength="100"
                    value="<?= htmlspecialchars($_SESSION["user_email"] ?? "") ?>"
                    required
                >

                <textarea
                    class="form-control mb-3"
                    name="message"
                    rows="5"
                    placeholder="Your Message"
                    maxlength="5000"
                    required
                ></textarea>

                <button type="submit" class="full-btn">Send Message</button>
            </form>
        </div>
    </div>
</main>

<?php include "includes/footer.php"; ?>