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

function filterTable() {
    // Obtener el valor de búsqueda
    let input = document.getElementById('searchInput');
    let filter = input.value.toLowerCase();
    let table = document.getElementById('productTable');
    let rows = table.getElementsByClassName('productRow');

    // Iterar sobre todas las filas de la tabla y ocultar las que no coinciden con la búsqueda
    for (let i = 0; i < rows.length; i++) {
        let nameCell = rows[i].getElementsByClassName('productName')[0];
        let descCell = rows[i].getElementsByClassName('productDescription')[0];
        let priceCell = rows[i].getElementsByClassName('productPrice')[0];

        let nameText = nameCell.textContent || nameCell.innerText;
        let descText = descCell.textContent || descCell.innerText;
        let priceText = priceCell.textContent || priceCell.innerText;

        if (nameText.toLowerCase().indexOf(filter) > -1 || 
            descText.toLowerCase().indexOf(filter) > -1 ||
            priceText.toLowerCase().indexOf(filter) > -1) {
            rows[i].style.display = '';
        } else {
            rows[i].style.display = 'none';
        }
    }
}