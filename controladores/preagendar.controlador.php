<?php
header("Content-Type: application/json; charset=utf-8");

require_once "../modelos/agenda.modelo.php";

/**
 * Espera JSON:
 * {
 *   "id_especialista": 1,
 *   "id_usuario": 123,                       // BIGINT en tu esquema
 *   "codigo_cita": "CITA-YYYYMMDD-XXXX",
 *   "cita_inicio": "YYYY-MM-DD HH:MM",
 *   "cita_fin": "YYYY-MM-DD HH:MM",
 *   "motivo": "Opcional"
 * }
 */

try {
  $in = json_decode(file_get_contents("php://input"), true) ?? [];

  $req = ["id_especialista", "id_usuario", "codigo_cita", "cita_inicio", "cita_fin"];
  foreach ($req as $campo) {
    if (!isset($in[$campo]) || $in[$campo] === "" || $in[$campo] === null) {
      http_response_code(400);
      echo json_encode(["ok" => false, "error" => "Falta el campo: $campo"]);
      exit();
    }
  }

  $idEsp = (int) $in["id_especialista"];
  $idUser = (string) $in["id_usuario"]; // BIGINT → lo pasamos como string
  $codigo = trim($in["codigo_cita"]);
  $inicio = trim($in["cita_inicio"]);
  $fin = trim($in["cita_fin"]);
  $motivo = isset($in["motivo"]) ? trim((string) $in["motivo"]) : null;

  // 1) Traslape
  if (AgendaModelo::hayTraslape($idEsp, $inicio, $fin)) {
    http_response_code(409);
    echo json_encode(["ok" => false, "error" => "El horario se solapa con otra cita"]);
    exit();
  }

  // 2) Código único
  if (AgendaModelo::existeCodigoCita($codigo)) {
    http_response_code(409);
    echo json_encode(["ok" => false, "error" => "El código ya existe, vuelve a generar"]);
    exit();
  }

  // 3) Insert
  $res = AgendaModelo::crearCitaPreagenda([
    "id_especialista" => $idEsp,
    "id_usuario" => $idUser,
    "codigo_cita" => $codigo,
    "cita_inicio" => $inicio,
    "cita_fin" => $fin,
    "motivo" => $motivo,
  ]);

  if (!$res["ok"]) {
    http_response_code(500);
    echo json_encode(["ok" => false, "error" => $res["error"] ?? "No se pudo crear la cita"]);
    exit();
  }

  echo json_encode(["ok" => true, "id" => $res["id"]], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(["ok" => false, "error" => "Error interno en preagendar"]);
}
