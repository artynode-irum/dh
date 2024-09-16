<?php
session_start();
include '../include/config.php'; // Check if user is logged in and has the role of 'patient' if
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'patient') {
    header("Location: index.php");
    exit();
} //
// Handle form submission 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $description = $_POST['description'];
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
    $payment = $_POST['payment'];
    $certificate_type = $_POST['certificate_type'];
    $reason = $_POST['reason'];
    $illness_description = $_POST['illness_description']; // $relevantDocument=$_POST['relevant_document'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $dob = $_POST['dob']; //
    $card_number = $_POST['card_number']; // $security_code=$_POST['security_code']; //
    $expiration_date = $_POST['expiration_date'];
    $card_number = null;
    $security_code = null;
    $expiration_date = null;
    $country = $_POST['country'];
    $title = $_POST['title'];
    $gender = $_POST['gender']; // Sanitize inputs
    $description = htmlspecialchars($description);
    $from_date = htmlspecialchars($from_date);
    $to_date = htmlspecialchars($to_date);
    $payment = htmlspecialchars($payment);
    $certificate_type = htmlspecialchars($certificate_type);
    $reason = htmlspecialchars($reason);
    $illness_description = htmlspecialchars($illness_description);
    $name = htmlspecialchars($name);
    $email = htmlspecialchars($email);
    $phone = htmlspecialchars($phone);
    $dob = htmlspecialchars($dob);
    $card_number = htmlspecialchars($card_number);
    $security_code = htmlspecialchars($security_code);
    $expiration_date = htmlspecialchars($expiration_date);
    $country = htmlspecialchars($country);
    $title = htmlspecialchars($title);
    $gender = htmlspecialchars($gender);
    $file = $_FILES['relevant_document'] ?? null;
    $uploadDir = 'patient_doc/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true); // Create directory if not
        exit();
    }
    if ($file == null) {
        $filePath = "null";
    } else {
        $filePath = "patient_doc/" . basename($file['name']);
        move_uploaded_file($file['tmp_name'], $filePath);
    } // $filePath="https://multiplepromosolutions.com/dh/patient/" .
    $filePath;
    $filePath = "http://localhost/dh/patient/" . $filePath; // Insert the request into the database
    $username = $_SESSION['username'];
    $query = "INSERT INTO medical_certificate 
              (patient_id, description, request_date_time, status, payment, from_date, to_date, certificate_type, reason, illness_description, document_path, name, email, phone, dob, card_number, security_code, expiration_date, country, gender, title) 
              VALUES 
              ((SELECT id FROM patient WHERE username = ?), ?, NOW(), 'pending', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->
        prepare($query);
    $stmt->bind_param(
        "ssissssssssssssssss",
        $username,
        $description,
        $payment,
        $from_date,
        $to_date,
        $certificate_type,
        $reason,
        $illness_description,
        $filePath,
        $name,
        $email,
        $phone,
        $dob,
        $card_number,
        $security_code,
        $expiration_date,
        $country,
        $gender,
        $title
    );


    if ($stmt->execute()) {
        include '../charge.php';
        header("Location: medical_certificate.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<?php include("include/sidebar.php"); ?>

<div class="second-section">
    <section>
        <div class="navbar">
            <div class="page-title">
                <span>Request Medical Certificate</span>
            </div>
            <?php include("include/navbar.php"); ?>
        </div>

        <div class="content">
            <div class="request-form table-responsive">
                <h2>Request Medical Certificate</h2>
                <form action="" id="requestForm" method="POST" enctype="multipart/form-data">

                    <label><b>Which type of Medical Certificate are you after?</b> <b
                            class="required-asterisk">*</b></label>
                    <span>
                        <section>
                            <img src="assets/img/Medical-Certificate-for-Work.webp" alt="Medical Certificate for Work">
                            <input type="radio" id="work" name="certificate_type" value="Work" required>
                            <label for="work">Work</label>
                        </section>
                        <section>
                            <img src="assets/img/Medical-Certificate-For-Study.webp"
                                alt="Medical Certificate for Study">
                            <input type="radio" id="study" name="certificate_type" value="Study" required>
                            <label for="study">Study</label>
                        </section>
                        <section>
                            <img src="assets/img/Carers-Certificate-Online.webp" alt="Carers Certificate Online">
                            <input type="radio" id="carer" name="certificate_type" value="Carer" required>
                            <label for="carer">Carer's</label>
                        </section>

                    </span>

                    <label><b>Select the main reason for needing the medical certificate:</b> <b
                            class="required-asterisk">*</b></label>
                    <p>
                        <input type="radio" id="migraine" name="reason" value="Migraine" required>
                        <label for="migraine">Migraine</label>
                    </p>
                    <p>
                        <input type="radio" id="headache" name="reason" value="Headache" required>
                        <label for="headache">Headache</label>
                    </p>
                    <p>
                        <input type="radio" id="period_pain" name="reason" value="Period Pain" required>
                        <label for="period_pain">Period Pain</label>
                    </p>
                    <p>
                        <input type="radio" id="common_cold_flu" name="reason" value="Common Cold/Flu" required>
                        <label for="common_cold_flu">Common Cold/Flu</label>
                    </p>
                    <p>
                        <input type="radio" id="cough" name="reason" value="Cough" required>
                        <label for="cough">Cough</label>
                    </p>
                    <p>
                        <input type="radio" id="other" name="reason" value="Other" required>
                        <label for="other">Other</label>
                    </p>


                    <label><b>Describe your illness, symptoms, and any relevant information. Be as specific as
                            possible:</b></label>
                    <textarea name="illness_description" id="illness_description"></textarea>

                    <label for="description"><b>Medical Certificate Description:</b></label>
                    <textarea name="description" id="description" required></textarea>



                    <table>
                        <tr>
                            <td colspan="2">
                                <label for="relevant_document"><b>Upload relevant document (if any):</b></label>
                                <input type="file" name="relevant_document" class="width-hundred">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="fromDate"><b>From Date:</b></label>
                                <input type="date" name="from_date" id="fromDate" class="width-hundred" required
                                    min="<?php echo date('Y-m-d'); ?>">
                            </td>
                            <td>
                                <label for="toDate"><b>To Date:</b></label>
                                <input type="date" name="to_date" id="toDate" class="width-hundred" required
                                    min="<?php echo date('Y-m-d'); ?>">
                            </td>
                        </tr>

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
                                <label for="dob"><b>Date Of Birth:</b></label>
                                <input type="date" name="dob" id="dob" class="width-hundred" required
                                    max="<?php echo date('Y-m-d'); ?>">
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
                    </table>


                    <label><b>Service Acknowledgment and Guidelines:</b> <b class="required-asterisk">*</b></label>
                    <p>
                        <input type="checkbox" name="certificate_policy" id="certificate_policy" required>
                        <label for="certificate_policy">I hereby acknowledge and confirm the following:</label>
                    <div class="scrollable-section">

                        <ol>
                            <li>I understand that by submitting a request for telehealth services on this website, I
                                am
                                affirming that I am not severely unwell and do not require urgent medical attention.
                            </li>
                            <br>
                            <li>I am aware that I do not have any of the following symptoms:
                                <ul>
                                    <li>Chest pain</li>
                                    <li>Temperature of 39 degrees Celsius (102 degrees Fahrenheit) or above</li>
                                    <li>Shortness of breath</li>
                                    <li>One-sided limb weakness</li>
                                    <li>Slurring of speech</li>
                                    <li>Any other symptoms that may indicate a medical emergency, whether or not
                                        they are
                                        listed
                                        here.</li>
                                </ul>
                            </li> <br>
                            <li>I recognize that this telehealth service is not a substitute for emergency medical
                                care. If
                                I experience any of the severe symptoms listed above or any other concerning medical
                                condition, I will immediately seek appropriate medical attention, including calling
                                000 or
                                visiting the nearest emergency room.</li>
                            <br>
                            <li>I understand that the telehealth service provided on this website is intended for
                                non-emergent medical issues, routine healthcare, and medical consultations that can
                                be
                                safely addressed through telemedicine.</li>
                            <br>
                            <li>If my symptoms do not improve or if I have ongoing health concerns, I agree to
                                schedule an
                                appointment with a General Practitioner (GP) for an in-person evaluation and further
                                medical
                                guidance.</li>
                        </ol>
                        <br>
                        <p>By submitting this acknowledgment, I confirm that I have read and understood the criteria
                            outlined above, and I agree to use this telehealth service responsibly and in accordance
                            with
                            these guidelines.</p>
                    </div>
                    </p>
                    <!-- <label><b>Choose appropriate certificate:</b></label>
                <p>
                    <input type="radio" id="regular" name="payment" value="10" required>
                    <label for="regular">Regular Certificate - $10</label>
                </p>
                <p>
                    <input type="radio" id="priority" name="payment" value="20" required>
                    <label for="priority">Priority Certificate - $20</label>
                </p>
                <small>Priority certificates are cleared within 12 hours.</small> -->

                    <label><b>Choose appropriate certificate:</b></label>
                    <p>
                        <input type="radio" id="regular" name="payment" value="10" required onchange="updateTotal()">
                        <label for="regular">Regular Certificate - $10</label>
                    </p>
                    <p>
                        <input type="radio" id="priority" name="payment" value="20" required onchange="updateTotal()">
                        <label for="priority">Priority Certificate - $20</label>
                    </p>
                    <small>Priority certificates are cleared within 12 hours.</small>

                    <!-- <h3 id="total">Total: - </h3> -->



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
                                <label for="security_code"><b>Expiration Date:</b></label>
                                <!-- <input type="tel" name="security_code" id="security_code" required pattern="\d{3}"
                                maxlength="3" class="width-hundred" placeholder="Enter 3-digit code"
                                title="Security code must be 3 digits" inputmode="numeric"
                                oninput="this.value = this.value.replace(/\D/g, '').slice(0, 3);"> -->
                                <div id="card-expiry"></div>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <label for="expiration_date"><b>Security Code:</b></label>
                                <!-- <input type="date" name="expiration_date" id="expiration_date" class="width-hundred"
                                required min=""> -->
                                <div id="card-cvc"></div>
                            </td>
                            <td>
                                <?php include("include/countries.php"); ?>

                            </td>
                        </tr>
                    </table>
                    <div id="card-errors" class="required-asterisk" role="alert"></div>
                    <h3 id="total">Total: - </h3>
                    <input type="hidden" name="total" id="total-value" value="">
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
        alert("Medical Certificate request submitted successfully!");
    </script>
<?php
// endif; 
?> -->

<script src="assets/script.js"></script>
<script src="https://js.stripe.com/v3/"></script>
<script>
    var stripe = Stripe('pk_test_51PqenA04aDQdDBaS4LHhSDd2xsXAVVa7bS9U0EtIAjrWLysUljQaP7c21vVuvSwomcRAekFmAW73JxXvbPywZPuq00I59fXy8P');
    var elements = stripe.elements();

    var style = {
        base: {
            fontSize: '16px',
            color: '#32325d',
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