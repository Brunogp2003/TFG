<?php
include "lib/barcode.php";
require("funciones.php");
session_start();

// Controlamos que la sesión sigue activa
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

conectar_BD();

// Obtenemos el ID de usuario de la sesión
$idUser = $_SESSION['user_id'];

// Obtener el número de producto de la sesión
if(isset($_GET['num_producto'])){
    $numProducto = $_GET['num_producto'];
}else{
    echo "No se ha especificado un número de producto.";
    exit;
}

// Seleccionamos el producto asociado a este usuario
$consulta = "SELECT idProducto, Nombre, Descripcion, precio, CantidadEnStock, UrlImagen FROM Producto WHERE idProducto = $numProducto AND Usuario_ID = $idUser";
$resultado = ejecuta_SQL($consulta);

// Verificamos si hay productos asociados al usuario
if ($resultado && $resultado->rowCount() > 0) {
    $row = $resultado->fetch();
    // Guardamos los valores en variables
    $numProducto = $row['idProducto'];
    $nombreProducto = $row['Nombre'];
    $descripcion = $row['Descripcion'];
    $precio = $row['precio'];
    $cantidad = $row['CantidadEnStock'];
    $urlImagen = $row['UrlImagen'];
} else {
    echo "Producto no encontrado o no tiene permiso para verlo.";
    exit;
}

// Construir la URL de la página actual del producto
$currentUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
// Generar y mostrar el código QR para el producto actual con la URL de la página actual
$generator = new barcode_generator();
$svg = $generator->render_svg("qr", $currentUrl, "");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Producto</title>
    <!-- favicon -->
	<link rel="shortcut icon" type="image/png" href="assets/img/logo.png">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/responsive.css">

</head>
<body style ="background-color: orange;">
    <div class="container"><br><br>
        <h1 class="text-center">Detalles del Producto</h1><br><br><br><br>
        
            <div class="product-content">
                <div>
                    <?php if ($urlImagen): ?>
                        <img src="<?php echo htmlspecialchars($urlImagen); ?>" alt="Imagen del Producto" class="product-image">
                    <?php else: ?>
                        <img src="/mnt/data/image.png" alt="Imagen del Producto" class="product-image">
                    <?php endif; ?>
                </div>
                <div class="product-details">
                    <h2><?php echo htmlspecialchars($nombreProducto); ?></h2>
                    <p><?php echo htmlspecialchars($descripcion); ?></p>
                    <p class="product-price"><?php echo htmlspecialchars($precio); ?> €</p>
                    <p>Stock: <?php echo htmlspecialchars($cantidad); ?></p>
                    
                    <div class="text-center">
                        <?php echo $svg; ?>
                    </div>
                </div>
            
        </div>
        <div class="text-center">
            <a href="inicio.php" class="btn btn-secondary">Volver</a>
        </div>
    </div>
</body>
</html>

