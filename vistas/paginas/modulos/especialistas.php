<?php
    $rutaEspecialidad = $_GET["pagina"];

    // Llamar al controlador
    $especialistas = ControladorEspecialistas::ctrMostrarEspecialistas($rutaEspecialidad);

    // Convertir array PHP a JSON para JS
    $especialistasJSON = json_encode($especialistas, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    $especialidad = $especialistas[0]['especialidad'];
    $especialistaSeleccionado = $especialistas[0];
?>

<main class="min-h-screen bg-slate-50">
  <div class="mx-auto w-full max-w-7xl px-4 py-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
      <button
        class="flex items-center gap-2 rounded-xl h-11 px-4 bg-white hover:bg-slate-50 border border-borde transition-colors cursor-pointer"
      >
        <i data-lucide="chevron-left" class="h-5 w-5 text-slate-700"></i>
        <span class="font-semibold text-slate-700">Regresar</span>
      </button>
      <div class="text-right">
        <h1 class="text-3xl font-bold text-blue-800 font-montserrat">
          <?php echo $especialidad; ?>
        </h1>
        <p class="text-slate-600 mt-1">
          <?php echo count($especialistas); ?>
          especialistas disponibles
        </p>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Columna izquierda: Lista de especialistas -->
      <div class="lg:col-span-2 space-y-6">
        <!-- Carrusel de especialistas -->
        <div class="carousel-container pb-2">
          <div class="flex gap-3 min-w-max">
            <?php foreach ($especialistas as $index =>
            $especialista): ?>
            <button
              class="especialista-btn rounded-xl px-6 py-3 font-medium whitespace-nowrap flex-shrink-0 transition-colors <?php echo $index === 0 ? 'bg-blue-700 text-white' : 'bg-white border border-borde hover:bg-slate-50'; ?>"
               data-especialista='<?= htmlspecialchars(json_encode($especialista, JSON_UNESCAPED_UNICODE), ENT_QUOTES, "UTF-8") ?>'
              data-index="<?= $index; ?>"
            >
              <?php echo $especialista['nombre']; ?>
            </button>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Información del especialista seleccionado -->
        <div
          id="especialista-info"
          class="rounded-2xl border border-borde bg-white p-6 shadow-sm"
        >
          <div class="flex items-start gap-4">
            <!-- Foto de perfil -->
            <div class="flex-shrink-0">
              <div class="relative w-20 h-20">
                <img
                  id="especialista-foto"
                  src="<?php echo $especialistaSeleccionado['foto']; ?>"
                  alt="Foto de <?php echo $especialistaSeleccionado['nombre']; ?>"
                  class="w-full h-full rounded-full object-cover"
                />
              </div>
            </div>

            <!-- Información básica al costado -->
            <div class="flex-1">
              <h2
                id="especialista-nombre"
                class="text-2xl font-bold text-blue-800 font-montserrat"
              >
                <?php echo $especialistaSeleccionado['nombre']; ?>
              </h2>
              <div class="flex items-center gap-2 mt-1">
                <i data-lucide="user" class="h-4 w-4 text-blue-600"></i>
                <span
                  id="especialista-especialidad"
                  class="text-blue-600 font-medium"
                  ><?php echo $especialistaSeleccionado['especialidad']; ?></span
                >
              </div>
            </div>
          </div>

          <div class="mt-4">
            <p
              id="especialista-descripcion"
              class="text-slate-600 leading-relaxed"
            >
              <?php echo $especialistaSeleccionado['descripcion']; ?>
            </p>
          </div>
        </div>

        <!-- Certificaciones -->
        <div class="rounded-2xl border border-borde bg-white p-6 shadow-sm">
          <div class="flex items-center gap-3 mb-4">
            <i data-lucide="award" class="h-6 w-6 text-blue-600"></i>
            <h3 class="text-xl font-semibold text-blue-800 font-montserrat">
              Certificaciones
            </h3>
          </div>

          <div id="certificaciones-lista" class="grid gap-3">
            <?php foreach ($especialistaSeleccionado['certificaciones'] as $cert): ?>
            <div class="flex items-center gap-3 p-3 bg-blue-50 rounded-xl">
              <i
                data-lucide="check-circle-2"
                class="h-5 w-5 text-blue-600 flex-shrink-0"
              ></i>
              <span class="text-slate-700 font-medium"
                ><?php echo $cert; ?></span
              >
            </div>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Selector de fecha y hora -->
        <div class="rounded-2xl border border-borde bg-white p-6 shadow-sm">
          <div class="flex items-center gap-3 mb-6">
            <i data-lucide="calendar" class="h-6 w-6 text-blue-600"></i>
            <h3 class="text-xl font-semibold text-blue-800 font-montserrat">
              Selecciona fecha y hora
            </h3>
          </div>

          <div id="fecha-selector">
            <div class="text-center py-8">
              <div
                class="w-16 h-16 mx-auto bg-slate-100 rounded-full flex items-center justify-center mb-4"
              >
                <i data-lucide="calendar" class="h-8 w-8 text-slate-400"></i>
              </div>
              <p class="text-slate-500 mb-4">
                Selecciona una fecha para ver los horarios disponibles
              </p>
              <button
                id="abrir-modal"
                class="bg-blue-700 hover:bg-blue-800 text-white rounded-xl px-6 py-3 transition-colors"
              >
                Seleccionar Fecha
              </button>
            </div>
          </div>

          <div id="fecha-seleccionada" class="space-y-4 hidden">
            <!-- Fecha seleccionada -->
            <div
              class="flex items-center justify-between p-4 bg-blue-50 rounded-xl"
            >
              <div class="flex items-center gap-3">
                <i data-lucide="calendar" class="h-5 w-5 text-blue-600"></i>
                <span id="fecha-texto" class="font-medium text-blue-800"></span>
              </div>
              <button
                id="cambiar-fecha"
                class="rounded-lg px-3 py-1 text-sm border border-borde bg-transparent hover:bg-slate-50 transition-colors"
              >
                Cambiar
              </button>
            </div>

            <!-- Horarios disponibles -->
            <div id="horarios-container">
              <p class="text-slate-600 mb-4">Horarios disponibles:</p>
              <div
                class="grid grid-cols-2 md:grid-cols-3 gap-3"
                id="horarios-grid"
              >
                <!-- Los horarios se generarán dinámicamente -->
              </div>
            </div>

            <div
              id="hora-seleccionada"
              class="flex items-center justify-between p-4 bg-emerald-50 rounded-xl hidden"
            >
              <div class="flex items-center gap-3">
                <i data-lucide="clock" class="h-5 w-5 text-emerald-600"></i>
                <span
                  id="hora-texto"
                  class="font-medium text-emerald-800"
                ></span>
              </div>
              <button
                id="cambiar-hora"
                class="rounded-lg px-3 py-1 text-sm border border-borde hover:bg-slate-50 transition-colors"
              >
                Cambiar
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Columna derecha: Resumen -->
      <div class="lg:sticky lg:top-6 lg:h-fit">
        <div class="rounded-2xl border border-borde bg-white p-6 shadow-sm">
          <h3 class="text-2xl font-bold text-blue-800 font-montserrat mb-6">
            Resumen de Cita
          </h3>

          <div class="space-y-6">
            <div>
              <p class="text-sm font-medium text-slate-500 mb-2">
                ESPECIALIDAD
              </p>
              <p
                id="resumen-especialidad"
                class="text-lg font-semibold text-slate-800"
              >
                <?php echo $especialidad; ?>
              </p>
            </div>

            <div class="h-px bg-slate-200"></div>

            <div>
              <p class="text-sm font-medium text-slate-500 mb-2">
                ESPECIALISTA
              </p>
              <p
                id="resumen-especialista"
                class="text-lg font-semibold text-slate-800"
              >
                <?php echo $especialistaSeleccionado['nombre']; ?>
              </p>
            </div>

            <div class="h-px bg-slate-200"></div>

            <div>
              <p class="text-sm font-medium text-slate-500 mb-2">
                FECHA Y HORA
              </p>
              <div id="resumen-fecha-hora">
                <p class="text-slate-400">Por seleccionar</p>
              </div>
            </div>

            <!-- Formulario para enviar datos -->
            <form
              id="form-cita"
              method="POST"
              action="<?php echo $ruta; ?>agenda"
            >
              <input
                type="hidden"
                name="especialista"
                id="form-especialista"
                value="<?php echo $especialistaSeleccionado['nombre']; ?>"
              />
              <input
                type="hidden"
                name="especialista_id"
                id="form-especialista-id"
                value="<?php echo $especialistaSeleccionado['id']; ?>"
              />
              <input
                type="hidden"
                name="especialidad"
                id="form-especialidad"
                value="<?php echo $especialidad; ?>"
              />
              <input type="hidden" name="fecha" id="form-fecha" value="" />
              <input type="hidden" name="hora" id="form-hora" value="" />
              <input
                type="hidden"
                name="cita_pago"
                id="form-precio"
                value="<?php echo number_format((float)'150', 2, '.', ',')  ?>"
              />

              <button
                type="submit"
                id="validar-btn"
                disabled
                class="w-full h-12 rounded-xl text-lg font-semibold mt-6 transition-colors bg-slate-200 text-slate-400 cursor-not-allowed"
              >
                Selecciona fecha y hora
              </button>
            </form>

            <p
              id="continuar-texto"
              class="text-xs text-slate-500 text-center hidden"
            >
              Al continuar serás redirigido a la agenda para confirmar tu cita
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div
      id="modal"
      class="modal fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50"
    >
      <div class="bg-white rounded-2xl w-full max-w-sm mx-4 p-6">
        <div class="mb-4">
          <h2
            id="modal-titulo"
            class="text-2xl font-bold text-blue-800 font-montserrat"
          >
            Agendar Cita con
            <?php echo $especialistaSeleccionado['nombre']; ?>
          </h2>
          <p class="text-slate-600">
            Selecciona la fecha y hora de tu preferencia
          </p>
        </div>

        <!-- Toggle de vista -->
        <div class="flex rounded-lg bg-slate-100 p-1 mb-4">
          <button
            id="tab-fecha"
            class="flex-1 py-3 px-4 rounded-md text-sm font-medium transition-colors flex items-center justify-center gap-2 bg-blue-600 text-white shadow-sm"
          >
            <i data-lucide="calendar" class="h-4 w-4"></i>
            Fecha
          </button>
          <button
            id="tab-hora"
            class="flex-1 py-3 px-4 rounded-md text-sm font-medium transition-colors flex items-center justify-center gap-2 text-slate-600 hover:text-slate-900 opacity-50 cursor-not-allowed"
            disabled
          >
            <i data-lucide="clock" class="h-4 w-4"></i>
            Hora
          </button>
        </div>

        <!-- Vista de fecha -->
        <div id="vista-fecha" class="space-y-4">
          <div>
            <h3
              class="text-lg font-semibold text-blue-800 font-montserrat mb-2"
            >
              Selecciona una fecha
            </h3>
            <p class="text-sm text-slate-500">Los domingos no hay atención</p>
          </div>

          <!-- Navegación del mes -->
          <div class="flex items-center justify-between">
            <button
              id="mes-anterior"
              class="p-2 hover:bg-slate-100 rounded-lg transition-colors"
            >
              <i data-lucide="chevron-left" class="h-5 w-5"></i>
            </button>
            <h3
              id="mes-actual"
              class="text-lg font-semibold text-blue-800 font-montserrat"
            ></h3>
            <button
              id="mes-siguiente"
              class="p-2 hover:bg-slate-100 rounded-lg transition-colors"
            >
              <i data-lucide="chevron-right" class="h-5 w-5"></i>
            </button>
          </div>

          <!-- Días de la semana -->
          <div
            class="grid grid-cols-7 gap-1 text-center text-sm font-medium text-blue-800"
          >
            <div class="p-2">Do</div>
            <div class="p-2">Lu</div>
            <div class="p-2">Ma</div>
            <div class="p-2">Mi</div>
            <div class="p-2">Ju</div>
            <div class="p-2">Vi</div>
            <div class="p-2">Sá</div>
          </div>

          <!-- Calendario -->
          <div id="calendario-grid" class="grid grid-cols-7 gap-1">
            <!-- Los días se generarán dinámicamente -->
          </div>
        </div>

        <!-- Vista de hora -->
        <div id="vista-hora" class="space-y-4 hidden">
          <div>
            <h3 class="text-lg font-semibold text-blue-800 font-montserrat">
              Selecciona una hora
            </h3>
            <p
              id="fecha-seleccionada-texto"
              class="text-sm text-slate-600 mt-1"
            ></p>
          </div>

          <div
            class="grid grid-cols-2 gap-3 max-h-60 overflow-y-auto"
            id="horas-grid"
          >
            <!-- Las horas se generarán dinámicamente -->
          </div>
        </div>

        <!-- Botón cerrar -->
        <button
          id="cerrar-modal"
          class="absolute top-4 right-4 p-2 hover:bg-slate-100 rounded-lg transition-colors"
        >
          <i data-lucide="x" class="h-5 w-5"></i>
        </button>
      </div>
    </div>
  </div>
</main>

<script>
  // Inicializar Lucide icons
  lucide.createIcons();

  // Variables globales
  let especialistaActual = <?php echo json_encode($especialistaSeleccionado); ?>;
  let fechaSeleccionada = null;
  let horaSeleccionada = null;
  let mesActual = new Date();
  let vistaModal = 'fecha';

  const horarios = ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00'];
  const meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
  const hoy = new Date();
  hoy.setHours(0, 0, 0, 0);

  // Funciones de utilidad
  function formatearFecha(fecha) {
      return fecha.toLocaleDateString('es-ES', {
          weekday: 'long',
          day: 'numeric',
          month: 'long',
          year: 'numeric'
      });
  }

  function esDomingo(fecha) {
      return fecha.getDay() === 0;
  }

  function esPasado(fecha) {
      return fecha < hoy;
  }

  function esHoy(fecha) {
      return fecha.getTime() === hoy.getTime();
  }

  // Selección de especialista
  document.querySelectorAll('.especialista-btn').forEach(btn => {
      btn.addEventListener('click', function() {
          // Actualizar botones
          document.querySelectorAll('.especialista-btn').forEach(b => {
              b.className = 'especialista-btn rounded-xl px-6 py-3 font-medium whitespace-nowrap flex-shrink-0 transition-colors bg-white border border-borde hover:bg-slate-50';
          });
          this.className = 'especialista-btn rounded-xl px-6 py-3 font-medium whitespace-nowrap flex-shrink-0 transition-colors bg-blue-700 text-white';

          // Actualizar especialista actual
          especialistaActual = JSON.parse(this.dataset.especialista);

          console.log(especialistaActual);
          

          // Actualizar información
          document.getElementById('especialista-foto').src = especialistaActual.foto;
          document.getElementById('especialista-foto').alt = `Foto de ${especialistaActual.nombre}`;
          document.getElementById('especialista-nombre').textContent = especialistaActual.nombre;
          document.getElementById('especialista-especialidad').textContent = especialistaActual.especialidad;
          document.getElementById('especialista-descripcion').textContent = especialistaActual.descripcion;
          document.getElementById('resumen-especialista').textContent = especialistaActual.nombre;
          document.getElementById('modal-titulo').textContent = `Agendar Cita con ${especialistaActual.nombre}`;

          // Actualizar certificaciones
          const certificacionesLista = document.getElementById('certificaciones-lista');
          certificacionesLista.innerHTML = '';
          especialistaActual.certificaciones.forEach(cert => {
              const div = document.createElement('div');
              div.className = 'flex items-center gap-3 p-3 bg-blue-50 rounded-xl';
              div.innerHTML = `
                  <i data-lucide="check-circle-2" class="h-5 w-5 text-blue-600 flex-shrink-0"></i>
                  <span class="text-slate-700 font-medium">${cert}</span>
              `;
              certificacionesLista.appendChild(div);
          });

          // Actualizar campos del formulario
          document.getElementById('form-especialista').value = especialistaActual.nombre;
          document.getElementById('form-especialista-id').value = especialistaActual.id;
          document.getElementById('form-precio').value = especialistaActual.precio;

          // Reinicializar iconos
          lucide.createIcons();
      });
  });

  // Modal
  const modal = document.getElementById('modal');
  const abrirModalBtn = document.getElementById('abrir-modal');
  const cerrarModalBtn = document.getElementById('cerrar-modal');
  const cambiarFechaBtn = document.getElementById('cambiar-fecha');

  function abrirModal() {
      modal.classList.add('active');
      vistaModal = 'fecha';
      actualizarVistaModal();
      generarCalendario();
  }

  function cerrarModal() {
      modal.classList.remove('active');
  }

  abrirModalBtn.addEventListener('click', abrirModal);
  cambiarFechaBtn.addEventListener('click', abrirModal);
  cerrarModalBtn.addEventListener('click', cerrarModal);

  // Tabs del modal
  document.getElementById('tab-fecha').addEventListener('click', function() {
      vistaModal = 'fecha';
      actualizarVistaModal();
  });

  document.getElementById('tab-hora').addEventListener('click', function() {
      if (fechaSeleccionada) {
          vistaModal = 'hora';
          actualizarVistaModal();
          generarHorarios();
      }
  });

  function actualizarVistaModal() {
      const tabFecha = document.getElementById('tab-fecha');
      const tabHora = document.getElementById('tab-hora');
      const vistaFecha = document.getElementById('vista-fecha');
      const vistaHora = document.getElementById('vista-hora');

      if (vistaModal === 'fecha') {
          tabFecha.className = 'flex-1 py-3 px-4 rounded-md text-sm font-medium transition-colors flex items-center justify-center gap-2 bg-blue-600 text-white shadow-sm';
          tabHora.className = `flex-1 py-3 px-4 rounded-md text-sm font-medium transition-colors flex items-center justify-center gap-2 text-slate-600 hover:text-slate-900 ${!fechaSeleccionada ? 'opacity-50 cursor-not-allowed' : ''}`;
          vistaFecha.classList.remove('hidden');
          vistaHora.classList.add('hidden');
      } else {
          tabFecha.className = 'flex-1 py-3 px-4 rounded-md text-sm font-medium transition-colors flex items-center justify-center gap-2 text-slate-600 hover:text-slate-900';
          tabHora.className = 'flex-1 py-3 px-4 rounded-md text-sm font-medium transition-colors flex items-center justify-center gap-2 bg-blue-600 text-white shadow-sm';
          vistaFecha.classList.add('hidden');
          vistaHora.classList.remove('hidden');
      }
  }

  // Navegación de mes
  document.getElementById('mes-anterior').addEventListener('click', function() {
      mesActual.setMonth(mesActual.getMonth() - 1);
      generarCalendario();
  });

  document.getElementById('mes-siguiente').addEventListener('click', function() {
      mesActual.setMonth(mesActual.getMonth() + 1);
      generarCalendario();
  });

  function generarCalendario() {
      const mesNombre = meses[mesActual.getMonth()];
      const año = mesActual.getFullYear();
      document.getElementById('mes-actual').textContent = `${mesNombre.charAt(0).toUpperCase() + mesNombre.slice(1)} ${año}`;

      const primerDia = new Date(año, mesActual.getMonth(), 1);
      const ultimoDia = new Date(año, mesActual.getMonth() + 1, 0);
      const calendarioGrid = document.getElementById('calendario-grid');

      calendarioGrid.innerHTML = '';

      // Días vacíos al inicio
      for (let i = 0; i < primerDia.getDay(); i++) {
          const div = document.createElement('div');
          div.className = 'p-2';
          calendarioGrid.appendChild(div);
      }

      // Días del mes
      for (let dia = 1; dia <= ultimoDia.getDate(); dia++) {
          const fecha = new Date(año, mesActual.getMonth(), dia);
          const button = document.createElement('button');
          button.textContent = dia;

          const esDeshabilitado = esPasado(fecha) || esHoy(fecha) || esDomingo(fecha);

          if (esDeshabilitado) {
              button.className = 'p-3 text-sm rounded-lg transition-colors font-medium text-slate-300 cursor-not-allowed';
              button.disabled = true;
          } else {
              button.className = 'p-3 text-sm rounded-lg transition-colors font-medium hover:bg-blue-50 text-slate-700 cursor-pointer hover:text-blue-700';
              button.addEventListener('click', function() {
                  seleccionarFecha(fecha);
              });
          }

          calendarioGrid.appendChild(button);
      }
  }

  function seleccionarFecha(fecha) {
      fechaSeleccionada = fecha;
      horaSeleccionada = null;

      // Actualizar UI
      document.getElementById('fecha-selector').classList.add('hidden');
      document.getElementById('fecha-seleccionada').classList.remove('hidden');
      document.getElementById('fecha-texto').textContent = formatearFecha(fecha);
      document.getElementById('hora-seleccionada').classList.add('hidden');

      // Mostrar horarios
      document.getElementById('horarios-container').classList.remove('hidden');
      generarHorariosDisponibles();

      // Actualizar resumen
      actualizarResumen();

      // Habilitar tab de hora en modal
      document.getElementById('tab-hora').disabled = false;
      document.getElementById('tab-hora').classList.remove('opacity-50', 'cursor-not-allowed');

      // Cambiar a vista de hora en modal
      vistaModal = 'hora';
      actualizarVistaModal();
      generarHorarios();
  }

  function generarHorariosDisponibles() {
      const horariosGrid = document.getElementById('horarios-grid');
      horariosGrid.innerHTML = '';

      horarios.forEach(hora => {
          const button = document.createElement('button');
          button.className = 'rounded-xl p-4 h-auto hover:bg-blue-50 hover:border border-borde-blue-300 bg-transparent border border-borde transition-colors';
          button.innerHTML = `
              <div class="flex items-center gap-2">
                  <i data-lucide="clock" class="h-4 w-4 text-blue-600"></i>
                  <span class="font-medium">${hora}</span>
              </div>
          `;
          button.addEventListener('click', function() {
              seleccionarHora(hora);
          });
          horariosGrid.appendChild(button);
      });

      lucide.createIcons();
  }

  function generarHorarios() {
      if (!fechaSeleccionada) return;

      document.getElementById('fecha-seleccionada-texto').textContent = formatearFecha(fechaSeleccionada);

      const horasGrid = document.getElementById('horas-grid');
      horasGrid.innerHTML = '';

      horarios.forEach(hora => {
          const button = document.createElement('button');
          button.className = 'p-4 text-sm rounded-xl border border-borde transition-colors hover:bg-blue-50 hover:border border-borde-blue-300 cursor-pointer';
          button.innerHTML = `
              <div class="flex items-center justify-center gap-2">
                  <i data-lucide="clock" class="h-4 w-4 text-blue-600"></i>
                  <span class="font-medium text-slate-700">${hora}</span>
              </div>
          `;
          button.addEventListener('click', function() {
              seleccionarHora(hora);
          });
          horasGrid.appendChild(button);
      });

      lucide.createIcons();
  }

  function seleccionarHora(hora) {
      horaSeleccionada = hora;

      // Actualizar UI
      document.getElementById('horarios-container').classList.add('hidden');
      document.getElementById('hora-seleccionada').classList.remove('hidden');
      document.getElementById('hora-texto').textContent = `Hora seleccionada: ${hora}`;

      // Actualizar resumen
      actualizarResumen();

      // Cerrar modal
      cerrarModal();
  }

  function actualizarResumen() {
      const resumenFechaHora = document.getElementById('resumen-fecha-hora');
      const validarBtn = document.getElementById('validar-btn');
      const continuarTexto = document.getElementById('continuar-texto');

      if (fechaSeleccionada && horaSeleccionada) {
          resumenFechaHora.innerHTML = `
              <div class="space-y-2">
                  <div class="flex items-center gap-2">
                      <i data-lucide="calendar" class="h-4 w-4 text-blue-600"></i>
                      <span class="text-slate-800">${formatearFecha(fechaSeleccionada)}</span>
                  </div>
                  <div class="flex items-center gap-2">
                      <i data-lucide="clock" class="h-4 w-4 text-blue-600"></i>
                      <span class="text-slate-800">${horaSeleccionada}</span>
                  </div>
              </div>
          `;

          validarBtn.disabled = false;
          validarBtn.className = 'w-full h-12 rounded-xl text-lg font-semibold mt-6 transition-colors bg-blue-700 hover:bg-blue-800 text-white cursor-pointer';
          validarBtn.textContent = 'Validar Disponibilidad';
          continuarTexto.classList.remove('hidden');

          // Actualizar campos del formulario
          document.getElementById('form-fecha').value = fechaSeleccionada.toISOString();
          document.getElementById('form-hora').value = horaSeleccionada;

          lucide.createIcons();
      } else {
          resumenFechaHora.innerHTML = '<p class="text-slate-400">Por seleccionar</p>';
          validarBtn.disabled = true;
          validarBtn.className = 'w-full h-12 rounded-xl text-lg font-semibold mt-6 transition-colors bg-slate-200 text-slate-400 cursor-not-allowed';
          validarBtn.textContent = 'Selecciona fecha y hora';
          continuarTexto.classList.add('hidden');
      }
  }

  // Cambiar hora
  document.getElementById('cambiar-hora').addEventListener('click', function() {
      horaSeleccionada = null;
      document.getElementById('horarios-container').classList.remove('hidden');
      document.getElementById('hora-seleccionada').classList.add('hidden');
      actualizarResumen();
  });

  // Cerrar modal al hacer clic fuera
  modal.addEventListener('click', function(e) {
      if (e.target === modal) {
          cerrarModal();
      }
  });

  // Inicializar calendario
  generarCalendario();
</script>
