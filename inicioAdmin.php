<?php
require("funciones.php");
session_start();

// Controlamos que la sesión sigue activa
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Manejo del cierre de sesión
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Conexión a la base de datos
conectar_BD();

// Obtenemos el ID de usuario de la sesión
$idUser = $_SESSION['user_id'];

// Si se envía un formulario de adición o edición
if (isset($_POST['nombre']) && isset($_POST['correo']) && isset($_POST['contrasenia']) && isset($_POST['rol']) && isset($_POST['plan'])) {
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $contrasenia = trim($_POST['contrasenia']);
    $rol = trim($_POST['rol']);
    $plan = trim($_POST['plan']);
    $id_usuario = isset($_POST['id_usuario']) ? $_POST['id_usuario'] : null;

    if ($nombre == '' || $correo == '' || $contrasenia == '' || $rol == '' || $plan == '') {
        die ("<BR><BR><center>Todos los campos son obligatorios.</center>");
    }

    // Hash de la contraseña
    $hass = password_hash($contrasenia, PASSWORD_BCRYPT);

    if ($id_usuario) {
        // Actualizar usuario
        $consulta_usuario = "UPDATE Usuario SET Nombre='$nombre', Correo='$correo', Contrasenia='$hass', Rol='$rol', PlanAdquirido='$plan' WHERE idUsuario='$id_usuario'";
    } else {
        // Insertar nuevo usuario
        $consulta_usuario = "INSERT INTO Usuario (Nombre, Correo, Contrasenia, Rol, PlanAdquirido) VALUES ('$nombre','$correo','$hass','$rol','$plan')";
    }
    $resultado_usuario = ejecuta_SQL($consulta_usuario);

    // Redirigir a la página de inicio
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Si se envía una solicitud de eliminación
if (isset($_GET['delete'])) {
    $id_usuario = $_GET['delete'];

    // Eliminar primero los productos asociados al usuario
    $consulta_productos = "DELETE FROM producto WHERE Usuario_ID = $id_usuario";
    $resultado_productos = ejecuta_SQL($consulta_productos);

    // Luego eliminar el usuario
    $consulta_usuario = "DELETE FROM Usuario WHERE idUsuario = $id_usuario";
    $resultado_usuario = ejecuta_SQL($consulta_usuario);

    // Redirigir a la página de inicio
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Seleccionamos todos los usuarios
$consulta = "SELECT idUsuario, Nombre, Correo, Contrasenia, Rol, PlanAdquirido FROM Usuario";
$resultado = ejecuta_SQL($consulta);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Gestión de Usuarios">
    <title>Gestión de Usuarios</title>
    <!-- favicon -->
	<link rel="shortcut icon" type="image/png" href="assets/img/logo.png">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
</head>
<body style="background: orange;">
<div style="display: flex; justify-content: space-between;">
  <h1 style="flex-grow: 1; text-align: center; padding-top: 15px;">StockMaster</h1>
  <?php


// Controlamos que la sesión sigue activa
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Manejo del cierre de sesión
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: index.html");
    exit;
}
?>
  <form method="post" action="">
  <button type="submit" name="logout" class="btn btn-secondary" action="index.html">Logout</button>
    </form>
</div>
        <form>
            <input type="text" id="searchInput" placeholder="Buscar usuarios..." onkeyup="filterTableAdmin()">
        </form>
    </center>
    <br><br>
    <div>
    <?php
    // Verificamos si hay usuarios
    if ($resultado && $resultado->rowCount() > 0) {
        echo "<table id='userTable' BORDER='0' cellspacing='1' cellpadding='1' width='80%' align='center'>
                <tr><th bgcolor='black'><font color='white' face='arial, helvetica'>Nombre</font></th>
                    <th bgcolor='black'><font color='white' face='arial, helvetica'>Correo</font></th>
                    <th bgcolor='black'><font color='white' face='arial, helvetica'>Contraseña</font></th>
                    <th bgcolor='black'><font color='white' face='arial, helvetica'>Rol</font></th>
                    <th bgcolor='black'><font color='white' face='arial, helvetica'>Plan Adquirido</font></th>
                    <th bgcolor='black'><font color='white' face='arial, helvetica'>Operaciones</font></th>
                </tr>";
        foreach ($resultado as $row) {
            // Guardamos los valores en variables
            $idUsuario = $row['idUsuario'];
            $nombreUsuario = $row['Nombre'];
            $correoUsuario = $row['Correo'];
            $contraseniaUsuario = $row['Contrasenia'];
            $rolUsuario = $row['Rol'];
            $planUsuario = $row['PlanAdquirido'];
            // Imprimimos los datos en la tabla
            echo "<tr class='userRow'>
                    <td align='center' class='userName'>$nombreUsuario</td>
                    <td align='left' class='userEmail'>$correoUsuario</td>
                    <td align='left' class='userPassword'>$contraseniaUsuario</td>
                    <td align='left' class='userRole'>$rolUsuario</td>
                    <td align='left' class='userPlan'>$planUsuario</td>
                    <td align='center'>
                        <button onclick='showEditFormAdmin($idUsuario, \"$nombreUsuario\", \"$correoUsuario\", \"$contraseniaUsuario\", \"$rolUsuario\", \"$planUsuario\")' class='btn btn-primary'>Editar</button>
                        <a href='?delete=$idUsuario' class='btn btn-danger'>Eliminar</a>
                    </td>
                  </tr>";
        }
        echo "</table><br><center>";
        // Botón para agregar un nuevo usuario
        echo "<button onclick='showAddFormAdmin()' class='btn btn-success'>Nuevo usuario</button>";
       
    } else { // No hay ningún usuario
        echo "<br><br><center><h3>No hay usuarios que mostrar</h3><br><br>";
        echo "<button onclick='showAddFormAdmin()' class='btn btn-success'>Nuevo usuario</button>";
        
    }
    ?>
    </div>

    <!-- Formulario de Edición y Adición -->
    <br>
    <div id="formContainer" style="display:none;">
        <center><h3 id="formTitle">Nuevo Usuario</h3></center>
        <form id="userForm" method="post" action="">
            <input type="hidden" name="id_usuario" id="formIdUsuario">
            <table align='center'>
                <tr>
                    <td>Nombre:</td>
                    <td><input type='text' name='nombre' id='formNombre' size='20' maxlength='30'></td>
                </tr>
                <tr>
                    <td>Correo:</td>
                    <td><input type='text' name='correo' id='formCorreo' size='30' maxlength='100'></td>
                </tr>
                <tr>
                    <td>Contraseña:</td>
                    <td><input type='password' name='contrasenia' id='formContrasenia' size='20' maxlength='100'></td>
                </tr>
                <tr>
                    <td>Rol:</td>
                    <td><input type='text' name='rol' id='formRol' size='20' maxlength='50'></td>
                </tr>
                <tr>
                    <td>Plan Adquirido:</td>
                    <td><input type='text' name='plan' id='formPlan' size='20' maxlength='50'></td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <input type="submit" value="Guardar Cambios" class="btn btn-primary">
                    </td>
                </tr>
            </table>
        </form>
    </div>

    <script src="script.js"></script>
</body>
</html>
