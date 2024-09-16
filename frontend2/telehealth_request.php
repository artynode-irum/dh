<?php
session_start();
include '../include/config.php';
require '../vendor/autoload.php';
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client;

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $description = $_POST['description'];
    $payment = $_POST['payment'];
    $firstname = $_POST['fname'];
    $lastname = $_POST['lname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $dob = $_POST['dob'];
    $card_number = "";
    $security_code = "";
    $medicare_number = "";
    $expiry_year = $_POST['expiry_year'];
    $expiry_month = $_POST['expiry_month'];
    $expiry_date = $_POST['expiry_date'];
    $country = $_POST['country'];
    $title = $_POST['title'];
    $gender = $_POST['gender'];
    $appointment_type = $_POST['appointment_type'];
    $appointment_day = $_POST['appointment_day'];
    $appointment_time = $_POST['appointment_time'];
    $address = $_POST['address'];
    $cityCode = $_POST['cityCode'];
    $postcode = $_POST['postcode'];
    $stateCode = $_POST['stateCode'];


    // Sanitize inputs
    $description = htmlspecialchars($description);
    $payment = htmlspecialchars($payment);
    $firstname = htmlspecialchars($firstname);
    $lastname = htmlspecialchars($lastname);
    $email = htmlspecialchars($email);
    $phone = htmlspecialchars($phone);
    $dob = htmlspecialchars($dob);
    // $card_number = htmlspecialchars($card_number);
    // $security_code = htmlspecialchars($security_code);
    // $medicare_number = htmlspecialchars($medicare_number);
    $expiry_year = htmlspecialchars($expiry_year);
    $expiry_month = htmlspecialchars($expiry_month);
    $expiry_date = htmlspecialchars($expiry_date);
    $country = htmlspecialchars($country);
    $title = htmlspecialchars($title);
    $gender = htmlspecialchars($gender);
    $appointment_type = htmlspecialchars($appointment_type);
    $appointment_day = htmlspecialchars($appointment_day);
    $appointment_time = htmlspecialchars($appointment_time);
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


    $token = "xBOWmliN6PNAnRWKAuy6A7vHO2E";  // Access the token from the .env file
    $name = $firstname . $lastname;
    $client = new Client();
    $apiResponse = $client->post('https://api.medirecords.com/v1/patients', [
        'headers' => [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ],
        'json' => [
            "defaultPracticeId" => "1ec6ded0-24c7-47d7-93de-9f139b578ed2",
            "usualDoctorId" => "1ec6ded0-24c7-47d7-93de-9f139b578ed2",
            "titleCode" => $title,
            "firstName" => $firstname,
            "lastName" => $lastname,
            "gender" => $gender,
            "dob" => $dob,
            "email" => $email,
            "mobilePhone" => $phone,
            "contactMethod" => 1
        ],
    ]);

    if ($apiResponse->getStatusCode() == 201) {
        $responseData = json_decode($apiResponse->getBody(), true);
        $patient_id = $responseData['id']; // Adjust according to the actual response structure
    } else {
        echo "Failed to save data.";
        exit;
    }

    $client = new Client([
        'base_uri' => 'https://api.medirecords.com/v1/',
        'headers' => [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ]
    ]);

    $addressData = [
        "addressType" => 1,
        "addressLine1" => substr($address, 0, 45),
        "addressLine2" => substr($address, 45),
        "cityCode" => $cityCode,
        "postcode" => $postcode,
        "stateCode" => $stateCode,
        "countryCode" => "AU"
    ];

    try {
        $response = $client->request('POST', "patients/{$patient_id}/addresses", [
            'json' => $addressData
        ]);

        // Handle success
        // $responseData = json_decode($response->getBody(), true);
        // echo "Address saved successfully!";
        // print_r($responseData);

    } catch (RequestException $e) {
        // Handle error
        if ($e->hasResponse()) {
            echo 'Error: ' . $e->getResponse()->getBody()->getContents();
        } else {
            echo 'Error: ' . $e->getMessage();
        }
    }


    $query1 = "SELECT id, hpassword FROM patient WHERE email = ?";
    $stmt1 = $conn->prepare($query1);
    $stmt1->bind_param('s', $email);
    $stmt1->execute();
    $stmt1->bind_result($Id, $password);
    $stmt1->fetch();
    $stmt1->close();

    if ($password == null) {
        $password = bin2hex(random_bytes(10));
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query2 = "INSERT INTO patient (medirecord_id, email, username, email_verify, password, hpassword) 
        VALUES (?, ?,'1', ?, ?, ?)";
        $stmt2 = $conn->prepare($query2);
        $stmt2->bind_param('sssss', $patient_id, $email, $name, $hashed_password, $password);
        $stmt2->execute();
        $Id = $conn->insert_id;
        $stmt2->close();
    }

    // Insert the request into the database

    // Insert the request into the database
    // $username = $_SESSION['username'];
    $query = "INSERT INTO appointments 
    (patient_id, description, request_date_time, status, payment, name, email, phone, dob, card_number, security_code, country, gender, title, appointment_type, appointment_day, appointment_time, medicare_number, medicare_expiration_date) 
    VALUES 
    (?, ?, NOW(), 'pending', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($query);
    $stmt->bind_param(
        "sssssssssssssssss",
        $Id,
        $description,
        $payment,
        $name,
        $email,
        $phone,
        $dob,
        $card_number,
        $security_code,
        $country,
        $gender,
        $title,
        $appointment_type,
        $appointment_day,
        $appointment_time,
        $medicare_number,
        $medicare_date
    );

    if ($stmt->execute()) {
        $doctorAssigned = false;
        include '../charge.php';
        include '../emails/telehealth_mail.php';
        header("Location: ../thankyou.html");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<?php include("assets/navbar.php"); ?>


<div class="content">
    <div class="request-form">
        <form action="telehealth_request.php" id="requestForm" method="POST">
            <h2 class="text-center">Request Telehealth Appointment</h2>
            <div class="form-step active" id="step1">

                <div class="row">
                    <div class="col-12 col-md-6">
                        <!-- <label for="title"><b>Title:</b></label> -->
                        <div class="input-group">
                            <i class="fas fa-id-card"></i>
                            <select name="title" name="title" id="title" class="width-hundred" required>
                                <option value="" disabled selected>Select Title</option>
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
                        <!-- <label for="gender"><b>Gender:</b></label> -->
                        <div class="input-group">
                            <i class="fas fa-venus-mars"></i>
                            <select name="gender" name="gender" id="gender" class="width-hundred" required>
                                <option value="" disabled selected>Select Gender</option>
                                <option value="1">Female</option>
                                <option value="2">Male</option>
                                <option value="3">Other</option>
                                <option value="4">Unknown</option>
                            </select>
                            </select>
                        </div>

                    </div>
                </div>


                <div class="row">
                    <div class="col-9 col-md-6">
                        <div class="input-group">
                            <i class="fa-solid fa-cake-candles"></i> <!-- Icon for birthday -->
                            <input type="text" name="dob" id="dob" class="width-hundred" placeholder="Date of Birth"
                                required>
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
                        <div class="input-group">
                            <i class="fas fa-user"></i>
                            <input type="text" name="fname" id="fname" class="width-hundred" required
                                placeholder="First Name">
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="input-group">
                            <i class="fas fa-user"></i>
                            <input type="text" name="lname" id="lname" class="width-hundred" required
                                placeholder="Last Name">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-6">
                        <!-- <label for="phone"><b>Phone:</b></label> -->
                        <div class="input-group">
                            <i class="fas fa-phone-alt"></i>
                            <input type="number" class="width-hundred" name="phone" id="phone" required
                                placeholder="Write Phone Number">
                        </div>

                    </div>

                    <div class="col-12 col-md-6">
                        <!-- <label for="medicare_number"><b>Medicare Number:</b></label> -->
                        <div class="input-group">
                            <i class="fa-solid fa-hashtag"></i>
                            <input type="text" class="width-hundred" name="medicare_number" id="medicare_number"
                                maxlength="12" placeholder="Enter Medicare number" oninput="formatMedicareNumber(this)">
                        </div>

                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-6">
                        <!-- <label for="email"><b>Email:</b></label> -->
                        <div class="input-group">
                            <i class="fas fa-envelope"></i>
                            <input type="email" class="width-hundred" name="email" id="email" required
                                placeholder="Your email">
                        </div>

                    </div>

                    <div class="col-12 col-md-6">
                        <!-- <label for="address"><b>Address:</b></label> -->
                        <div class="input-group">
                            <!-- <label for="medicare_number"><b>Medicare Number:</b></label> -->
                            <!-- <i class="fas fa-venus-mars"></i> -->
                            <i class="fa-solid fa-location-dot"></i>
                            <input name="address" id="address" class="width-hundred" placeholder="Your address"
                                oninput="getSuggestions(this.value)">
                        </div>
                        <div id="suggestions"></div>
                        <!-- some hidden fiedls -->
                        <input type="hidden" readonly id="cityCode" name="cityCode" placeholder="City Code">
                        <input type="hidden" readonly id="postcode" name="postcode" placeholder="Postcode">
                        <input type="hidden" readonly id="stateCode" name="stateCode" placeholder="State Code">
                        <input type="hidden" readonly id="countryCode" name="countryCode" placeholder="Country Code">
                        <input type="hidden" readonly id="latitude" name="latitude">
                        <input type="hidden" readonly id="longitude" name="longitude">
                        <input type="hidden" readonly id="timeZone" name="timeZone">
                        <input type="hidden" readonly id="userTime" name="userTime">
                    </div>
                </div>


                <!-- <span id="age"></span> -->

                <label for="expiry_year"><b>Medicare expiry:</b></label>

                <div class="row">
                    <div class="col-12 d-md-flex d-block gap-2">
                        <div class="input-group">
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


                        <div class="input-group">
                            <!-- <label for="expiry_month"><b>Expiry Month:</b></label> -->
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


                        <div class="input-group">
                            <!-- <label for="expiry_date"><b>Expiry Date:</b></label> -->
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


                    </div>
                </div>

                <label><b>Service Acknowledgment and Guidelines:</b> <b class="required-asterisk">*</b></label>
                <div class="checkbox-group">
                    <label for="certificate_policy">
                        <input type="checkbox" name="certificate_policy" id="certificate_policy" required>
                        I hereby acknowledge and confirm the following:</label>
                </div>

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
                        not hesitate to reach out to our team.
                    </p>
                </div>

                <button type="button" class="button-class width-hundred my-4" onclick="validateStep(1)">Next</button>

            </div>

            <div class="form-step" id="step2">

                <label><b>Book an appointment</b></label>

<br>

                <label for="appointment-type"><b>Choose Appointment Date and Time</b><b
                        class="required-asterisk">*</b></label>
                <div class="input-group">
                    <!-- <i class="fas fa-calendar-alt"></i> -->
                    <i class="fa-solid fa-headset"></i>
                    <select name="appointment_type" id="appointment-type" class="width-hundred" required>
                        <!-- <option value="" disabled selected>Select Appointment Type</option> -->
                        <option value="" disabled>Select Appointment Type</option>
                        <option value="Telehealth" selected>Telehealth</option>
                    </select>
                </div>

                <!-- <label for="appointment-day"><b>Appointment Day</b></label> -->
                <div class="input-group">
                    <i class="fas fa-calendar-alt"></i>
                    <input type="date" name="appointment_day" id="appointment-day" class="width-hundred" required
                        min="<?php echo date('Y-m-d'); ?>">
                </div>

                <!-- <label for="appointment-time"><b>Appointment Time</b></label> -->
                <div class="input-group">
                    <!-- <i class="fas fa-calendar-alt"></i> -->
                    <i class="fa-solid fa-clock"></i>
                    <select name="appointment_time" id="appointment-time" class="width-hundred" required>
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

                        <!-- Default placeholder option -->
                        <option value="" selected disabled>Select</option>

                        <!-- Populate time slots -->
                        <?php foreach ($time_slots as $value => $label): ?>
                            <option value="<?php echo $value; ?>"><?php echo $label; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <label for="description"><b>Any other Note you want to share related to appointment:</b></label>
                <div class="input-group">
                    <!-- <i class="fas fa-calendar-alt"></i> -->
                    <i class="fa-solid fa-note-sticky"></i>
                    <input name="description" id="description" class="width-hundred">
                </div>

                <div class="d-flex gap-md-4 gap-1">
                    <button type="button" class="button-class w-50 my-4" onclick="prevStep()">
                        Back
                    </button>
                    <!-- <button type="button" name="register" class="button-class w-50 my-4" onclick="validateStep(2)">
                            Next
                        </button> -->

                    <button type="button" name="register" class="button-class w-50 my-4"
                        onclick="validateStep(2)">Next</button>
                </div>

            </div>

            <div class="form-step" id="step3">

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

                <input type="hidden" name="total" value="39">

                <h3>Total: $39</h3>

                <div class="d-flex gap-md-4 gap-1">
                    <button type="button" class="button-class w-50 my-4" onclick="prevStep()">
                        Back
                    </button>
                    <button type="submit" name="register" class="button-class w-50 my-4" onclick="validateStep()">
                        Submit
                    </button>
                </div>

            </div>
        </form>
    </div>
    <?php include("../include/footer.php"); ?>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="assets/script.js"></script>
<script src="assets/telehealth.js"></script>
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


    const apiKey = '33c82b06f120487fa9e6f38f7758b7f2'; // OpenCage API key
    // Fetch address suggestions and coordinates
    function getSuggestions(query) {
        if (query.length < 3) {
            $('#suggestions').empty();
            return;
        }

        $.ajax({
            url: `https://api.opencagedata.com/geocode/v1/json`,
            data: {
                q: query,
                key: apiKey,
                limit: 5,
                countrycode: 'AU'
            },
            success: function (data) {
                if (data.results && data.results.length > 0) {
                    showSuggestions(data.results);
                } else {
                    $('#suggestions').empty();
                }
            }
        });
    }

    // Show the address suggestions
    function showSuggestions(results) {
        const $suggestions = $('#suggestions');
        $suggestions.empty();

        results.forEach(result => {
            const $div = $('<div>').text(result.formatted).click(() => selectSuggestion(result));
            $suggestions.append($div);
        });
    }

    // When user selects a suggestion, capture local time and prepare the form
    function selectSuggestion(result) {
        console.log(result);
        const address = result.formatted;
        const cityCode = result.components.city || result.components.town || result.components.village || '';
        const postcode = result.components.postcode || '';
        const stateCode = result.components.state_code || result.components.state || '';
        const countryCode = result.components.country_code || '';
        const { lat, lng } = result.geometry; // Get the latitude and longitude

        $('#address').val(address);
        $('#cityCode').val(cityCode);
        $('#postcode').val(postcode);
        $('#stateCode').val(stateCode);
        $('#countryCode').val(countryCode);
        $('#latitude').val(lat);
        $('#longitude').val(lng);
        $('#suggestions').empty();

        // Fetch time zone and local time for the user
        getLocalTime(lat, lng);
    }

    // Fetch time for the user's location
    function getLocalTime(lat, lng) {
        $.ajax({
            url: 'proxy.php?lat=' + lat + '&lng=' + lng,
            data: {
                lat: lat,
                lng: lng
            },
            success: function (data) {
                if (data.status === "OK") {
                    const localTime = new Date(data.timestamp * 1000).toLocaleString(); // Convert Unix timestamp to local time
                    $('#userTime').val(data.formatted); // Set hidden input field value
                    $('#userLocalTimeDisplay').text(data.format); // Display the time
                    $('#timeZone').val(data.zoneName); // Save the time zone
                } else {
                    $('#userLocalTimeDisplay').text('Unable to fetch local time');
                }
            },
            error: function () {
                $('#userLocalTimeDisplay').text('Error fetching local time');
            }
        });
    }
</script>
</body>

</html>