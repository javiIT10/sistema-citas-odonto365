<?php
header("Content-Type: application/json; charset=utf-8");

require_once "../modelos/agenda.modelo.php";

try {
  $input = json_decode(file_get_contents("php://input"), true) ?? [];
  $codigo = isset($input["codigo"]) ? trim($input["codigo"]) : "";

  if ($codigo === "") {
    http_response_code(400);
    echo json_encode(["error" => "No se recibió código"]);
    exit();
  }

  $existe = AgendaModelo::existeCodigoCita($codigo);
  echo json_encode(["existe" => $existe], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(["error" => "Error interno al validar código"]);
}
