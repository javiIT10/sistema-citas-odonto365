<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Manejar preflight requests
if ($_SERVER["REQUEST_METHOD"] == "OPTIONS") {
  exit(0);
}

require_once "../modelos/perfil.modelo.php";

class PerfilControlador
{
  private $modelo;

  public function __construct()
  {
    $this->modelo = new PerfilModelo();
  }

  /**
   * Procesar petición y devolver datos según la acción solicitada
   */
  public function procesarPeticion()
  {
    try {
      // Obtener datos de la petición
      $input = json_decode(file_get_contents("php://input"), true);

      if (!$input) {
        throw new Exception("Datos de entrada inválidos");
      }

      $action = $input["action"] ?? "obtenerDatosPerfil";

      switch ($action) {
        case "obtenerDatosPerfil":
          $this->obtenerDatosPerfil($input);
          break;

        case "actualizarPerfil":
          $this->actualizarPerfil($input);
          break;

        case "confirmarPago":
          $this->confirmarPago($input);
          break;

        default:
          throw new Exception("Acción no válida");
      }
    } catch (Exception $e) {
      http_response_code(400);
      echo json_encode([
        "success" => false,
        "error" => $e->getMessage(),
      ]);
    }
  }

  /**
   * Obtener datos completos del perfil del usuario
   */
  private function obtenerDatosPerfil($input)
  {
    if (!isset($input["userId"])) {
      throw new Exception("ID de usuario requerido");
    }

    $userId = $input["userId"];

    // Simular tiempo de procesamiento realista
    usleep(200000); // 200ms

    // Obtener datos del modelo
    $datosUsuario = $this->modelo->obtenerDatosUsuario($userId);
    $citaPendiente = $this->modelo->obtenerCitaPendiente($userId);
    $historialCitas = $this->modelo->obtenerHistorialCitas($userId);

    // Validar que el usuario existe
    if (!$datosUsuario) {
      throw new Exception("Usuario no encontrado");
    }

    // Preparar respuesta
    $respuesta = [
      "success" => true,
      "data" => [
        "usuario" => $datosUsuario,
        "citaPendiente" => $citaPendiente,
        "historialCitas" => $historialCitas,
        "estadisticas" => [
          "totalCitas" => count($historialCitas),
          "citasCompletadas" => count(
            array_filter($historialCitas, function ($cita) {
              return $cita["status"] === "completada";
            })
          ),
          "citasCanceladas" => count(
            array_filter($historialCitas, function ($cita) {
              return $cita["status"] === "cancelada";
            })
          ),
        ],
      ],
      "timestamp" => date("Y-m-d H:i:s"),
    ];

    echo json_encode($respuesta);
  }

  /**
   * Actualizar información del perfil del usuario
   */
  private function actualizarPerfil($input)
  {
    if (!isset($input["userId"]) || !isset($input["datosActualizados"])) {
      throw new Exception("Datos insuficientes para actualizar perfil");
    }

    $userId = $input["userId"];
    $datosActualizados = $input["datosActualizados"];

    $resultado = $this->modelo->actualizarDatosUsuario($userId, $datosActualizados);

    if ($resultado) {
      echo json_encode([
        "success" => true,
        "message" => "Perfil actualizado correctamente",
        "timestamp" => date("Y-m-d H:i:s"),
      ]);
    } else {
      throw new Exception("Error al actualizar el perfil");
    }
  }

  /**
   * Confirmar pago de cita
   */
  private function confirmarPago($input)
  {
    if (!isset($input["citaId"]) || !isset($input["paymentId"])) {
      throw new Exception("ID de cita y pago requeridos");
    }

    $citaId = $input["citaId"];
    $paymentId = $input["paymentId"];

    $resultado = $this->modelo->confirmarPagoCita($citaId, $paymentId);

    if ($resultado) {
      echo json_encode([
        "success" => true,
        "message" => "Pago confirmado correctamente",
        "citaId" => $citaId,
        "paymentId" => $paymentId,
        "timestamp" => date("Y-m-d H:i:s"),
      ]);
    } else {
      throw new Exception("Error al confirmar el pago");
    }
  }
}

// Procesar la petición
$controlador = new PerfilControlador();
$controlador->procesarPeticion();
?>
