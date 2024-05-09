var login = document.getElementById("login");
var regis = document.getElementById("register");
var nocuenta = document.getElementById("nocuenta");
var cuenta = document.getElementById("cuenta");

nocuenta.addEventListener("click", function() {
    login.style.display = "none";
    regis.style.display = "block";
});

cuenta.addEventListener("click", function() {
    login.style.display = "block";
    regis.style.display = "none";
});
