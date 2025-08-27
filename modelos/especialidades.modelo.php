<?php
require_once "conexion.php";

class ModeloEspecialidades
{
    /* =============== MOSTRAR ESPECIALIDADES =============== */
    static public function mdlMostrarEspecialidades()
    {
        $conexion = Conexion::conectar();
        $stmt = $conexion->prepare("SELECT * FROM especialidades");
        $stmt->execute();

        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Cerrar conexión
        $stmt = null;
        $conexion = null;

        return $resultados;
    }
}
