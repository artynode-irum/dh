<?php
// session_start();
// include '../../include/config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

$username = $_SESSION['username'];
$sql_query = "SELECT * FROM admin WHERE username='$username'";
$query_result = $conn->query($sql_query);

if ($query_result->num_rows > 0) {
    $user = $query_result->fetch_assoc();
} else {
    echo "User not found.";
    exit();
}
?>

<div class="navbar-title">

    <!-- <span onclick="toggleDropdown()">  -->
    <!-- <img src="<?php
    // echo $user['profile_picture'];
    ?>" alt="profile" style="width: 50px"> -->
    <!-- <img src="assets/img/profile.png" alt="profile" style="width: 50px"> -->
    <!-- <button class="navbar-profile" onclick="toggleDropdown()">ABC 123</button> -->
    <!-- <button class="navbar-profile">  -->
    <?php
    // echo $username;
    // ?> </button>


    </span> 



    <span onclick="toggleDropdown()">
        <!-- Use PHP to dynamically set the profile picture -->
        <img src="assets/img/<?php
        echo $user['profile_picture'];
        ?>" alt="Profile Picture" class="navbar-img">
        <!-- <img src=" -->
        <?php
        // echo htmlspecialchars($user['profile_picture']); 
        ?>
        <!-- " alt="profile" style="width: 50px"> -->
        <!-- Display the username dynamically -->
        <button class="navbar-profile">
            <?php echo htmlspecialchars($username); ?>
        </button>
    </span>


    <div class="dropdown-content" id="dropdownContent">
        <div>
            <img src="assets/img/<?php
            echo $user['profile_picture'];
            ?>" alt="Profile Picture" width="50">
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