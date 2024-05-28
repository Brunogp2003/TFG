<?php

require("funciones.php");
session_start();

// Controlamos que la sesión sigue activa
if (!isset($_SESSION['user_id'])) {
    $host  = $_SERVER['HTTP_HOST'];
    $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $extra = 'inicio.php';
    header("Location: http://$host$uri/$extra");  
}

conectar_BD(); 

if(isset($_GET['num_producto'])) {
    $num_producto = $_GET['num_producto'];
    
    // Luego, eliminamos la fila en la tabla Producto
    $consulta_producto = "DELETE FROM Producto WHERE idProducto = $num_producto";
    $resultado_producto = ejecuta_SQL($consulta_producto);

    // Aquí obtén el ID del usuario de la sesión para usarlo en la redirección
    $idUser = $_SESSION['user_id'];

    // Redirige a la página de inicio con el ID de usuario en la URL
    $host = $_SERVER['HTTP_HOST'];
    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $extra = "inicio.php?num_user=$idUser"; // Pasar el ID del usuario en la URL
    header("Location: http://$host$uri/$extra");
    exit();
}

imprimir_footer();
?>
