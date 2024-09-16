let currentStep = 1;

function showStep(stepNumber) {
  document.querySelectorAll(".form-step").forEach((step) => {
    step.classList.remove("active");
  });

  document.getElementById(`step${stepNumber}`).classList.add("active");
  document
    .getElementById(`step${stepNumber}`)
    .classList.add("animate__animated");
  document.getElementById(`step${stepNumber}`).classList.add("animate__fadeIn");
  document.getElementById(`step${stepNumber}`).classList.add("animate__faster");
}

// function validateStep(stepNumber) {
//     var hasErrors = false;

//     if (hasErrors == false) {
//         if (currentStep < 3) {
//             currentStep++;
//             showStep(currentStep);
//         }
//     }
// }

function prevStep() {
  if (currentStep > 1) {
    currentStep--;
    hasErrors = true;
    showStep(currentStep);
  }
}

function validateStep(stepNumber) {
  let isValid = true;

  // Helper function to clear previous errors
  function clearErrors() {
    document.querySelectorAll(".input-error").forEach((element) => {
      element.classList.remove("input-error");
    });
  }

  clearErrors(); // Clear any existing errors before validation

  switch (stepNumber) {
    case 1:
      // Validate Step 1
      const certificateType = document.querySelector(
        'input[name="certificate_type"]:checked'
      );
      const reason = document.querySelector('input[name="reason"]:checked');
      const illnessDescription = document
        .getElementById("illness_description")
        .value.trim();
      const description = document.getElementById("description").value.trim();


      if (!certificateType) {
        isValid = false;
        document
          .querySelector('input[name="certificate_type"]')
          .classList.add("input-error");
      }

      if (!reason) {
        isValid = false;
        document
          .querySelector('input[name="reason"]')
          .classList.add("input-error");
      }

      if (!illnessDescription) {
        isValid = false;
        document
          .getElementById("illness_description")
          .classList.add("input-error");
      }


      if (!certificateType || !reason || !illnessDescription) {
        alert("Please provide required fields.");
      }

      break;

    case 2:
      // Validate Step 2
      const title = document.getElementById("title");
      const fname = document.getElementById("fname").value.trim();
      const lname = document.getElementById("lname").value.trim();
      const gender = document.getElementById("gender");
      const dob = document.getElementById("dob").value;
      const email = document.getElementById("email").value.trim();
      const phone = document.getElementById("phone").value.trim();
      const fromDate = document.getElementById("fromDate").value;
      const toDate = document.getElementById("toDate").value;
      const policyChecked =
        document.getElementById("certificate_policy").checked;

      if (
        !title ||
        !fname ||
        !lname ||
        !gender ||
        !dob ||
        !email ||
        !phone ||
        !fromDate ||
        !toDate ||
        !policyChecked
      ) {
        alert("Please provide required fields.");
      }

      if (!title.value) {
        isValid = false;
        title.classList.add("input-error");
      }

      if (!fname) {
        isValid = false;
        document.getElementById("fname").classList.add("input-error");
      }

      if (!lname) {
        isValid = false;
        document.getElementById("lname").classList.add("input-error");
      }

      if (!gender.value) {
        isValid = false;
        gender.classList.add("input-error");
      }

      if (!dob) {
        isValid = false;
        document.getElementById("dob").classList.add("input-error");
      }

      if (!email) {
        isValid = false;
        document.getElementById("email").classList.add("input-error");
      }

      if (!phone) {
        isValid = false;
        document.getElementById("phone").classList.add("input-error");
      }

      if (!fromDate) {
        isValid = false;
        document.getElementById("fromDate").classList.add("input-error");
      }

      if (!toDate) {
        isValid = false;
        document.getElementById("toDate").classList.add("input-error");
      }

      if (!policyChecked) {
        isValid = false;
        document
          .getElementById("certificate_policy").classList.add("input-error");
      }
      break;

    case 3:
      // Validate Step 3
      const payment = document.querySelector('input[name="payment"]:checked');
      const stripeToken = document.querySelector('input[name="stripeToken"]');

      if (!payment) {
        isValid = false;
        document.querySelectorAll('input[name="payment"]').forEach((input) => {
          input.classList.add("input-error");
        });
      }

      if (!stripeToken) {
        isValid = false;
        document
          .querySelector('input[name="stripeToken"]')
          .classList.add("input-error");
      }
      break;
  }

  if (isValid) {
    if (stepNumber < 3) {
      currentStep++;
      showStep(currentStep);
    }
  }
}


// Initialize Flatpickr and restrict to the present date only
flatpickr("#fromDate", {
  enableTime: false,  // Disable time selection
  // dateFormat: "Y-m-d",  // Custom date format
  dateFormat: "d-m-Y",  // Custom date format
  minDate: "today",  // Restrict to today's date or future
  // maxDate: "today",  // Restrict to today's date only
});

// Initialize Flatpickr and restrict to the present date only
flatpickr("#toDate", {
  enableTime: false,  // Disable time selection
  // dateFormat: "Y-m-d",  // Custom date format
  dateFormat: "d-m-Y",  // Custom date format
  minDate: "today",  // Restrict to today's date or future
  // maxDate: "today",  // Restrict to today's date only
});
