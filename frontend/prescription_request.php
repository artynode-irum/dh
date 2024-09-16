<?php
session_start();
include '../include/config.php';
require '../vendor/autoload.php';

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client;

$appointment_id = $_GET['id'] ?? null;
$doctorId = $_GET['doctor_id'] ?? null;
$patientId = $_GET['patient_id'] ?? null;
$status = isset($_GET['status']) ? $_GET['status'] : 'pending';
$isAssigned = false;

if ($appointment_id) {
    $query = "SELECT patient.username AS patient_username, appointments.doctor_id AS doctor_id , doctor.username AS doctor_username, patient.email, appointments.name, appointments.status, appointments.email, appointments.phone, appointments.dob, appointments.address, appointments.city_code, appointments.postcode, appointments.state_code, appointments.area_timeZone, appointments.country, appointments.gender, appointments.title,appointments.medicare_number, appointments.medicare_expiration_date 
              FROM appointments 
              LEFT JOIN patient ON appointments.patient_id = patient.id 
              LEFT JOIN doctor ON appointments.doctor_id = doctor.id 
              WHERE appointments.id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $appointment_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $appointment = $result->fetch_assoc();
    $stmt->close();
    $parts = explode(' ', $appointment['name']);
    $firstName = $parts[0];
    $remainingParts = array_slice($parts, 1); // All except the first part
    $lastName = implode(' ', $remainingParts);
    $status = isset($appointment['status']) ? $appointment['status'] : "pending";
    $isAssigned = ($appointment['status'] == 'pending') ? false : true;
}

