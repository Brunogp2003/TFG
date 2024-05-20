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

// Seleccionamos los productos asociados a este usuario
$consulta = "SELECT idProducto, Nombre, Descripcion, precio, CantidadEnStock FROM Producto WHERE idProducto = $numProducto AND Usuario_ID = $idUser";
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
                <td align='left' style='padding-left: 10px;'>$descripcion</td>
                <td align='left' style='padding-left: 10px;'>$precio</td>
                <td align='center'>$cantidad</td>
                <td align='center'>" . boton_ficticio("Ver", "producto.php?num_producto=$numProducto"). boton_peligroso("Eliminar", "borrar.php?num_producto=$numProducto")."</td>";
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
}

// Generar y mostrar el código de barras para el producto actual
$generator = new barcode_generator();
header('Content-Type: image/svg+xml');
$svg = $generator->render_svg("qr", "producto.php?num_producto=$numProducto","");
echo $svg;

?>
