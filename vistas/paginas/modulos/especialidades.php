    <?php
    $especialidades = ControladorEspecialidades::ctrMostrarEspecialidades();
    ?>
    <!--==================== ESPECIALIDADES ====================-->
    <section
      class="w-full bg-gradient-to-b from-fondo-alternativo to-fondo-principal"
    >
      <div class="px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="text-center mb-12">
          <small
            class="encabezado inline-block text-estatus-proxima-texto bg-estatus-proxima-bg font-medium mb-4"
          >
            Nuestras Especialidades
          </small>
          <h2 class="tracking-tighter max-w-2xl mx-auto">
            Diferentes tipos especialidades dentales para tu salud bucal
          </h2>
        </div>
        <div class="grid gap-6 mb-12 md:grid-cols-[repeat(2,1fr)] xl:grid-cols-[repeat(3,1fr)]">
          <?php foreach ($especialidades as $llave => $valor) : ?>
        
          <!-- especialidad  -->
          <a href="<?php echo $valor["ruta"]; ?>" class="bg-fondo-principal shadow-lg rounded-lg relative hover:shadow-xl transition-shadow duration-300">
            <div class="absolute inset-0 grid grid-cols-3 grid-rows-3">
              <div class="flex items-center justify-center">
                <div class="w-1.5 h-1.5 rounded-full bg-primario/20"></div>
              </div>
              <div class="flex items-center justify-center"></div>
              <div class="flex items-center justify-center">
                <div class="w-1.5 h-1.5 rounded-full bg-primario/20"></div>
              </div>
              <div class="flex items-center justify-center"></div>
              <div class="flex items-center justify-center">
                <div class="w-1.5 h-1.5 rounded-full bg-primario/20"></div>
              </div>
              <div class="flex items-center justify-center"></div>
              <div class="flex items-center justify-center">
                <div class="w-1.5 h-1.5 rounded-full bg-primario/20"></div>
              </div>
              <div class="flex items-center justify-center"></div>
              <div class="flex items-center justify-center">
                <div class="w-1.5 h-1.5 rounded-full bg-primario/20"></div>
              </div>
            </div>
            <div class="p-6 relative">
              <div class="mb-2">
                <?php echo $valor["icono_svg"]; ?>
              </div>
              <h4 class="mb-2"><?php echo $valor["especialidad_nombre"]; ?></h4>
              <p>
                <?php echo $valor["especialidad_descripcion"]; ?>
              </p>
            </div>
          </a>

          <?php endforeach; ?>
          
        </div>
      </div>
    </section>