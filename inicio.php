<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Responsive Bootstrap4 Shop Template, Created by Imran Hossain from https://imransdesign.com/">

	<!-- title -->
	<title>Inventario</title>

	<!-- favicon -->
	<link rel="shortcut icon" type="image/png" href="assets/img/favicon.png">
	<!-- google font -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Poppins:400,700&display=swap" rel="stylesheet">
	<!-- fontawesome -->
	<link rel="stylesheet" href="assets/css/all.min.css">
	<!-- bootstrap -->
	<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
	<!-- owl carousel -->
	<link rel="stylesheet" href="assets/css/owl.carousel.css">
	<!-- magnific popup -->
	<link rel="stylesheet" href="assets/css/magnific-popup.css">
	<!-- animate css -->
	<link rel="stylesheet" href="assets/css/animate.css">
	<!-- mean menu css -->
	<link rel="stylesheet" href="assets/css/meanmenu.min.css">
	<!-- main style -->
	<link rel="stylesheet" href="assets/css/main.css">
	<!-- responsive -->
	<link rel="stylesheet" href="assets/css/responsive.css">

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
    header("Location: login.php");
    exit;
}

// Manejo del cierre de sesión
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}

// Conexión a la base de datos
conectar_BD(); 

// Obtenemos el ID de usuario de la sesión
$idUser = $_SESSION['user_id'];

// Seleccionamos los productos asociados a este usuario
$consulta = "SELECT idProducto, Nombre, Descripcion, precio, CantidadEnStock FROM Producto WHERE Usuario_ID = $idUser";
$resultado = ejecuta_SQL($consulta);

// Verificamos si hay productos asociados al usuario
if ($resultado && $resultado->rowCount() > 0) {
    echo "<table BORDER='0' cellspacing='1' cellpadding='1' width='80%' align='center'>
            <tr><th bgcolor='black'><font color='white' face='arial, helvetica'>Nombre</font></th>
                <th bgcolor='black'><font color='white' face='arial, helvetica'>Descripción</font></th>
                <th bgcolor='black'><font color='white' face='arial, helvetica'>Precio</font></th>
                <th bgcolor='black'><font color='white' face='arial, helvetica'>Cantidad en Stock</font></th>
                <th bgcolor='black'><font color='white' face='arial, helvetica'>Operaciones</font></th>
            </tr>";
    foreach ($resultado as $row) {    
        // Guardamos los valores en variables
        $numProducto = $row['idProducto'];
        $nombreProducto = $row['Nombre'];
        $descripcion = $row['Descripcion'];
        $precio = $row['precio'];
        $cantidad = $row['CantidadEnStock'];
        // Imprimimos los datos en la tabla
        echo "<tr>
                <td align='center'>$nombreProducto</td>
                <td align='left'>&nbsp;&nbsp;$descripcion</td>
                <td align='left'>&nbsp;&nbsp;$precio</td>
                <td align='center'>$cantidad</td>
                <td align='center'>" . boton_ficticio("Ver", "producto.php?num_producto=$numProducto"). boton_peligroso("Eliminar", "borrar.php?num_producto=$numProducto")."</td> ";
    }
    echo "</table><br><center>";
    // Botón para agregar un nuevo producto
    echo boton_ficticio('Nuevo producto','AnadirProducto.php');
    echo '<form method="post" action="">
        <center>
            <button type="submit" name="logout">Logout</button>
        </center>
    </form>';
    echo "</center>";
} else { // No hay ningún producto asociado al usuario
    echo "<br><br><center><h3>No hay productos que mostrar</h3><br><br>";
    echo boton_ficticio('Nuevo producto','AnadirProducto.php');
    echo '<form method="post" action="">
        <center>
            <button type="submit" name="logout">Logout</button>
        </center>
    </form>';
    echo "</center>";
}
?>

</div>

</body>
