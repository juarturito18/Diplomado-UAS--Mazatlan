<?php
require_once __DIR__ . '/../models/Producto.php';

class ProductoController
{
    private Producto $modelo;

    public function __construct()
    {
        $this->modelo = new Producto();
    }

    private function responderJSON(array $datos, int $codigo = 200): void
    {
        http_response_code($codigo);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($datos, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    public function index(): void
    {
        $productos = $this->modelo->obtenerTodos();
        $this->responderJSON([
            'mensaje' => 'Lista de productos',
            'total' => count($productos),
            'data' => $productos,
        ]);
    }

    public function show(string $id_producto): void
    {
        $id = filter_var($id_producto, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        if ($id === false) {
            $this->responderJSON(['error' => 'El parámetro id_producto no es válido'], 400);
        }
        $producto = $this->modelo->obtenerPorId($id);
        if ($producto === false) {
            $this->responderJSON(['error' => 'Producto no encontrado'], 404);
        }
        $this->responderJSON(['mensaje' => 'Producto encontrado', 'data' => $producto]);
    }
}
