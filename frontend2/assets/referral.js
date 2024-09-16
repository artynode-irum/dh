let currentStep = 1;

function showStep(stepNumber) {
  document.querySelectorAll(".form-step").forEach((step) => {
    step.classList.remove("active");
  });

  const stepElement = document.getElementById(`step${stepNumber}`);
  stepElement.classList.add("active");
  stepElement.classList.add("animate__animated");
  stepElement.classList.add("animate__fadeIn");
  stepElement.classList.add("animate__faster");
}

function validateStep(stepNumber) {
  let hasErrors = false;

  function clearErrors() {
    document.querySelectorAll('.input-error').forEach((input) => {
      input.classList.remove('input-error');
    });
  }

  clearErrors(); // Clear any existing errors before validation

  switch (stepNumber) {
    case 1:
      // Validate Step 1 fields
      const referralType = document.querySelector('input[name="referral_type"]:checked');
      if (!referralType) {
        hasErrors = true;
        document.querySelector('input[name="referral_type"]').classList.add('input-error');
      }

      const otherDetailsInput = document.getElementById('otherDetails');
      if (document.getElementById('other').checked && !otherDetailsInput.value) {
        hasErrors = true;
        otherDetailsInput.classList.add('input-error');
      }

      const titleInput = document.getElementById('title');
      if (!titleInput.value) {
        hasErrors = true;
        titleInput.classList.add('input-error');
      }

      const nameInput = document.getElementById('name');
      if (!nameInput.value) {
        hasErrors = true;
        nameInput.classList.add('input-error');
      }

      const genderInput = document.getElementById('gender');
      if (!genderInput.value) {
        hasErrors = true;
        genderInput.classList.add('input-error');
      }

      const emailInput = document.getElementById('email');
      if (!emailInput.value) {
        hasErrors = true;
        emailInput.classList.add('input-error');
      }

      const phoneInput = document.getElementById('phone');
      if (!phoneInput.value) {
        hasErrors = true;
        phoneInput.classList.add('input-error');
      }

      const addressInput = document.getElementById('address');
      if (!addressInput.value) {
        hasErrors = true;
        addressInput.classList.add('input-error');
      }

      const dobInput = document.getElementById('dob');
      if (!dobInput.value) {
        hasErrors = true;
        dobInput.classList.add('input-error');
      }

      const expiry_year = document.getElementById('expiry_year');
      if (!expiry_year.value) {
        hasErrors = true;
        expiry_year.classList.add('input-error');
      }

      const expiry_month = document.getElementById('expiry_month');
      if (!expiry_month.value) {
        hasErrors = true;
        expiry_month.classList.add('input-error');
      }

      const expiry_date = document.getElementById('expiry_date');
      if (!expiry_date.value) {
        hasErrors = true;
        expiry_date.classList.add('input-error');
      }

      const certificate_policy = document.getElementById('certificate_policy');
      if (!certificate_policy.checked) {
        hasErrors = true;
        certificate_policy.classList.add('input-error');
      }

      if (hasErrors) {
        alert("Please provide the required fields.");
      }

      break;

    case 2:
      // Validate Step 2 fields
      const appointmentDay = document.getElementById('appointment-day');
      const appointmentTime = document.getElementById('appointment-time');
      if (!appointmentDay.value) {
        hasErrors = true;
        appointmentDay.classList.add('input-error');
      }
      if (!appointmentTime.value) {
        hasErrors = true;
        appointmentTime.classList.add('input-error');
      }

      if (hasErrors) {
        alert("Please provide the required appointment details.");
      }

      break;

    case 3:
      // Validate Step 3 fields
      const cardNumber = document.getElementById('card_number');
      if (!cardNumber.value) {
        hasErrors = true;
        cardNumber.classList.add('input-error');
      }

      const securityCode = document.getElementById('security_code');
      if (!securityCode.value) {
        hasErrors = true;
        securityCode.classList.add('input-error');
      }

      const expirationDate = document.getElementById('expiration_date');
      if (!expirationDate.value) {
        hasErrors = true;
        expirationDate.classList.add('input-error');
      }

      if (hasErrors) {
        alert("Please provide the required credit card details.");
      }

      break;

    default:
      hasErrors = true;
      alert('Unknown step number.');
      break;
  }

  if (!hasErrors) {
    if (currentStep < 3) {
      currentStep++;
      showStep(currentStep);
    } else {
      document.getElementById('requestForm').submit();
    }
  }
}

function prevStep() {
  if (currentStep > 1) {
    currentStep--;
    showStep(currentStep);
  }
}

document.addEventListener("DOMContentLoaded", function () {
  const otherRadio = document.getElementById("other");
  const otherInputContainer = document.getElementById("other-input-container");
  const otherDetailsInput = document.getElementById("otherDetails");

  function handleRadioChange() {
    if (otherRadio.checked) {
      otherInputContainer.style.display = "block";
      otherDetailsInput.required = true;
    } else {
      otherInputContainer.style.display = "none";
      otherDetailsInput.required = false;
    }
  }

  const radioButtons = document.querySelectorAll('input[name="referral_type"]');
  radioButtons.forEach((radio) => {
    radio.addEventListener("change", handleRadioChange);
  });

  handleRadioChange();
});
