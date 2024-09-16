<?php 
session_start(); 
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./assets/styles.css">
        <title>Terms & Conditions</title>
    </head>
    <style>
        .study-section {
            display: flex;
            flex-direction: column;
            padding: 50px 30px;
            gap: 20px;
        }

        .heading-one {
            font-weight: 600;
            font-size: 40px;
            text-transform: capitalize;
        }

        .study-section p {
            font-weight: 300;
        }

        .bold-font {
            font-weight: 600 !important;
        }
    </style>

    <body>
        <?php
        include("include/navbar.php");
        ?>
        <div class="study-section">
            <h1 class="heading-one">Terms & conditions</h1>

            <h2>Disclaimers</h2>
            <p>
                We provide a service allowing you to obtain personalized, comprehensive diagnostics and insights about
                your
                health via online assessments and Consultations with a medical practitioner <strong
                    class="bold-font">(Services)</strong>. If
                you require
                immediate medical attention, contact your treating general practitioner or call 000.
            </p>
            <p>
                If you are in doubt about the seriousness of your condition, the appropriateness or effectiveness of
                using
                our Services or believe that you, or another person is in an urgent, dangerous or emergency situation,
                you
                should not use our Services and instead contact 000 immediately or seek alternative and appropriate
                medical
                services.
            </p>
            <p>
                As part of our services, our role is limited to referring you to an applicable third party Australian
                registered medical practitioner or specialist. The medical practitioner or specialist may, based on
                their
                own judgements, medical expertise and assessment of your suitability for such treatments, provide
                alternative medicines or treatments to you. We are not responsible for, nor do we make any guarantees as
                to,
                medical advice, products or services that a third party (including a medical practitioner) may provide
                you.
            </p>
            <p class="bold-font">
                Pricing of our services is subject to change without prior notice and may vary during peak hours,
                after-hours, and weekends.
            </p>

            <h2>Our Disclosures </h2>

            <p>
                Our complete terms and conditions are contained below, but some important points for you to know before
                you
                accept our Terms are set out below:
            </p>
            <p>
                1. We will handle your personal information in accordance with our privacy policy, available at
                <a href="https://www.doctorhelp.com.au/">https://www.doctorhelp.com.au/</a>;
            </p>
            <p>
                2. Subject to your Consumer Law Rights:
            </p>
            <ul>
                <li> our aggregate liability for any Liability arising from or in connection with
                    these Terms will
                    be
                    limited to the Fees paid by you to us in respect of the supply of the relevant Services to which
                    the Liability relates; and
                </li>
                <li>we will not be liable for you not providing us or a Third Party Medical Provider
                    with correct
                    and complete current health and medical information, any negligence or errors made by a Third
                    Party Medical Provider, failure to follow any reasonable instructions provided to you by us,
                    Consequential Loss or delays or failures in performance due to Force Majeure Events.</li>
            </ul>

            </>
            <p>
                These Terms does not intend to limit your rights and remedies at law, including any of your Consumer Law
                Rights.
            </p>

            <h2>1. Acceptance</h2>
            <p>1.1 These terms and conditions <strong class="bold-font">(Terms)</strong> are entered into between Pack A
                Pill Pty Ltd ACN 634 507 949 (we, us
                or our) and you, together the Parties and each a Party.</p>
            <p>1.2 We provide an online platform (Platform) where you can make a booking for an initial health
                consultation
                (Consultation) with an Australian registered medical practitioner (Doctor) who will discuss your health
                with
                you and recommend a course of action to assess your health.</p>
            <p>1.3 You accept these Terms by the earlier of (a) accepting these Terms on the Platform; (b) filling out
                the
                consultation form on our Platform (Consultation Form); or (c) making payment of any part of the Fees.
            </p>
            <p>1.4 These Terms will terminate upon the completion of the Services in your Booking (as determined by us).
            </p>

        </div>
        <?php
        include("include/footer.php");
        ?>

    </body>

    </html>