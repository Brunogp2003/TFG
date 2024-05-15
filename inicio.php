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
// Mantenemos la sesión
session_start();

// Si la sesión no existe te vuelve a enviar al index que es el login
if (!isset($_SESSION['num_user'])) {
    $host  = $_SERVER['HTTP_HOST'];
    $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $extra = 'login.php';
    header("Location: http://$host$uri/$extra");
    exit; // Importante: terminamos el script después de redirigir
}
$idUser =$_SESSION['user_id'];
// Conectamos base de datos
conectar_BD(); 
// Seleccionamos el login y la contraseña de los usuarios donde el num_usuario es el mismo que el de la sesión
$consulta = "SELECT * FROM Usuario WHERE idUsuario = $idUser  ";
// Almacenamos la info de la consulta
$resultado = ejecuta_SQL($consulta);
foreach ($resultado as $myrow) {    
    // Guarda los valores de myrow con variables
    $idUser = $myrow[0];
}
// Si obtiene más de una fila
if ($resultado->rowCount() > 0) {
    // Seleccionamos todo de la tabla Producto
    $consulta = "SELECT Producto.idProducto, Producto.Nombre, Producto.Descripcion, Producto.precio, Producto.CantidadEnStock, Producto.Usuario_ID FROM Producto, Usuario where Usuario_ID = $idUser ";
    // Obtenemos lo que ejecuta la consulta    
    $resultado = ejecuta_SQL($consulta);
    // Lo metemos en un array
    $matriz = $resultado->fetchAll();
    echo "<TABLE BORDER='0' cellspacing='1' cellpadding='1' width='80%' align='center'>
            <TR><th bgcolor='black'><FONT color='white' face='arial, helvetica'>Nombre</FONT></th>
                <th bgcolor='black'><FONT color='white' face='arial, helvetica'>Descripción</FONT></th>
                <th bgcolor='black'><FONT color='white' face='arial, helvetica'>Precio</FONT></th>
                <th bgcolor='black'><FONT color='white' face='arial, helvetica'>Cantidad en Stock</FONT></th>
                <th bgcolor='black'><FONT color='white' face='arial, helvetica'>Operaciones</FONT></th>
            </TR>";
    foreach ($matriz as $myrow) {    
        // Guarda los valores de myrow con variables
        list($numProducto, $nombreProducto, $Descripcion, $precio, $CantidadEnStock, $Usuario_ID) = $myrow;
        // Imprime los datos en una tabla
        echo "<TR>
                <TD align='center'>$nombreProducto</TD>
                <TD align='left'>&nbsp;&nbsp;$Descripcion</TD>
                <TD align='left'>&nbsp;&nbsp;$precio</TD>
                <TD align='center'>$CantidadEnStock</TD>
                <TD align='center'>" . boton_ficticio("Ver", "producto.php?num_producto=$numProducto"). boton_peligroso("Eliminar", "borrar.php?num_producto=$numProducto")."</TD> ";
    }
    echo "</table><BR><CENTER>";
    // Botón que pone nuevo mensaje y te lleva a responder.php
    echo boton_ficticio('Nuevo producto','AnadirProducto.php');boton_ficticio("Logout", "index.html") ;
    echo "</CENTER>";
} else { // No hay ningún mensaje
    echo "<br><br><center><h3>No hay mensajes que mostrar</h3><br><br><a href='index.php'>Vuelva a Intentarlo</a></center>";
}
imprimir_footer();
?>
</div>

</body>
