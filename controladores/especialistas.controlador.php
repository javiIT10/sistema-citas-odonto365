<?php

class ControladorEspecialistas
{
    static public function ctrMostrarEspecialistas($rutaEspecialidad)
    {
        return ModeloEspecialistas::mdlMostrarEspecialistas($rutaEspecialidad);
    }
}