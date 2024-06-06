<?php
// save_payment.php

include "funciones.php";
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

// Obtener el ID de usuario de la sesión
$idUsuario = $_SESSION['user_id'];

// Obtener los detalles del pago de la solicitud POST
$data = json_decode(file_get_contents('php://input'), true);

// Obtener los detalles del pago
$idProducto = 1; // Debes ajustar esto según el producto que se esté comprando
$cantidad = $data['purchase_units'][0]['amount']['value'];
$detalles = json_encode($data);
$tarjeta = substr($data['payer']['funding_instruments'][0]['credit_card']['number'], -4); // Obtener los últimos 4 dígitos de la tarjeta

// Insertar en la base de datos
conectar_BD();
$sql = "INSERT INTO Pagos (idUsuario, idProducto, cantidad, detalles, tarjeta) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => $conn->error]);
    exit;
}

$stmt->bind_param("iidss", $idUsuario, $idProducto, $cantidad, $detalles, $tarjeta);

if ($stmt->execute()) {
    // Redirigir a login.php después de un pago exitoso
    header("Location: login.php");
    exit;
} else {
    echo json_encode(['status' => 'error', 'message' => $stmt->error]);
    exit;
}

$stmt->close();
$conn->close();
?>
