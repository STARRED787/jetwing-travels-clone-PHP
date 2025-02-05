document.addEventListener("DOMContentLoaded", function () {
  var navbar = document.getElementById("navbar");
  var navLinks = document.querySelectorAll(".navbar-nav .nav-link");
  var toggler = document.querySelector(".navbar-toggler");
  var searchBar = document.getElementById("searchBar");
  var searchIcon = document.getElementById("searchIcon");

  // Function to change navbar color on scroll
  window.addEventListener("scroll", function () {
    if (window.scrollY > 50) {
      navbar.classList.add("bg-white", "shadow");
      navbar.classList.remove("navbar-light");
      navbar.classList.add("navbar-dark");

      // Change text color to black
      navLinks.forEach((link) => (link.style.color = "black"));
    } else {
      navbar.classList.remove("bg-white", "shadow");
      navbar.classList.add("navbar-light");
      navbar.classList.remove("navbar-dark");

      // Change text color back to white
      navLinks.forEach((link) => (link.style.color = "white"));
    }
  });

  // Change navbar background when toggler is clicked (for mobile)
  toggler.addEventListener("click", function () {
    navbar.classList.toggle("navbar-black");
  });

  // Toggle search bar on click
  searchIcon.addEventListener("click", function (event) {
    event.preventDefault();
    searchBar.style.display =
      searchBar.style.display === "block" ? "none" : "block";
  });
});
