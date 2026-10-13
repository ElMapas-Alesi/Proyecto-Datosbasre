drop database if exists libreria;
create database libreria;
use libreria;

create table categorias (
    id_categoria int auto_increment primary key,
    nombre varchar(100) not null,
    descripcion varchar(255)
);

create table proveedores (
    id_proveedor int auto_increment primary key,
    nombre varchar(100) not null,
    telefono int,
    email varchar(100),
    direccion varchar(255)
);

create table marcas (
    id_marca int auto_increment primary key,
    nombre varchar(100) not null,
    pais_origen varchar(100)
);

create table clientes (
    id_cliente int auto_increment primary key,
    nombre varchar(100) not null,
    apellido varchar(100) not null,
    email varchar(100),
    telefono int,
    direccion varchar(255)
);

create table empleados (
    id_empleado int auto_increment primary key,
    nombre varchar(100) not null,
    apellido varchar(100) not null,
    cargo varchar(100),
    fecha_contratacion date
);

create table sucursales (
    id_sucursal int auto_increment primary key,
    nombre varchar(100) not null,
    direccion varchar(255)
);

create table metodo_de_pagos (
    id_metodo int auto_increment primary key,
    nombre varchar(100) not null,
    descripcion varchar(255)
);

create table promociones (
    id_promocion int auto_increment primary key,
    nombre varchar(100) not null,
    porcentaje_descuento decimal(10.2),
    fecha_inicio date,
    fecha_fin date
);

create table productos (
    id_producto int auto_increment primary key,
    nombre varchar(100) not null,
    precio decimal(10,2) not null,
    stock int default 0,
    id_proveedor int,
    id_categoria int,
    id_marca int,
    foreign key (id_proveedor) references proveedores(id_proveedor),
    foreign key (id_categoria) references categorias(id_categoria),
    foreign key (id_marca) references marcas(id_marca)
);

create table ventas (
    id_venta int auto_increment primary key,
    fecha datetime not null,
    total decimal(10.2) not null,
    id_cliente int,
    id_empleado int,
    id_metodo int,
    foreign key (id_cliente) references clientes(id_cliente),
    foreign key (id_empleado) references empleados(id_empleado),
    foreign key (id_metodo) references metodo_de_pagos(id_metodo)
);

create table detalle_ventas (
    id_detalle int auto_increment primary key,
    cantidad int not null,
    precio_unitario decimal(10,2) not null,
    subtotal decimal(10,2) not null,
    id_venta int not null,
    id_producto int not null,
    foreign key (id_venta) references venta(id_venta),
    foreign key (id_producto) references productos(id_producto)
);

create table compra_proveedores (
    id_compra int auto_increment primary key,
    fecha datetime not null,
    total decimal(10,2) not null,
    id_proveedor int,
    id_empleado int,
    foreign key (id_proveedor) references proveedores(id_proveedor),
    foreign key (id_empleado)  references empleados(id_empleado)
);

create table detalle_compras (
    id_detalle int auto_increment primary key,
    cantidad int not null,
    costo_unitario decimal(10,2) not null,
    subtotal decimal(10,2) not null,
    id_compra int not null,
    id_producto int not null,
    foreign key (id_compra) references compra_proveedor(id_compra),
    foreign key (id_producto) references productos(id_producto)
);

create table inventario_movimientos (
    id_movimiento int auto_increment primary key,
    tipo_movimiento varchar(50) not null,
    cantidad int not null,
    motivo varchar(255),
    fecha datetime not null,
    id_producto int not null,
    id_empleado int,
    foreign key (id_producto) references productos(id_producto),
    foreign key (id_empleado) references empleados(id_empleado)
);

create table producto_promociones (
    id_producto_promocion int auto_increment primary key,
    id_producto int not null,
    id_promocion int not null,
    foreign key (id_producto) references productos(id_producto),
    foreign key (id_promocion) references promociones(id_promocion)
);