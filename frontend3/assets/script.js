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


// Initialize Flatpickr with an icon and date limitation
flatpickr("#dob", {
  enableTime: false, // Disable time selection
  dateFormat: "d-m-Y", // Custom date format
  maxDate: new Date().setFullYear(new Date().getFullYear() - 18), // Limit to 18+ years old
  onChange: function (selectedDates, dateStr, instance) {
    calculateAge(dateStr); // Call age calculator when date is selected
  },
});

// Age calculator function
function calculateAge(dobStr) {
  var [day, month, year] = dobStr.split('-').map(Number); // Split and convert to numbers
  var dobDate = new Date(year, month - 1, day); // JavaScript Date object (month is 0-based)
  var today = new Date();
  var age = today.getFullYear() - dobDate.getFullYear();
  var monthDifference = today.getMonth() - dobDate.getMonth();

  if (
    monthDifference < 0 ||
    (monthDifference === 0 && today.getDate() < dobDate.getDate())
  ) {
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
