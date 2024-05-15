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
if (!isset($_SESSION['num_user'])) {
    $host  = $_SERVER['HTTP_HOST'];
    $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $extra = 'inicio.php';
    header("Location: http://$host$uri/$extra");  
}

conectar_BD(); 

if(isset($_GET['num_producto'])) {
    $num_producto = $_GET['num_producto'];
    
    //Añado un nuevo producto
    $consulta_movimiento = "DELETE FROM Movimiento WHERE Producto_ID = $num_producto";
    $resultado_movimiento = ejecuta_SQL($consulta_movimiento);

    // Luego, eliminamos la fila en la tabla Producto
    $consulta_producto = "DELETE FROM Producto WHERE idProducto = $num_producto";
    $resultado_producto = ejecuta_SQL($consulta_producto);

    // Aquí obtén el ID del usuario de la sesión para usarlo en la redirección
    $idUser = $_SESSION['user_id'];

    // Redirige a la página de inicio con el ID de usuario en la URL
    $host = $_SERVER['HTTP_HOST'];
    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $extra = "inicio.phpr"; // Pasar el ID del usuario en la URL
    header("Location: http://$host$uri/$extra");
    exit();
}

imprimir_footer();
?>
<div>
<BR><BR><center><h3>Nuevo Producto</h3></CENTER>
     <br><br><form name='form1' id='anadir' method='post' action='alta.php'>
     <table align='center'>
     <tr><td >Nombre    :</td>
         <td><input type='text' name='nombre' value='' size='20' maxlength='30'></td></tr>
     <tr><td >Descripción     :</td>
         <td><input type='text' name='login' value='' size='12' maxlength='20'></td></tr>
     <tr><td >Precio    :</td>
         <td><input type='password' name='password' value='' size='12' maxlength='12'></td></tr>
     <tr><td >Cantidad     :</td>
         <td><input type='text' name='email' value='' size='20' maxlength='30'></td></tr>
         <label for="imagen">Imagen:</label>
        <input type="file" name="imagen" id="imagen" accept="image/*"><br><br>

        <input type="submit" value="Añadir Producto">        
    </table>
    </form>
</div>
</div>