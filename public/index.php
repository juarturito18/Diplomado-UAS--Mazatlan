<?php
/** Front Controller: todas las solicitudes de la API pasan por aquí. */
ini_set('display_errors', '0'); // No mostrar detalles internos en respuestas JSON.
error_reporting(E_ALL);

require_once __DIR__ . '/../controllers/ProductoController.php';
$routes = require __DIR__ . '/../routes/api.php';

function errorJSON(string $mensaje, int $codigo): void
{
    http_response_code($codigo);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['error' => $mensaje], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

try {
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    // Permite probar sin mod_rewrite: index.php?ruta=/productos/abc
    $uri = isset($_GET['ruta']) ? (string) $_GET['ruta'] : parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    $basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/index.php'));
    if ($basePath === '/') {
        $basePath = '';
    }
    // Eliminar únicamente el prefijo real, no todas sus apariciones.
    if (!isset($_GET['ruta']) && $basePath !== '' && ($uri === $basePath || str_starts_with($uri, $basePath . '/'))) {
        $uri = substr($uri, strlen($basePath));
    }
    $uri = $uri ?: '/';
    if ($uri !== '/') {
        $uri = rtrim($uri, '/');
    }

    // Ruta dinámica: GET /productos/2
    if ($method === 'GET' && preg_match('#^/productos/([^/]+)$#', $uri, $matches)) {
        (new ProductoController())->show($matches[1]);
    }

    // Ruta estática: GET /productos
    if (isset($routes[$method][$uri])) {
        $route = $routes[$method][$uri];
        $controller = new $route['controller']();
        $controller->{$route['action']}();
    }

    if ($uri === '/productos' || preg_match('#^/productos/[^/]+$#', $uri)) {
        header('Allow: GET');
        errorJSON('Método HTTP no permitido', 405);
    }
    errorJSON('Endpoint no encontrado', 404);
} catch (Throwable $e) {
    http_response_code(500);

    header('Content-Type: application/json; charset=utf-8');

    echo json_encode([
        'error' => 'Error interno del servidor',
        'detalle' => $e->getMessage(),
        'archivo' => $e->getFile(),
        'linea' => $e->getLine()
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

    exit;
    
}
