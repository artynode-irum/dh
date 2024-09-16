<?php
// session_start();
// include '../include/config.php';
// require '../vendor/autoload.php';
// use GuzzleHttp\Client;


// // Handle form submission
// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $description = $_POST['description'];
//     $from_date = $_POST['from_date'];
//     $to_date = $_POST['to_date'];
//     $payment = $_POST['payment'];
//     $certificate_type = $_POST['certificate_type'];
//     $reason = $_POST['reason'];
//     $illness_description = $_POST['illness_description'];
//     // $relevantDocument = $_POST['relevant_document'];
//     $firstname = $_POST['fname'];
//     $lastname = $_POST['lname'];
//     $email = $_POST['email'];
//     $phone = $_POST['phone'];
//     $dob = $_POST['dob'];
//     $address = Null;
//     // $address = $_POST['address'];
//     // $card_number = $_POST['card_number'];
//     // $security_code = $_POST['security_code'];
//     // $expiration_date = $_POST['expiration_date'];
//     $card_number = null;
//     $security_code = null;
//     $expiration_date = null;
//     $country = $_POST['country'];
//     $title = $_POST['title'];
//     $gender = $_POST['gender'];

//     // Sanitize inputs
//     $description = htmlspecialchars($description);
//     $from_date = htmlspecialchars($from_date);
//     $to_date = htmlspecialchars($to_date);
//     $payment = htmlspecialchars($payment);
//     $certificate_type = htmlspecialchars($certificate_type);
//     $reason = htmlspecialchars($reason);
//     $illness_description = htmlspecialchars($illness_description);
//     $firstname = htmlspecialchars($firstname);
//     $lastname = htmlspecialchars($lastname);
//     $email = htmlspecialchars($email);
//     $phone = htmlspecialchars($phone);
//     $dob = htmlspecialchars($dob);
//     $address = htmlspecialchars($address);
//     $card_number = htmlspecialchars($card_number);
//     $security_code = htmlspecialchars($security_code);
//     $expiration_date = htmlspecialchars($expiration_date);
//     $country = htmlspecialchars($country);
//     $title = htmlspecialchars($title);
//     $gender = htmlspecialchars($gender);


//     $file = $_FILES['relevant_document'] ?? null;
//     $uploadDir = 'patient_doc/';
//     if (!is_dir($uploadDir)) {
//         mkdir($uploadDir, 0777, true); // Create directory if not exists
//     }
//     if ($file == null) {
//         $filePath = "null";
//     } else {
//         $filePath = "patient_doc/" . basename($file['name']);
//         move_uploaded_file($file['tmp_name'], $filePath);
//     }
//     $filePath = "localhost/doctorhelp/dh/patient/" . $filePath;

//     $name = $firstname . $lastname;
//     // Send data to external API
//     $token = "xBOWmliN6PNAnRWKAuy6A7vHO2E";  // Access the token from the .env file
//     $client = new Client();
//     $apiResponse = $client->post('https://api.medirecords.com/v1/patients', [
//         'headers' => [
//             'Authorization' => 'Bearer ' . $token,
//             'Accept' => 'application/json',
//         ],
//         'json' => [
//             "defaultPracticeId" => "1ec6ded0-24c7-47d7-93de-9f139b578ed2",
//             "usualDoctorId" => "1ec6ded0-24c7-47d7-93de-9f139b578ed2",
//             "titleCode" => $title,
//             "firstName" => $firstname,
//             "lastName" => $lastname,
//             "gender" => $gender,
//             "dob" => $dob,
//             "email" => $email,
//             "mobilePhone" => $phone,
//             "contactMethod" => 1
//         ],
//     ]);

//     if ($apiResponse->getStatusCode() == 201) {
//         $responseData = json_decode($apiResponse->getBody(), true);
//         $patientId = $responseData['id']; // Adjust according to the actual response structure
//     } else {
//         echo "Failed to save data.";
//         exit;
//     }

//     $query1 = "SELECT id, hpassword FROM patient WHERE email = ?";
//     $stmt1 = $conn->prepare($query1);
//     $stmt1->bind_param('s', $email);
//     $stmt1->execute();
//     $stmt1->bind_result($Id, $password);
//     $stmt1->fetch();
//     $stmt1->close();

