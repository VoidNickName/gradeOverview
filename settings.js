function firstNameOnClick() {
    var firstName = document.getElementById("changeFirstName");
    var lastName = document.getElementById("changeLastName");
    var username = document.getElementById("changeUsername");
    var password = document.getElementById("changePassword");
    firstName.style.display = "block";
    lastName.style.display = "none";
    username.style.display = "none";
    password.style.display = "none";
}

function lastNameOnClick() {
  var firstName = document.getElementById("changeFirstName");
  var lastName = document.getElementById("changeLastName");
  var username = document.getElementById("changeUsername");
  var password = document.getElementById("changePassword");
  firstName.style.display = "none";
  lastName.style.display = "block";
  username.style.display = "none";
  password.style.display = "none";
}

function usernameOnClick() {
  var firstName = document.getElementById("changeFirstName");
  var lastName = document.getElementById("changeLastName");
  var username = document.getElementById("changeUsername");
  var password = document.getElementById("changePassword");
  firstName.style.display = "none";
  lastName.style.display = "none";
  username.style.display = "block";
  password.style.display = "none";
}

function passwordOnClick() {
  var firstName = document.getElementById("changeFirstName");
  var lastName = document.getElementById("changeLastName");
  var username = document.getElementById("changeUsername");
  var password = document.getElementById("changePassword");
  firstName.style.display = "none";
  lastName.style.display = "none";
  username.style.display = "none";
  password.style.display = "block";
}

var strength = {
    0: "Worst",
    1: "Bad",
    2: "Weak",
    3: "Good",
    4: "Strong"
  }

var password2 = document.getElementById('password2');
var meter = document.getElementById('password-strength-meter');
var text = document.getElementById('password-strength-text');

password2.addEventListener('input', function() {
  var val = password2.value;
  var result = zxcvbn(val);

  // Update the password strength meter
  meter.value = result.score;

  // Update the text indicator
  if (val !== "") {
    text.innerHTML = "Strength: " + strength[result.score]; 
  } else {
    text.innerHTML = "";
  }
});