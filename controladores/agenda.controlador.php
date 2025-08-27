<?php
header("Content-Type: application/json; charset=utf-8");

require_once "../modelos/agenda.modelo.php";

try {
  $input = json_decode(file_get_contents("php://input"), true) ?? [];
  $especialistaId = isset($input["especialista_id"]) ? (int) $input["especialista_id"] : 0;

  if ($especialistaId <= 0) {
    http_response_code(400);
    echo json_encode(["error" => "Falta o es inválido especialista_id"]);
    exit();
  }

  $eventos = AgendaModelo::obtenerEventosPorEspecialista($especialistaId);
  echo json_encode($eventos, JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(["error" => "Error interno al cargar disponibilidad"]);
}
