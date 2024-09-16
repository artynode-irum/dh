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
});

function toggleSidebar() {
  var sidebar = document.getElementById("sidebar");
  var content = document.querySelector(".second-section");
  var sidebarLogo = document.querySelector(".sidebar-logo");

  if (sidebar.style.width === "max-content" || sidebar.style.width === "") {
    sidebar.style.width = "50px";
    content.style.width = "100%";
    sidebarLogo.style.display = "none";
    content.classList.remove("sidebar-open");
  } else {
    sidebar.style.width = "max-content";
    sidebarLogo.style.display = "inline";
    sidebarLogo.style.marginRight = "20px";
    content.classList.add("sidebar-open");
  }
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


function toggleDropdown() {
  var dropdown = document.getElementById("dropdownContent");
  // dropdown.classList.toggle("show");
  // dropdown.style.display = "block";

  if (dropdown.style.display === "block") {
    dropdown.style.display = "none";
  } else {
    dropdown.style.display = "block";
  }
  // element.classList.toggle(".dropdown-content-display");
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