document.addEventListener("DOMContentLoaded", function () {
  // Set initial sidebar state
  var sidebar = document.getElementById("sidebar");
  var content = document.querySelector(".content");

  sidebar.style.width = "max-content";
  // content.style.marginLeft = "300px";
  content.classList.add("sidebar-open");

  // Set sidebar state based on screen size
  if (window.innerWidth <= 1176) {
    sidebar.style.width = "50px";
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
  dropdownToggle.addEventListener("onclick", function (event) {
    event.stopPropagation();
    // Prevents the click event from bubbling up
    toggleDropdown();
  });

  // Close the dropdown if the user clicks outside of it
  window.addEventListener("click", function (event) {
    var dropdown = document.getElementById("dropdownContent");
    if (
      !event.target.closest(".navbar-title") &&
      dropdown.classList.contains("show")
    ) {
      dropdown.classList.remove("show");
    }
  });

  // Highlight active sidebar link based on current page
  highlightActiveLink();
});

function toggleSidebar() {
  var sidebar = document.getElementById("sidebar");
  // var sidebarText = document.querySelectorAll(".side-bar-link");
  var content = document.querySelector(".content");
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


// Get the modal
var modal = document.getElementById("requestModal");

// Get the button that opens the modal
var btn = document.querySelector(".button-class");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close-button")[0];

// When the user clicks the button, open the modal
btn.onclick = function() {
    modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}



// Price 

function updateTotal() {
  // Get the total element
  const totalElement = document.getElementById('total');
  const totalInput = document.getElementById('total-value');

  // Get all radio buttons with name 'payment'
  const paymentOptions = document.getElementsByName('payment');

  let selectedValue = 'Error'; // Default value

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