// var_dump($_POST);
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $description = $_POST['description'];
    $payment = isset($_POST['total']) ? $_POST['total'] : '25.99';
    $firstname = $_POST['fname'];
    $lastname = $_POST['lname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $dob = $_POST['dob'];
    $card_number = "";
    $security_code = "";
    $expiration_date = "";
    $country = isset($_POST['country']) ? $_POST['country'] : '';
    $title = $_POST['title'];
    $gender = $_POST['gender'];
    $treatment = $_POST['treatment'];
    $prescriptionafter = $_POST['prescriptionafter'];
    $dosage = isset($_POST['dosage']) ? $_POST['dosage'] : Null;
    $previously_taken_medi = $_POST['previously_taken_medi'];
    $currentlyppb = $_POST['currentlyppb'];
    $health_condition = $_POST['health_condition'];
    $known_allergies = isset($_POST['known_allergies']) ? $_POST['known_allergies'] : "";
    $reason_known_allergies_yes = $_POST['reason_known_allergies_yes'];
    $over_the_counter_drugs = $_POST['over_the_counter_drugs'];
    $healthcare_provider_person_recently = isset($_POST['healthcare_provider_person_recently']) ? $_POST['healthcare_provider_person_recently'] : Null;
    $specific_medication_seeking = isset($_POST['specific_medication_seeking']) ? $_POST['specific_medication_seeking'] : Null;
    $known_nill_allergies = $_POST['known_nill_allergies'];
    $medication_used_previously = isset($_POST['medication_used_previously']) ? $_POST['plan_schedule'] : Null;
    $plan_schedule = isset($_POST['plan_schedule']) ? $_POST['plan_schedule'] : Null;
    $appointment_type = $_POST['appointment_type'];
    $appointment_day = $_POST['appointment_day'];
    $appointment_time = $_POST['appointment_time'];
    $adverse_reactions = isset($_POST['adverse_reactions']) ? $_POST['adverse_reactions'] : Null;
    $address = $_POST['address'];
    $addressLine1 = $_POST['addressLine1'];
    $cityCode = $_POST['cityCode'];
    $postcode = $_POST['postcode'];
    $stateCode = $_POST['stateCode'];
    $timeZone = $_POST['timeZone'];

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
    // $expiration_date = htmlspecialchars($expiration_date);
    $country = htmlspecialchars($country);
    $title = htmlspecialchars($title);
    $gender = htmlspecialchars($gender);
    $prescriptionafter = htmlspecialchars($prescriptionafter);
    $dosage = htmlspecialchars($dosage);
    $previously_taken_medi = htmlspecialchars($previously_taken_medi);
    $currentlyppb = htmlspecialchars($currentlyppb);
    $health_condition = htmlspecialchars($health_condition);
    $known_allergies = htmlspecialchars($known_allergies);
    $reason_known_allergies_yes = htmlspecialchars($reason_known_allergies_yes);
    $over_the_counter_drugs = htmlspecialchars($over_the_counter_drugs);
    $healthcare_provider_person_recently = htmlspecialchars($healthcare_provider_person_recently);
    $specific_medication_seeking = htmlspecialchars($specific_medication_seeking);
    $known_nill_allergies = htmlspecialchars($known_nill_allergies);
    $medication_used_previously = htmlspecialchars($medication_used_previously);
    $plan_schedule = htmlspecialchars($plan_schedule);
    $appointment_type = htmlspecialchars($appointment_type);
    $appointment_day = htmlspecialchars($appointment_day);
    $appointment_time = htmlspecialchars($appointment_time);
    $adverse_reactions = htmlspecialchars($adverse_reactions);
    $address = htmlspecialchars($address);



    $file = $_FILES['relevant_document'] ?? null;
    $uploadDir = 'patient_doc/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true); // Create directory if not exists
    }
    if ($file !== null) {
        $filePath = $uploadDir . basename($file['name']);
        move_uploaded_file($file['tmp_name'], $filePath);
    } else {
        $filePath = "null";
    }


    // $filePath = "https://multiplepromosolutions.com/dh/patient/" . $filePath;
    $filePath = "http://localhost/dh/frontend/" . $filePath;
    $name = $firstname . " " . $lastname;

    if ($status == 'pending') {
        $token = "xBOWmliN6PNAnRWKAuy6A7vHO2E";  // Access the token from the .env file
        // Send data to external API
        $client = new Client();
        $formatted_date = DateTime::createFromFormat('d-m-Y', $dob)->format('Y-m-d');

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
                "dob" => $formatted_date,
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
            'base_uri' => 'https://api.medirecords.com/v1/',  // Base URI of the API
            'headers' => [
                'Authorization' => 'Bearer ' . $token,   // Replace with your actual API token
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ]
        ]);

        $addressData = [
            "addressType" => 1,
            "addressLine1" => $addressLine1,
            // "addressLine1" => substr($address, 0, 45),
            // "addressLine2" => substr($address, 45),
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
        VALUES (?, ?, ?,'1', ?, ?)";
        $stmt2 = $conn->prepare($query2);
        $stmt2->bind_param('sssss', $patient_id, $email, $name, $hashed_password, $password);
        $stmt2->execute();
        $Id = $conn->insert_id;
        $stmt2->close();
    }

    // Insert the request into the database

    // Insert the request into the database
    // $username = $_SESSION['username'];
    if ($status == 'pending') {
        $query = "INSERT INTO prescription 
        (patient_id, description, request_date_time, status, payment, name, email, phone, dob, address, city_code,postcode, state_code, area_timeZone, card_number, security_code, expiration_date, country, gender, title, treatment, prescriptionafter, dosage, previously_taken_medi, currentlyppb, health_condition, known_allergies, over_the_counter_drugs, healthcare_provider_person_recently, specific_medication_seeking, known_nill_allergies, medication_used_previously, plan_schedule, appointment_type, appointment_day, appointment_time, adverse_reactions, document_path, reason_known_allergies_yes) 
        VALUES 
        (?, ?, NOW(), 'pending', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param(
            "ssissssssssssssssssssssssssssssssssss",
            $Id,
            $description,
            $payment,
            $name,
            $email,
            $phone,
            $dob,
            $address,
            $cityCode,
            $postcode,
            $stateCode,
            $timeZone,
            $card_number,
            $security_code,
            $expiration_date,
            $country,
            $gender,
            $title,
            $treatment,
            $prescriptionafter,
            $dosage,
            $previously_taken_medi,
            $currentlyppb,
            $health_condition,
            $known_allergies,
            $over_the_counter_drugs,
            $healthcare_provider_person_recently,
            $specific_medication_seeking,
            $known_nill_allergies,
            $medication_used_previously,
            $plan_schedule,
            $appointment_type,
            $appointment_day,
            $appointment_time,
            $adverse_reactions,
            $filePath,
            $reason_known_allergies_yes
        );
    } else {
        $query = "INSERT INTO prescription 
    (patient_id, doctor_id, description, request_date_time, status, payment, name, email, phone, dob, address, city_code,postcode, state_code, area_timeZone, card_number, security_code, expiration_date, country, gender, title, treatment, prescriptionafter, dosage, previously_taken_medi, currentlyppb, health_condition, known_allergies, over_the_counter_drugs, healthcare_provider_person_recently, specific_medication_seeking, known_nill_allergies, medication_used_previously, plan_schedule, appointment_type, appointment_day, appointment_time, adverse_reactions, document_path, reason_known_allergies_yes) 
    VALUES 
    (?, ?, ?, NOW(), 'assigned', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,  ?)";

        $stmt = $conn->prepare($query);
        $stmt->bind_param(
            "sssissssssssssssssssssssssssssssssssss",
            $Id,
            $doctorId,
            $description,
            $payment,
            $name,
            $email,
            $phone,
            $dob,
            $address,
            $cityCode,
            $postcode,
            $stateCode,
            $timeZone,
            $card_number,
            $security_code,
            $expiration_date,
            $country,
            $gender,
            $title,
            $treatment,
            $prescriptionafter,
            $dosage,
            $previously_taken_medi,
            $currentlyppb,
            $health_condition,
            $known_allergies,
            $over_the_counter_drugs,
            $healthcare_provider_person_recently,
            $specific_medication_seeking,
            $known_nill_allergies,
            $medication_used_previously,
            $plan_schedule,
            $appointment_type,
            $appointment_day,
            $appointment_time,
            $adverse_reactions,
            $filePath,
            $reason_known_allergies_yes
        );
    }

    if ($stmt->execute()) {
        $doctorAssigned = false;
        if ($status == 'pending') {
            include '../charge.php';
        }
        require '../emails/prescription_mail.php';
        header("Location: ../thankyou.html");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    // include '../charge.php';
}
?>


<?php include("assets/navbar.php"); ?>

<div class="container-fluid m-0 p-0">
    <div class="content">
        <div class="request-form">
            <!-- <form action="prescription_request.php" id="requestForm" method="POST" enctype="multipart/form-data"> -->
            <form
                action="prescription_request.php<?php echo isset($appointment['status']) ? '?status=' . $appointment['status'] . '&doctor_id=' . $appointment['doctor_id'] : '' ?>"
                id="requestForm" method="POST" enctype="multipart/form-data">
                <h2 class="text-center">Request Prescription</h2>
                <div class="form-step active " id="step1">

                    <label><b>Choose the treatment</b> <b class="required-asterisk">*</b></label>
                    <div class="radio-pill-group">
                        <input type="radio" id="migraine" name="treatment" value="Mental Health">
                        <label for="migraine">Mental Health</label>

                        <input type="radio" id="skinconditions" name="treatment" value="Skin Conditions">
                        <label for="skinconditions">Skin Conditions</label>

                        <input type="radio" id="asthama" name="treatment" value="Asthama">
                        <label for="asthama">Asthama</label>

                        <input type="radio" id="womenhealth" name="treatment" value="Women's Health">
                        <label for="womenhealth">Women's Health</label>

                        <input type="radio" id="BPCHC" name="treatment"
                            value="Blood Pressure, Cholesterol and Heart Conditions">
                        <label for="BPCHC">Blood Pressure, Cholesterol and Heart Conditions</label>

                        <input type="radio" id="diabetes" name="treatment" value="Diabetes">
                        <label for="diabetes">Diabetes</label>

                        <input type="radio" id="menhealth" name="treatment" value="Men's Health">
                        <label for="menhealth">Men's Health</label>

                        <input type="radio" id="dhr" name="treatment" value="Dyspepsia/Heartburn and Reflux">
                        <label for="dhr">Dyspepsia/Heartburn and Reflux</label>

                        <input type="radio" id="paininflammation" name="treatment" value="Pain and Inflammation">
                        <label for="paininflammation">Pain and Inflammation</label>

                        <input type="radio" id="ahf" name="treatment" value="Allergies and Hay Fever">
                        <label for="ahf">Allergies and Hay Fever</label>

                        <input type="radio" id="gout" name="treatment" value="Gout">
                        <label for="gout">Gout</label>

                        <input type="radio" id="migrainerelief" name="treatment" value="Migraine Relief">
                        <label for="migrainerelief">Migraine Relief</label>

                        <input type="radio" id="morningsickness" name="treatment" value="Morning Sickness">
                        <label for="morningsickness">Morning Sickness</label>
                    </div>

                    <label><b>Please select the prescription you are after:</b> <b
                            class="required-asterisk">*</b></label>

                    <!-- Skin Conditions -->
                    <div class="radio-pill-group">
                        <p id="advantan-container" class="hidden">
                            <input type="radio" id="advantan" name="prescriptionafter" value="Advantan">
                            <label for="advantan">Advantan</label>
                        </p>

                        <p id="ats-container" class="hidden">
                            <input type="radio" id="ats" name="prescriptionafter" value="Antroquoril (Topical steroid)">
                            <label for="ats">Antroquoril (Topical steroid)</label>
                        </p>
                        <p id="cmc-container" class="hidden">
                            <input type="radio" id="cmc" name="prescriptionafter" value="Celestone M Cream">
                            <label for="cmc">Celestone M Cream</label>
                        </p>
                        <p id="diprosone-container" class="hidden">
                            <input type="radio" id="diprosone" name="prescriptionafter" value="Diprosone">
                            <label for="diprosone">Diprosone</label>
                        </p>
                        <p id="doxycycline-container" class="hidden">
                            <input type="radio" id="doxycycline" name="prescriptionafter" value="Doxycycline">
                            <label for="doxycycline">Doxycycline</label>
                        </p>
                        <p id="eleuphratointment-container" class="hidden">
                            <input type="radio" id="eleuphratointment" name="prescriptionafter"
                                value="Eleuphrat Ointment (Potent topical steroid (Betamethasone Proprionate) )">
                            <label for="eleuphratointment">Eleuphrat Ointment (Potent topical steroid (Betamethasone
                                Proprionate) )</label>
                        </p>
                        <p id="elidelcream-container" class="hidden">
                            <input type="radio" id="elidelcream" name="prescriptionafter"
                                value="Elidel Cream (Pimecrolimus)">
                            <label for="elidelcream">Elidel Cream (Pimecrolimus)</label>
                        </p>
                        <p id="elocon-container" class="hidden">
                            <input type="radio" id="elocon" name="prescriptionafter" value="elocon">
                            <label for="elocon">Elocon</label>
                        </p>
                        <p id="epiduogel-container" class="hidden">
                            <input type="radio" id="epiduogel" name="prescriptionafter"
                                value="EpiDuo Gel (Topical gel for acne (Containing Adapalene + benzoyl peroxide))">
                            <label for="epiduogel">EpiDuo Gel (Topical gel for acne (Containing Adapalene + benzoyl
                                peroxide))</label>
                        </p>
                        <p id="kenacombointment-container" class="hidden">
                            <input type="radio" id="kenacombointment" name="prescriptionafter"
                                value="Kenacomb Ointment (Steroid, antibacterial, and antifungal ointment )">
                            <label for="kenacombointment">Kenacomb Ointment (Steroid, antibacterial, and antifungal
                                ointment
                                )</label>
                        </p>
                        <p id="minomycin-container" class="hidden">
                            <input type="radio" id="minomycin" name="prescriptionafter"
                                value="Minomycin (Minocycline for moderate to severe acne )">
                            <label for="minomycin">Minomycin (Minocycline for moderate to severe acne )</label>
                        </p>
                        <p id="mupriocin-container" class="hidden">
                            <input type="radio" id="mupriocin" name="prescriptionafter"
                                value="Mupriocin 2% (Topical antibacterial )">
                            <label for="mupriocin">Mupriocin 2% (Topical antibacterial )</label>
                        </p>
                        <p id="novasonelotion-container" class="hidden">
                            <input type="radio" id="novasonelotion" name="prescriptionafter"
                                value="Novasone Lotion 0.1% (Topical steroid containing mometasone furoate for dermatitis, eczema, and psoriasis )">
                            <label for="novasonelotion">Novasone Lotion 0.1% (Topical steroid containing mometasone
                                furoate
                                for
                                dermatitis, eczema, and psoriasis )</label>
                        </p>
                        <p id="prednisolone-container" class="hidden">
                            <input type="radio" id="prednisolone" name="prescriptionafter"
                                value="Prednisolone (emergency supply of steroid for management of flares) -">
                            <label for="prednisolone">Prednisolone (emergency supply of steroid for management of
                                flares)
                                -</label>
                        </p>
                        <p id="tretinoin-container" class="hidden">
                            <input type="radio" id="tretinoin" name="prescriptionafter"
                                value="Tretinoin (Retrieve) Cream - Topical retinoid used in the treatment of acne">
                            <label for="tretinoin">Tretinoin (Retrieve) Cream - Topical retinoid used in the
                                treatment
                                of
                                acne</label>
                        </p>
                        <p id="daivobet-container" class="hidden">
                            <input type="radio" id="daivobet" name="prescriptionafter" value="Daivobet">
                            <label for="daivobet">Daivobet</label>
                        </p>

                        <!-- Mental Health -->

                        <p id="fluoxetine-container" class="hidden">
                            <input type="radio" id="fluoxetine" name="prescriptionafter"
                                value="Fluoxetine 20mg (Fluoxetine (Apo) Cap 20Mg 28)">
                            <label for="fluoxetine">Fluoxetine 20mg (Fluoxetine (Apo) Cap 20Mg 28)</label>
                        </p>
                        <p id="fluvoxaminemaleate-container" class="hidden">
                            <input type="radio" id="fluvoxaminemaleate" name="prescriptionafter"
                                value="Fluvoxamine Maleate">
                            <label for="fluvoxaminemaleate">Fluvoxamine Maleate</label>
                        </p>
                        <p id="sertraline-container" class="hidden">
                            <input type="radio" id="sertraline" name="prescriptionafter" value="Sertraline (Zoloft)">
                            <label for="sertraline">Sertraline (Zoloft)</label>
                        </p>
                        <p id="citalopram-container" class="hidden">
                            <input type="radio" id="citalopram" name="prescriptionafter" value="Citalopram">
                            <label for="citalopram">Citalopram</label>
                        </p>
                        <p id="cymbalta-container" class="hidden">
                            <input type="radio" id="cymbalta" name="prescriptionafter" value="Cymbalta">
                            <label for="cymbalta">Cymbalta</label>
                        </p>
                        <p id="efexorxr-container" class="hidden">
                            <input type="radio" id="efexorxr" name="prescriptionafter" value="Efexor-XR (Venlafaxine)">
                            <label for="efexorxr">Efexor-XR (Venlafaxine)</label>
                        </p>
                        <p id="lexapro-container" class="hidden">
                            <input type="radio" id="lexapro" name="prescriptionafter" value="Lexapro (Escitalopram)">
                            <label for="lexapro">Lexapro (Escitalopram)</label>
                        </p>
                        <p id="lovan-container" class="hidden">
                            <input type="radio" id="lovan" name="prescriptionafter" value="Lovan (Fluoxetine) 20mg">
                            <label for="lovan">Lovan (Fluoxetine) 20mg</label>
                        </p>
                        <p id="loxalate-container" class="hidden">
                            <input type="radio" id="loxalate" name="prescriptionafter" value="Loxalate (Escitalopram)">
                            <label for="loxalate">Loxalate (Escitalopram)</label>
                        </p>
                        <p id="paroxetine-container" class="hidden">
                            <input type="radio" id="paroxetine" name="prescriptionafter"
                                value="Paroxetine (Aropax) 20mg">
                            <label for="paroxetine">Paroxetine (Aropax) 20mg</label>
                        </p>
                        <p id="pristiq-container" class="hidden">
                            <input type="radio" id="pristiq" name="prescriptionafter" value="Pristiq (Desvenlafaxine)">
                            <label for="pristiq">Pristiq (Desvenlafaxine)</label>
                        </p>
                        <p id="zoloft-container" class="hidden">
                            <input type="radio" id="zoloft" name="prescriptionafter" value="Zoloft">
                            <label for="zoloft">Zoloft</label>
                        </p>
                        <p id="mirtazapine-container" class="hidden">
                            <input type="radio" id="mirtazapine" name="prescriptionafter" value="Mirtazapine">
                            <label for="mirtazapine">Mirtazapine</label>
                        </p>


                        <!-- Asthama  -->

                        <p id="Flixotide-container" class="hidden">
                            <input type="radio" id="Flixotide" name="prescriptionafter" value="Flixotide">
                            <label for="Flixotide">Flixotide</label>
                        </p>

                        <p id="seretidesccuhaler-container" class="hidden">
                            <input type="radio" id="seretidesccuhaler" name="prescriptionafter"
                                value="Seretide Accuhaler">
                            <label for="seretidesccuhaler">Seretide Accuhaler</label>
                        </p>

                        <p id="seretidemdi-container" class="hidden">
                            <input type="radio" id="seretidemdi" name="prescriptionafter" value="Seretide MDI">
                            <label for="seretidemdi">Seretide MDI</label>
                        </p>

                        <p id="symbicortrapihaler-container" class="hidden">
                            <input type="radio" id="symbicortrapihaler" name="prescriptionafter"
                                value="Symbicort Rapihaler">
                            <label for="symbicortrapihaler">Symbicort Rapihaler</label>
                        </p>

                        <p id="symbicortturbuhaler-container" class="hidden">
                            <input type="radio" id="symbicortturbuhaler" name="prescriptionafter"
                                value="Symbicort Turbuhaler">
                            <label for="symbicortturbuhaler">Symbicort Turbuhaler</label>
                        </p>

                        <p id="ventolin-container" class="hidden">
                            <input type="radio" id="ventolin" name="prescriptionafter" value="Ventolin">
                            <label for="ventolin">Ventolin</label>
                        </p>

                        <!-- Women's Health -->



                        <p id="birthcontrol-container" class="hidden">
                            <input type="radio" id="birthcontrol" name="prescriptionafter" value="Birth Control">
                            <label for="birthcontrol">Birth Control</label>
                        </p>

                        <p id="menopause-container" class="hidden">
                            <input type="radio" id="menopause" name="prescriptionafter" value="Menopause">
                            <label for="menopause">Menopause</label>
                        </p>

                        <!-- Blood Pressure, Cholesterol and Heart Conditions -->

                        <p id="amlodipine-container" class="hidden">
                            <input type="radio" id="amlodipine" name="prescriptionafter" value="Amlodipine">
                            <label for="amlodipine">Amlodipine</label>
                        </p>

                        <p id="atenolol-container" class="hidden">
                            <input type="radio" id="atenolol" name="prescriptionafter"
                                value="Atenolol 50mg (Atenolol (Apo) Tab 50Mg 30)">
                            <label for="atenolol">Atenolol 50mg (Atenolol (Apo) Tab 50Mg 30)</label>
                        </p>

                        <p id="avapro-container" class="hidden">
                            <input type="radio" id="avapro" name="prescriptionafter" value="Avapro 300mg (Irbesartan)">
                            <label for="avapro">Avapro 300mg (Irbesartan)</label>
                        </p>

                        <p id="avaprohct-container" class="hidden">
                            <input type="radio" id="avaprohct" name="prescriptionafter" value="Avapro HCT">
                            <label for="avaprohct">Avapro HCT</label>
                        </p>

                        <p id="caduet-container" class="hidden">
                            <input type="radio" id="caduet" name="prescriptionafter" value="Caduet">
                            <label for="caduet">Caduet</label>
                        </p>

                        <p id="coveram-container" class="hidden">
                            <input type="radio" id="coveram" value="Coveram" name="prescriptionafter">
                            <label for="coveram">Coveram</label>
                        </p>

                        <p id="coversyl-container" class="hidden">
                            <input type="radio" id="coversyl" value="Coversyl" name="prescriptionafter">
                            <label for="coversyl">Coversyl</label>
                        </p>

                        <p id="coversylplus-container" class="hidden">
                            <input type="radio" id="coversylplus" value="Coversyl Plus" name="prescriptionafter">
                            <label for="coversylplus">Coversyl Plus</label>
                        </p>

                        <p id="crestor-container" class="hidden">
                            <input type="radio" id="crestor" value="Crestor" name="prescriptionafter">
                            <label for="crestor">Crestor</label>
                        </p>

                        <p id="frusemide-container" class="hidden">
                            <input type="radio" id="frusemide" value="Frusemide" name="prescriptionafter">
                            <label for="frusemide">Frusemide</label>
                        </p>

                        <p id="irbesartan-container" class="hidden">
                            <input type="radio" id="irbesartan" value="Irbesartan" name="prescriptionafter">
                            <label for="irbesartan">Irbesartan</label>
                        </p>

                        <p id="lipitor-container" class="hidden">
                            <input type="radio" id="lipitor" value="Lipitor" name="prescriptionafter">
                            <label for="lipitor">Lipitor</label>
                        </p>

                        <p id="metoprolol-container" class="hidden">
                            <input type="radio" id="metoprolol" value="Metoprolol" name="prescriptionafter">
                            <label for="metoprolol">Metoprolol</label>
                        </p>

                        <p id="propranolol-container" class="hidden">
                            <input type="radio" id="propranolol" value="Propranolol" name="prescriptionafter">
                            <label for="propranolol">Propranolol</label>
                        </p>

                        <p id="olmesartan-container" class="hidden">
                            <input type="radio" id="olmesartan" value="Olmesartan" name="prescriptionafter">
                            <label for="olmesartan">Olmesartan</label>
                        </p>

                        <p id="perindopril-container" class="hidden">
                            <input type="radio" id="perindopril" value="Perindopril" name="prescriptionafter">
                            <label for="perindopril">Perindopril</label>
                        </p>

                        <p id="olmetec-container" class="hidden">
                            <input type="radio" id="olmetec" value="Olmetec 20mg (Olmesartan)" name="prescriptionafter">
                            <label for="olmetec">Olmetec 20mg (Olmesartan)</label>
                        </p>

                        <p id="reaptan-container" class="hidden">
                            <input type="radio" id="reaptan" value="Reaptan" name="prescriptionafter">
                            <label for="reaptan">Reaptan</label>
                        </p>

                        <p id="telmisartan-container" class="hidden">
                            <input type="radio" id="telmisartan" value="Telmisartan (Micardis)"
                                name="prescriptionafter">
                            <label for="telmisartan">Telmisartan (Micardis)</label>
                        </p>

                        <!-- Diabetes -->

                        <p id="metformin500-container" class="hidden">
                            <input type="radio" id="metformin500" value="Metformin 500mg" name="prescriptionafter">
                            <label for="metformin500">Metformin 500mg</label>
                        </p>

                        <p id="metformin1000-container" class="hidden">
                            <input type="radio" id="metformin1000" value="Metformin 1000mg" name="prescriptionafter">
                            <label for="metformin1000">Metformin 1000mg</label>
                        </p>

                        <p id="metforminxr500-container" class="hidden">
                            <input type="radio" id="metforminxr500"
                                value="Metformin XR 500mg (Metformin Xr 500 (Apo) Er-Tab)" name="prescriptionafter">
                            <label for="metforminxr500">Metformin XR 500mg (Metformin Xr 500 (Apo) Er-Tab)</label>
                        </p>

                        <p id="novorapidflexpen-container" class="hidden">
                            <input type="radio" id="novorapidflexpen" value="Novorapid Flexpen 100units/ml (Insulin)"
                                name="prescriptionafter">
                            <label for="novorapidflexpen">Novorapid Flexpen 100units/ml (Insulin)</label>
                        </p>

                        <p id="novorapidpenfill-container" class="hidden">
                            <input type="radio" id="novorapidpenfill" value="Novorapid Penfill 100units/ml (Insulin)"
                                name="prescriptionafter">
                            <label for="novorapidpenfill">Novorapid Penfill 100units/ml (Insulin)</label>
                        </p>

                        <p id="novorapidvial-container" class="hidden">
                            <input type="radio" id="novorapidvial" value="Novorapid Vial 100units/ml (Insulin)"
                                name="prescriptionafter">
                            <label for="novorapidvial">Novorapid Vial 100units/ml (Insulin)</label>
                        </p>

                        <p id="diaforminxr1000-container" class="hidden">
                            <input type="radio" id="diaforminxr1000" value="Diaformin XR 1000mg (Meformin)"
                                name="prescriptionafter">
                            <label for="diaforminxr1000">Diaformin XR 1000mg (Meformin)</label>
                        </p>

                        <!-- Men's Health -->

                        <p id="priligy-container" class="hidden">
                            <input type="radio" id="priligy" value="Priligy (Dapoxetine)" name="prescriptionafter">
                            <label for="priligy">Priligy (Dapoxetine)</label>
                        </p>

                        <p id="sildenafil50-container" class="hidden">
                            <input type="radio" id="sildenafil50"
                                value="Sildenafil (Viagra) 50mg (Erectile dysfunction)" name="prescriptionafter">
                            <label for="sildenafil50">Sildenafil (Viagra) 50mg (Erectile dysfunction)</label>
                        </p>

                        <p id="sildenafil100-container" class="hidden">
                            <input type="radio" id="sildenafil100"
                                value="Sildenafil (Viagra) 100mg (Erectile dysfunction)" name="prescriptionafter">
                            <label for="sildenafil100">Sildenafil (Viagra) 100mg (Erectile dysfunction)</label>
                        </p>

                        <p id="tadalafil10-container" class="hidden">
                            <input type="radio" id="tadalafil10" value="Tadalafil (Cialis) 10mg (Erectile dysfunction)"
                                name="prescriptionafter">
                            <label for="tadalafil10">Tadalafil (Cialis) 10mg (Erectile dysfunction)</label>
                        </p>

                        <p id="tadalafil20-container" class="hidden">
                            <input type="radio" id="tadalafil20" value="Tadalafil (Cialis) 20mg (Erectile dysfunction)"
                                name="prescriptionafter">
                            <label for="tadalafil20">Tadalafil (Cialis) 20mg (Erectile dysfunction)</label>
                        </p>

                        <p id="tadalafil50-container" class="hidden">
                            <input type="radio" id="tadalafil50" value="Tadalafil (Cialis) 50mg (Erectile dysfunction)"
                                name="prescriptionafter">
                            <label for="tadalafil50">Tadalafil (Cialis) 50mg (Erectile dysfunction)</label>
                        </p>

                        <!-- Dyspepsia/Heartburn and Reflux -->

                        <p id="zoton15-container" class="hidden">
                            <input type="radio" id="zoton15" value="Zoton 15mg (Zoton Fastabs Or-Distab 15Mg 2)"
                                name="prescriptionafter">
                            <label for="zoton15">Zoton 15mg (Zoton Fastabs Or-Distab 15Mg 2)</label>
                        </p>

                        <p id="zoton30-container" class="hidden">
                            <input type="radio" id="zoton30" value="Zoton 30mg (Zoton Fastabs Or-Distab 30Mg 2)"
                                name="prescriptionafter">
                            <label for="zoton30">Zoton 30mg (Zoton Fastabs Or-Distab 30Mg 2)</label>
                        </p>

                        <p id="nexium20-container" class="hidden">
                            <input type="radio" id="nexium20" value="Nexium 20mg (Esomeprazole)"
                                name="prescriptionafter">
                            <label for="nexium20">Nexium 20mg (Esomeprazole)</label>
                        </p>

                        <p id="nexium40-container" class="hidden">
                            <input type="radio" id="nexium40" value="Nexium 40mg (Esomeprazole)"
                                name="prescriptionafter">
                            <label for="nexium40">Nexium 40mg (Esomeprazole)</label>
                        </p>

                        <p id="acimax10-container" class="hidden">
                            <input type="radio" id="acimax10" value="Acimax 10mg (Omeprazole)" name="prescriptionafter">
                            <label for="acimax10">Acimax 10mg (Omeprazole)</label>
                        </p>

                        <p id="acimax20-container" class="hidden">
                            <input type="radio" id="acimax20" value="Acimax 20mg (Omeprazole)" name="prescriptionafter">
                            <label for="acimax20">Acimax 20mg (Omeprazole)</label>
                        </p>

                        <p id="pariet10-container" class="hidden">
                            <input type="radio" id="pariet10" value="Pariet 10mg (Rabeprazole)"
                                name="prescriptionafter">
                            <label for="pariet10">Pariet 10mg (Rabeprazole)</label>
                        </p>

                        <p id="pariet20-container" class="hidden">
                            <input type="radio" id="pariet20" value="Pariet 20mg (Rabeprazole)"
                                name="prescriptionafter">
                            <label for="pariet20">Pariet 20mg (Rabeprazole)</label>
                        </p>

                        <p id="somac20-container" class="hidden">
                            <input type="radio" id="somac20" value="Somac 20mg (Pantoprazole)" name="prescriptionafter">
                            <label for="somac20">Somac 20mg (Pantoprazole)</label>
                        </p>

                        <p id="somac40-container" class="hidden">
                            <input type="radio" id="somac40" value="Somac 40mg (Pantoprazole)" name="prescriptionafter">
                            <label for="somac40">Somac 40mg (Pantoprazole) </label>
                        </p>

                        <!-- Pain and Inflammation -->

                        <p id="meloxicam7_5-container" class="hidden">
                            <input type="radio" id="meloxicam7_5" value="Meloxicam 7.5mg Capsule"
                                name="prescriptionafter">
                            <label for="meloxicam7_5">Meloxicam 7.5mg Capsule</label>
                        </p>

                        <p id="meloxicam15-container" class="hidden">
                            <input type="radio" id="meloxicam15" value="Meloxicam 15mg Capsule"
                                name="prescriptionafter">
                            <label for="meloxicam15">Meloxicam 15mg Capsule</label>
                        </p>

                        <p id="celebrex60-container" class="hidden">
                            <input type="radio" id="celebrex60" value="Celebrex 100mg (Celebrex Cap 100Mg 60)"
                                name="prescriptionafter">
                            <label for="celebrex60">Celebrex 100mg (Celebrex Cap 100Mg 60)</label>
                        </p>

                        <p id="celebrex30-container" class="hidden">
                            <input type="radio" id="celebrex30" value="Celebrex 100mg (Celebrex Cap 200Mg 30)"
                                name="prescriptionafter">
                            <label for="celebrex30">Celebrex 100mg (Celebrex Cap 200Mg 30)</label>
                        </p>

                        <p id="naprosyn250-container" class="hidden">
                            <input type="radio" id="naprosyn250" value="Naprosyn 250mg (Naprosyn Tab 250Mg 50)"
                                name="prescriptionafter">
                            <label for="naprosyn250">Naprosyn 250mg (Naprosyn Tab 250Mg 50)</label>
                        </p>

                        <p id="naprosyn500-container" class="hidden">
                            <input type="radio" id="naprosyn500" value="Naprosyn 500mg (Naprosyn Tab 500Mg 50)"
                                name="prescriptionafter">
                            <label for="naprosyn500">Naprosyn 500mg (Naprosyn Tab 500Mg 50)</label>
                        </p>

                        <p id="voltaren-container" class="hidden">
                            <input type="radio" id="voltaren" value="Voltaren 50 (Voltaren Ec-Tabs 50Mg 50)"
                                name="prescriptionafter">
                            <label for="voltaren">Voltaren 50 (Voltaren Ec-Tabs 50Mg 50)</label>
                        </p>

                        <p id="voltarenrapid-container" class="hidden">
                            <input type="radio" id="voltarenrapid" value="Voltaren Rapid 50mg (Diclofenac) "
                                name="prescriptionafter">
                            <label for="voltarenrapid">Voltaren Rapid 50mg (Diclofenac) </label>
                        </p>

                        <!-- Allergies and Hay Fever -->

                        <p id="avamys-container" class="hidden">
                            <input type="radio" id="avamys" value="Avamys (Fluticasone furoate)"
                                name="prescriptionafter">
                            <label for="avamys">Avamys (Fluticasone furoate)</label>
                        </p>

                        <p id="dymista-container" class="hidden">
                            <input type="radio" id="dymista" value="Dymista (Azelastine + fluticasone propionate)"
                                name="prescriptionafter">
                            <label for="dymista">Dymista (Azelastine + fluticasone propionate)</label>
                        </p>

                        <p id="omnaris-container" class="hidden">
                            <input type="radio" id="omnaris" value="Omnaris (Ciclesonide)" name="prescriptionafter">
                            <label for="omnaris">Omnaris (Ciclesonide)</label>
                        </p>

                        <p id="rhinocort-container" class="hidden">
                            <input type="radio" id="rhinocort" value="Rhinocort (Budesonide) " name="prescriptionafter">
                            <label for="rhinocort">Rhinocort (Budesonide) </label>
                        </p>

                        <!-- Gout -->

                        <p id="allopurinol100-container" class="hidden">
                            <input type="radio" id="allopurinol100" value="Allopurinol 100mg " name="prescriptionafter">
                            <label for="allopurinol100">Allopurinol 100mg</label>
                        </p>

                        <p id="allopurinol300-container" class="hidden">
                            <input type="radio" id="allopurinol300" value="Allopurinol 300mg " name="prescriptionafter">
                            <label for="allopurinol300">Allopurinol 300mg</label>
                        </p>

                        <p id="colgout500-container" class="hidden">
                            <input type="radio" id="colgout500" value="Colgout 500mcg (Colchicine - Gout flare)"
                                name="prescriptionafter">
                            <label for="colgout500">Colgout 500mcg (Colchicine - Gout flare)</label>
                        </p>

                        <!-- Migraine Relief -->

                        <p id="imigran50-container" class="hidden">
                            <input type="radio" id="imigran50" value="Imigran FC Tablets 50mg" name="prescriptionafter">
                            <label for="imigran50">Imigran FC Tablets 50mg</label>
                        </p>

                        <p id="imigran20-container" class="hidden">
                            <input type="radio" id="imigran20" value="Imigran Nasal Spray 20mg (Sumatriptan)"
                                name="prescriptionafter">
                            <label for="imigran20">Imigran Nasal Spray 20mg (Sumatriptan)</label>
                        </p>

                        <p id="maxaltwafers10-container" class="hidden">
                            <input type="radio" id="maxaltwafers10" value="Maxalt Wafers 10mg (Rizatriptan)"
                                name="prescriptionafter">
                            <label for="maxaltwafers10">Maxalt Wafers 10mg (Rizatriptan)</label>
                        </p>

                        <p id="relpax40-container" class="hidden">
                            <input type="radio" id="relpax40" value="Relpax 40mg (Eletriptan)" name="prescriptionafter">
                            <label for="relpax40">Relpax 40mg (Eletriptan)</label>
                        </p>

                        <p id="relpax80-container" class="hidden">
                            <input type="radio" id="relpax80" value="Relpax 80mg (Eletriptan)" name="prescriptionafter">
                            <label for="relpax80">Relpax 80mg (Eletriptan)</label>
                        </p>

                        <!-- Morning Sickness -->

                        <p id="zofran10-container" class="hidden">
                            <input type="radio" id="zofran10" value="Zofran Zydis Wafers 4mg (10 wafers)"
                                name="prescriptionafter">
                            <label for="zofran10">Zofran Zydis Wafers 4mg (10 wafers)</label>
                        </p>

                        <p id="zofran4-container" class="hidden">
                            <input type="radio" id="zofran4" value="Zofran Zydis Wafers 8mg (4 wafers)"
                                name="prescriptionafter">
                            <label for="zofran4">Zofran Zydis Wafers 8mg (4 wafers)</label>
                        </p>
                    </div>

                    <!-- Dosage -->

                    <div id="fluvoxamine-dosage" class="hidden">
                        <!-- Fluvoxamine Maleate -->
                        <label><b>Select Dosage:</b></label>
                        <div class="radio-pill-group">

                            <input type="radio" id="fluvoxamine-50mg" value="50mg (Fluvoxamine (Apo) Tab 50Mg 30)"
                                name="dosage">
                            <label for="fluvoxamine-50mg">50mg (Fluvoxamine (Apo) Tab 50Mg 30)</label>

                            <input type="radio" id="fluvoxamine-100mg" value="100mg (Fluvoxamine (Apo) Tab 100Mg 30)"
                                name="dosage">
                            <label for="fluvoxamine-100mg">100mg (Fluvoxamine (Apo) Tab 100Mg 30)</label>
                        </div>
                    </div>


                    <div id="sertralinezoloft-dosage" class="hidden">
                        <!-- Sertraline (Zoloft) -->
                        <label><b>Select Dosage:</b></label>
                        <div class="radio-pill-group">
                            <input type="radio" id="sertraline-100mg"
                                value="100mg (Sertraline (Zoloft) 100mg - Sertraline)" name="dosage">
                            <label for="sertraline-100mg">100mg (Sertraline (Zoloft) 100mg - Sertraline)</label>

                            <input type="radio" id="sertraline-50mg"
                                value="50mg (Sertraline (Zoloft) 50mg - Sertraline)" name="dosage">
                            <label for="sertraline-50mg">50mg (Sertraline (Zoloft) 50mg - Sertraline)</label>
                        </div>
                    </div>

                    <div id="citalopram-dosage" class="hidden">
                        <!-- Citalopram -->
                        <label><b>Select Dosage:</b></label>
                        <div class="radio-pill-group">
                            <input type="radio" id="citalopram-20mg" value="20mg (Citalopram 20mg - Citalopram)"
                                name="dosage">
                            <label for="citalopram-20mg">20mg (Citalopram 20mg - Citalopram)</label>

                            <input type="radio" id="citalopram-40mg" value="40mg (Citalopram 40mg - Citalopram)"
                                name="dosage">
                            <label for="citalopram-40mg">40mg (Citalopram 40mg - Citalopram)</label>
                        </div>
                    </div>

                    <div id="cymbalta-dosage" class="hidden">
                        <!-- Cymbalta -->
                        <label><b>Select Dosage:</b></label>
                        <div class="radio-pill-group">
                            <input type="radio" id="Cymbalta30mg" value="30mg (Duloxetine)" name="dosage">
                            <label for="Cymbalta30mg">30mg (Duloxetine)</label>

                            <input type="radio" id="Cymbalta60mg" value="60mg (Duloxetine) " name="dosage">
                            <label for="Cymbalta60mg">60mg (Duloxetine) </label>
                        </div>
                    </div>

                    <div id="efexorxr-dosage" class="hidden">
                        <!--  Efexor-XR (Venlafaxine) -->
                        <label><b>Select Dosage:</b></label>
                        <div class="radio-pill-group">
                            <input type="radio" id="Efexor-XR-Venlafaxine-37mg" value="60mg (Duloxetine) "
                                name="dosage">
                            <label for="Efexor-XR-Venlafaxine-37mg"> 37.5 mg</label>

                            <input type="radio" id="Efexor-XR-Venlafaxine-75mg" value="30mg (Duloxetine)" name="dosage">
                            <label for="Efexor-XR-Venlafaxine-75mg">75 mg</label>

                            <input type="radio" id="Efexor-XR-Venlafaxine-150mg" value="60mg (Duloxetine) "
                                name="dosage">
                            <label for="Efexor-XR-Venlafaxine-150mg"> 150 mg</label>
                        </div>
                    </div>

                    <div id="lexapro-dosage" class="hidden">
                        <!-- Lexapro (Escitalopram) -->
                        <label><b>Select Dosage:</b></label>
                        <div class="radio-pill-group">
                            <input type="radio" id="Lexapro10mg" value="10mg" name="dosage">
                            <label for="Lexapro10mg"> 10 mg</label>

                            <input type="radio" id="Lexapro20mg" value="20mg" name="dosage">
                            <label for="Lexapro20mg">20 mg</label>
                        </div>
                    </div>

                    <div id="loxalate-dosage" class="hidden">
                        <!-- Loxalate (Escitalopram) -->
                        <label><b>Select Dosage:</b></label>
                        <div class="radio-pill-group">
                            <input type="radio" id="Loxalate10mg" value="10mg" name="dosage">
                            <label for="Loxalate10mg"> 10 mg</label>

                            <input type="radio" id="Loxalate20mg" value="20mg" name="dosage">
                            <label for="Loxalate20mg">20 mg</label>
                        </div>
                    </div>

                    <div id="pristiq-dosage" class="hidden">
                        <!-- Pristiq (Desvenlafaxine) -->
                        <label><b>Select Dosage:</b></label>
                        <div class="radio-pill-group">
                            <input type="radio" id="Pristiq50mg" value="50mg" name="dosage">
                            <label for="Pristiq50mg"> 50 mg</label>

                            <input type="radio" id="Pristiq100mg" value="100mg" name="dosage">
                            <label for="Pristiq100mg">100 mg</label>
                        </div>
                    </div>

                    <div id="zoloft-dosage" class="hidden">
                        <!-- Zoloft -->
                        <label><b>Select Dosage:</b></label>
                        <div class="radio-pill-group">
                            <input type="radio" id="Zoloft50mg" value="50mg" name="dosage">
                            <label for="Zoloft50mg">50mg (Zoloft Tab 50mg (As Hcl) 30 - Sertraline)</label>

                            <input type="radio" id="Zoloft100mg" value="100mg" name="dosage">
                            <label for="Zoloft100mg">100mg (Zoloft Tab 100mg (As Hcl) 30 - Sertraline)</label>
                        </div>
                    </div>

                    <div id="mirtazapine-dosage" class="hidden">
                        <!-- Mirtazapine -->
                        <label><b>Select Dosage:</b></label>
                        <div class="radio-pill-group">
                            <input type="radio" id="Mirtazapine-15mg" value="15mg" name="dosage">
                            <label for="Mirtazapine-15mg">15 mg</label>

                            <input type="radio" id="Mirtazapine-30mg" value="30mg" name="dosage">
                            <label for="Mirtazapine-30mg">30 mg</label>

                            <input type="radio" id="Mirtazapine-45mg" value="45mg" name="dosage">
                            <label for="Mirtazapine-45mg">45 mg</label>
                        </div>
                    </div>

                    <div id="advantan-dosage" class="hidden">
                        <!-- Advantan -->
                        <label><b>Select Type</b></label>
                        <div class="radio-pill-group">
                            <input type="radio" id="cream" value="Cream" name="dosage">
                            <label for="cream">Cream</label>

                            <input type="radio" id="FattyOintment" value="Fatty Ointment" name="dosage">
                            <label for="FattyOintment">Fatty Ointment</label>

                            <input type="radio" id="Ointment" value="Ointment" name="dosage">
                            <label for="Ointment">Ointment</label>
                        </div>
                    </div>

                    <div id="diprosone-dosage" class="hidden">
                        <!-- Diprosone -->
                        <label><b>Select Type</b></label>
                        <div class="radio-pill-group">
                            <input type="radio" id="cream50g" value="Cream 50g" name="dosage">
                            <label for="cream50g">Cream 50g</label>

                            <input type="radio" id="Diprosonecream15g" value="Cream 15g" name="dosage">
                            <label for="Diprosonecream15g">Cream 15g</label>

                            <input type="radio" id="ovointment30g" value="OV Ointment 30g " name="dosage">
                            <label for="ovointment30g">OV Ointment 30g </label>
                        </div>
                    </div>

                    <div id="doxycycline-dosage" class="hidden">
                        <!-- Doxycycline -->
                        <label><b>Select Type</b></label>
                        <div class="radio-pill-group">
                            <input type="radio" id="antibiotic100mg" value="100mg (Antibiotic for severe acne)"
                                name="dosage">
                            <label for="antibiotic100mg">100mg (Antibiotic for severe acne)</label>

                            <input type="radio" id="500mg" value="50mg (Antibiotic used for mild to moderate acne)"
                                name="dosage">
                            <label for="500mg">50mg (Antibiotic used for mild to moderate acne)</label>
                        </div>

                    </div>

                    <div id="elocon-dosage" class="hidden">
                        <!-- Elocon -->

                        <label><b>Select Type</b></label>
                        <div class="radio-pill-group">
                            <input type="radio" id="Eloconcream15g" value="Cream 15g" name="dosage">
                            <label for="Eloconcream15g">Cream 15g</label>

                            <input type="radio" id="cream45g" value="Cream 45g" name="dosage">
                            <label for="cream45g">Cream 45g</label>

                            <input type="radio" id="lotion" value="Lotion" name="dosage">
                            <label for="lotion">Lotion</label>

                            <input type="radio" id="ointment15g" value="Ointment 15g" name="dosage">
                            <label for="ointment15g">Ointment 15g</label>
                        </div>

                    </div>

                    <div id="daivobet-dosage" class="hidden">
                        <!-- Daivobet -->

                        <label><b>Select Type</b></label>
                        <div class="radio-pill-group">
                            <input type="radio" id="Gel50" value="Gel 50/500" name="dosage">
                            <label for="Gel50">Gel 50/500</label>

                            <input type="radio" id="Ointment50" value="Ointment 50/500" name="dosage">
                            <label for="Ointment50">Ointment 50/500</label>
                        </div>

                    </div>

                    <div id="flixotide-dosage" class="hidden">
                        <!-- Flixotide -->

                        <label><b>Select Type</b></label>
                        <div class="radio-pill-group">
                            <input type="radio" id="125mcg" value="125mcg" name="dosage">
                            <label for="125mcg">125mcg</label>

                            <input type="radio" id="250mcg" value="250mcg" name="dosage">
                            <label for="250mcg">250mcg</label>

                            <input type="radio" id="MDI250mcg" value="MDI 250mcg" name="dosage">
                            <label for="MDI250mcg">MDI 250mcg</label>

                            <input type="radio" id="Accuhaler500mcg" value="Accuhaler 500mcg" name="dosage">
                            <label for="Accuhaler500mcg">Accuhaler 500mcg</label>

                            <input type="radio" id="Junior50mcg" value="Junior 50mcg" name="dosage">
                            <label for="Junior50mcg">Junior 50mcg</label>
                        </div>

                    </div>

                    <div id="SeretideAccuhaler-dosage" class="hidden">
                        <!-- Seretide Accuhaler -->

                        <label><b>Select Type</b></label>
                        <div class="radio-pill-group">
                            <input type="radio" id="100/50" value="100/50" name="dosage">
                            <label for="100/50">100/50</label>

                            <input type="radio" id="250/50" value="250/50" name="dosage">
                            <label for="250/50">250/50</label>

                            <input type="radio" id="500/50" value="500/50" name="dosage">
                            <label for="500/50">500/50</label>
                        </div>

                    </div>

                    <div id="SeretideMDI-dosage" class="hidden">
                        <!-- Seretide MDI -->

                        <label><b>Select Type</b></label>
                        <div class="radio-pill-group">
                            <input type="radio" id="125/25" value="125/25" name="dosage">
                            <label for="125/25">125/25</label>

                            <input type="radio" id="250/25" value="250/25" name="dosage">
                            <label for="250/25">250/25</label>

                            <input type="radio" id="50/25" value="50/25" name="dosage">
                            <label for="50/25">50/25</label>
                        </div>

                    </div>

                    <div id="SymbicortRapihaler-dosage" class="hidden">
                        <!-- Symbicort Rapihaler -->

                        <label><b>Select Type</b></label>
                        <div class="radio-pill-group">
                            <input type="radio" id="100/3" value="100/3" name="dosage">
                            <label for="100/3">100/3</label>

                            <input type="radio" id="Rapihaler200/6" value="200/6" name="dosage">
                            <label for="Rapihaler200/6">200/6</label>

                            <input type="radio" id="Rapihaler400/12" value="400/12" name="dosage">
                            <label for="Rapihaler400/12">400/12</label>
                        </div>

                    </div>

                    <div id="SymbicortTurbuhaler-dosage" class="hidden">
                        <!-- Symbicort Turbuhaler -->

                        <label><b>Select Type</b></label>
                        <div class="radio-pill-group">
                            <input type="radio" id="100/6" value="100/6" name="dosage">
                            <label for="100/6">100/6</label>

                            <input type="radio" id="Turbuhaler200/6" value="200/6" name="dosage">
                            <label for="Turbuhaler200/6">200/6</label>

                            <input type="radio" id="Turbuhaler400/12" value="400/12" name="dosage">
                            <label for="Turbuhaler400/12">400/12</label>
                        </div>

                    </div>

                    <div id="Ventolin-dosage" class="hidden">
                        <!-- 	Ventolin -->

                        <label><b>Select Type</b></label>
                        <div class="radio-pill-group">
                            <input type="radio" id="Nebules2.5mg" value="Nebules 2.5mg" name="dosage">
                            <label for="Nebules2.5mg">Nebules 2.5mg</label>

                            <input type="radio" id="Nebules5mg" value="Nebules 5mg" name="dosage">
                            <label for="Nebules5mg">Nebules 5mg</label>

                            <input type="radio" id="100mcgCFC-FreeInhaler" value="100mcg CFC-Free Inhaler"
                                name="dosage">
                            <label for="100mcgCFC-FreeInhaler">100mcg CFC-Free Inhaler</label>
                        </div>

                    </div>

                    <div id="BirthControl-dosage" class="hidden">
                        <!-- 	Birth Control -->

                        <label><b>Select Type</b></label>
                        <div class="radio-pill-group">
                            <input type="radio" id="Brenda35" value="Brenda 35 (Brenda-35 Ed Tab 2mg;35mcg 3 Packs)"
                                name="dosage">
                            <label for="Brenda35">Brenda 35 (Brenda-35 Ed Tab 2mg;35mcg 3 Packs)</label>

                            <input type="radio" id="Diane35" value="Diane 35 (Diane-35 Ed Tablet 2mg;35mcg 3 Packs)"
                                name="dosage">
                            <label for="Diane35">Diane 35 (Diane-35 Ed Tablet 2mg;35mcg 3 Packs)</label>

                            <input type="radio" id="Eleanor150/30"
                                value="Eleanor 150/30 (Levonorgestrel + ethinylestradiol)" name="dosage">
                            <label for="Eleanor150/30">Eleanor 150/30 (Levonorgestrel + ethinylestradiol)</label>

                            <input type="radio" id="Estelle35" value="Estelle (Estelle 35 Ed Tablet 2mg;35mcg Packs)"
                                name="dosage">
                            <label for="Estelle35">Estelle (Estelle 35 Ed Tablet 2mg;35mcg Packs)</label>

                            <input type="radio" id="Evelyn150/30"
                                value="Evelyn 150/30 (Levonorgestrel + ethinylestradiol)" name="dosage">
                            <label for="Evelyn150/30">Evelyn 150/30 (Levonorgestrel + ethinylestradiol)</label>

                            <input type="radio" id="Femme-Tab20/100"
                                value="Femme-Tab 20/100 (Levonorgestrel + ethinylestradiol)" name="dosage">
                            <label for="Femme-Tab20/100">Femme-Tab 20/100 (Levonorgestrel +
                                ethinylestradiol)</label>

                            <input type="radio" id="Femme-Tab30/150"
                                value="Femme-Tab 30/150 (Levonorgestrel + ethinylestradiol)" name="dosage">
                            <label for="Femme-Tab30/150">Femme-Tab 30/150 (Levonorgestrel +
                                ethinylestradiol)</label>

                            <input type="radio" id="implanon" value="Implanon (Hormone implant)" name="dosage">
                            <label for="implanon">Implanon (Hormone implant)</label>

                            <input type="radio" id="LevlenED" value="Levlen ED (Levonorgestrel + ethinylestradiol)"
                                name="dosage">
                            <label for="LevlenED">Levlen ED (Levonorgestrel + ethinylestradiol)</label>

                            <input type="radio" id="Loette" value="Loette (Levonorgestrel + ethinylestradiol)"
                                name="dosage">
                            <label for="Loette">Loette (Levonorgestrel + ethinylestradiol)</label>

                            <input type="radio" id="LogynonED" value="Logynon ED (Levonorgestrel + ethinylestradiol)"
                                name="dosage">
                            <label for="LogynonED">Logynon ED (Levonorgestrel + ethinylestradiol)</label>

                            <input type="radio" id="Microgynon20ED"
                                value="Microgynon 20 ED (Levonorgestrel + ethinylestradiol)" name="dosage">
                            <label for="Microgynon20ED">Microgynon 20 ED (Levonorgestrel + ethinylestradiol)</label>

                            <input type="radio" id="Microgynon30"
                                value="Microgynon 30 (Levonorgestrel + ethinylestradiol)" name="dosage">
                            <label for="Microgynon30">Microgynon 30 (Levonorgestrel + ethinylestradiol)</label>

                            <input type="radio" id="Microgynon50"
                                value="Microgynon 50 (Levonorgestrel + ethinylestradiol)" name="dosage">
                            <label for="Microgynon50">Microgynon 50 (Levonorgestrel + ethinylestradiol)</label>

                            <input type="radio" id="Mirena" value="Mirena (Hormone IUD)" name="dosage">
                            <label for="Mirena">Mirena (Hormone IUD)</label>

                            <input type="radio" id="Monofeme" value="Monofeme (Levonorgestrel + ethinylestradiol)"
                                name="dosage">
                            <label for="Monofeme">Monofeme (Levonorgestrel + ethinylestradiol)</label>

                            <input type="radio" id="Nordette" value="Nordette (Levonorgestrel + ethinylestradiol)"
                                name="dosage">
                            <label for="Nordette">Nordette (Levonorgestrel + ethinylestradiol)</label>

                            <input type="radio" id="Noriday" value="Noriday (Norethisterone)" name="dosage">
                            <label for="Noriday">Noriday (Norethisterone)</label>

                            <input type="radio" id="Norimin" value="Norimin (Norethisterone + ethinylestradiol)"
                                name="dosage">
                            <label for="Norimin">Norimin (Norethisterone + ethinylestradiol)</label>

                            <input type="radio" id="Norimin-1" value="Norimin-1 (Norethisterone + ethinylestradiol)"
                                name="dosage">
                            <label for="Norimin-1">Norimin-1 (Norethisterone + ethinylestradiol)</label>

                            <input type="radio" id="Yasmin" value="Yasmin (Yasmin Tablet 3mg;30mcg 28 3 Packs)"
                                name="dosage">
                            <label for="Yasmin">Yasmin (Yasmin Tablet 3mg;30mcg 28 3 Packs)</label>


                            <input type="radio" id="Yaz3Packs" value="Yaz (Yaz Tab 20mcg/3mg 28 3 Packs)" name="dosage">
                            <label for="Yaz3Packs">Yaz (Yaz Tab 20mcg/3mg 28 3 Packs)</label>

                            <input type="radio" id="Yaz4Packs" value="Yaz (Yaz Flex Tab 20mcg/3Mg 30 4 Packs)"
                                name="dosage">
                            <label for="Yaz4Packs">Yaz (Yaz Flex Tab 20mcg/3Mg 30 4 Packs)</label>

                            <input type="radio" id="Zoely" value="Zoely (Nomegestrol + Estradiol) " name="dosage">
                            <label for="Zoely">Zoely (Nomegestrol + Estradiol) </label>
                        </div>

                    </div>


                    <div id="Menopause-dosage" class="hidden">
                        <!-- Menopause -->
                        <label><b>Select Type</b></label>
                        <div class="radio-pill-group">
                            <input type="radio" id="EstalisContinuous50/140"
                                value="Estalis Continuous 50/140 (Estalis Continuous 50/140 Ptch)" name="dosage">
                            <label for="EstalisContinuous50/140">Estalis Continuous 50/140 (Estalis Continuous
                                50/140
                                Ptch)</label>

                            <input type="radio" id="EstalisContinuous50/250"
                                value="Estalis Continuous 50/250 (Estalis Continuous 50/250 Ptch)" name="dosage">
                            <label for="EstalisContinuous50/250">Estalis Continuous 50/250 (Estalis Continuous
                                50/250
                                Ptch)</label>

                            <input type="radio" id="EstalisSequi50/140"
                                value="Estalis Sequi 50/140 (Estalis Sequi Ptch 50/140, 8 1)" name="dosage">
                            <label for="EstalisSequi50/140">Estalis Sequi 50/140 (Estalis Sequi Ptch 50/140, 8
                                1)</label>

                            <input type="radio" id="EstalisSequi50/250"
                                value="Estalis Sequi 50/250 (Estalis Sequi Ptch 50/250, 8 1)" name="dosage">
                            <label for="EstalisSequi50/250">Estalis Sequi 50/250 (Estalis Sequi Ptch 50/250, 8
                                1)</label>

                            <input type="radio" id="Kliogest2mg" value="Kliogest Tab 2Mg/1Mg 1" name="dosage">
                            <label for="Kliogest2mg">Kliogest Tab 2Mg/1Mg 1</label>

                            <input type="radio" id="Kliogest1mg" value="Kliovance Tablet 1Mg;500Mcg 28" name="dosage">
                            <label for="Kliogest1mg">Kliovance Tablet 1Mg;500Mcg 28</label>

                            <input type="radio" id="LivialTab" value="Livial Tab 2.5Mg 28" name="dosage">
                            <label for="LivialTab">Livial Tab 2.5Mg 28</label>
                        </div>
                    </div>


                    <!-- **************************************************************************************** -->
                    <!-- **************************** Remaining Dosages ***************************************** -->
                    <!-- **************************************************************************************** -->
                    <!-- **************************** Remaining Dosages Start *********************************** -->


                    <div id="Amlodipine-dosage" class="hidden">
                        <!-- Amlodipine -->
                        <label><b>Select Type</b></label>
                        <div class="radio-pill-group">

                            <input type="radio" id="10mgAmlodipineTab30" value="10mg (Amlodipine (Apo) Tab 10Mg 30)"
                                name="dosage">
                            <label for="10mgAmlodipineTab30">10mg (Amlodipine (Apo) Tab 10Mg 30)</label>



                            <input type="radio" id="5mgAmlodipineApoTab30)" value="5mg (Amlodipine (Apo) Tab 5Mg 30)"
                                name="dosage">
                            <label for="5mgAmlodipineApoTab30"> 5mg (Amlodipine (Apo) Tab 5Mg 30)</label>

                        </div>
                    </div>

                    <div id="AvaproHCT-dosage" class="hidden">
                        <!-- Avapro HCT -->
                        <label><b>Select Type</b></label>
                        <div class="radio-pill-group">

                            <input type="radio" id="150/12.5mgIrbesartan&hydrochlorothiazide"
                                value="150/12.5mg (Irbesartan + hydrochlorothiazide)" name="dosage">
                            <label for="150/12.5mgIrbesartan&hydrochlorothiazide"> 150/12.5mg (Irbesartan +
                                hydrochlorothiazide)</label>



                            <input type="radio" id="300/12.5mgIrbesartan&hydrochlorothiazide"
                                value="300/12.5mg (Irbesartan + hydrochlorothiazide)" name="dosage">
                            <label for="300/12.5mgIrbesartan&hydrochlorothiazide"> 300/12.5mg (Irbesartan +
                                hydrochlorothiazide)</label>

                        </div>
                    </div>

                    <div id="Caduet-dosage" class="hidden">
                        <!-- Caduet -->
                        <label><b>Select Type</b></label>
                        <div class="radio-pill-group">
                            <p>
                                <input type="radio" id="10/10mgAmlodipine" value="10/10mg (Amlodipine + atorvastatin)"
                                    name="dosage">
                                <label for="10/10mgAmlodipine">10/10mg (Amlodipine + atorvastatin)</label>
                            </p>

                            <p>
                                <input type="radio" id="10/20mgAmlodipine" value="10/20mg (Amlodipine + atorvastatin)"
                                    name="dosage">
                                <label for="10/20mgAmlodipine">10/20mg (Amlodipine + atorvastatin)</label>
                            </p>

                            <p>
                                <input type="radio" id="10/40mgAmlodipine" value="10/40mg (Amlodipine + atorvastatin)"
                                    name="dosage">
                                <label for="10/40mgAmlodipine">10/40mg (Amlodipine + atorvastatin)</label>
                            </p>

                            <p>
                                <input type="radio" id="10/80mgAmlodipine" value="10/80mg (Amlodipine + atorvastatin)"
                                    name="dosage">
                                <label for="10/80mgAmlodipine">10/80mg (Amlodipine + atorvastatin)</label>
                            </p>

                            <p>
                                <input type="radio" id="5/10mgAmlodipine" value="5/10mg (Amlodipine + atorvastatin)"
                                    name="dosage">
                                <label for="5/10mgAmlodipine">5/10mg (Amlodipine + atorvastatin)</label>
                            </p>

                            <p>
                                <input type="radio" id="5/20mgAmlodipine" value="5/20mg (Amlodipine + atorvastatin)"
                                    name="dosage">
                                <label for="5/20mgAmlodipine">5/20mg (Amlodipine + atorvastatin)</label>
                            </p>

                            <p>
                                <input type="radio" id="5/40mgAmlodipine" value="5/40mg (Amlodipine + atorvastatin)"
                                    name="dosage">
                                <label for="5/40mgAmlodipine">5/40mg (Amlodipine + atorvastatin)</label>
                            </p>

                            <p>
                                <input type="radio" id="5/80mgAmlodipine" value="5/80mg (Amlodipine + atorvastatin)"
                                    name="dosage">
                                <label for="5/80mgAmlodipine">5/80mg (Amlodipine + atorvastatin)</label>
                            </p>

                        </div>
                    </div>

                    <div id="Coveram-dosage" class="hidden">
                        <!-- Coveram -->
                        <label><b>Select Type</b></label>
                        <div class="radio-pill-group">
                            <p>
                                <input type="radio" id="10/10mgPerindopril&amlodipine"
                                    value="10/10mg (Perindopril + amlodipine)" name="dosage">
                                <label for="10/10mgPerindopril&amlodipine">10/10mg (Perindopril +
                                    amlodipine)</label>
                            </p>

                            <p>
                                <input type="radio" id="10/5mgPerindopril&amlodipine"
                                    value="10/5mg (Perindopril + amlodipine)" name="dosage">
                                <label for="10/5mgPerindopril&amlodipine">10/5mg (Perindopril + amlodipine)</label>
                            </p>

                            <p>
                                <input type="radio" id="5/10mgPerindopril&amlodipine"
                                    value="5/10mg (Perindopril + amlodipine)" name="dosage">
                                <label for="5/10mgPerindopril&amlodipine">5/10mg (Perindopril + amlodipine)</label>
                            </p>

                            <p>
                                <input type="radio" id="5/5mgPerindopril&amlodipine"
                                    value="5/5mg (Perindopril + amlodipine)" name="dosage">
                                <label for="5/5mgPerindopril&amlodipine">5/5mg (Perindopril + amlodipine)</label>
                            </p>

                        </div>
                    </div>

                    <div id="Coversyl-dosage" class="hidden">
                        <!-- Coversyl -->
                        <label><b>Select Type</b></label>
                        <div class="radio-pill-group">
                            <p>
                                <input type="radio" id="2.5mgPerindopril" value="2.5mg (Perindopril)" name="dosage">
                                <label for="2.5mgPerindopril">2.5mg (Perindopril)</label>
                            </p>

                            <p>
                                <input type="radio" id="5mgPerindopril" value="5mg (Perindopril)" name="dosage">
                                <label for="5mgPerindopril">5mg (Perindopril)</label>
                            </p>
                        </div>
                    </div>

                    <div id="CoversylPlus-dosage" class="hidden">
                        <!-- Coversyl Plus -->
                        <label><b>Select Type</b></label>
                        <div class="radio-pill-group">
                            <p>
                                <input type="radio" id="2.5/0.625mgIndapamidehemihydrate&perindopril"
                                    value="2.5/0.625mg (Indapamide hemihydrate + perindopril)" name="dosage">
                                <label for="2.5/0.625mgIndapamidehemihydrate&perindopril">2.5/0.625mg (Indapamide
                                    hemihydrate +
                                    perindopril)</label>
                            </p>

                            <p>
                                <input type="radio" id="5/1.25mgIndapamidehemihydrate&perindopril"
                                    value="5/1.25mg (Indapamide hemihydrate + perindopril)" name="dosage">
                                <label for="5/1.25mgIndapamidehemihydrate&perindopril">5/1.25mg (Indapamide
                                    hemihydrate
                                    +
                                    perindopril)</label>
                            </p>
                        </div>
                    </div>

                    <div id="Crestor-dosage" class="hidden">
                        <!-- Crestor -->
                        <label><b>Select Type</b></label>
                        <div class="radio-pill-group">
                            <p>
                                <input type="radio" id="5mgRosuvastatin" value="5mg (Rosuvastatin)" name="dosage">
                                <label for="5mgRosuvastatin">5mg (Rosuvastatin)</label>
                            </p>

                            <p>
                                <input type="radio" id="10mgRosuvastatin" value="10mg (Rosuvastatin)" name="dosage">
                                <label for="10mgRosuvastatin">10mg (Rosuvastatin)</label>
                            </p>

                            <p>
                                <input type="radio" id="20mgRosuvastatin" value="20mg (Rosuvastatin)" name="dosage">
                                <label for="20mgRosuvastatin">20mg (Rosuvastatin)</label>
                            </p>

                            <p>
                                <input type="radio" id="40mgRosuvastatin" value="40mg (Rosuvastatin)" name="dosage">
                                <label for="40mgRosuvastatin">40mg (Rosuvastatin)</label>
                            </p>
                        </div>

                    </div>

                    <div id="Frusemide-dosage" class="hidden">
                        <!-- Frusemide -->
                        <label><b>Select Type</b></label>
                        <div class="radio-pill-group">
                            <p>
                                <input type="radio" id="20mgDiuretic" value="20mg (Diuretic)" name="dosage">
                                <label for="20mgDiuretic">20mg (Diuretic)</label>
                            </p>

                            <p>
                                <input type="radio" id="40mgDiuretic" value="40mg (Diuretic)" name="dosage">
                                <label for="40mgDiuretic">40mg (Diuretic)</label>
                            </p>
                        </div>
                    </div>

                    <div id="Irbesartan-dosage" class="hidden">
                        <!-- Irbesartan -->
                        <label><b>Select Type</b></label>
                        <div class="radio-pill-group">
                            <p>
                                <input type="radio" id="20mgDiuretic" value="20mg (Diuretic)" name="dosage">
                                <label for="20mgDiuretic">20mg (Diuretic)</label>
                            </p>

                            <p>
                                <input type="radio" id="75mgIrbesartan" value="75mg (Irbesartan (Apo) Tab 75Mg 30)"
                                    name="dosage">
                                <label for="75mgIrbesartan">75mg (Irbesartan (Apo) Tab 75Mg 30)</label>
                            </p>

                            <p>
                                <input type="radio" id="150mgIrbesartan" value="150mg (Irbesartan)" name="dosage">
                                <label for="150mgIrbesartan">150mg (Irbesartan)</label>
                            </p>

                            <p>
                                <input type="radio" id="300mgIrbesartan" value="300mg (Irbesartan)" name="dosage">
                                <label for="300mgIrbesartan">300mg (Irbesartan)</label>
                            </p>
                        </div>
                    </div>

                    <div id="Lipitor-dosage" class="hidden">
                        <!-- Lipitor -->
                        <label><b>Select Type</b></label>
                        <div class="radio-pill-group">
                            <p>
                                <input type="radio" id="10mgAtorvastatin" value="10mg (Atorvastatin)" name="dosage">
                                <label for="10mgAtorvastatin">10mg (Atorvastatin)</label>
                            </p>

                            <p>
                                <input type="radio" id="20mgAtorvastatin" value="20mg (Atorvastatin)" name="dosage">
                                <label for="20mgAtorvastatin">20mg (Atorvastatin)</label>
                            </p>

                            <p>
                                <input type="radio" id="40mgAtorvastatin" value="40mg (Atorvastatin)" name="dosage">
                                <label for="40mgAtorvastatin">40mg (Atorvastatin)</label>
                            </p>

                            <p>
                                <input type="radio" id="80mgAtorvastatin" value="80mg (Atorvastatin)" name="dosage">
                                <label for="80mgAtorvastatin">80mg (Atorvastatin)</label>
                            </p>
                        </div>

                    </div>

                    <div id="Metoprolol-dosage" class="hidden">
                        <!-- Metoprolol -->
                        <label><b>Select Type</b></label>
                        <div class="radio-pill-group">
                            <p>
                                <input type="radio" id="100mgMetoprololTartrate" value="100mg (Metoprolol Tartrate)"
                                    name="dosage">
                                <label for="100mgMetoprololTartrate">100mg (Metoprolol Tartrate)</label>
                            </p>

                            <p>
                                <input type="radio" id="50mgMetoprololTartrate" value="50mg (Metoprolol Tartrate)"
                                    name="dosage">
                                <label for="50mgMetoprololTartrate">50mg (Metoprolol Tartrate)</label>
                            </p>
                        </div>
                    </div>

                    <div id="Propranolol-dosage" class="hidden">
                        <!-- Propranolol -->
                        <label><b>Select Type</b></label>
                        <div class="radio-pill-group">
                            <p>
                                <input type="radio" id="10mgPropranolol" value="10mg (Propranolol (Apo) Tab 10Mg 100)"
                                    name="dosage">
                                <label for="10mgPropranolol">10mg (Propranolol (Apo) Tab 10Mg 100)</label>
                            </p>

                            <p>
                                <input type="radio" id="40mgPropranolol" value="40mg (Propranolol (Apo) Tab 10Mg 100)"
                                    name="dosage">
                                <label for="40mgPropranolol">40mg (Propranolol (Apo) Tab 10Mg 100)</label>
                            </p>
                        </div>
                    </div>

                    <div id="Olmesartan-dosage" class="hidden">
                        <!-- Olmesartan -->
                        <label><b>Select Type</b></label>
                        <div class="radio-pill-group">
                            <p>
                                <input type="radio" id="20mgOlmesartan" value="20mg (Olmesartan (Apo) Tab 20Mg 30)"
                                    name="dosage">
                                <label for="20mgOlmesartan">20mg (Olmesartan (Apo) Tab 20Mg 30)</label>
                            </p>

                            <p>
                                <input type="radio" id="40mgOlmesartan" value="40mg (Olmesartan (Apo) Tab 20Mg 30)"
                                    name="dosage">
                                <label for="40mgOlmesartan">40mg (Olmesartan (Apo) Tab 20Mg 30)</label>
                            </p>
                        </div>
                    </div>

                    <div id="Perindopril-dosage" class="hidden">
                        <!-- Perindopril -->
                        <label><b>Select Type</b></label>
                        <div class="radio-pill-group">
                            <p>
                                <input type="radio" id="8mgPerindopril" value="8mg (Perindopril (Apo) Tab 8Mg 30)"
                                    name="dosage">
                                <label for="8mgPerindopril">8mg (Perindopril (Apo) Tab 8Mg 30)</label>
                            </p>

                            <p>
                                <input type="radio" id="2mgPerindopril" value="2mg (Perindopril (Apotex) Tab 2Mg 3) "
                                    name="dosage">
                                <label for="2mgPerindopril">2mg (Perindopril (Apotex) Tab 2Mg 3)</label>
                            </p>
                        </div>
                    </div>

                    <div id="Reaptan-dosage" class="hidden">
                        <!-- Reaptan -->
                        <label><b>Select Type</b></label>
                        <div class="radio-pill-group">
                            <p>
                                <input type="radio" id="10/10mgReaptan" value="10/10mg (Reaptan 10/10 Tab 10Mg/10Mg 30)"
                                    name="dosage">
                                <label for="10/10mgReaptan">10/10mg (Reaptan 10/10 Tab 10Mg/10Mg 30)</label>
                            </p>

                            <p>
                                <input type="radio" id="10/5mgReaptan" value="10/5mg (Reaptan 10/5 Tab 10Mg/5Mg 30)"
                                    name="dosage">
                                <label for="10/5mgReaptan">10/5mg (Reaptan 10/5 Tab 10Mg/5Mg 30)</label>
                            </p>

                            <p>
                                <input type="radio" id="5/5mgReaptan" value="5/5mg (Reaptan 5/5 Tab 5Mg/5Mg 30)"
                                    name="dosage">
                                <label for="5/5mgReaptan">5/5mg (Reaptan 5/5 Tab 5Mg/5Mg 30)</label>
                            </p>
                        </div>
                    </div>

                    <div id="TelmisartanMicardis-dosage" class="hidden">
                        <!-- Telmisartan (Micardis) -->
                        <label><b>Select Type</b></label>
                        <div class="radio-pill-group">
                            <p>
                                <input type="radio" id="40mgTelmisartan" value="40mg (Telmisartan)" name="dosage">
                                <label for="40mgTelmisartan">40mg (Telmisartan)</label>
                            </p>

                            <p>
                                <input type="radio" id="80mgTelmisartan" value="80mg (Telmisartan)" name="dosage">
                                <label for="80mgTelmisartan">80mg (Telmisartan)</label>
                            </p>
                        </div>

                    </div>


                    <!-- **************************************************************************************** -->
                    <!-- **************************************************************************************** -->
                    <!-- **************************************************************************************** -->
                    <!-- **************************** Remaining Dosages ***************************************** -->
                    <!-- **************************************************************************************** -->
                    <!-- **************************** Remaining Dosages End ************************************* -->
                    <!-- **************************************************************************************** -->
                    <!-- **************************************************************************************** -->
                    <!-- **************************************************************************************** -->
                    <!-- **************************************************************************************** -->
                    <!-- **************************************************************************************** -->
                    <label><b>Are you currently pregnant, planning to become pregnant, or breastfeeding?</b> <b
                            class="required-asterisk">*</b></label>

                    <div class="radio-pill-group">
                        <input type="radio" id="preg" value="Pregnant" name="currentlyppb">
                        <label for="preg">Pregnant</label>

                        <input type="radio" id="Breastfeeding" value="Breastfeeding" name="currentlyppb">
                        <label for="Breastfeeding">Breastfeeding</label>

                        <input type="radio" id="becomepregnant" value="Planning to become pregnant" name="currentlyppb">
                        <label for="becomepregnant">Planning to become pregnant</label>

                        <input type="radio" id="none" value="None" name="currentlyppb">
                        <label for="none">None</label>
                    </div>

                    <!-- <div class="row">
                            <div class="col-12">
                                <label for="description"><b>Any other Note you want to share:</b></label>
                                <div class="input-group">
                                    <i class="fas fa-id-card"></i>
                                    <input name="description" id="description" required>
                                </div>
                            </div>
                        </div> -->

                    <button type="button" class="button-class width-hundred my-4"
                        onclick="validateStep(1)">Next</button>

                </div>


                <div class="form-step" id="step2">






                    <label><b>Have you previously taken this medication?</b> <b class="required-asterisk">*</b></label>

                    <div class="row">
                        <div class="col-12">
                            <div class="radio-pill-group">
                                <input type="radio" id="previouslytakenmediyes" value="Yes"
                                    name="previously_taken_medi">
                                <label for="previouslytakenmediyes" class="w-25 text-center">Yes</label>

                                <input type="radio" id="previouslytakenmedino" value="No" name="previously_taken_medi">
                                <label for="previouslytakenmedino" class="w-25 text-center">No</label>
                            </div>
                        </div>
                    </div>




                    <div id="previouslytakenmedicine-Yes">

                        <label for="health-condition"><b>For what health condition(s) is this medication being
                                prescribed?</b>
                            <b class="required-asterisk">*</b></label>
                        <div class="row">
                            <div class="col-12">
                                <div class="input-group">
                                    <!-- <i class="fa-solid fa-pen"></i> -->
                                    <i class="fa-solid fa-file-medical"></i>
                                    <input name="health_condition" id="health-condition">
                                </div>
                            </div>
                        </div>


                        <label><b>Do you have any known allergies to medications or any other relevant
                                allergies?</b>
                            <b class="required-asterisk">*</b></label>

                        <div class="radio-pill-group">
                            <input type="radio" id="known-allergies-yes" value="Yes" name="known_allergies">
                            <label for="known-allergies-yes" class="w-25 text-center">Yes</label>

                            <input type="radio" id="known-allergies-no" value="No" name="known_allergies">
                            <label for="known-allergies-no" class="w-25 text-center">No</label>
                        </div>
                        <br>



                        <div id="allergy-details" class="hidden">
                            <div class="row">
                                <div class="col-12 ">
                                    <label for="reasonknown-allergies-yes"> <b> Please provide more details for
                                            above.</b></label>
                                    <div class="input-group">
                                        <!-- <i class="fas fa-id-card"></i> -->
                                        <i class="fa-solid fa-person-dots-from-line"></i>
                                        <input id="reasonknown-allergies-yes" name="reason_known_allergies_yes">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <label><b>Have you experienced any side effects or adverse reactions while taking this
                                medication?</b>
                            <b class="required-asterisk">*</b></label>

                        <div class="radio-pill-group">
                            <input type="radio" id="adverse_reactions_yes" value="Yes" name="adverse_reactions">
                            <label for="adverse_reactions_yes" class="w-25 text-center">Yes</label>

                            <input type="radio" id="adverse_reactions_no" value="No" name="adverse_reactions">
                            <label for="adverse_reactions_no" class="w-25 text-center">No</label>
                        </div>

                        <div class="row">
                            <div class="col-12 ">
                                <label for="over-the-counter-drugs"><b>Are you currently taking any other
                                        medications, including over-the-counter drugs or supplements? (reply 'Nil'
                                        if not taking any)</b>
                                    <b class="required-asterisk">*</b></label>
                                <div class="input-group">
                                    <!-- <i class="fas fa-id-card"></i> -->
                                    <i class="fa-solid fa-pills"></i>
                                    <input name="over_the_counter_drugs" id="over-the-counter-drugs">
                                </div>
                            </div>
                        </div>

                        <label><b>Have you seen a healthcare provider in person recently regarding
                                this medical condition, or planning to follow up?</b>
                            <b class="required-asterisk">*</b></label>

                        <div class="radio-pill-group">
                            <input type="radio" id="health-provider-yes" value="Yes"
                                name="healthcare_provider_person_recently">
                            <label for="health-provider-yes" class="w-25 text-center">Yes</label>

                            <input type="radio" id="health-provider-no" value="No"
                                name="healthcare_provider_person_recently">
                            <label for="health-provider-no" class="w-25 text-center">No</label>
                        </div>
                    </div>

                    <div id="previouslytakenmedicine-No">

                        <label><b>Are you aware of the specific medication you're seeking?</b> <b
                                class="required-asterisk">*</b></label>

                        <div class="radio-pill-group">
                            <input type="radio" id="specificmedicationyes" value="Yes"
                                name="specific_medication_seeking">
                            <label for="specificmedicationyes" class="w-25 text-center">Yes</label>

                            <input type="radio" id="specificmedicationno" value="No" name="specific_medication_seeking">
                            <label for="specificmedicationno" class="w-25 text-center">No</label>
                        </div>


                        <div class="row">
                            <div class="col-12">
                                <label for="knownnillallergies"><b>Do you have any known allergies to
                                        medications or any other relevant allergies?(Reply 'Nil' if none)</b>
                                    <b class="required-asterisk">*</b></label>
                                <div class="input-group">
                                    <!-- <i class="fas fa-id-card"></i> -->
                                    <i class="fa-solid fa-person-dots-from-line"></i>
                                    <input name="known_nill_allergies" id="knownnillallergies">
                                </div>
                            </div>
                        </div>


                        <label><b>Have you ever used this medication previously?</b>
                            <b class="required-asterisk">*</b></label>
                        <div class="radio-pill-group">
                            <input type="radio" id="yesmedicationpreviouslyyes" value="Yes"
                                name="medication_used_previously">
                            <label for="yesmedicationpreviouslyyes" class="w-25 text-center">Yes</label>

                            <input type="radio" id="medicationpreviouslyno" value="No"
                                name="medication_used_previously">
                            <label for="medicationpreviouslyno" class="w-25 text-center">No</label>
                        </div>


                        <label><b>Do you plan to schedule a follow-up appointment with your GP in the near
                                future?</b><b class="required-asterisk">*</b></label>
                        <div class="radio-pill-group">
                            <input type="radio" id="yesplanschedule" value="Yes" name="plan_schedule">
                            <label for="yesplanschedule" class="w-25 text-center">Yes</label>

                            <input type="radio" id="noplanschedule" value="No" name="plan_schedule">
                            <label for="noplanschedule" class="w-25 text-center">No</label>
                        </div>

                    </div>

                    <div class="d-flex gap-md-4 gap-1">
                        <button type="button" class="button-class w-50 my-4" onclick="prevStep()">
                            Back
                        </button>
                        <button type="button" name="register" class="button-class w-50 my-4" onclick="validateStep(2)">
                            Next
                        </button>
                    </div>
                </div>

                <div class="form-step" id="step3">
                    <h4 class="text-center">Patient Information</h4>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="input-group">
                                <i class="fas fa-id-card"></i>
                                <select name="title" id="title" class="width-hundred">
                                    <option value="" disabled <?php echo empty($appointment['title']) ? 'selected' : ''; ?>>Select title</option>
                                    <option value="315890000" <?php echo (isset($appointment['title']) && $appointment['title'] == '315890000') ? 'selected' : ''; ?>>Mr</option>
                                    <option value="315890001" <?php echo (isset($appointment['title']) && $appointment['title'] == '315890001') ? 'selected' : ''; ?>>Mrs</option>
                                    <option value="315890002" <?php echo (isset($appointment['title']) && $appointment['title'] == '315890002') ? 'selected' : ''; ?>>Ms</option>
                                    <option value="315890003" <?php echo (isset($appointment['title']) && $appointment['title'] == '315890003') ? 'selected' : ''; ?>>Miss</option>
                                    <option value="315890004" <?php echo (isset($appointment['title']) && $appointment['title'] == '315890004') ? 'selected' : ''; ?>>Mx</option>
                                    <option value="315890005" <?php echo (isset($appointment['title']) && $appointment['title'] == '315890005') ? 'selected' : ''; ?>>Dr</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="input-group">
                                <i class="fas fa-venus-mars"></i>
                                <!-- <label for="gender"><b>Gender:</b></label> -->
                                <select name="gender" name="gender" id="gender" class="width-hundred">
                                    <option value="" disabled <?php echo empty($appointment['gender']) ? 'selected' : ''; ?>>Select gender</option>
                                    <option value="1" <?php echo (isset($appointment['gender']) && $appointment['gender'] == '1') ? 'selected' : ''; ?>>
                                        Female</option>
                                    <option value="2" <?php echo (isset($appointment['gender']) && $appointment['gender'] == '2') ? 'selected' : ''; ?>>
                                        Male</option>
                                    <option value="3" <?php echo (isset($appointment['gender']) && $appointment['gender'] == '3') ? 'selected' : ''; ?>>
                                        Other</option>
                                    <option value="4" <?php echo (isset($appointment['gender']) && $appointment['gender'] == '4') ? 'selected' : ''; ?>>
                                        Unknown</option>
                                </select>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="input-group">
                                <i class="fas fa-user"></i>
                                <input type="text" name="fname" id="fname" class="width-hundred"
                                    placeholder="First Name" value="<?php echo isset($firstName) ? $firstName : '' ?>">
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="input-group">
                                <i class="fas fa-user"></i>
                                <input type="text" name="lname" id="lname" class="width-hundred" placeholder="Last Name"
                                    value="<?php echo isset($lastName) ? $lastName : '' ?>">
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="input-group">
                                <!-- <label for="email"><b>Email:</b></label> -->
                                <!-- <i class="fas fa-venus-mars"></i> -->
                                <i class="fa-solid fa-envelope"></i>
                                <input type="email" class="width-hundred" name="email" id="email"
                                    placeholder="Write Email"
                                    value="<?php echo isset($appointment['email']) ? $appointment['email'] : '' ?>">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <!-- <div class="input-group">
                                <i class="fas fa-phone-alt"></i>
                                <input type="number" class="width-hundred" name="phone" id="phone"
                                    placeholder="Write Phone Number" value="<?php
                                    // echo isset($appointment['phone']) ? $appointment['phone'] : '' ?>">
                            </div> -->

                            <?php
                            function isValidAustralianPhoneNumber($phoneNumber)
                            {
                                // Remove any non-numeric characters
                                $phoneNumber = preg_replace('/\D/', '', $phoneNumber);

                                // Check if it matches Australian phone number formats
                                return preg_match('/^(?:61|0)[2-478]\d{8}$|^04\d{8}$/', $phoneNumber);
                            }

                            $phone = isset($_POST['phone']) ? $_POST['phone'] : '';

                            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                                if (!isValidAustralianPhoneNumber($phone)) {
                                    echo "Invalid phone number format.";
                                }
                            }
                            ?>


                            <div class="input-group">
                                <i class="fa-solid fa-phone-flip"></i>
                                <input type="text" class="width-hundred" name="phone" id="phone"
                                    placeholder="Write Phone Number"
                                    value="<?php echo isset($appointment['phone']) ? htmlspecialchars($appointment['phone'], ENT_QUOTES, 'UTF-8') : '' ?>"
                                    oninput="validatePhoneNumber(this)">
                            </div>
                            <span id="phone-error" style="color: red; display: none;"><small>Invalid phone number
                                    format.</small></span>

                        </div>
                    </div>


                    <div class="row">
                        <div class="col-9 col-md-6">
                            <div class="input-group">
                                <i class="fa-solid fa-cake-candles"></i> <!-- Icon for birthday -->
                                <input type="text" name="dob" id="dob" class="width-hundred" placeholder="Date of Birth"
                                    value="<?php echo isset($appointment['dob']) ? $appointment['dob'] : '' ?>">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div>
                                <b>Age: <span id="age"></span></b> <!-- Display Age -->
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="input-group">
                                <!-- <label for="medicare_number"><b>Medicare Number:</b></label> -->
                                <!-- <i class="fas fa-venus-mars"></i> -->
                                <i class="fa-solid fa-location-dot"></i>
                                <input name="address" id="address" class="width-hundred" placeholder="Your address"
                                    oninput="getSuggestions(this.value)"
                                    value="<?php echo isset($appointment['address']) ? $appointment['address'] : '' ?>">
                            </div>
                            <div id="suggestions"></div>
                            <!-- some hidden fiedls -->
                            <input type="hidden" readonly id="addressLine1" name="addressLine1">
                            <input type="hidden" readonly id="cityCode" name="cityCode" placeholder="City Code"
                                value="<?php echo isset($appointment['city_code']) ? $appointment['city_code'] : '' ?>">
                            <input type="hidden" readonly id="postcode" name="postcode" placeholder="Postcode"
                                value="<?php echo isset($appointment['postcode']) ? $appointment['postcode'] : '' ?>">
                            <input type="hidden" readonly id="stateCode" name="stateCode" placeholder="State Code"
                                value="<?php echo isset($appointment['state_code']) ? $appointment['state_code'] : '' ?>">
                            <input type="hidden" readonly id="countryCode" name="countryCode" placeholder="Country Code"
                                value='AU'>
                            <input type="hidden" readonly id="latitude" name="latitude">
                            <input type="hidden" readonly id="longitude" name="longitude">
                            <input type="hidden" readonly id="timeZone" name="timeZone"
                                value="<?php echo isset($appointment['area_timeZone']) ? $appointment['area_timeZone'] : '' ?>">
                            <input type="hidden" readonly id="userTime" name="userTime">
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="input-group">
                                <!-- <label for="address"><b>Address:</b></label> -->
                                <!-- <i class="fas fa-venus-mars"></i> -->
                                <i class="fa-solid fa-hashtag"></i>
                                <input type="text" class="width-hundred" name="medicare_number" id="medicare_number"
                                    maxlength="12" placeholder="Enter medicare number"
                                    oninput="formatMedicareNumber(this)"
                                    value="<?php echo isset($appointment['	medicare_number']) ? $appointment['	medicare_number'] : '' ?>">
                            </div>
                        </div>
                    </div>


                    <label><b>Service Acknowledgment and Guidelines:</b> <b class="required-asterisk">*</b></label>
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



                    <div class="d-flex gap-md-4 gap-1">
                        <button type="button" class="button-class w-50 my-4" onclick="prevStep()">
                            Back
                        </button>
                        <button type="button" name="register" class="button-class w-50 my-4" onclick="validateStep(3)">
                            Next
                        </button>
                    </div>

                </div>

                <div class="form-step" id="step4">
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
                    <div class="row">
                        <div class="col-12">
                            <label for="appointment-type"><b>Book an appointment <br> Choose Appointment Date and
                                    Time</b><b class="required-asterisk">*</b></label>
                            <div class="input-group">
                                <!-- <i class="fas fa-calendar-alt"></i> -->
                                <i class="fa-solid fa-headset"></i>
                                <select name="appointment_type" id="appointment-type" class="width-hundred">
                                    <!-- <option value="" disabled selected>Select Appointment Type</option> -->
                                    <option value="" disabled>Select Appointment Type</option>
                                    <option value="Telehealth" selected>Telehealth</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <label for="appointment-day"><b>Appointment Day</b></label>
                            <div class="input-group">
                                <i class="fas fa-calendar-alt"></i>
                                <input type="date" name="appointment_day" id="appointment-day" class="width-hundred"
                                    min="<?php echo date('Y-m-d'); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <label for="appointment-time"><b>Appointment Time</b></label>
                            <div class="input-group">
                                <!-- <i class="fas fa-calendar-alt"></i> -->
                                <i class="fa-solid fa-clock"></i>
                                <select name="appointment_time" id="appointment-time" class="width-hundred">
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
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <label for="description"><b>Any other Note you want to share:</b></label> <br>
                            <div class="input-group">
                                <!-- <i class="fas fa-calendar-alt"></i> -->
                                <i class="fa-solid fa-note-sticky"></i>
                                <input name="description" id="description" class="width-hundred">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-md-4 gap-1">
                        <button type="button" class="button-class w-50 my-4" onclick="prevStep()">
                            Back
                        </button>
                        <button type="button" name="register" class="button-class w-50 my-4" onclick="validateStep(4)">
                            Next
                        </button>
                    </div>

                </div>

                <div class="form-step" id="step5">

                    <?php if (!$isAssigned) { ?>
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
                        <input type="hidden" name="total" value="25.99">
                        <h3>Total: $25.99</h3>
                    <?php } else { ?>
                        <p>This certificate is free because the patient is assigned to you.</p>
                    <?php } ?>
                    <div class="d-flex gap-md-4 gap-1">
                        <button type="button" class="button-class w-50 my-4" onclick="prevStep()">
                            Back
                        </button>
                        <button type="submit" name="register" class="button-class w-50 my-4" onclick="validateStep(5)">
                            Submit
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <?php include("../include/footer.php"); ?>

    </div>
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



