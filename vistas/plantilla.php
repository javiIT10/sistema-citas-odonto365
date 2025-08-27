<?php

$ruta = ControladorRuta::ctrRuta();
$servidor = ControladorRuta::ctrServidor();

$pagina = $_GET["pagina"] ?? null;
$rutasEspecialidades = ControladorEspecialidades::ctrMostrarEspecialidades();
$obtenerRutas = array_column($rutasEspecialidades, "ruta");
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Title -->
    <title>Odonto365</title>

    
    <!--==================== CSS ====================-->
    <!-- CSS Perzonalizado -->
    <link rel="stylesheet" href="vistas/src/css/output.css" />

    <!--==================== SCRIPTS ====================-->
    <!-- lucide -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- SDK Mercado Pago -->
     <script src="https://sdk.mercadopago.com/js/v2"></script>
  </head>
  <body class="font-body bg-fondo-alternativo min-h-screen">

      <?php // Cargar el header

if ($pagina) {
        if ($pagina === "agenda") {
          include "vistas/paginas/modulos/header-agenda.php";
        }
      } else {
        include "vistas/paginas/modulos/header-inicio.php";
      } ?>

    <!--==================== MAIN ====================-->
    <main>
      <?php if ($pagina) {
        if (in_array($pagina, $obtenerRutas)) {
          include "paginas/especialistas.php";
        } elseif (in_array($pagina, ["agenda", "perfil"])) {
          include "paginas/$pagina.php";
        }
      } else {
        include "paginas/inicio.php";
      } ?>
    </main>

    <!--==================== JavaScript ====================-->
    <?php if ($pagina) {
      if ($pagina === "perfil") {
        echo '<script src="vistas/src/js/perfil.js"></script>';
      }
    } else {
      echo '<script src="vistas/src/js/main.js"></script>';
    } ?>
  </body>
</html>
