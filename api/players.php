<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../config/db.php';

$method = $_SERVER['REQUEST_METHOD'];
$input  = json_decode(file_get_contents('php://input'), true);

switch ($method) {

    // ── GET: listar todos o uno por code ──────────────────────────
    case 'GET':
        if (isset($_GET['code'])) {
            $stmt = $pdo->prepare("SELECT * FROM player WHERE code = ?");
            $stmt->execute([$_GET['code']]);
            $player = $stmt->fetch(PDO::FETCH_ASSOC);
            echo $player
                ? json_encode($player)
                : json_encode(['error' => 'Jugador no encontrado']);
        } else {
            $stmt = $pdo->query("SELECT code, name, dorsal, position FROM player ORDER BY code ASC");
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        }
        break;

    // ── POST: crear jugador ───────────────────────────────────────
    case 'POST':
        if (empty($input['code']) || empty($input['name']) || empty($input['dorsal']) || empty($input['position'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Todos los campos son obligatorios']);
            break;
        }
        $stmt = $pdo->prepare("INSERT INTO player (code, name, dorsal, position) VALUES (?, ?, ?, ?)");
        $stmt->execute([$input['code'], $input['name'], $input['dorsal'], $input['position']]);
        http_response_code(201);
        echo json_encode(['message' => 'Jugador creado correctamente']);
        break;

    // ── PUT: editar jugador ───────────────────────────────────────
    case 'PUT':
        if (empty($input['code'])) {
            http_response_code(400);
            echo json_encode(['error' => 'El campo code es obligatorio']);
            break;
        }
        $stmt = $pdo->prepare("UPDATE player SET name = ?, dorsal = ?, position = ? WHERE code = ?");
        $stmt->execute([$input['name'], $input['dorsal'], $input['position'], $input['code']]);
        echo json_encode(['message' => 'Jugador actualizado correctamente']);
        break;

    // ── DELETE: borrar jugador ────────────────────────────────────
    case 'DELETE':
        $code = $_GET['code'] ?? $input['code'] ?? null;
        if (!$code) {
            http_response_code(400);
            echo json_encode(['error' => 'Se requiere el code del jugador']);
            break;
        }
        $stmt = $pdo->prepare("DELETE FROM player WHERE code = ?");
        $stmt->execute([$code]);
        echo json_encode(['message' => 'Jugador eliminado correctamente']);
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Método no permitido']);
}

$pdo = null;