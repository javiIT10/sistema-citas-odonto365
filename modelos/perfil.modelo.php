<?php

class PerfilModelo
{
  private $citaPendiente = [
    "id" => "CITA-20250125-0001",
    "especialista" => "Dr. Carlos Mendoza",
    "especialidad" => "Cardiología",
    "fecha" => "2025-01-25",
    "hora" => "10:30",
    "total" => 250.0,
    "estado" => "pendiente_pago",
    "descripcion" => "Consulta cardiológica de seguimiento",
    "duracion" => 60, // minutos
    "ubicacion" => "Consultorio 205, Torre Médica",
  ];

  private $historialCitas = [
    [
      "id" => "CITA-20250120-0001",
      "status" => "agendada",
      "especialista" => "Dr. Carlos Mendoza",
      "especialidad" => "Cardiología",
      "fecha" => "2025-01-20",
      "hora" => "10:30",
      "total" => 250.0,
      "pagado" => true,
    ],
    [
      "id" => "CITA-20250118-0002",
      "status" => "completada",
      "especialista" => "Dra. Ana Rodríguez",
      "especialidad" => "Dermatología",
      "fecha" => "2025-01-18",
      "hora" => "14:00",
      "total" => 180.0,
      "pagado" => true,
    ],
    [
      "id" => "CITA-20250115-0003",
      "status" => "cancelada",
      "especialista" => "Dr. Luis Hernández",
      "especialidad" => "Neurología",
      "fecha" => "2025-01-15",
      "hora" => "09:15",
      "total" => 300.0,
      "pagado" => false,
    ],
    [
      "id" => "CITA-20250112-0004",
      "status" => "completada",
      "especialista" => "Dra. Patricia Silva",
      "especialidad" => "Ginecología",
      "fecha" => "2025-01-12",
      "hora" => "16:45",
      "total" => 220.0,
      "pagado" => true,
    ],
    [
      "id" => "CITA-20250110-0005",
      "status" => "agendada",
      "especialista" => "Dr. Roberto Vega",
      "especialidad" => "Oftalmología",
      "fecha" => "2025-01-25",
      "hora" => "11:00",
      "total" => 200.0,
      "pagado" => true,
    ],
    [
      "id" => "CITA-20250108-0006",
      "status" => "completada",
      "especialista" => "Dr. Miguel Torres",
      "especialidad" => "Traumatología",
      "fecha" => "2025-01-08",
      "hora" => "13:30",
      "total" => 280.0,
      "pagado" => true,
    ],
    [
      "id" => "CITA-20250105-0007",
      "status" => "cancelada",
      "especialista" => "Dra. Carmen López",
      "especialidad" => "Endocrinología",
      "fecha" => "2025-01-05",
      "hora" => "15:15",
      "total" => 260.0,
      "pagado" => false,
    ],
    [
      "id" => "CITA-20250103-0008",
      "status" => "completada",
      "especialista" => "Dr. Fernando Ruiz",
      "especialidad" => "Urología",
      "fecha" => "2025-01-03",
      "hora" => "09:45",
      "total" => 240.0,
      "pagado" => true,
    ],
    [
      "id" => "CITA-20241230-0009",
      "status" => "agendada",
      "especialista" => "Dra. Isabel Moreno",
      "especialidad" => "Psiquiatría",
      "fecha" => "2024-12-30",
      "hora" => "12:00",
      "total" => 350.0,
      "pagado" => true,
    ],
    [
      "id" => "CITA-20241228-0010",
      "status" => "completada",
      "especialista" => "Dr. Alejandro Díaz",
      "especialidad" => "Neumología",
      "fecha" => "2024-12-28",
      "hora" => "16:30",
      "total" => 290.0,
      "pagado" => true,
    ],
  ];

  /**
   * Obtener cita pendiente de pago con validación mejorada
   */
  public function obtenerCitaPendiente($userId)
  {
    // Simular consulta a base de datos con validación
    if (!$userId || !$this->validarUsuario($userId)) {
      return null;
    }

    // Verificar si hay cita pendiente para este usuario
    if ($userId === "USR-20250116-001") {
      return $this->citaPendiente;
    }

    return null;
  }

  /**
   * Obtener historial de citas ordenado por fecha
   */
  public function obtenerHistorialCitas($userId)
  {
    if (!$userId || !$this->validarUsuario($userId)) {
      return [];
    }

    // Ordenar por fecha descendente (más recientes primero)
    $historial = $this->historialCitas;
    usort($historial, function ($a, $b) {
      return strtotime($b["fecha"]) - strtotime($a["fecha"]);
    });

    return $historial;
  }

  /**
   * Obtener datos del usuario con información más completa
   */
  public function obtenerDatosUsuario($userId)
  {
    if (!$this->validarUsuario($userId)) {
      return null;
    }

    // Datos de prueba del usuario más completos
    return [
      "id" => $userId,
      "nombre" => "Dr. María García López",
      "email" => "maria.garcia@agendamaster.com",
      "telefono" => "+52 55 1234 5678",
      "foto" => "professional-doctor-portrait.png",
      "fechaRegistro" => "2024-01-15",
      "ultimoAcceso" => date("Y-m-d H:i:s"),
      "verificado" => true,
      "tipoUsuario" => "paciente",
    ];
  }

  /**
   * Nueva función para actualizar datos del usuario
   */
  public function actualizarDatosUsuario($userId, $datosActualizados)
  {
    if (!$this->validarUsuario($userId)) {
      return false;
    }

    // En un caso real, aquí se actualizaría la base de datos
    // Por ahora simulamos que la actualización es exitosa

    // Validar campos permitidos para actualización
    $camposPermitidos = ["nombre", "email", "telefono", "foto"];
    foreach ($datosActualizados as $campo => $valor) {
      if (!in_array($campo, $camposPermitidos)) {
        throw new Exception("Campo no permitido para actualización: $campo");
      }
    }

    return true;
  }

  /**
   * Nueva función para confirmar pago de cita
   */
  public function confirmarPagoCita($citaId, $paymentId)
  {
    // Validar que la cita existe y está pendiente de pago
    if ($citaId !== $this->citaPendiente["id"]) {
      return false;
    }

    // En un caso real, aquí se actualizaría el estado de la cita en la base de datos
    // y se registraría el pago

    // Simular actualización exitosa
    $this->citaPendiente["estado"] = "pagada";
    $this->citaPendiente["paymentId"] = $paymentId;
    $this->citaPendiente["fechaPago"] = date("Y-m-d H:i:s");

    return true;
  }

  /**
   * Nueva función para validar usuario
   */
  private function validarUsuario($userId)
  {
    // Validación básica del formato del ID de usuario
    return preg_match('/^USR-\d{8}-\d{3}$/', $userId);
  }

  /**
   * Nueva función para obtener estadísticas del usuario
   */
  public function obtenerEstadisticasUsuario($userId)
  {
    if (!$this->validarUsuario($userId)) {
      return null;
    }

    $historial = $this->obtenerHistorialCitas($userId);

    return [
      "totalCitas" => count($historial),
      "citasCompletadas" => count(
        array_filter($historial, function ($cita) {
          return $cita["status"] === "completada";
        })
      ),
      "citasCanceladas" => count(
        array_filter($historial, function ($cita) {
          return $cita["status"] === "cancelada";
        })
      ),
      "citasAgendadas" => count(
        array_filter($historial, function ($cita) {
          return $cita["status"] === "agendada";
        })
      ),
      "totalGastado" => array_sum(
        array_map(function ($cita) {
          return $cita["pagado"] ? $cita["total"] : 0;
        }, $historial)
      ),
    ];
  }
}
?>
