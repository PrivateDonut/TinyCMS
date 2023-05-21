document.addEventListener("DOMContentLoaded",
function() {
  const navbarToggler = document.querySelector(".navbar-toggler");
  const navbarMenu = document.querySelector(".navbar-collapse");

  navbarToggler.addEventListener("click",
  function() {
    navbarMenu.classList.toggle("show");
  });
});