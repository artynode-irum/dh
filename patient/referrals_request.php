<?php
session_start();
include '../include/config.php';

// Check if user is logged in and has the role of 'patient'
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'patient') {
    header("Location: index.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $description = $_POST['description'];
    $payment = $_POST['payment'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $dob = $_POST['dob'];
    $card_number = "";
    $security_code = "";
    // $expiration_date = "";
    $medicare_number = $_POST['medicare_number'];
    $expiry_year = $_POST['expiry_year'];
    $expiry_month = $_POST['expiry_month'];
    $expiry_date = $_POST['expiry_date'];
    $country = $_POST['country'];
    $title = $_POST['title'];
    $gender = $_POST['gender'];
    $appointment_type = $_POST['appointment_type'];
    $appointment_day = $_POST['appointment_day'];
    $appointment_time = $_POST['appointment_time'];
    $other_details = $_POST['otherDetails'];
    $referral_provider = $_POST['referral-provider'];
    $referral_type = $_POST['referral_type'];
    $address = $_POST['address'];

    // Sanitize inputs
    $description = htmlspecialchars($description);
    $payment = htmlspecialchars($payment);
    $name = htmlspecialchars($name);
    $email = htmlspecialchars($email);
    $phone = htmlspecialchars($phone);
    $dob = htmlspecialchars($dob);
    $card_number = htmlspecialchars($card_number);
    $security_code = htmlspecialchars($security_code);
    // $expiration_date = htmlspecialchars($expiration_date);
    $medicare_number = htmlspecialchars($medicare_number);
    $expiry_year = htmlspecialchars($expiry_year);
    $expiry_month = htmlspecialchars($expiry_month);
    $expiry_date = htmlspecialchars($expiry_date);
    $country = htmlspecialchars($country);
    $title = htmlspecialchars($title);
    $gender = htmlspecialchars($gender);
    $appointment_type = htmlspecialchars($appointment_type);
    $appointment_day = htmlspecialchars($appointment_day);
    $appointment_time = htmlspecialchars($appointment_time);
    $other_details = htmlspecialchars($other_details);
    $referral_provider = htmlspecialchars($referral_provider);
    $referral_type = htmlspecialchars($referral_type);
    $address = htmlspecialchars($address);

    // Validate and format expiry date
    if ($expiry_year && $expiry_month && $expiry_date) {
        // Convert month name to numeric value
        $month_number = date("m", strtotime($expiry_month));
        // Ensure two-digit format for date and month
        $formatted_month = str_pad($month_number, 2, "0", STR_PAD_LEFT);
        $formatted_date = str_pad($expiry_date, 2, "0", STR_PAD_LEFT);
        // Create a formatted expiry date string
        $medicare_date = $expiry_year . '-' . $formatted_month . '-' . $formatted_date;
    } else {
        $medicare_date = '-'; // Handle missing expiry date
    }

    // Insert the request into the database
    $username = $_SESSION['username'];
    $query = "INSERT INTO referrals 
    (patient_id, description, request_date_time, status, payment, name, email, phone, dob, card_number, security_code, country, address, gender, title, appointment_type, appointment_day, appointment_time, medicare_number, medicare_expiration_date, other_details, referral_provider, referral_type) 
    VALUES 
    ((SELECT id FROM patient WHERE username = ?), ?, NOW(), 'pending', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($query);
    $stmt->bind_param(
        "sssssssssssssssssssss",
        $username,
        $description,
        $payment,
        $name,
        $email,
        $phone,
        $dob,
        $card_number,
        $security_code,
        $country,
        $address,
        $gender,
        $title,
        $appointment_type,
        $appointment_day,
        $appointment_time,
        $medicare_number,
        $medicare_date,
        $other_details,
        $referral_provider,
        $referral_type
    );

    if ($stmt->execute()) {
        include '../charge.php';
        header("Location: referrals.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<style>
    .hidden {
        display: none;
    }
</style>

<?php include("include/sidebar.php"); ?>

<div class="second-section">
    <section>
        <div class="navbar">
            <div class="page-title">
                <span>Request A Referral</span>
            </div>
            <?php include("include/navbar.php"); ?>
        </div>

        <div class="content">
            <div class="request-form table-responsive">
                <h2>Request Referral Appointment</h2>
                <form action="referrals_request.php" id="requestForm" method="POST">


                    <label><b>What type of referral are you after?</b> <b class="required-asterisk">*</b></label>
                    <p>
                        <input type="radio" id="radiology" name="referral_type" value="Radiology" required>
                        <label for="radiology">Radiology</label>
                    </p>
                    <p>
                        <input type="radio" id="pathology" name="referral_type" value="Pathology" required>
                        <label for="pathology">Pathology</label>
                    </p>
                    <p>
                        <input type="radio" id="specialist" name="referral_type" value="Specialist" required>
                        <label for="specialist">Specialist</label>
                    </p>

                    <p>
                        <input type="radio" id="other" name="referral_type" value="Other" required>
                        <label for="other">Other</label>
                    </p>

                    <p id="other-input">
                        <label for="otherDetails"><b>Please specify:</b></label><br>
                        <input type="text" id="otherDetails" name="otherDetails">
                    </p>


                    <label for="referral-provider"><b>Referral Provider Name or Number</b></label>
                    <input type="text" id="referral-provider" name="referral-provider">


                    <table>

                        <tr>
                            <td>
                                <label for="title"><b>Title:</b></label>
                                <select name="title" name="title" id="title" class="width-hundred" required>
                                    <option value="" disabled selected>Select</option>
                                    <option value="Mr">Mr</option>
                                    <option value="Mrs">Mrs</option>
                                    <option value="Ms">Ms</option>
                                    <option value="Miss">Miss</option>
                                    <option value="Mx">Mx</option>
                                    <option value="Dr">Dr</option>
                                </select>
                            </td>
                            <td>
                                <label for="name"><b>Name:</b></label>
                                <input type="text" name="name" id="name" class="width-hundred" required
                                    placeholder="Full Name">

                            </td>
                        </tr>

                        <tr>
                            <td>

                                <label for="gender"><b>Gender:</b></label>
                                <select name="gender" name="gender" id="gender" class="width-hundred" required>
                                    <option value="" disabled selected>Select</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                    <option value="Unknown">Unknown</option>
                                </select>
                            </td>
                            <td>
                                <label for="address"><b>Address:</b></label>
                                <input name="address" id="address" class="width-hundred">

                            </td>

                            <td>
                                <span>

                                </span>
                            </td>


                        </tr>
                        <tr>

                            <td>
                                <!-- <span> -->
                                <label for="dob"><b>Date Of Birth:</b></label>
                                <input type="date" name="dob" id="dob" class="width-hundred" required max="2006-12-31"
                                    onchange="calculateAge()">
                                <!-- <label for="age"><b>Age:</b></label>
                                <span id="age"></span> -->
                                <!-- </span> -->
                            </td>
                            <td>
                                <label for="age"><b>Age:</b></label>
                                <span id="age"></span>
                            </td>




                        </tr>

                        <tr>
                            <td>
                                <label for="email"><b>Email:</b></label>
                                <input type="email" class="width-hundred" name="email" id="email" required
                                    placeholder="Write Email">
                            </td>
                            <td>
                                <label for="phone"><b>Phone:</b></label>
                                <input type="number" class="width-hundred" name="phone" id="phone" required
                                    placeholder="Write Phone Number">
                            </td>
                        </tr>

                        <tr>
                            <!-- <h3>Medicare</h3> -->
                        </tr>
                        <tr>
                            <td>
                                <label for="medicare_number"><b>Medicare Number:</b></label>
                                <input type="text" class="width-hundred" name="medicare_number" id="medicare_number"
                                    maxlength="12" placeholder="Enter 11-digit number"
                                    oninput="formatMedicareNumber(this)">

                            </td>

                            <td class="medicare-expiry">
                                <div>
                                    <label for="expiry_year"><b>Expiry Year:</b></label>
                                    <br>
                                    <select id="expiry_year" name="expiry_year">
                                        <option value="" disabled selected>Select Year</option>
                                        <option value="2024">2024</option>
                                        <option value="2025">2025</option>
                                        <option value="2026">2026</option>
                                        <option value="2027">2027</option>
                                        <option value="2028">2028</option>
                                        <option value="2029">2029</option>
                                        <option value="2030">2030</option>
                                        <option value="2031">2031</option>
                                        <option value="2032">2032</option>
                                        <option value="2033">2033</option>
                                        <option value="2034">2034</option>
                                        <option value="2035">2035</option>
                                        <option value="2036">2036</option>
                                        <option value="2037">2037</option>
                                        <option value="2038">2038</option>
                                        <option value="2039">2039</option>

                                    </select>
                                </div>
                                <div>
                                    <label for="expiry_month"><b>Expiry Month:</b></label>
                                    <br>
                                    <select id="expiry_month" name="expiry_month">
                                        <option value="" disabled selected>Select Month</option>
                                        <option value="January">January</option>
                                        <option value="February">February</option>
                                        <option value="March">March</option>
                                        <option value="April">April</option>
                                        <option value="May">May</option>
                                        <option value="June">June</option>
                                        <option value="July">July</option>
                                        <option value="August">August</option>
                                        <option value="September">September</option>
                                        <option value="October">October</option>
                                        <option value="November">November</option>
                                        <option value="December">December</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="expiry_date"><b>Expiry Date:</b></label>
                                    <br>
                                    <select id="expiry_date" name="expiry_date">
                                        <option value="" disabled selected>Select Date</option>
                                        <option value="01">01</option>
                                        <option value="02">02</option>
                                        <option value="03">03</option>
                                        <option value="04">04</option>
                                        <option value="05">05</option>
                                        <option value="06">06</option>
                                        <option value="07">07</option>
                                        <option value="08">08</option>
                                        <option value="09">09</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                        <option value="13">13</option>
                                        <option value="14">14</option>
                                        <option value="15">15</option>
                                        <option value="16">16</option>
                                        <option value="17">17</option>
                                        <option value="18">18</option>
                                        <option value="19">19</option>
                                        <option value="20">20</option>
                                        <option value="21">21</option>
                                        <option value="22">22</option>
                                        <option value="23">23</option>
                                        <option value="24">24</option>
                                        <option value="25">25</option>
                                        <option value="26">26</option>
                                        <option value="27">27</option>
                                        <option value="28">28</option>
                                        <option value="29">29</option>
                                        <option value="30">30</option>
                                        <option value="31">31</option>
                                    </select>
                                </div>
                            </td>
                        </tr>
                    </table>


                    <label><b>Service Acknowledgment and Guidelines:</b> <b class="required-asterisk">*</b></label>
                    <p>
                        <input type="checkbox" name="certificate_policy" id="certificate_policy" required>
                        <label for="certificate_policy">I hereby acknowledge and confirm the following:</label>
                    <div class="scrollable-section">

                        <p>At Doctor Help, we are committed to providing safe and responsible healthcare services
                            through
                            telehealth. It is important to note that there are certain medications that we will never
                            issue
                            a prescription for from an online request. This policy is in place to ensure the well-being
                            and
                            safety of our patients. The following is a list of medications that fall under this
                            category:
                        </p>
                        <br>
                        <ol>

                            <li>Anaesthetic agents (e.g., propofol, midazolam, ketamine).</li> <br>

                            <li>Prescription stimulants (such as amphetamine, dexamphetamine, etc).</li> <br>

                            <li>Barbiturates.</li> <br>

                            <li>Methadone (when prescribed for the treatment of drug dependency).</li> <br>

                            <li>Chemotherapy and most anti-cancer treatments.</li> <br>

                            <li>Prohibited substances.</li> <br>

                            <li>Medicines that a patient is not currently taking, without a consultation with a Doctor
                                Help
                                provider via telehealth.</li> <br>

                            <li>Opiate painkillers (including but not limited to: Codeine, Fentanyl, morphine,
                                oxycodone,
                                hydromorphone, tramadol, and tapentadol).</li> <br>

                            <li>Benzodiazepines (including but not limited to diazepam [Valium], lorazepam [Ativan]).
                            </li>
                            <br>

                            <li>Drugs that are susceptible to abuse, such as pregabalin (Lyrica) (unless prescribed for
                                proven epilepsy), dexamphetamine, or gabapentin (unless prescribed for proven epilepsy).
                            </li> <br>

                            <li>Any medication for which the prescriber is not satisfied that it will be used for the
                                stated
                                purpose or is not the most appropriate treatment for the described medical condition.
                            </li>
                            <br>

                        </ol>
                        <p>We appreciate your understanding and cooperation in adhering to these guidelines as we
                            prioritize
                            your health and safety in every consultation. If you have any questions or concerns, please
                            do
                            not hesitate to reach out to our team.</p>
                    </div>
                    </p>



                    <label><b>Book an appointment</b></label>

                    <?php
                    // Create an array of time slots for 24 hours
                    $time_slots = [];
                    for ($hour = 0; $hour < 24; $hour++) {
                        $formatted_hour = str_pad($hour, 2, '0', STR_PAD_LEFT); // Pad single digit hours with a leading zero
                        $time_24 = $formatted_hour . ':00';

                        // Convert to 12-hour format with AM/PM
                        $am_pm = ($hour < 12) ? 'AM' : 'PM';
                        $hour_12 = ($hour % 12) ?: 12; // Convert 0 to 12 for midnight
                        $time_12 = str_pad($hour_12, 2, '0', STR_PAD_LEFT) . ':00 ' . $am_pm;

                        // Add time slot to array
                        $time_slots[$time_24] = $time_12;
                    }
                    ?>

                    <table>
                        <tr>
                            <td colspan="2">
                                <label for="appointment-type"><b>Choose Appointment Date and Time</b><b
                                        class="required-asterisk">*</b></label>
                                <select name="appointment_type" id="appointment-type" class="width-hundred" required>
                                    <!-- <option value="" disabled selected>Select Appointment Type</option> -->
                                    <option value="" disabled>Select Appointment Type</option>
                                    <option value="Telehealth" selected>Telehealth</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="appointment-day"><b>Appointment Day</b></label>
                                <input type="date" name="appointment_day" id="appointment-day" class="width-hundred"
                                    required min="<?php echo date('Y-m-d');
                                    ; ?>">

                            </td>
                            <td>
                                <label for="appointment-time"><b>Appointment Time</b></label>
                                <select name="appointment_time" id="appointment-time" class="width-hundred" required>
                                    <?php foreach ($time_slots as $value => $label): ?>
                                        <option value="<?php echo $value; ?>"><?php echo $label; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">

                                <label for="description"><b>Any other Note you want to share:</b></label> <br>
                                <textarea name="description" id="description" class="width-hundred" required></textarea>
                            </td>
                        </tr>
                    </table>



                    <label><b>Credit Card:</b> <b class="required-asterisk">*</b></label>
                    <table>
                        <tr>
                            <td>
                                <label for="card_number"><b>Card Number:</b></label>
                                <!-- <input type="tel" name="card_number" id="card_number" required pattern="\d{16}"
                                maxlength="16" placeholder="Enter 16-digit card number"
                                title="Card number must be 16 digits" class="width-hundred" inputmode="numeric"
                                oninput="this.value = this.value.replace(/\D/g, '').slice(0, 16);"> -->
                                <div id="card-number"></div>
                            </td>
                            <td>
                                <label for="security_code"><b>Security Code:</b></label>
                                <!-- <input type="tel" name="security_code" id="security_code" required pattern="\d{3}"
                                maxlength="3" class="width-hundred" placeholder="Enter 3-digit code"
                                title="Security code must be 3 digits" inputmode="numeric"
                                oninput="this.value = this.value.replace(/\D/g, '').slice(0, 3);"> -->

                                <div id="card-cvc"></div>
                            </td>
                        </tr>

                        <tr>
                            <td>

                                <label for="expiration_date"><b>Expiration Date:</b></label>
                                <!-- <input type="date" name="expiration_date" id="expiration_date" class="width-hundred"
                                required min="<?php
                                // echo date('Y-m-d');
                                ?>"> -->
                                <div id="card-expiry"></div>
                            </td>
                            <td>
                                <?php include("include/countries.php"); ?>
                            </td>
                        </tr>
                    </table>
                    <div id="card-errors" class="required-asterisk" role="alert"></div>

                    <input type="hidden" name="total" value="39">

                    <h3>Total: $39</h3>
                    <input type="submit" value="Submit" class="button-class">
                </form>
            </div>

        </div>
    </section>
    <?php include("include/footer.php"); ?>
</div>

<!-- <?php
// if ($success == 1): 
?>
    <script type="text/javascript">
        alert("Prescriptoin form submitted successfully!");
    </script>
<?php
// endif; 
?> -->


<script src="assets/script.js"></script>
<script src="../frontend/assets/referral.js"></script>
<script src="https://js.stripe.com/v3/"></script>
<script>
    var stripe = Stripe('pk_test_51PqenA04aDQdDBaS4LHhSDd2xsXAVVa7bS9U0EtIAjrWLysUljQaP7c21vVuvSwomcRAekFmAW73JxXvbPywZPuq00I59fXy8P');
    var elements = stripe.elements();

    var style = {
        base: {
            fontSize: '16px',
            color: '#777777',
            fontWeight: '600',
        }
    };

    var cardNumber = elements.create('cardNumber', { style: style });
    var cardExpiry = elements.create('cardExpiry', { style: style });
    var cardCvc = elements.create('cardCvc', { style: style });

    cardNumber.mount('#card-number');
    cardExpiry.mount('#card-expiry');
    cardCvc.mount('#card-cvc');

    function displayError(event) {
        var displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    }

    cardNumber.addEventListener('change', displayError);
    cardExpiry.addEventListener('change', displayError);
    cardCvc.addEventListener('change', displayError);

    var form = document.getElementById('requestForm');
    form.addEventListener('submit', function (event) {
        event.preventDefault();

        stripe.createToken(cardNumber).then(function (result) {
            if (result.error) {
                var errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.error.message;
            } else {
                var hiddenInput = document.createElement('input');
                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', 'stripeToken');
                hiddenInput.setAttribute('value', result.token.id);
                form.appendChild(hiddenInput);

                form.submit();
            }
        });
    });
</script>
</body>

</html>