var login = document.getElementById("login"); // Formulario de inicio de sesión
var regis = document.getElementById("register"); // Formulario de registro
var nocuenta = document.getElementById("nocuenta"); // Botón para cambiar a formulario de registro
var cuenta = document.getElementById("cuenta"); // Botón para cambiar a formulario de inicio de sesión
var formularioEdit = document.getElementById("formularioEdit"); // Formulario de edición de productos (no usado en este código)

// Evento para mostrar el formulario de registro y ocultar el de inicio de sesión
nocuenta.addEventListener("click", function() {
    login.style.display = "none"; // Oculta el formulario de inicio de sesión
    regis.style.display = "block"; // Muestra el formulario de registro
});

// Evento para mostrar el formulario de inicio de sesión y ocultar el de registro
cuenta.addEventListener("click", function() {
    login.style.display = "block"; // Muestra el formulario de inicio de sesión
    regis.style.display = "none"; // Oculta el formulario de registro
});

// Asegurarse de que el DOM esté completamente cargado antes de añadir el evento para el input de búsqueda
document.addEventListener("DOMContentLoaded", function() {
document.getElementById("searchInput").addEventListener("keyup", filterTable); // Añadir evento de búsqueda
});

function showAddFormAdmin() {
    document.getElementById('formTitle').innerText = "Nuevo Usuario";
    document.getElementById('formIdUsuario').value = "";
    document.getElementById('formNombre').value = "";
    document.getElementById('formCorreo').value = "";
    document.getElementById('formContrasenia').value = "";
    document.getElementById('formRol').value = "";
    document.getElementById('formPlan').value = "";
    document.getElementById('formContainer').style.display = "block";
}

function showEditFormAdmin(id, nombre, correo, contrasenia, rol, plan) {
    document.getElementById('formTitle').innerText = "Editar Usuario";
    document.getElementById('formIdUsuario').value = id;
    document.getElementById('formNombre').value = nombre;
    document.getElementById('formCorreo').value = correo;
    document.getElementById('formContrasenia').value = contrasenia;
    document.getElementById('formRol').value = rol;
    document.getElementById('formPlan').value = plan;
    document.getElementById('formContainer').style.display = "block";
}

function filterTableAdmin() {
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("searchInput");
    filter = input.value.toLowerCase();
    table = document.getElementById("userTable");
    tr = table.getElementsByTagName("tr");
    for (i = 1; i < tr.length; i++) {
        tr[i].style.display = "none";
        td = tr[i].getElementsByTagName("td");
        for (var j = 0; j < td.length; j++) {
            if (td[j]) {
                txtValue = td[j].textContent || td[j].innerText;
                if (txtValue.toLowerCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                    break;
                }
            }
        }
    }
}

// Función para mostrar el formulario de añadir nuevo producto
function showAddForm() {
    document.getElementById('formTitle').innerText = "Nuevo Producto"; // Cambia el título del formulario
    document.getElementById('formNumProducto').value = ''; // Limpia el campo oculto de ID de producto
    document.getElementById('formNombre').value = ''; // Limpia el campo de nombre
    document.getElementById('formDescripcion').value = ''; // Limpia el campo de descripción
    document.getElementById('formPrecio').value = ''; // Limpia el campo de precio
    document.getElementById('formCantidad').value = ''; // Limpia el campo de cantidad
    document.getElementById('formContainer').style.display = 'block'; // Muestra el contenedor del formulario
}

// Función para mostrar el formulario de edición de producto con valores precargados
function showEditForm(id, nombre, descripcion, precio, cantidad) {
    document.getElementById('formTitle').innerText = "Editar Producto"; // Cambia el título del formulario
    document.getElementById('formNumProducto').value = id; // Rellena el campo oculto de ID de producto
    document.getElementById('formNombre').value = nombre; // Rellena el campo de nombre
    document.getElementById('formDescripcion').value = descripcion; // Rellena el campo de descripción
    document.getElementById('formPrecio').value = precio; // Rellena el campo de precio
    document.getElementById('formCantidad').value = cantidad; // Rellena el campo de cantidad
    document.getElementById('formContainer').style.display = 'block'; // Muestra el contenedor del formulario
}

// Función para filtrar la tabla de productos basada en el input de búsqueda
function filterTable() {
    let input = document.getElementById("searchInput"); // Input de búsqueda
    let filter = input.value.toLowerCase(); // Valor del input en minúsculas
    let rows = document.querySelectorAll("#productTable .productRow"); // Todas las filas de la tabla de productos

    // Iterar sobre todas las filas de la tabla
    rows.forEach(row => {
        let productName = row.querySelector(".productName").innerText.toLowerCase(); // Obtener el nombre del producto en minúsculas
        if (productName.indexOf(filter) > -1) {
            row.style.display = ""; // Mostrar la fila si coincide
        } else {
            row.style.display = "none"; // Ocultar la fila si no coincide
        }
    });
}

// Asegurarse de que el DOM esté completamente cargado antes de añadir el evento para el input de búsqueda
document.addEventListener("DOMContentLoaded", function() {
    let searchInput = document.getElementById('searchInput'); // Input de búsqueda
    let productTable = document.getElementById('productTable'); // Tabla de productos
  
    // Evento para filtrar la tabla de productos mientras se escribe en el input de búsqueda
    searchInput.addEventListener('keyup', function() {
        let filter = this.value.toLowerCase(); // Valor del input en minúsculas
        let rows = productTable.getElementsByClassName('productRow'); // Todas las filas de la tabla de productos
  
        // Iterar sobre todas las filas de la tabla
        for (let i = 0; i < rows.length; i++) {
            let nameCell = rows[i].getElementsByClassName('productName')[0]; // Celda del nombre del producto
            let descCell = rows[i].getElementsByClassName('productDescription')[0]; // Celda de la descripción del producto
            let priceCell = rows[i].getElementsByClassName('productPrice')[0]; // Celda del precio del producto
  
            let nameText = nameCell.textContent || nameCell.innerText; // Texto del nombre del producto
            let descText = descCell.textContent || descCell.innerText; // Texto de la descripción del producto
            let priceText = priceCell.textContent || priceCell.innerText; // Texto del precio del producto
  
            // Mostrar la fila si coincide el nombre, descripción o precio del producto con el filtro
            if (nameText.toLowerCase().indexOf(filter) > -1 || 
                descText.toLowerCase().indexOf(filter) > -1 ||
                priceText.toLowerCase().indexOf(filter) > -1) {
                rows[i].style.display = '';
            } else {
                rows[i].style.display = 'none';
            }
        }
    });
});
