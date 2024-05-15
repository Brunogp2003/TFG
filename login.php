<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Form</title>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
<link rel="stylesheet" href="./help-documentation/css/main.css">
<style>
/* Custom CSS styles can go here */
</style>
</head>
<body>

<div class="main">
  <div class="container">
    <div class="middle">
      <div id="login">
        <img src="assets/img/logo.png" alt="">
        <form method="post">
          <fieldset class="clearfix">
            <p><span class="fa fa-user"></span><input type="text" name="nombre" placeholder="Username" required></p>
            <p><span class="fa fa-lock"></span><input type="password" name="password" placeholder="Password" required></p>
            <div>
              <span style="width:48%; text-align:left; display: inline-block;"><a id="nocuenta" class="small-text" href="#">No tengo cuenta</a></span>
              <span style="width:50%; text-align:right; display: inline-block;"><input type="submit" name="login" value="Iniciar sesion"></span>
            </div>
          </fieldset>
          <div class="clearfix"></div>
        </form>

        <div class="clearfix"></div>
      </div> <!-- end login -->
      <div id="register" style="display:none;">
        <img src="assets/img/logo.png" alt="">
        <form method="post">
          <fieldset class="clearfix">
            <p><span class="fa fa-user"></span><input type="text" name="nombre" placeholder="Username" required></p>
            <p><span class="fa fa-envelope"></span><input type="email" name="email" placeholder="Email" required></p>
            <p><span class="fa fa-lock"></span><input type="password" name="password" placeholder="Password" required></p>
            <div>
              <span style="width:48%; text-align:left; display: inline-block;"><a id="cuenta" class="small-text" href="#">Tengo una cuenta</a></span>
              <span style="width:50%; text-align:right; display: inline-block;"><input type="submit" name="register" value="Registrarse"></span>
            </div>
          </fieldset>
          <div class="clearfix"></div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php
require("funciones.php");

// Comprobación y manejo del inicio de sesión
if (isset($_POST['login'])) {
  $nombre = trim($_POST['nombre']);
  $passw = trim($_POST['password']);
  conectar_BD();
  if (($nombre == '') or ($passw == '')) {
      die ("<BR><BR><center>El nombre/password no deben ser vacíos.</center>");
  }
  $consulta = "SELECT idUsuario, Nombre, Contrasenia FROM Usuario WHERE Nombre='$nombre'";
  $resultado = ejecuta_SQL($consulta);
  if ($resultado->rowCount() > 0) {
      $matriz = $resultado->fetchAll();
      foreach ($matriz as $myrow) {    
        // Guarda los valores de myrow con variables
        list($idUser, $nombre, $password) = $myrow;
      }
      if (password_verify($passw, $password)) {
        session_start();
        $host = $_SERVER['HTTP_HOST'];
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = "inicio.php"; // Pasar el ID del usuario en la URL
        header("Location: http://$host$uri/$extra");
        exit();
    
      } else {
          echo "<BR><BR>El usuario o la contraseña no son correctos<br><br>";
      }
  } else {
      echo "<BR><BR>El usuario o la contraseña no son correctos<br><br>";
  }
}


// Comprobación y manejo del registro de usuario
if (isset($_POST['register'])) {
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['email']);
    $passw = trim($_POST['password']);
    $hass = password_hash($passw, PASSWORD_BCRYPT);
    conectar_BD();
    if (($nombre == '') or ($correo == '') or ($passw == '')) {
        die ("<BR><BR><center>El nombre/email/password no deben ser vacíos. Elija otro.</center>");
    }
    $consulta = "SELECT Nombre, Correo FROM Usuario WHERE Nombre='$nombre' OR Correo='$correo'";
    $resultado = ejecuta_SQL($consulta);
    if ($resultado->rowCount() > 0) {
        echo "<BR><BR><center>El usuario ya se encuentra registrado. Elija otro.</center>";
    } else {
        $consulta = "INSERT INTO Usuario (idUsuario,Nombre, Correo, Contrasenia) VALUES ('$idUser','$nombre', '$correo', '$hass')";
        $resultado = ejecuta_SQL($consulta);
        session_start();
        $_SESSION['num_user'] = $nombre; // Guarda el nombre de usuario en la sesión    
        $host = $_SERVER['HTTP_HOST'];
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'inicio.php';
        header("Location: http://$host$uri/$extra");
        exit();    
    }
}
?>

<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="script.js"></script>
</body>
</html>
