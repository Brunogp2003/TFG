<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Responsive Bootstrap4 Shop Template, Created by Imran Hossain from https://imransdesign.com/">
    <link rel="shortcut icon" type="image/png" href="assets/img/logo.png">
    <title>Inventario</title>
    <!-- Tu código CSS y JavaScript -->
</head>
<body background="orange">
    <br><br>
    <!-- Cabecera fija -->
    <CENTER><h1>Stocker</h1><CENTER>
    <br><br>
    <div>
    <?php
require("funciones.php");
session_start();

// Controlamos que la sesión sigue activa
if (!isset($_SESSION['user_id'])) {
    $host  = $_SERVER['HTTP_HOST'];
    $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $extra = 'inicio.php';
    header("Location: http://$host$uri/$extra");  
    exit(); // Importante: terminamos el script después de redirigir
}

conectar_BD(); 

if(isset($_POST['nombre']) && isset($_POST['descr']) && isset($_POST['precio']) && isset($_POST['cantidad'])) {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descr'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];
    $usuarioID = $_SESSION['user_id']; 

    // Insertar en la base de datos
    $consulta_producto = "INSERT INTO Producto (Nombre, Descripcion, Precio, CantidadEnStock, Usuario_ID) VALUES ('$nombre','$descripcion','$precio','$cantidad', $usuarioID )";
    $resultado_producto = ejecuta_SQL($consulta_producto);

    // Redirigir a la página de inicio
    $host = $_SERVER['HTTP_HOST'];
    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $extra = "inicio.php"; // Corregido el nombre de la página
    header("Location: http://$host$uri/$extra");
    exit();
}

imprimir_footer();
?>


        <div>
            <BR><BR><center><h3>Nuevo Producto</h3></CENTER>
            <br><br>
            <!-- Agrega enctype="multipart/form-data" para permitir subir archivos -->
            <form name='form1' id='anadir' method='post' action=''>
                <table align='center'>
                    <tr>
                        <td >Nombre:</td>
                        <td><input type='text' name='nombre' value='' size='20' maxlength='30'></td>
                    </tr>
                    <tr>
                        <td >Descripción:</td>
                        <td><input type='text' name='descr' value='' size='12' maxlength='20'></td>
                    </tr>
                    <tr>
                        <td >Precio:</td>
                        <td><input type='text' name='precio' value='' size='12' maxlength='12'></td>
                    </tr>
                    <tr>
                        <td >Cantidad:</td>
                        <td><input type='text' name='cantidad' value='' size='20' maxlength='30'></td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center">
                            <input type="submit" value="Añadir Producto">
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</body>
</html>
