<?php
session_start();
include 'include/config.php';

$alertMessage = '';
$redirectUrl = 'contact.php';

if (isset($_POST['submit'])) {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $message = $_POST['message'];

  // Prepare and bind
  $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $name, $email, $message);

  if ($stmt->execute()) {
    $alertMessage = "Message sent successfully!";
  } else {
    $alertMessage = "Message was not sent successfully! Error: " . $stmt->error;
  }

  $_SESSION['alertMessage'] = $alertMessage;
  $stmt->close();
  $conn->close();

  // Redirect to the same page
  header("Location: contact.php");
  exit();
}
?>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Check for alert message in session
    <?php if (isset($_SESSION['alertMessage'])): ?>
      var alertMessage = "<?php echo addslashes($_SESSION['alertMessage']); ?>";
      alert(alertMessage);
      <?php unset($_SESSION['alertMessage']); // Clear the message after displaying ?>
    <?php endif; ?>
  });
</script>
<?php include("include/navbar.php"); ?>


<div class="content">
  <div class="request-form">
    <h2 class="text-center">Contact Us</h2>
    <form action="" method="POST">
      <div class="row">
        <div class="col-12">
          <div class="input-group">
            <i class="fa-solid fa-user-pen"></i>
            <input type="text" class="width-hundred" required name="name" id="name" placeholder="Your Name">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <div class="input-group">
            <i class="fas fa-envelope"></i>
            <input type="email" class="width-hundred" required name="email" id="email" placeholder="Your Email">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <div class="input-group">
            <i class="fa-solid fa-message"></i>
            <textarea id="message" name="message" class="width-hundred" rows="4" placeholder="Write your query here..."
              required></textarea>
          </div>
        </div>
      </div>
      <button type="submit" name="submit" class="button-class">Send Message</button>
    </form>
  </div>
  <?php include('include/footer.php'); ?>
</div>
</body>

</html>