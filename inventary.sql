-- Crear la tabla de Usuario
CREATE TABLE Usuario (
    idUsuario INT PRIMARY KEY AUTO_INCREMENT,
    Nombre VARCHAR(50),
    Correo VARCHAR(100),
    Contrasenia VARCHAR(100),
    Rol VARCHAR(50),
    PlanAdquirido VARCHAR(50)
);

-- Insertar algunos datos de ejemplo en la tabla de Usuario
INSERT INTO Usuario (Nombre, Correo, Contrasenia, Rol, PlanAdquirido) VALUES
('paco', 'paco@gmail.com', 'paco','user','plan1'),
('bruno', 'bruno@gmail.com', 'bruno','admin','plan2');

-- Crear la tabla de Producto
CREATE TABLE Producto (
    idProducto INT PRIMARY KEY AUTO_INCREMENT,
    Nombre VARCHAR(100),
    Descripcion TEXT,
    Precio DECIMAL(10, 2),
    CantidadEnStock INT,
    UrlImagen VARCHAR(255),
    Usuario_ID INT,
    FOREIGN KEY (Usuario_ID) REFERENCES Usuario(idUsuario)
);

-- Insertar algunos datos de ejemplo en la tabla de Producto
INSERT INTO Producto (Nombre, Descripcion, Precio, CantidadEnStock,UrlImagen, Usuario_ID) VALUES
('Producto1', 'Descripción del producto 1', 10.99, 100,'http://example.com/images/producto1.jpg' ,1),
('Producto2', 'Descripción del producto 2', 20.50, 50,'http://example.com/images/producto1.jpg', 2);

-- Crear la tabla de Movimiento
CREATE TABLE Mensajes (
    idMensaje INT PRIMARY KEY AUTO_INCREMENT,
    FechaHora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    DescripcionMensaje VARCHAR(200),
    Cantidad INT,
    Producto_ID INT,
    Usuario_ID INT,
    FOREIGN KEY (Producto_ID) REFERENCES Producto(idProducto),
    FOREIGN KEY (Usuario_ID) REFERENCES Usuario(idUsuario)
);

-- Insertar algunos datos de ejemplo en la tabla de Movimiento
INSERT INTO Mensajes (DescripcionMensaje, Cantidad) VALUES
('paco', 10, 1, 1), -- Se añaden 10 unidades del Producto1 por el Usuario1
('salida', 5, 2, 2);   -- Se retiran 5 unidades del Producto2 por el Usuario2
