<?php
require_once "conexion.php";

class ModeloEspecialistas
{
  /* =============== MOSTRAR ESPECIALISTAS =============== */
  /* =============== MOSTRAR ESPECIALISTAS =============== */
  public static function mdlMostrarEspecialistas($rutaEspecialidad)
  {
    try {
      $db = Conexion::conectar();

      $sql = "SELECT 
                    s.id                   AS id,
                    s.nombre               AS nombre,
                    e.especialidad_nombre  AS especialidad,
                    s.foto                 AS foto,
                    s.descripcion          AS descripcion,
                    s.certificaciones      AS certificaciones
                FROM especialidades e
                INNER JOIN especialistas s 
                    ON s.id_especialidad = e.id_especialidad
                WHERE e.ruta = :ruta
                ORDER BY s.nombre ASC";

      $stmt = $db->prepare($sql);
      $stmt->bindParam(":ruta", $rutaEspecialidad, PDO::PARAM_STR);
      $stmt->execute();

      $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

      // Certificaciones: string "a|b|c" -> array; si es NULL -> []
      foreach ($resultados as &$fila) {
        if (!empty($fila["certificaciones"])) {
          $fila["certificaciones"] = array_map("trim", explode("|", $fila["certificaciones"]));
        } else {
          $fila["certificaciones"] = [];
        }
      }

      return $resultados;
    } catch (PDOException $e) {
      error_log("Error en mdlMostrarEspecialistas: " . $e->getMessage());
      return [];
    }
  }
}
