<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Responsive Bootstrap4 Shop Template, Created by Imran Hossain from https://imransdesign.com/">
    <link rel="shortcut icon" type="image/png" href="assets/img/logo.png">
    <title>Inventario</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
</head>
<body style="background: orange;">
    <!-- Cabecera fija -->
    <CENTER><h1>Stocker</h1></CENTER>
    <!-- Buscador -->
    <center>
        <form>
            <input type="text" id="searchInput" placeholder="Buscar productos..." onkeyup="filterTable()">
        </form>
    </center>
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

// Manejo del formulario de producto
if (isset($_POST['nombre']) && isset($_POST['descr']) && isset($_POST['precio']) && isset($_POST['cantidad']) && isset($_FILES['imagen'])) {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descr'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];
    $num_producto = isset($_POST['num_producto']) ? $_POST['num_producto'] : null;

    // Manejo de la imagen
    $urlImagen = null;
    if ($_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['imagen']['tmp_name'];
        $fileName = $_FILES['imagen']['name'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Nuevos nombres de archivo para evitar conflictos
        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;

        // Directorio de carga
        $uploadFileDir = 'img/';
        $dest_path = $uploadFileDir . $newFileName;

        // Mover el archivo subido a la ubicación deseada
        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $urlImagen = $dest_path;
        } else {
            echo 'Error al mover el archivo cargado al directorio de destino.';
            exit;
        }
    } else if ($num_producto) {
        // Obtener la URL de la imagen existente si no se ha cargado una nueva
        $consulta_imagen = "SELECT UrlImagen FROM Producto WHERE idProducto = $num_producto";
        $resultado_imagen = ejecuta_SQL($consulta_imagen);
        if ($resultado_imagen && $resultado_imagen->rowCount() > 0) {
            $fila_imagen = $resultado_imagen->fetch(PDO::FETCH_ASSOC);
            $urlImagen = $fila_imagen['UrlImagen'];
        }
    } else {
        echo 'Error al cargar la imagen.';
        exit;
    }

    if ($num_producto) {
        // Actualizar producto
        $consulta_producto = "UPDATE Producto SET Nombre='$nombre', Descripcion='$descripcion', Precio='$precio', CantidadEnStock='$cantidad', UrlImagen='$urlImagen' WHERE idProducto='$num_producto'";
    } else {
        // Insertar nuevo producto
        $consulta_producto = "INSERT INTO Producto (Nombre, Descripcion, Precio, CantidadEnStock, UrlImagen, Usuario_ID) VALUES ('$nombre','$descripcion','$precio','$cantidad', '$urlImagen', $idUser)";
    }
    $resultado_producto = ejecuta_SQL($consulta_producto);

    // Redirigir a la página del producto actual
    header("Location: inicio.php");
    exit();
}

if (isset($_POST['descripcionMensaje']) && isset($_POST['cantidadMensaje'])) {
    // Recuperar los valores del formulario
    $descripcionMensaje = $_POST['descripcionMensaje'];
    $cantidadMensaje = $_POST['cantidadMensaje'];

    // Consulta SQL para insertar un nuevo mensaje
    $consulta_mensaje = "INSERT INTO Mensajes (DescripcionMensaje, Cantidad, Usuario_ID) VALUES ('$descripcionMensaje', '$cantidadMensaje', '$idUser')";

    // Ejecutar la consulta SQL
    $resultado_mensaje = ejecuta_SQL($consulta_mensaje);

    // Verificar si la consulta se ejecutó con éxito
    if ($resultado_mensaje) {
        // Redirigir a la página de inicio
        header("Location: inicio.php");
        exit();
    } else {
        // Mostrar un mensaje de error si la consulta falla
        echo "Error al guardar el mensaje en la base de datos.";
    }
}


