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

    // Si se envía un formulario de adición o edición
    if (isset($_POST['nombre']) && isset($_POST['descr']) && isset($_POST['precio']) && isset($_POST['cantidad'])) {
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descr'];
        $precio = $_POST['precio'];
        $cantidad = $_POST['cantidad'];
        $num_producto = isset($_POST['num_producto']) ? $_POST['num_producto'] : null;

        if ($num_producto) {
            // Actualizar producto
            $consulta_producto = "UPDATE Producto SET Nombre='$nombre', Descripcion='$descripcion', Precio='$precio', CantidadEnStock='$cantidad' WHERE idProducto='$num_producto'";
        } else {
            // Insertar nuevo producto
            $consulta_producto = "INSERT INTO Producto (Nombre, Descripcion, Precio, CantidadEnStock, Usuario_ID) VALUES ('$nombre','$descripcion','$precio','$cantidad', $idUser)";
        }
        $resultado_producto = ejecuta_SQL($consulta_producto);

        // Redirigir a la página de inicio
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    // Si se envía una solicitud de eliminación
    if (isset($_GET['delete'])) {
        $num_producto = $_GET['delete'];

        // Luego, eliminamos la fila en la tabla Producto
        $consulta_producto = "DELETE FROM Producto WHERE idProducto = $num_producto";
        $resultado_producto = ejecuta_SQL($consulta_producto);

        // Redirigir a la página de inicio
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    // Seleccionamos los productos asociados a este usuario
    $consulta = "SELECT idProducto, Nombre, Descripcion, precio, CantidadEnStock FROM Producto WHERE Usuario_ID = $idUser";
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
                    $precio = $row['precio'];
                    $cantidad = $row['CantidadEnStock'];
                    // Imprimimos los datos en la tabla
                    echo "<tr class='productRow'>
                            <td align='center' class='productName'>$nombreProducto</td>
                            <td align='left' class='productDescription'>&nbsp;&nbsp;$descripcion</td>
                            <td align='left' class='productPrice'>&nbsp;&nbsp;$precio</td>
                            <td align='center'>$cantidad</td>
                            <td align='center'>
                                <button onclick='showEditForm($numProducto, \"$nombreProducto\", \"$descripcion\", \"$precio\", \"$cantidad\")' class='btn btn-primary'>Editar</button>
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
        </form>';
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
    ?>
    </div>

    <!-- Formulario de Edición y Adición -->
    <br>
    <div id="formContainer" style="display:none;">
        <center><h3 id="formTitle">Nuevo Producto</h3></CENTER>
        <form id="productForm" method="post" action="">
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
