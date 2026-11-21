<?php
require_once __DIR__ . '/../config/database.php';

class Producto
{
    private PDO $conexion;

    public function __construct()
    {
        $this->conexion = conectarDB();
    }

    public function obtenerTodos(): array
    {
        $sql = 'SELECT id_producto, nombre, descripcion, precio_compra, precio_venta, existencia
                FROM productos ORDER BY id_producto ASC';
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function obtenerPorId(int $id_producto): array|false
    {
        $sql = 'SELECT id_producto, nombre, descripcion, precio_compra, precio_venta, existencia
                FROM productos WHERE id_producto = :id_producto';
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':id_producto', $id_producto, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
}
