<?php

require_once "controladores/especialidades.controlador.php";
require_once "modelos/especialidades.modelo.php";

require_once "controladores/especialistas.controlador.php";
require_once "modelos/especialistas.modelo.php";

require_once "controladores/plantilla.controlador.php";
require_once "controladores/ruta.controlador.php";

$plantilla = new ControladorPlantilla();
$plantilla->ctrPlantilla();