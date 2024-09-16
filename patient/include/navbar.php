<?php
// session_start();
include '../include/config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'patient') {
    header("Location: index.php");
    exit();
}

$username = $_SESSION['username'];
$sql_query = "SELECT * FROM patient WHERE username='$username'";
$query_result = $conn->query($sql_query);

if ($query_result->num_rows > 0) {
    $user = $query_result->fetch_assoc();
} else {
    echo "User not found.";
    exit();
}
?>

<div class="navbar-title">
    <span onclick="toggleDropdown()">


        <img src="assets/img/<?php
        echo $user['profile_picture'];
        ?>" alt="Profile Picture" class="navbar-img">

        <!-- <img src="assets/img/profile.png" alt="profile" style="width: 50px"> -->
        <!-- <button class="navbar-profile" onclick="toggleDropdown()">ABC 123</button> -->
        <button class="navbar-profile"> <?php
        echo $username;
        ?> </button>


    </span>

    <div class="dropdown-content" id="dropdownContent">
        <div>
            <img src="assets/img/<?php
            echo $user['profile_picture'];
            ?>" alt="Profile Picture" class="navbar-img">
            <!-- <img src="assets/img/profile.png" alt="" style="width: 50px"> -->
            <h5><?php
            echo $username;
            ?></h5>
            <p><?php echo $user['email']; ?></p>
            <hr>
            <a href="profile.php">Profile</a>
            <a href="../logout.php">Logout</a>
        </div>
    </div>
</div>