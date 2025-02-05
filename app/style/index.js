function changeNavbarColor() {
  var navbar = document.getElementById("navbar");
  var navLinks = document.querySelectorAll(".navbar-nav .nav-link");
  var toggler = document.querySelector(".navbar-toggler");

  if (document.body.scrollTop > 50 || document.documentElement.scrollTop > 50) {
    navbar.classList.add("bg-white", "shadow"); // Change navbar background to white
    navbar.classList.remove("navbar-light");
    navbar.classList.add("navbar-dark");

    // Change text color to black
    navLinks.forEach((link) => {
      link.style.color = "black";
    });

    // Ensure toggler gets a black background
    toggler.style.backgroundColor = "black";
  } else {
    navbar.classList.remove("bg-white", "shadow");
    navbar.classList.add("navbar-light");
    navbar.classList.remove("navbar-dark");

    // Change text color back to white
    navLinks.forEach((link) => {
      link.style.color = "white";
    });

    // Reset toggler background to transparent
    toggler.style.backgroundColor = "transparent";
  }
}

// Attach scroll event
window.onscroll = function () {
  changeNavbarColor();
};
