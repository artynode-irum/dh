// Price

function updateTotal() {
  // Get the total element
  const totalElement = document.getElementById("total");
  const totalInput = document.getElementById("total-value");

  // Get all radio buttons with name 'payment'
  const paymentOptions = document.getElementsByName("payment");

  let selectedValue = "Error"; // Default value

  // Iterate through the radio buttons to find the selected one
  for (const option of paymentOptions) {
    if (option.checked) {
      selectedValue = option.value;
      break;
    }
  }

  // Update the total element with the selected value or error
  totalElement.textContent = `Total: $${selectedValue}`;
  totalInput.value = parseInt(selectedValue);
}

// Medicare Number

function formatMedicareNumber(input) {
  // Remove non-digit characters
  let value = input.value.replace(/\D/g, "");

  // Insert the '/' before the 11th digit if needed
  if (value.length > 10) {
    value = value.slice(0, 10) + "/" + value.slice(10);
  }

  // Limit the value to 12 characters (11 digits + 1 '/')
  input.value = value.slice(0, 12);
}

// // age calculator

//   function calculateAge() {
//     var dob = document.getElementById('dob').value;
//     if (!dob) {
//       document.getElementById('age').textContent = '';
//       return;
//     }

//     var dobDate = new Date(dob);
//     var today = new Date();
//     var age = today.getFullYear() - dobDate.getFullYear();
//     var monthDifference = today.getMonth() - dobDate.getMonth();

//     if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < dobDate.getDate())) {
//       age--;
//     }

//     document.getElementById('age').textContent = age + ' years';
//   }

// Initialize Flatpickr with an icon and date limitation
flatpickr("#dob", {
  enableTime: false, // Disable time selection
  dateFormat: "d-m-Y", // Custom date format
  maxDate: new Date().setFullYear(new Date().getFullYear() - 17), // Limit to 17+ years old
  onChange: function (selectedDates, dateStr, instance) {
    calculateAge(dateStr); // Call age calculator when date is selected
  },
});

// Age calculator function
function calculateAge(dobStr) {
  // Convert date string from 'd-m-Y' format to a Date object
  const [day, month, year] = dobStr.split('-').map(Number);
  const dobDate = new Date(year, month - 1, day); // Months are zero-based in JavaScript Date
  const today = new Date();
  let age = today.getFullYear() - dobDate.getFullYear();
  const monthDifference = today.getMonth() - dobDate.getMonth();

  if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < dobDate.getDate())) {
    age--;
  }

  // Display the calculated age with 'years old'
  document.getElementById("age").textContent = age + " years old";
}

// Call calculateAge on page load if the DOB field already has a value
document.addEventListener("DOMContentLoaded", function () {
  var dobField = document.getElementById("dob").value;

  // If there is a pre-filled DOB, calculate the age
  if (dobField) {
    calculateAge(dobField);
  }
});

// // Initialize Flatpickr with an icon and date limitation
// flatpickr("#dob", {
//   enableTime: false, // Disable time selection
//   dateFormat: "d-m-Y", // Custom date format for the input value
//   altInput: true, // Enable alternative input
//   altFormat: "d-m-Y", // Display date format
//   maxDate: new Date().setFullYear(new Date().getFullYear() - 17), // Limit to 17+ years old
//   onChange: function (selectedDates, dateStr) {
//     calculateAge(dateStr); // Call age calculator when date is selected
//   },
// });

// // Age calculator function
// function calculateAge(dob) {
//   // Convert d-m-Y to Y-m-d format
//   const [day, month, year] = dob.split('-');
//   const dobDate = new Date(`${year}-${month}-${day}`); // Construct date in Y-m-d format
//   const today = new Date();
  
//   let age = today.getFullYear() - dobDate.getFullYear();
//   const monthDifference = today.getMonth() - dobDate.getMonth();

//   if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < dobDate.getDate())) {
//     age--;
//   }

//   // Display the calculated age with 'years old'
//   document.getElementById("age").textContent = age + " years old";
// }

