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

imprimir_cabecera();
// Conectamos base de datos
conectar_BD(); 
// Seleccionamos el login y la contraseña de los usuarios donde el num_usuario es el mismo que el de la sesión
$consulta = "SELECT Nombre, Contrasenia FROM Usuario WHERE Nombre='" . $_SESSION['num_user'] . "'";
// Almacenamos la info de la consulta
$resultado = ejecuta_SQL($consulta);
// Si obtiene más de una fila
if ($resultado->rowCount() > 0) {
    // Seleccionamos todo de la tabla Producto
    $consulta = "SELECT Nombre, Descripcion, precio, CantidadEnStock, Usuario_ID FROM Producto";
    // Obtenemos lo que ejecuta la consulta    
    $resultado = ejecuta_SQL($consulta);
    // Lo metemos en un array
    $matriz = $resultado->fetchAll();
    echo "<br><TABLE BORDER='0' cellspacing='1' cellpadding='1' width='80%' align='center'>
            <TR><th bgcolor='black'><FONT color='white' face='arial, helvetica'>Nombre</FONT></th>
                <th bgcolor='black'><FONT color='white' face='arial, helvetica'>Descripción</FONT></th>
                <th bgcolor='black'><FONT color='white' face='arial, helvetica'>Precio</FONT></th>
                <th bgcolor='black'><FONT color='white' face='arial, helvetica'>Cantidad en Stock</FONT></th>
            </TR>";
    foreach ($matriz as $myrow) {    
        // Guarda los valores de myrow con variables
        list($nombre, $Descripcion, $precio, $CantidadEnStock, $Usuario_ID) = $myrow;
        // Imprime los datos en una tabla
        echo "<TR>
                <TD align='center'>$nombre</TD>
                <TD align='left'>&nbsp;&nbsp;$Descripcion</TD>
                <TD align='left'>&nbsp;&nbsp;$precio</TD>
                <TD align='center'>$CantidadEnStock</TD>
              </TR>";       
    }
    echo "</table><BR><CENTER>";
    // Botón que pone nuevo mensaje y te lleva a responder.php
    echo boton_ficticio('Nuevo mensaje','responder.php');
    echo "</CENTER>";
} else { // No hay ningún mensaje
    echo "<br><br><center><h3>No hay mensajes que mostrar</h3><br><br><a href='index.php'>Vuelva a Intentarlo</a></center>";
}

imprimir_footer();
?>
