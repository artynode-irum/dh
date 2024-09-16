<?php
session_start();
include '../include/config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_type = $_POST['user_type'];
    $email = $_POST['email'];
    $name = $_POST['name'];
    $password = $_POST['password'];
    $arpha_number = $_POST['arpha_number'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Choose the correct table and query based on user type
    if ($user_type == "patient") {
        $sql = "INSERT INTO patient (email, username, password, hpassword) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $email, $name, $password, $hashed_password);
    } elseif ($user_type == "doctor") {
        $sql = "INSERT INTO doctor (email, username, password, hpassword, aphra) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $email, $name, $password, $hashed_password, $arpha_number);
    } elseif ($user_type == "admin") {
        $sql = "INSERT INTO admin (email, username, password, hpassword) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $email, $name, $password, $hashed_password);
    } else {
        $message = "Invalid user type.";
    }

    if (empty($message)) {
        if ($stmt->execute()) {
            $message = "New user added successfully!";
        } else {
            $message = "Error: " . $stmt->error;
        }
        $stmt->close();
    }

    $conn->close();

    // Echo JavaScript alert based on the message
    echo "<script>alert('$message'); window.location.href='add_user.php';</script>";
    exit();
}
?>

<?php include("include/sidebar.php"); ?>

<div class="second-section">
    <section>
        <div class="navbar">
            <div class="page-title">
                <span>Add New User</span>
            </div>
            <?php include("include/navbar.php"); ?>
        </div>
        <br><br>

        <div class="add-new-user">
            <form action="add_user.php" method="post">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="input-group">
                                <i class="fas fa-id-card"></i>
                                <select name="user_type" id="user_type" required>
                                    <option value="" disabled selected>Select User Type</option>
                                    <option value="patient">Patient</option>
                                    <option value="doctor">Doctor</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="input-group">
                                <i class="fas fa-user"></i>
                                <input type="text" id="name" name="name" placeholder="User Name" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="input-group">
                                <i class="fas fa-envelope"></i>
                                <input type="email" id="email" name="email" placeholder="User Email" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="input-group">
                                <i class="fa-solid fa-key"></i>
                                <input type="password" id="password" name="password" placeholder="User Password"
                                    required>
                            </div>
                        </div>
                    </div>
                    <div id="additional-fields"></div>
                    <input type="submit" value="Submit" class="button-class">
                </div>
            </form>
        </div>
    </section>
    <?php include("include/footer.php"); ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const userTypeSelect = document.getElementById('user_type');
        const additionalFieldsDiv = document.getElementById('additional-fields');

        userTypeSelect.addEventListener('change', function () {
            const selectedValue = userTypeSelect.value;

            // Clear existing additional fields
            additionalFieldsDiv.innerHTML = '';

            if (selectedValue === 'doctor') {
                // Create a new input field for 'Doctor'
                const inputField = document.createElement('div');
                inputField.classList.add('input-group');
                inputField.innerHTML = `
                <div class="row">
                <label for="arpha_number">Doctor ARPHA Number:</label>
                    <div class="col-12">
                       <div class="input-group">
                           <i class="fa-solid fa-hashtag"></i>
                           <input type="text" id="arpha_number" name="arpha_number" placeholder="Enter ARPHA Number" required>
                        </div>
                    </div>
                </div>
                `;
                additionalFieldsDiv.appendChild(inputField);
            }
        });
    });
</script>

<script src="assets/script.js"></script>
</body>

</html>