<!-- <?php
// include("assets/prescription_queries.php"); 
?> -->
<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCfDEB0GQd1uRQHOUy5qQDpzzElbskoQV8&libraries=places"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="assets/script.js"></script>
<script src="assets/prescription.js"></script>
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

    var cardNumber = elements.create('cardNumber', {
        style: style
    });
    var cardExpiry = elements.create('cardExpiry', {
        style: style
    });
    var cardCvc = elements.create('cardCvc', {
        style: style
    });

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

    // // Fetch address suggestions and coordinates
    // function getSuggestions(query) {
    //     const apiKey = '33c82b06f120487fa9e6f38f7758b7f2'; // OpenCage API key
    //     if (query.length < 3) {
    //         $('#suggestions').empty();
    //         return;
    //     }

    //     $.ajax({
    //         url: `https://api.opencagedata.com/geocode/v1/json`,
    //         data: {
    //             q: query,
    //             key: apiKey,
    //             limit: 6,
    //             countrycode: 'AU'
    //         },
    //         success: function (data) {
    //             if (data.results && data.results.length > 0) {
    //                 showSuggestions(data.results);
    //             } else {
    //                 $('#suggestions').empty();
    //             }
    //         }
    //     });
    // }

    // // Show the address suggestions
    // function showSuggestions(results) {
    //     const $suggestions = $('#suggestions');
    //     $suggestions.empty();

    //     results.forEach(result => {
    //         const $div = $('<div>').text(result.formatted).click(() => selectSuggestion(result));
    //         $suggestions.append($div);
    //     });
    // }

    // // When user selects a suggestion, capture local time and prepare the form
    // function selectSuggestion(result) {
    //     const address = result.formatted;
    //     const cityCode = result.components.city || result.components.town || result.components.village || '';
    //     const postcode = result.components.postcode || '';
    //     const stateCode = result.components.state_code || result.components.state || '';
    //     const countryCode = result.components.country_code || '';
    //     const { lat, lng } = result.geometry; // Get the latitude and longitude

    //     $('#address').val(address);
    //     $('#cityCode').val(cityCode);
    //     $('#postcode').val(postcode);
    //     $('#stateCode').val(stateCode);
    //     $('#countryCode').val(countryCode);
    //     $('#latitude').val(lat);
    //     $('#longitude').val(lng);
    //     $('#suggestions').empty();

    //     // Fetch time zone and local time for the user
    //     getLocalTime(lat, lng);
    // }






    // Initialize Google Autocomplete
    let autocomplete;

    function initializeAutocomplete() {
        autocomplete = new google.maps.places.Autocomplete(
            document.getElementById('address'), {
            componentRestrictions: {
                country: 'AU'
            },
            types: ['geocode']
        }
        );

        autocomplete.addListener('place_changed', fillInAddress);
    }

    // Fill the form fields after selecting an address
    function fillInAddress() {
        const place = autocomplete.getPlace();
        console.log(place);

        if (!place.geometry) {
            console.log("No details available for input: '" + place.name + "'");
            return;
        }

        const address = place.formatted_address;
        const addressLine1 = place.name;
        const city = getComponent(place, 'locality') || getComponent(place, 'administrative_area_level_2');
        const postcode = getComponent(place, 'postal_code');
        const state = getShortComponent(place, 'administrative_area_level_1');
        const country = getShortComponent(place, 'country');
        const lat = place.geometry.location.lat();
        const lng = place.geometry.location.lng();

        // Fill form fields with the details
        $('#address').val(address);
        $('#addressLine1').val(addressLine1);
        $('#cityCode').val(city);
        $('#postcode').val(postcode);
        $('#stateCode').val(state);
        $('#countryCode').val(country);
        $('#latitude').val(lat);
        $('#longitude').val(lng);

        // Clear suggestions div
        $('#suggestions').empty();

        // Optionally, fetch local time or other relevant info
        getLocalTime(lat, lng);
    }

    // Utility function to extract component from Google Place Result
    function getComponent(place, type) {
        const component = place.address_components.find(c => c.types.includes(type));
        return component ? component.long_name : '';
    }

    function getShortComponent(place, type) {
        const component = place.address_components.find(c => c.types.includes(type));
        return component ? component.short_name : '';
    }

    // Fetch time zone and local time for the user (similar to your original function)
    function getLocalTime(lat, lng) {
        // Add your implementation here, maybe using the Google Time Zone API
    }

    // Initialize autocomplete on page load
    google.maps.event.addDomListener(window, 'load', initializeAutocomplete);


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