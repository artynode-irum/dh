<?php
session_start();
include '../include/config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

$certificate_id = $_GET['certificate_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['message'])) {
    $message = htmlspecialchars($_POST['message']);
    $sender = 'Admin'; // Since the patient is sending the message

    $stmt = $conn->prepare("INSERT INTO chat_messages (medical_certificate_id, sender, message) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $certificate_id, $sender, $message);
    $stmt->execute();
    $stmt->close();

    // Redirect to prevent resubmission on page refresh
    header("Location: chat.php?certificate_id=" . $certificate_id);
    exit();
}

$query = "SELECT sender, message, timestamp FROM chat_messages WHERE medical_certificate_id = ? ORDER BY timestamp ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $certificate_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<?php include("include/sidebar.php"); ?>

<div class="second-section">
    <section>
        <div class="navbar">
            <div class="page-title">
                <span>Medical Certificate</span>
            </div>
            <?php include("include/navbar.php"); ?>
        </div>
        <div>
            <h2 class="page-header">Chat for Medical Certificate ID: <?php echo $certificate_id; ?></h2> <br>

            <div class="chat-box">
                <?php
                while ($row = $result->fetch_assoc()) {
                    $sender = htmlspecialchars($row['sender']);
                    $message = htmlspecialchars($row['message']);
                    $timestamp = htmlspecialchars($row['timestamp']);

                    echo "<div class='chat-message'>";
                    echo "<strong>{$sender}:</strong> {$message} <br><small>{$timestamp}</small>";
                    echo "</div>";
                }
                ?>
            </div>

            <form method="POST" action="">
                <br>
                <textarea name="message" required placeholder="Type your message here..."></textarea><br>
                <br>
                <button type="submit" class="button-class">Send</button>
            </form>
        </div>
    </section>
    <?php include("include/footer.php"); ?>
</div>
<script src="assets/script.js"></script>
</body>

</html>