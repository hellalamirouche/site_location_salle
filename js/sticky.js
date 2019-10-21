// sticky de la nav mobile :

window.onscroll = function() {myFunction()};

var navbar = document.getElementById("navmobile");
var sticky = navbar.offsetTop;

function myFunction() {
  if (window.pageYOffset >= sticky) {
    navbar.classList.add("sticky")
  } else {
    navbar.classList.remove("sticky");
  }
}
