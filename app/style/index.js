// Function to change navbar color on scroll
window.onscroll = function () {
  changeNavbarColor();
};

function changeNavbarColor() {
  var navbar = document.getElementById("navbar");
  var navLinks = document.querySelectorAll(".navbar-nav .nav-link");

  if (document.body.scrollTop > 50 || document.documentElement.scrollTop > 50) {
    navbar.classList.add("bg-white", "shadow"); // Add white background and shadow
    navbar.classList.remove("navbar-light");
    navbar.classList.add("navbar-dark");

    // Change text color to black
    navLinks.forEach((link) => {
      link.style.color = "black";
    });
  } else {
    navbar.classList.remove("bg-white", "shadow");
    navbar.classList.add("navbar-light");
    navbar.classList.remove("navbar-dark");

    // Change text color back to white (default)
    navLinks.forEach((link) => {
      link.style.color = "white";
    });
  }
}

document
  .getElementById("searchIcon")
  .addEventListener("click", function (event) {
    event.preventDefault(); // Prevent default anchor click behavior
    let searchBar = document.getElementById("searchBar");
    if (searchBar.style.display === "none" || searchBar.style.display === "") {
      searchBar.style.display = "block"; // Show search bar
    } else {
      searchBar.style.display = "none"; // Hide search bar
    }
  });
