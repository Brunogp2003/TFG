-- Crear la tabla de Usuario
CREATE TABLE Usuario (
    ID INT PRIMARY KEY AUTO_INCREMENT,
    Nombre VARCHAR(50),
    Correo VARCHAR(100),
    Contrasenia VARCHAR(100)
);

-- Insertar algunos datos de ejemplo en la tabla de Usuario
INSERT INTO Usuario (Nombre, Correo, Contrasenia) VALUES
('Usuario1', 'usuario1@example.com', 'contraseña1'),
('Usuario2', 'usuario2@example.com', 'contraseña2');

-- Crear la tabla de Producto
CREATE TABLE Producto (
    ID INT PRIMARY KEY AUTO_INCREMENT,
    Nombre VARCHAR(100),
    Descripcion TEXT,
    Precio DECIMAL(10, 2),
    CantidadEnStock INT,
    Usuario_ID INT,
    FOREIGN KEY (Usuario_ID) REFERENCES Usuario(ID)
);

-- Insertar algunos datos de ejemplo en la tabla de Producto
INSERT INTO Producto (Nombre, Descripcion, Precio, CantidadEnStock, Usuario_ID) VALUES
('Producto1', 'Descripción del producto 1', 10.99, 100, 1),
('Producto2', 'Descripción del producto 2', 20.50, 50, 2);

-- Crear la tabla de Movimiento
CREATE TABLE Movimiento (
    ID INT PRIMARY KEY AUTO_INCREMENT,
    FechaHora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    TipoMovimiento ENUM('entrada', 'salida'),
    Cantidad INT,
    Producto_ID INT,
    Usuario_ID INT,
    FOREIGN KEY (Producto_ID) REFERENCES Producto(ID),
    FOREIGN KEY (Usuario_ID) REFERENCES Usuario(ID)
);

-- Insertar algunos datos de ejemplo en la tabla de Movimiento
INSERT INTO Movimiento (TipoMovimiento, Cantidad, Producto_ID, Usuario_ID) VALUES
('entrada', 10, 1, 1), -- Se añaden 10 unidades del Producto1 por el Usuario1
('salida', 5, 2, 2);   -- Se retiran 5 unidades del Producto2 por el Usuario2
