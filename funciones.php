<?php

/* Fijamos las variables globales de la conexi�n al servidor MySQL.
   El nombre del servidor es el que admite por defecto el servidor
   local; el nombre del usuario y de la clave han sido dados de alta
   previamente en el servidor MySQL. */
      
   $DBHost="localhost";
   $DBUser="root";
   $DBPass="";
   $DB="inventary";
   $id_conexion=-1;
   //Boton para logout
   function boton_logut($caption,$url,$name)
   {
      if (isset($_POST['logout'])) { //Si se le clica
         session_destroy(); //Destruye la sesion
         header("Location: index.html"); //Manda para el index.html
         exit;
     }
      return "<a href = '$url' name= '$name' class='btn btn-primary'>$caption</A>";

   } 
   
   function boton_ficticio($caption,$url)
   {
      return "<a href = '$url' class='btn btn-primary'>$caption</A>";

   } 

   function boton_peligroso($caption,$url)
   {
      return "<a href = '$url' class='btn btn-danger'>$caption</A>";

   }
   //Sirve para conectar la base de datos
   function conectar_BD() 
   {
      global $DBHost, $DBUser, $DBPass, $DB, $id_conexion;

      try {
         $id_conexion = new PDO("mysql:host=" . $DBHost. ";dbname=" . $DB. ";charset=utf8", $DBUser, $DBPass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
         $id_conexion->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,  true);
         $id_conexion->setAttribute(PDO::NULL_TO_STRING, true);
      } 
      catch (PDOException $e) {
         die ("<p><H3>No se ha podido establecer la conexión.<P>Compruebe si está activado el 
         servidor de bases de datos MySQL.</H3></p>\n <p>Error: " . $e->getMessage() . "</p>\n");
      } 
   }
   //Para ejecutar las consultas
   function ejecuta_SQL($sql) 
   {
      global $id_conexion;

		$resultado=$id_conexion->query($sql);
		if (!$resultado){
			echo"<H3>No se ha podido ejecutar la consulta: <PRE>$sql</PRE><P><U> Errores</U>: </H3><PRE>";
			print_r($id_conexion->errorInfo());					
			die ("</PRE>");
		}
		return $resultado;
	} 

   function insert_id() {
      global $id_conexion;
      return $id_conexion->lastInsertId();
   }
?>