<?php
session_start();
?>

<title>Register</title>

<?php
include("include/navbar.php")
    ?>
<div class="content">
    <div class="request-form">

        <h2 class="text-center">Patient Registration</h2>
        <form action="register_handler.php" method="POST">


            <div class="row">
                <div class="col-12">
                    <!-- <label for="email">Email:</label> -->
                    <div class="input-group">
                        <i class="fas fa-envelope"></i>
                        <input type="email" class="width-hundred" name="email" id="email" placeholder="Your Email">
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-12">
                    <div class="input-group">
                        <i class="fa-solid fa-key"></i>
                        <input type="password" id="password" name="password" placeholder="Password" required
                            class="width-hundred">
                    </div>
                </div>
                <div class="col-12">
                    <!-- <label class="form-label" for="confirm_password">Confirm
                                    Password</label> -->
                    <div class="input-group">
                        <i class="fa-solid fa-key"></i>
                        <input type="password" id="confirm_password" name="confirm_password"
                            placeholder="Confirm Password" required class="width-hundred">
                    </div>
                </div>
            </div>
            <button type="submit" class="button-class width-hundred my-4" name="register">Submit</button>

            <h5>Already have an account? <a href="login.php">Login Now</a></h5>

        </form>
    </div>
    <?php
    include('include/footer.php')
        ?>
</div>
<script>
    let currentStep = 1;
    function showStep(stepNumber) {
        document.querySelectorAll(".form-step").forEach((step) => {
            step.classList.remove("active");
        });
        document.getElementById(`step${stepNumber}`).classList.add("active");
    }

    function validateStep(stepNumber) {
        var hasErrors = false;

        if (hasErrors == false) {
            if (currentStep < 2) {
                currentStep++;
                showStep(currentStep);
            }
        }
    }

    function prevStep() {
        if (currentStep > 1) {
            currentStep--;
            hasErrors = true;
            showStep(currentStep);
        }
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
</body>

</html>