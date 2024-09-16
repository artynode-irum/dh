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
  // Clear previous errors
  clearErrors();

  let isValid = false;

  switch (stepNumber) {
    case 1:
      // Validate form fields on step 1
      isValid = validateStep1();
      break;
    case 2:
      // Validate form fields on step 2
      isValid = validateStep2();
      break;
    case 3:
      // Validate form fields on step 3
      isValid = validateStep3();
      break;
  }

  if (isValid) {
    if (currentStep < 3) {
      currentStep++;
      showStep(currentStep);
    } else {
      // Assuming you want to submit the form at the end
      document.getElementById('requestForm').submit();
    }
  }
}

// Helper function to clear previous errors
function clearErrors() {
  document.querySelectorAll(".input-error").forEach((element) => {
    element.classList.remove("input-error");
  });
}

function validateStep1() {
  let isValid = true;

  // Validate fields and apply error class if invalid
  const fields = [
    "title",
    "fname",
    "lname",
    "dob",
    "email",
    // "phone",
    "gender",
    "address",
    // "medicare_number",
    // "expiry_year",
    // "expiry_month",
    // "expiry_date",
  ];

  fields.forEach((id) => {
    const field = document.getElementById(id);
    if (!field.value) {
      isValid = false;
      field.classList.add("input-error");
    }
  });

  
  const phoneInput = document.getElementById('phone');
  const phone_error = document.getElementById('phone-error');
  if (!phoneInput.value || phone_error.style.display !== 'none' ) {
    isValid = false;
    phoneInput.classList.add('input-error');
  }

  const certificatePolicy = document.getElementById("certificate_policy");
  if (!certificatePolicy.checked) {
    isValid = false;
    certificatePolicy.parentElement.classList.add("input-error");
  }

  const postCode = document.getElementById("cityCode").value;
  const stateCode = document.getElementById("postcode").value;
  const cityCode = document.getElementById("stateCode").value;
  const address = document.getElementById("address");
  if (postCode == "" || stateCode == "" || cityCode == "") {
    isValid = false;
    address.parentElement.classList.add("input-error");
    alert("address is not correct");
  }

  if (!isValid) {
    alert("Please provide the required fields.");
  }

  return isValid;
}

function validateStep2() {
  let isValid = true;

  // Validate fields and apply error class if invalid
  const fields = [
    "appointment-day",
    "appointment-time",
    // 'description',
  ];

  fields.forEach((id) => {
    const field = document.getElementById(id);
    if (!field.value) {
      isValid = false;
      field.classList.add("input-error");
    }
  });

  if (!isValid) {
    alert("Please provide the required fields.");
  }

  return isValid;
}

function validateStep3() {
  let isValid = true;

  // Validate fields and apply error class if invalid
  const fields = ["card-number", "card-expiry", "card-cvc"];

  fields.forEach((id) => {
    const field = document.querySelector(`#${id} input`);
    if (!field.value) {
      isValid = false;
      field.classList.add("input-error");
    }
  });

  if (!isValid) {
    alert("Please provide the required fields.");
  }

  return isValid;
}

function prevStep() {
  if (currentStep > 1) {
    currentStep--;
    showStep(currentStep);
  }
}

// document.addEventListener("DOMContentLoaded", function () {
//   const otherRadio = document.getElementById("other");
//   const otherInputContainer = document.getElementById("other-input-container");
//   const otherDetailsInput = document.getElementById("otherDetails");

//   function handleRadioChange() {
//     if (otherRadio.checked) {
//       otherInputContainer.style.display = "block";
//       otherDetailsInput.required = true;
//     } else {
//       otherInputContainer.style.display = "none";
//       otherDetailsInput.required = false;
//     }
//   }

//   const radioButtons = document.querySelectorAll('input[name="referral_type"]');
//   radioButtons.forEach((radio) => {
//     radio.addEventListener("change", handleRadioChange);
//   });

//   handleRadioChange();
// });
