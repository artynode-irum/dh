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
  dateFormat: "Y-m-d", // Custom date format
  maxDate: new Date().setFullYear(new Date().getFullYear() - 17), // Limit to 17+ years old
  onChange: function (selectedDates, dateStr, instance) {
    calculateAge(dateStr); // Call age calculator when date is selected
  },
});

// Age calculator function
function calculateAge(dob) {
  var dobDate = new Date(dob); // Convert selected date to Date object
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