// // Call calculateAge on page load if the DOB field already has a value
// document.addEventListener("DOMContentLoaded", function () {
//   var dobField = document.getElementById("dob").value;

//   // If there is a pre-filled DOB, calculate the age
//   if (dobField) {
//     calculateAge(dobField);
//   }
// });




// Specialization form

// Set sessionStorage flag after redirection
if (window.location.href.includes("telehealth.php")) {
  sessionStorage.setItem("formSubmitted", "true");
}

// Function to show alerts based on sessionStorage
function showAlert() {
  if (sessionStorage.getItem("formSubmitted") === "true") {
    sessionStorage.removeItem("formSubmitted");
    alert("Appointment updated successfully.");
    setTimeout(() => {
      window.location.href = "../doctor/telehealth.php";
    }, 1000);
  }
}

// Call the function on page load
showAlert();

// Function to count words
function countWords(str) {
  return str.trim().split(/\s+/).length;
}

// Function to update word count display
function updateWordCount() {
  const commentField = document.getElementById("comment");
  const wordCountDisplay = document.getElementById("word-count");
  const maxWords = 50;

  let commentText = commentField.value;
  let wordCount = countWords(commentText);

  // Display word count
  wordCountDisplay.textContent = wordCount;

  // Check if word count exceeds the limit
  if (wordCount > maxWords) {
    commentField.value = commentText.split(/\s+/).slice(0, maxWords).join(" ");
    wordCountDisplay.textContent = maxWords; // Set to max words limit
  }
}

// Add event listener to comment field
document.getElementById("comment").addEventListener("input", updateWordCount);

// Initialize word count on page load
updateWordCount();
// Set sessionStorage flag after redirection
if (window.location.href.includes("telehealth.php")) {
  sessionStorage.setItem("formSubmitted", "true");
}

// Function to show alerts based on sessionStorage
function showAlert() {
  if (sessionStorage.getItem("formSubmitted") === "true") {
    sessionStorage.removeItem("formSubmitted");
    alert("Appointment updated successfully.");
    setTimeout(() => {
      window.location.href = "../doctor/telehealth.php";
    }, 1000);
  }
}

// Call the function on page load
showAlert();

// Set sessionStorage flag after redirection
if (window.location.href.includes("telehealth.php")) {
  sessionStorage.setItem("formSubmitted", "true");
}

// Function to show alerts based on sessionStorage
function showAlert() {
  if (sessionStorage.getItem("formSubmitted") === "true") {
    sessionStorage.removeItem("formSubmitted");
    alert("Appointment updated successfully.");
    setTimeout(() => {
      window.location.href = "../doctor/telehealth.php";
    }, 1000);
  }
}

// Call the function on page load
showAlert();

document
  .getElementById("addressee-phone")
  .addEventListener("input", function (e) {
    // Replace non-numeric characters except for the leading '+'
    e.target.value = e.target.value.replace(/(?!^\+)\D/g, "");
  });
// Set sessionStorage flag after redirection
if (window.location.href.includes("telehealth.php")) {
  sessionStorage.setItem("formSubmitted", "true");
}

// Function to show alerts based on sessionStorage
function showAlert() {
  if (sessionStorage.getItem("formSubmitted") === "true") {
    sessionStorage.removeItem("formSubmitted");
    alert("Appointment updated successfully.");
    setTimeout(() => {
      window.location.href = "../doctor/telehealth.php";
    }, 1000);
  }
}

// Call the function on page load
showAlert();



// phone number validation

function validatePhoneNumber(input) {
  const phoneNumber = input.value;
  const phonePattern = /^(?:\+61|0)[2-478]\d{8}$|^04\d{8}$/; // Pattern for Australian phone numbers
  const errorSpan = document.getElementById('phone-error');

  if (phonePattern.test(phoneNumber)) {
      errorSpan.style.display = 'none';
      input.style.borderColor = '';
  } else {
      errorSpan.style.display = 'inline';
      input.style.borderColor = 'red';
  }
}