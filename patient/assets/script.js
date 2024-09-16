document.addEventListener("DOMContentLoaded", function () {
  var sidebar = document.getElementById("sidebar");
  var content = document.querySelector(".second-section");
  var sidebarLogo = document.querySelector(".sidebar-logo");

  // Initial sidebar state
  if (window.innerWidth <= 1176) {
    sidebar.style.width = "50px";
    // sidebar.style.display = "none";
    sidebarLogo.style.display = "none";
    content.classList.remove("sidebar-open");
  } else {
    sidebar.style.width = "max-content";
    content.classList.add("sidebar-open");
  }

  // Add event listener to the sidebar toggle icon
  var sidebarToggle = document.getElementById("sidebar-toggle");
  sidebarToggle.addEventListener("click", function () {
    toggleSidebar();
  });

  // Add event listener to the dropdown toggle in navbar
  var dropdownToggle = document.querySelector(".navbar-profile");
  dropdownToggle.addEventListener("click", function (event) {
    event.stopPropagation();
    toggleDropdown();
  });

  // Close the dropdown if the user clicks outside of it
  window.addEventListener("click", function (event) {
    var dropdown = document.getElementById("dropdownContent");
    if (!event.target.closest(".navbar-title") && dropdown.classList.contains("show")) {
      dropdown.classList.remove("show");
    }
  });

  // Highlight active sidebar link based on the current page
  highlightActiveLink();

  // Handle screen resize
  window.addEventListener("resize", function () {
    if (window.innerWidth <= 1176 && sidebar.style.width === "max-content") {
      sidebar.style.width = "50px";
      content.classList.remove("sidebar-open");
    } else if (window.innerWidth > 1176 && sidebar.style.width === "50px") {
      sidebar.style.width = "max-content";
      content.classList.add("sidebar-open");
    }
  });

// Referrals other

  const otherRadio = document.getElementById("other");
  const otherInput = document.getElementById("other-input");

  // Function to handle the change event of radio buttons
  function handleRadioChange() {
    if (otherRadio.checked) {
      otherInput.style.display = "block";
    } else {
      otherInput.style.display = "none";
    }
  }

  // Attach the event listener to the radio buttons
  document
    .querySelectorAll('input[name="referral_type"]')
    .forEach(function (radio) {
      radio.addEventListener("change", handleRadioChange);
    });

  // Initial check in case "Other" was pre-selected or if user loads the page with "Other" already selected
  handleRadioChange();

});

function toggleSidebar() {
  var sidebar = document.getElementById("sidebar");
  // var sidebarText = document.querySelectorAll(".side-bar-link");
  var content = document.querySelector(".second-section");
  var sidebarLogo = document.querySelector(".sidebar-logo");

  if (sidebar.style.width === "max-content") {
    sidebar.style.width = "50px";
    // sidebar.style.padding = "10px 0px 10px 0px";
    // content.style.marginLeft = "50px";
    // content.style.width = "calc(100% - 50px)";
    // sidebarText.style.display = "none";
    content.style.width = "100%";
    sidebarLogo.style.display = "none";
    content.classList.remove("sidebar-open");
  } else {
    sidebar.style.width = "max-content";
    // content.style.width = "100%";
    sidebarLogo.style.display = "inline";
    sidebarLogo.style.marginRight = "20px";
    // content.style.marginLeft = "300px";
    content.classList.add("sidebar-open");
  }
}

function toggleDropdown() {
  var dropdown = document.getElementById("dropdownContent");
  // dropdown.classList.toggle("show");
  // dropdown.style.display = "block";

  if (dropdown.style.display === "none") {
    dropdown.style.display = "block";
  } else {
    dropdown.style.display = "none";
  }
  // element.classList.toggle(".dropdown-content-display");
}

function highlightActiveLink() {
  var path = window.location.pathname;
  var page = path.split("/").pop();

  var links = document.querySelectorAll(".sidebar a");
  links.forEach(function (link) {
    link.classList.remove("active");
    if (link.getAttribute("href").indexOf(page) !== -1) {
      link.classList.add("active");
    }
  });
}

// MODAL
function openModal(modalId) {
  document.getElementById(modalId).style.display = "block";
}

// Function to close the modal
function closeModal(modalId) {
  document.getElementById(modalId).style.display = "none";
}

// Close the modal when clicking outside of it
window.onclick = function (event) {
  var modals = document.getElementsByClassName('modal');
  for (var i = 0; i < modals.length; i++) {
      if (event.target == modals[i]) {
          modals[i].style.display = "none";
      }
  }
}

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

// age calculator

function calculateAge() {
  var dob = document.getElementById("dob").value;
  if (!dob) {
    document.getElementById("age").textContent = "";
    return;
  }

  var dobDate = new Date(dob);
  var today = new Date();
  var age = today.getFullYear() - dobDate.getFullYear();
  var monthDifference = today.getMonth() - dobDate.getMonth();

  if (
    monthDifference < 0 ||
    (monthDifference === 0 && today.getDate() < dobDate.getDate())
  ) {
    age--;
  }

  document.getElementById("age").textContent = age + " years";
}