//     if ($password == null) {
//         $password = bin2hex(random_bytes(10));
//         $hashed_password = password_hash($password, PASSWORD_DEFAULT);
//         $query2 = "INSERT INTO patient (email, username, email_verify, password, hpassword) 
//         VALUES (?, ?,'1', ?, ?)";
//         $stmt2 = $conn->prepare($query2);
//         $stmt2->bind_param('ssss', $email, $name, $hashed_password, $password);
//         $stmt2->execute();
//         $Id = $conn->insert_id;
//         $stmt2->close();
//     }

//     // Insert the request into the database
//     // $username = $_SESSION['username'];
//     $query = "INSERT INTO medical_certificate 
//               ( patient_id, description, request_date_time, status, payment, from_date, to_date, certificate_type, reason, illness_description, document_path, name, email, phone, dob, address, card_number, security_code, expiration_date, country, gender, title) 
//               VALUES 
//               (?, ?, NOW(), 'pending', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
//     $stmt = $conn->prepare($query);
//     $stmt->bind_param("ssisssssssssssssssss", $Id, $description, $payment, $from_date, $to_date, $certificate_type, $reason, $illness_description, $filePath, $name, $email, $phone, $dob, $address, $card_number, $security_code, $expiration_date, $country, $gender, $title);

//     if ($stmt->execute()) {
//         $doctorAssigned = false;
//         require '../charge.php';
//         require '../emails/medicalcertificate.php';
//         // header("Location: ../thankyou.html");
//         exit();
//     } else {
//         echo "Error: " . $stmt->error;
//     }
// }
?>

