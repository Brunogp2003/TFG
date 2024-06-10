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

// Manejo del formulario de producto
if (isset($_POST['nombre']) && isset($_POST['descr']) && isset($_POST['precio']) && isset($_POST['cantidad'])) {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descr'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];
    $num_producto = isset($_POST['num_producto']) ? $_POST['num_producto'] : null;

    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($_FILES["imagen"]["name"]);
    move_uploaded_file($_FILES["imagen"]["tmp_name"], $targetFile);

    if ($num_producto) {
        // Actualizar producto
        $consulta_producto = "UPDATE Producto SET Nombre='$nombre', Descripcion='$descripcion', Precio='$precio', CantidadEnStock='$cantidad', UrlImagen='$targetFile' WHERE idProducto='$num_producto'";
    } else {
        // Insertar nuevo producto
        $consulta_producto = "INSERT INTO Producto (Nombre, Descripcion, Precio, CantidadEnStock, UrlImagen, Usuario_ID) VALUES ('$nombre','$descripcion','$precio','$cantidad', '$targetFile', $idUser)";
    }
    $resultado_producto = ejecuta_SQL($consulta_producto);

    // Redirigir a la página del producto actual
    header("Location: producto.php?num_producto=$numProducto");
    exit();
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
// Generar y mostrar el código de barras para el producto actual con la URL de la página actual
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
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <style>
        .product-container {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
    background: #fff;
    padding-top: 100px;
}

.product-content {
    display: flex;
    background: #fff;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 10px;
    max-width: 1200px; /* Ajustar el tamaño máximo del contenedor */
}

.product-image {
    flex: 1;
    max-width: 100%; /* Asegura que la imagen no exceda el tamaño del contenedor */
    margin-right: 20px;
}

.product-details {
    flex: 1;
    text-align: left;
}

.product-price {
    font-size: 2em;
    color: green;
}

.text-center {
    text-align: center;
}

.product-buttons {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-top: 10px;
}
    </style>
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

