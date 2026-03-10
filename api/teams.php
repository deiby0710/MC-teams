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

try {

    switch ($method) {

        // ── GET ─────────────────────────────────────────────
        case 'GET':

            if (isset($_GET['id'])) {

                $stmt = $pdo->prepare("SELECT * FROM team WHERE id = ?");
                $stmt->execute([$_GET['id']]);
                $team = $stmt->fetch();

                if (!$team) {
                    http_response_code(404);
                    echo json_encode(['error' => 'Equipo no encontrado']);
                    break;
                }

                echo json_encode($team);

            } else {

                $stmt = $pdo->query("SELECT * FROM team ORDER BY id ASC");
                echo json_encode($stmt->fetchAll());
            }

            break;


        // ── POST ────────────────────────────────────────────
        case 'POST':

            if (
                empty($input['name']) ||
                empty($input['league']) ||
                empty($input['city']) ||
                empty($input['founded_year'])
            ) {
                http_response_code(400);
                echo json_encode(['error' => 'Todos los campos son obligatorios']);
                break;
            }

            $stmt = $pdo->prepare("
                INSERT INTO team (name, league, city, founded_year)
                VALUES (?, ?, ?, ?)
            ");

            $stmt->execute([
                $input['name'],
                $input['league'],
                $input['city'],
                $input['founded_year']
            ]);

            http_response_code(201);
            echo json_encode(['message' => 'Equipo creado correctamente']);

            break;


        // ── PUT ─────────────────────────────────────────────
        case 'PUT':

            if (empty($input['id'])) {
                http_response_code(400);
                echo json_encode(['error' => 'El id es obligatorio']);
                break;
            }

            $stmt = $pdo->prepare("
                UPDATE team
                SET name = ?, league = ?, city = ?, founded_year = ?
                WHERE id = ?
            ");

            $stmt->execute([
                $input['name'],
                $input['league'],
                $input['city'],
                $input['founded_year'],
                $input['id']
            ]);

            if ($stmt->rowCount() === 0) {
                http_response_code(404);
                echo json_encode(['error' => 'Equipo no encontrado']);
                break;
            }

            echo json_encode(['message' => 'Equipo actualizado correctamente']);

            break;


        // ── DELETE ──────────────────────────────────────────
        case 'DELETE':

            $id = $_GET['id'] ?? $input['id'] ?? null;

            if (!$id) {
                http_response_code(400);
                echo json_encode(['error' => 'Se requiere el id del equipo']);
                break;
            }

            $stmt = $pdo->prepare("DELETE FROM team WHERE id = ?");
            $stmt->execute([$id]);

            if ($stmt->rowCount() === 0) {
                http_response_code(404);
                echo json_encode(['error' => 'Equipo no encontrado']);
                break;
            }

            echo json_encode(['message' => 'Equipo eliminado correctamente']);

            break;


        default:
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
    }

} catch (PDOException $e) {

    http_response_code(500);
    echo json_encode([
        'error' => 'Error interno del servidor',
        'details' => $e->getMessage()
    ]);
}

$pdo = null;