<?php include("assets/navbar.php"); ?>
<div class="container-fluid m-0 p-0">
    <div class="content">
        <div class="request-form">
            <form action="medical_certificate_request.php" id="requestForm" method="POST" enctype="multipart/form-data">
                <h2 class="text-center mt-3">Request Medical Certificate</h2>
                <div class="form-step active " id="step1">

                    <div class="row">
                        <label><b>Which type of Medical Certificate are you after?</b> <b
                                class="required-asterisk">*</b></label>
                        <div class="col-12 col-md-10 m-auto">
                            <span>
                                <section>
                                    <img src="assets/img/Medical-Certificate-for-Work.webp"
                                        alt="Medical Certificate for Work" class="img-fluid w-75">
                                    <!-- <input type="radio" id="work" name="certificate_type" value="Work">
                                        <label for="work">Work</label> -->
                                    <div class="radio-pill-group">
                                        <input type="radio" id="work" name="certificate_type" value="work">
                                        <label for="work">Work</label>
                                    </div>
                                </section>
                                <section>
                                    <img src="assets/img/Medical-Certificate-For-Study.webp"
                                        alt="Medical Certificate for Study" class="img-fluid w-75">
                                    <!-- <input type="radio" id="study" name="certificate_type" value="Study">
                                        <label for="study">Study</label> -->
                                    <div class="radio-pill-group">
                                        <input type="radio" id="study" name="certificate_type" value="study">
                                        <label for="study">Study</label>
                                    </div>
                                </section>
                                <section>
                                    <img src="assets/img/Carers-Certificate-Online.webp" alt="Carers Certificate Online"
                                        class="img-fluid w-75">
                                    <!-- <input type="radio" id="carer" name="certificate_type" value="Carer">
                                        <label for="carer">Carer's</label> -->
                                    <div class="radio-pill-group">
                                        <input type="radio" id="carer" name="certificate_type" value="carer">
                                        <label for="carer">Carer</label>
                                    </div>
                                </section>
                            </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <label><b>Select the main reason for needhe medical certificate:</b> <b
                                    class="required-asterisk">*</b></label>
                            <div class="radio-pill-group">
                                <input type="radio" id="migraine" name="reason" value="Migraine">
                                <label for="migraine">Migraine</label>

                                <input type="radio" id="headache" name="reason" value="Headache">
                                <label for="headache">Headache</label>

                                <input type="radio" id="period_pain" name="reason" value="Period Pain">
                                <label for="period_pain">Period Pain</label>

                                <input type="radio" id="common_cold_flu" name="reason" value="Common Cold/Flu">
                                <label for="common_cold_flu">Common Cold/Flu</label>

                                <input type="radio" id="cough" name="reason" value="Cough">
                                <label for="cough">Cough</label>

                                <input type="radio" id="other" name="reason" value="Other">
                                <label for="other">Other</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <label><b>Describe your illness, symptoms, and any relevant information. Be as specific
                                    as possible:</b> <b class="required-asterisk">*</b></label>
                            <div class="input-group">
                                <i class="fa-solid fa-pen"></i>
                                <input name="illness_description" id="illness_description">
                            </div>
                        </div>
                        <div class="col-12">
                            <label for="description"><b>Medical Certificate Description:</b></label>
                            <div class="input-group">
                                <i class="fa-solid fa-pen"></i>
                                <input name="description" id="description">
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <label for="relevant_document">Upload any relevant documents if any:</label>
                        <div class="col-12 ">
                            <!-- <div class="file-drop-area"> -->
                            <input type="file" name="relevant_document" class="file-input width-hundred">
                            <!-- <label for="file-upload-2" class="file-drop-label">
                                    Drag & drop a file here or <span>browse</span>
                                </label> -->
                            <!-- </div> -->
                        </div>
                    </div>

                    <!-- <button type="button" class="button-class width-hundred my-4"
                            onclick="validateStep(1)">Next</button> -->
                    <button type="button" class="button-class width-hundred my-4"
                        onclick="validateStep(1)">Next</button>

                </div>

                <div class="form-step" id="step2">

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <!-- <label for="name">Name:</label> -->
                            <div class="input-group">
                                <i class="fas fa-user"></i>
                                <input type="text" name="fname" id="fname" class="width-hundred"
                                    placeholder="First Name">
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <!-- <div class="input-group">
                                <i class="fa-solid fa-location-dot"></i>
                                <input name="address" id="address" class="width-hundred" placeholder="Your address">
                            </div> -->
                            <!-- <label for="name">Name:</label> -->
                            <div class="input-group">
                                <i class="fas fa-user"></i>
                                <input type="text" name="lname" id="lname" class="width-hundred"
                                    placeholder="Last Name">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <!-- <label for="title">Title:</label> -->
                            <div class="input-group">
                                <i class="fas fa-id-card"></i>
                                <select name="title" name="title" id="title" class="width-hundred">
                                    <option value="" disabled selected>Select title</option>
                                    <option value="315890000">Mr</option>
                                    <option value="315890001">Mrs</option>
                                    <option value="315890002">Ms</option>
                                    <option value="315890003">Miss</option>
                                    <option value="315890004">Mx</option>
                                    <option value="315890005">Dr</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="input-group">
                                <i class="fas fa-venus-mars"></i>
                                <select name="gender" name="gender" id="gender" class="width-hundred">
                                    <option value="" disabled selected>Select gender</option>
                                    <option value="1">Female</option>
                                    <option value="2">Male</option>
                                    <option value="3">Other</option>
                                    <option value="4">Unknown</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <!-- <label for="email">Email:</label> -->
                            <div class="input-group">
                                <i class="fas fa-envelope"></i>
                                <input type="email" class="width-hundred" name="email" id="email"
                                    placeholder="Your Email">
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <!-- <label for="phone">Phone:</label> -->
                            <div class="input-group">
                                <i class="fas fa-phone-alt"></i>
                                <input type="number" class="width-hundred" name="phone" id="phone"
                                    placeholder="Your Phone Number">
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-9 col-md-6">
                            <div class="input-group">
                                <i class="fa-solid fa-cake-candles"></i> <!-- Icon for birthday -->
                                <input type="text" name="dob" id="dob" class="width-hundred"
                                    placeholder="Date of Birth">
                            </div>
                        </div>
                        <div class="col-3 col-md-6">
                            <div>
                                <b>Age: <span id="age"></span></b> <!-- Display Age -->
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-12 col-md-6">
                            <!-- <label for="fromDate">From Date:</label> -->
                            <div class="input-group">
                                <i class="fa-solid fa-calendar-week"></i>
                                <input type="text" name="from_date" id="fromDate" class="width-hundred"
                                    placeholder="Select From Date">
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <!-- <label for="fromDate">From Date:</label> -->
                            <div class="input-group">
                                <i class="fa-solid fa-calendar-week"></i>
                                <input type="text" name="to_date" id="toDate" class="width-hundred"
                                    placeholder="Select To Date">
                            </div>
                        </div>

                        <!-- <div class="col-12 col-md-6"> -->
                        <!-- <label for="toDate">To Date:</label> -->
                        <!-- <div class="input-group">
                                <i class="fas fa-calendar-alt"></i>
                                <input type="date" name="to_date" id="toDate" class="width-hundred"
                                    min="<?php
                                    // echo date('Y-m-d'); ?>">
                            </div>
                        </div> -->
                    </div>

                    <label><b>Service Acknowledgment and Guidelines:</b> <b class="required-asterisk">*</b></label>
                    <p>
                    <div class="checkbox-group">
                        <input type="checkbox" name="certificate_policy" id="certificate_policy">
                        <label for="certificate_policy">I hereby acknowledge and confirm the following:</label>
                    </div>
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
                                        they
                                        are
                                        listed
                                        here.</li>
                                </ul>
                            </li> <br>
                            <li>I recognize that this telehealth service is not a substitute for emergency medical
                                care.
                                If
                                I experience any of the severe symptoms listed above or any other concerning medical
                                condition, I will immediately seek appropriate medical attention, including calling
                                000
                                or
                                visiting the nearest emergency room.</li>
                            <br>
                            <li>I understand that the telehealth service provided on this website is intended for
                                non-emergent medical issues, routine healthcare, and medical consultations that can
                                be
                                safely addressed through telemedicine.</li>
                            <br>
                            <li>If my symptoms do not improve or if I have ongoing health concerns, I agree to
                                schedule
                                an
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

                    <div class="d-flex gap-md-4 gap-1">
                        <!-- <button type="button" class="button-class w-50 my-4" onclick="prevStep()">
                                Back
                            </button> -->
                        <!-- <button type="button" name="register" class="button-class w-50 my-4"
                                onclick="validateStep(2)">
                                Next
                            </button> -->
                        <button type="button" class="button-class w-50 my-4" onclick="prevStep()">Back</button>
                        <button type="button" name="register" class="button-class w-50 my-4"
                            onclick="validateStep(2)">Next</button>
                    </div>

                </div>

                <div class="form-step" id="step3">

                    <label><b>Choose appropriate certificate:</b> <b class="required-asterisk">*</b></label>
                    <p>
                        <input type="radio" id="regular" name="payment" value="10" onchange="updateTotal()" required>
                        <label for="regular">Regular Certificate - $10</label>
                    </p>
                    <p>
                        <input type="radio" id="priority" name="payment" value="20" onchange="updateTotal()" required>
                        <label for="priority">Priority Certificate - $20</label>
                    </p>
                    <small>Priority certificates are cleared within 12 hours.</small> <br><br>

                    <label><b>Credit Card:</b> <b class="required-asterisk">*</b></label>

                    <div class="row">
                        <div class="col-md-6 col-12">
                            <label for="card_number">Card Number:</label>
                            <div id="card-number"></div>
                        </div>

                        <div class="col-md-6 col-12">
                            <label for="security_code">Expiration Date:</label>
                            <div id="card-expiry"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <label for="expiration_date"> Security Code:</label>
                            <div id="card-cvc"></div>
                        </div>

                        <div class="col-12 mt-3">
                            <?php include("assets/countries.php"); ?>
                        </div>
                    </div>

                    <div id="card-errors" role="alert" class="required-asterisk"></div>
                    <h3 id="total">Total: - </h3>
                    <input type="hidden" name="total" id="total-value" value="">

                    <div class="d-flex gap-md-4 gap-1">
                        <button type="button" class="button-class w-50 my-4" onclick="prevStep()">
                            Back
                        </button>
                        <button type="submit" name="register" class="button-class w-50 my-4" onclick="validateStep(3)">
                            Submit
                        </button>
                    </div>
                </div>

                <!-- <input type="submit" value="Submit" class="button-class"> -->
            </form>

        </div>
        <?php include("../include/footer.php"); ?>
    </div>
</div>

<!-- </div> -->

<script src="assets/script.js"></script>
<script src="assets/medicalCertificate.js"></script>
<script src="https://js.stripe.com/v3/"></script>
<script>
    var stripe = Stripe('pk_test_51PqenA04aDQdDBaS4LHhSDd2xsXAVVa7bS9U0EtIAjrWLysUljQaP7c21vVuvSwomcRAekFmAW73JxXvbPywZPuq00I59fXy8P');
    var elements = stripe.elements();

    var style = {
        base: {
            fontSize: '16px',
            color: '#777777',
            fontWeight: '600',
            // border: '1px solid black',
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