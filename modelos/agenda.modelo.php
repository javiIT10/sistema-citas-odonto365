<?php
require_once "conexion.php";

class AgendaModelo
{
  /**
   * Devuelve los rangos ocupados para un especialista.
   * Aquí excluimos solo canceladas; puedes filtrar más si deseas.
   */
  public static function obtenerEventosPorEspecialista(int $idEspecialista): array
  {
    $db = Conexion::conectar();
    $sql = "SELECT cita_inicio, cita_fin, codigo_cita, cita_status
                  FROM agenda
                 WHERE id_especialista = :id_especialista
                   AND cita_status <> 'cancelada'
              ORDER BY cita_inicio ASC";
    $st = $db->prepare($sql);
    $st->bindParam(":id_especialista", $idEspecialista, PDO::PARAM_INT);
    $st->execute();
    return $st->fetchAll(PDO::FETCH_ASSOC) ?: [];
  }

  /** ¿Existe ya ese código en la tabla agenda? */
  public static function existeCodigoCita(string $codigo): bool
  {
    $db = Conexion::conectar();
    $st = $db->prepare("SELECT COUNT(*) FROM agenda WHERE codigo_cita = :codigo");
    $st->bindParam(":codigo", $codigo, PDO::PARAM_STR);
    $st->execute();
    return (bool) $st->fetchColumn();
  }

  /**
   * Detección de traslape para un especialista
   * Regla: choca si hay citas en 'preagenda' o 'agendada' cuyo rango intersecta.
   */
  public static function hayTraslape(int $idEspecialista, string $inicio, string $fin): bool
  {
    $db = Conexion::conectar();
    $sql = "SELECT id
                  FROM agenda
                 WHERE id_especialista = :esp
                   AND cita_status IN ('preagenda','agendada')
                   AND (:inicio < cita_fin) AND (:fin > cita_inicio)
                 LIMIT 1";
    $st = $db->prepare($sql);
    $st->bindParam(":esp", $idEspecialista, PDO::PARAM_INT);
    $st->bindParam(":inicio", $inicio, PDO::PARAM_STR);
    $st->bindParam(":fin", $fin, PDO::PARAM_STR);
    $st->execute();
    return (bool) $st->fetch(PDO::FETCH_ASSOC);
  }

  /**
   * Crea una cita en estado PREAGENDA con pago PENDIENTE.
   * $data = [
   *   id_especialista (int), id_usuario (int/bigint),
   *   codigo_cita (string), cita_inicio (Y-m-d H:i), cita_fin (Y-m-d H:i),
   *   motivo (string|null)
   * ]
   */
  public static function crearCitaPreagenda(array $data): array
  {
    try {
      $db = Conexion::conectar();
      $sql = "INSERT INTO agenda
                (id_especialista, id_usuario, codigo_cita, cita_status, cita_pago, cita_inicio, cita_fin, motivo)
                VALUES
                (:id_especialista, :id_usuario, :codigo_cita, 'preagenda', 'pendiente', :cita_inicio, :cita_fin, :motivo)";
      $st = $db->prepare($sql);

      $st->bindValue(":id_especialista", (int) $data["id_especialista"], PDO::PARAM_INT);
      // id_usuario es BIGINT UNSIGNED: pásalo como string para evitar overflow en 32-bit
      $st->bindValue(":id_usuario", (string) $data["id_usuario"], PDO::PARAM_STR);
      $st->bindValue(":codigo_cita", (string) $data["codigo_cita"], PDO::PARAM_STR);
      $st->bindValue(":cita_inicio", (string) $data["cita_inicio"], PDO::PARAM_STR);
      $st->bindValue(":cita_fin", (string) $data["cita_fin"], PDO::PARAM_STR);

      if ($data["motivo"] === null || $data["motivo"] === "") {
        $st->bindValue(":motivo", null, PDO::PARAM_NULL);
      } else {
        $st->bindValue(":motivo", (string) $data["motivo"], PDO::PARAM_STR);
      }

      $st->execute();
      return ["ok" => true, "id" => $db->lastInsertId()];
    } catch (Throwable $e) {
      return ["ok" => false, "error" => $e->getMessage()];
    }
  }

  /**
   * Marcar cita como AGENDADA y pago PAGADO (cuando tu PSP confirme).
   */
  public static function confirmarPagoYCita(
    int $idCita,
    ?string $estadoPago = "aprobado",
    ?string $referencia = null,
    ?float $monto = null
  ): array {
    $db = Conexion::conectar();
    try {
      $db->beginTransaction();

      // (Opcional) registrar/actualizar pago
      if ($monto !== null || $referencia !== null) {
        $sqlPago = "INSERT INTO pagos (id_cita, metodo, referencia_pago, monto, estado)
                            VALUES (:id_cita, 'mercadopago', :ref, :monto, :estado)
                            ON DUPLICATE KEY UPDATE
                                referencia_pago = VALUES(referencia_pago),
                                monto = VALUES(monto),
                                estado = VALUES(estado)";
        $p = $db->prepare($sqlPago);
        $p->bindParam(":id_cita", $idCita, PDO::PARAM_INT);
        $p->bindParam(":ref", $referencia, PDO::PARAM_STR);
        $montoVal = $monto ?? 0;
        $p->bindParam(":monto", $montoVal);
        $p->bindParam(":estado", $estadoPago);
        $p->execute();
      }

      // Actualizar cita
      $sqlCita = "UPDATE agenda
                           SET cita_status = 'agendada',
                               cita_pago   = 'pagado'
                         WHERE id = :id";
      $c = $db->prepare($sqlCita);
      $c->bindParam(":id", $idCita, PDO::PARAM_INT);
      $c->execute();

      $db->commit();
      return ["ok" => true];
    } catch (Throwable $e) {
      $db->rollBack();
      return ["ok" => false, "error" => $e->getMessage()];
    }
  }
}
