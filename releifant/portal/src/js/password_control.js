var pass1;
var pass2;
window.onload = function () {
  pass1 = document.getElementById("inputPassword1");
  pass2 = document.getElementById("inputPassword2");

  pass1.addEventListener("keyup", check_values);
  pass2.addEventListener("keyup", check_values);
};

function check_values() {
  x1 = document.getElementById("no_match");
  x2 = document.getElementById("too_short");
  btn = document.getElementById("submit_btn");

  if (pass1.value != pass2.value || pass1.value.length < 8) {
    btn.disabled = true;
  } else {
    btn.disabled = false;
  }

  if (pass1.value != pass2.value) {
    x1.style.display = "block";
  } else {
    x1.style.display = "none";
  }
  if (pass1.value.length < 8) {
    x2.style.display = "block";
  } else {
    x2.style.display = "none";
  }
}
