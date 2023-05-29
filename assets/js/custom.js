document.addEventListener("DOMContentLoaded",
function() {
  const navbarToggler = document.querySelector(".navbar-toggler");
  const navbarMenu = document.querySelector(".navbar-collapse");

  navbarToggler.addEventListener("click",
  function() {
    navbarMenu.classList.toggle("show");
  });
});

document.addEventListener("DOMContentLoaded", function() {
  var dropdownToggle = document.querySelector(".dropdown-toggle");
  var dropdownMenu = document.querySelector("#userDropdownMenu");
  
  dropdownToggle.addEventListener("click", function(event) {
      event.preventDefault();
      dropdownMenu.classList.toggle("show");
  });
  
  window.addEventListener("click", function(event) {
      if (!dropdownToggle.contains(event.target) && !dropdownMenu.contains(event.target)) {
          dropdownMenu.classList.remove("show");
      }
  });
});