<?php
include "lib/barcode.php";
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

conectar_BD();

// Obtenemos el ID de usuario de la sesión
$idUser = $_SESSION['user_id'];

// Obtener el número de producto de la sesión
if(isset($_GET['num_producto'])){
    $numProducto = $_GET['num_producto'];
}else{
    // Manejar el caso en que no se recibe un número de producto
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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Responsive Bootstrap4 Shop Template, Created by Imran Hossain from https://imransdesign.com/">
    <link rel="shortcut icon" type="image/png" href="assets/img/logo.png">
    <title>Producto Detalle</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <style>
        .product-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }
        .product-image {
            max-width: 300px;
            margin-bottom: 20px;
        }
        .product-details {
            text-align: center;
        }
        .product-price {
            font-size: 2em;
            color: green;
        }
    </style>
</head>
<body style="background: orange;">
    <div class="container">
        <h1 class="text-center">Detalles del Producto</h1>
        <div class="product-container">
            <?php if ($urlImagen): ?>
                <img src="<?php echo htmlspecialchars($urlImagen); ?>" alt="Imagen del Producto" class="product-image">
            <?php endif; ?>
            <div class="product-details">
                <h2><?php echo htmlspecialchars($nombreProducto); ?></h2>
                <p><?php echo htmlspecialchars($descripcion); ?></p>
                <p class="product-price"><?php echo htmlspecialchars($precio); ?> €</p>
                <p>Stock: <?php echo htmlspecialchars($cantidad); ?></p>
                <div>
                    <a href="editarProducto.php?num_producto=<?php echo $numProducto; ?>" class="btn btn-primary">Editar</a>
                    <a href="borrar.php?num_producto=<?php echo $numProducto; ?>" class="btn btn-danger">Eliminar</a>
                </div>
            </div>
            <div class="text-center">
                <?php echo $svg; ?>
            </div>
        </div>
        <div class="text-center">
            <form method="post" action="">
                <button type="submit" name="logout" class="btn btn-secondary">Logout</button>
            </form>
        </div>
    </div>
</body>
</html>