// Si se envía una solicitud de eliminación de producto
if (isset($_GET['delete'])) {
    $num_producto = $_GET['delete'];

    // Eliminamos la fila en la tabla Producto
    $consulta_producto = "DELETE FROM Producto WHERE idProducto = $num_producto";
    $resultado_producto = ejecuta_SQL($consulta_producto);

    // Redirigir a la página de inicio
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Si se envía una solicitud de eliminación de mensaje
if (isset($_GET['deleteMessage'])) {
    $mensajeId = $_GET['deleteMessage'];

    // Eliminamos la fila en la tabla Mensajes
    $consulta_mensaje = "DELETE FROM Mensajes WHERE idMensaje = $mensajeId";
    $resultado_mensaje = ejecuta_SQL($consulta_mensaje);

    // Redirigir a la página de inicio
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Seleccionamos los productos asociados a este usuario
$consulta = "SELECT idProducto, Nombre, Descripcion, Precio, CantidadEnStock, UrlImagen FROM Producto WHERE Usuario_ID = $idUser";
$resultado = ejecuta_SQL($consulta);

// Verificamos si hay productos asociados al usuario
if ($resultado && $resultado->rowCount() > 0) {
    echo "<table id='productTable' BORDER='0' cellspacing='1' cellpadding='1' width='80%' align='center'>
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
                $precio = $row['Precio'];
                $cantidad = $row['CantidadEnStock'];
                $urlImagen = $row['UrlImagen'];
                // Imprimimos los datos en la tabla
                echo "<tr class='productRow'>
                        <td align='center' class='productName'>$nombreProducto</td>
                        <td align='left' class='productDescription'>&nbsp;&nbsp;$descripcion</td>
                        <td align='left' class='productPrice'>&nbsp;&nbsp;$precio</td>
                        <td align='center'>$cantidad</td>
                        <td align='center'>
                            <button onclick='showEditForm($numProducto, \"$nombreProducto\", \"$descripcion\", \"$precio\", \"$cantidad\", \"$urlImagen\")' class='btn btn-primary'>Editar</button>
                            <a href='producto.php?num_producto=$numProducto' class='btn btn-info'>Ver</a>
                            <a href='?delete=$numProducto' class='btn btn-danger'>Eliminar</a>
                        </td>";
            }
    echo "</table><br><center>";
    // Botón para agregar un nuevo producto
    echo "<button onclick='showAddForm()' class='btn btn-success'>Nuevo producto</button>";
    echo '<form method="post" action="">
        <center>
            <button type="submit" name="logout" class="btn btn-secondary">Logout</button>
        </center>
    </form><br><br>';
    echo "</center>";
} else { // No hay ningún producto asociado al usuario
    echo "<br><br><center><h3>No hay productos que mostrar</h3><br><br>";
    echo "<button onclick='showAddForm()' class='btn btn-success'>Nuevo producto</button>";
    echo '<form method="post" action="">
        <center>
            <button type="submit" name="logout" class="btn btn-secondary">Logout</button>
        </center>
    </form>';
    echo "</center>";
}

// Seleccionamos los mensajes asociados a este usuario
$consulta = "SELECT idMensaje, FechaHora, DescripcionMensaje, Cantidad FROM Mensajes WHERE Usuario_ID = $idUser";
$resultado_mensajes = ejecuta_SQL($consulta);

// Verificamos si hay mensajes asociados al usuario
if ($resultado_mensajes && $resultado_mensajes->rowCount() > 0) {
    echo "<table id='messageTable' BORDER='0' cellspacing='1' cellpadding='1' width='80%' align='center'>
            <tr><th bgcolor='black'><font color='white' face='arial, helvetica'>Fecha y Hora</font></th>
                <th bgcolor='black'><font color='white' face='arial, helvetica'>Descripción</font></th>
                <th bgcolor='black'><font color='white' face='arial, helvetica'>Cantidad</font></th>
                <th bgcolor='black'><font color='white' face='arial, helvetica'>Operaciones</font></th>
            </tr>";
    foreach ($resultado_mensajes as $row) {    
        // Guardamos los valores en variables
        $mensajeId = $row['idMensaje'];
        $fechaHora = $row['FechaHora'];
        $descripcionMensaje = $row['DescripcionMensaje'];
        $cantidadMensaje = $row['Cantidad'];

        // Imprimimos los datos en la tabla
        echo "<tr class='messageRow'>
                <td align='center' class='messageDate'>$fechaHora</td>
                <td align='left' class='messageDescription'>&nbsp;&nbsp;$descripcionMensaje</td>
                <td align='left' class='messageQuantity'>&nbsp;&nbsp;$cantidadMensaje</td>
                <td align='center'>
                    <button onclick='showEditMessageForm($mensajeId, \"$descripcionMensaje\", \"$cantidadMensaje\")' class='btn btn-primary'>Editar</button>
                    <a href='mensaje.php?mensaje_id=$mensajeId' class='btn btn-info'>Ver</a>
                    <a href='?deleteMessage=$mensajeId' class='btn btn-danger'>Eliminar</a>
                </td>";
    }
    echo "</table><br><center>";
    // Botón para agregar un nuevo mensaje
    echo "<button onclick='showAddMessageForm()' class='btn btn-success'>Nuevo mensaje</button>";
    echo "</center>";
} else { // No hay ningún mensaje asociado al usuario
    echo "<br><br><center><h3>No hay mensajes que mostrar</h3><br><br>";
    echo "<button onclick='showAddMessageForm()' class='btn btn-success'>Nuevo mensaje</button>";
    echo "</center>";
}
?>

    </div>

    <!-- Formulario de Edición y Adición de Productos -->
    <br>
    <div id="formContainer" style="display:none;">
    <center><h3 id="formTitle">Nuevo Producto</h3></center>
    <form id="productForm" method="post" action="" enctype="multipart/form-data">
        <input type="hidden" name="num_producto" id="formNumProducto">
        <table align='center'>
            <tr>
                <td>Nombre:</td>
                <td><input type='text' name='nombre' id='formNombre' size='20' maxlength='30'></td>
            </tr>
            <tr>
                <td>Descripción:</td>
                <td><input type='text' name='descr' id='formDescripcion' size='12' maxlength='20'></td>
            </tr>
            <tr>
                <td>Precio:</td>
                <td><input type='text' name='precio' id='formPrecio' size='12' maxlength='12'></td>
            </tr>
            <tr>
                <td>Cantidad:</td>
                <td><input type='text' name='cantidad' id='formCantidad' size='20' maxlength='30'></td>
            </tr>
            <tr>
                <td>Imagen:</td>
                <td><input type='file' name='imagen' id='formImagen'></td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <input type="submit" value="Guardar Cambios" class="btn btn-primary">
                </td>
            </tr>
        </table>
    </form>
</div>

<!-- Formulario de Edición y Adición de Mensajes -->
<br>
<div id="messageFormContainer" style="display:none;">
    <center><h3 id="messageFormTitle">Nuevo Mensaje</h3></center>
    <form id="messageForm" method="post" action="">
        <input type="hidden" name="mensajeId" id="messageFormId">
        <table align='center'>
            <tr>
                <td>Descripción del Mensaje:</td>
                <td><input type='text' name='descripcionMensaje' id='messageFormDescripcion' size='20' maxlength='200'></td>
            </tr>
            <tr>
                <td>Cantidad del Mensaje:</td>
                <td><input type='text' name='cantidadMensaje' id='messageFormCantidad' size='12' maxlength='12'></td>
            </tr>
            <tr>
                <td>Nombre del Producto:</td>
                <td><input type='text' name='nombreProductoMensaje' id='messageFormNombreProducto' size='12' maxlength='100'></td>
            </tr>
            <tr>
                <td>Descripción del Producto:</td>
                <td><input type='text' name='descripcionProductoMensaje' id='messageFormDescripcionProducto' size='12' maxlength='200'></td>
            </tr>
            <tr>
                <td>Precio del Producto:</td>
                <td><input type='text' name='precioProductoMensaje' id='messageFormPrecioProducto' size='12' maxlength='12'></td>
            </tr>
            <tr>
                <td>Cantidad del Producto:</td>
                <td><input type='text' name='cantidadProductoMensaje' id='messageFormCantidadProducto' size='12' maxlength='12'></td>
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
