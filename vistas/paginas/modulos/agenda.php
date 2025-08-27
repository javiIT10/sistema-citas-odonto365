<?php

// Configuración de zona horaria
date_default_timezone_set("America/Mexico_City");

// Función para recibir datos POST
function obtenerDatosCita()
{
  $datosPorDefecto = [
    "especialista_id" => 1,
    "especialista" => "Dra. María González",
    "especialidad" => "Ortodoncia",
    "fecha" => "2025-08-14",
    "hora" => "11:00",
    "cita_pago" => 150,
  ];

  //  Validar si existe un id de especialista POST
  if (!isset($_POST["especialista_id"])) {
    header("Location: http://localhost/sistema-citas-odonto365/");
    exit();
  }

  // Si vienen datos por POST, los usamos; si no, usamos los por defecto
  return [
    "especialista_id" => $_POST["especialista_id"] ?? $datosPorDefecto["especialista_id"],
    "especialista" => $_POST["especialista"] ?? $datosPorDefecto["especialista"],
    "especialidad" => $_POST["especialidad"] ?? $datosPorDefecto["especialidad"],
    "fecha" => $_POST["fecha"] ?? $datosPorDefecto["fecha"],
    "hora" => $_POST["hora"] ?? $datosPorDefecto["hora"],
    "cita_pago" => floatval($_POST["cita_pago"] ?? $datosPorDefecto["cita_pago"]),
  ];
}

$datosCita = obtenerDatosCita();
?>
<!-- Loader -->
<div
  id="loader"
  class="fixed inset-0 bg-white bg-opacity-80 flex flex-col items-center justify-center z-50 hidden"
>
  <svg
    class="animate-spin h-10 w-10 text-blue-700 mb-3"
    xmlns="http://www.w3.org/2000/svg"
    fill="none"
    viewBox="0 0 24 24"
  >
    <circle
      class="opacity-25"
      cx="12"
      cy="12"
      r="10"
      stroke="currentColor"
      stroke-width="4"
    ></circle>
    <path
      class="opacity-75"
      fill="currentColor"
      d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
    ></path>
  </svg>
  <p class="text-blue-800 font-semibold font-montserrat">
    Cargando disponibilidad...
  </p>
</div>

<main class="mx-auto w-full max-w-7xl px-4 py-6">

  <!-- Layout responsive -->
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Columna izquierda: Calendario -->
    <div class="space-y-6">
      <!-- Mensaje de error cuando hay conflicto -->
      <div
        id="mensajeConflicto"
        class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm hidden"
      >
        <div class="text-center space-y-4">
          <div
            class="w-16 h-16 mx-auto bg-red-100 rounded-full flex items-center justify-center"
          >
            <svg
              class="h-8 w-8 text-red-600"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
              ></path>
            </svg>
          </div>
          <div>
            <h3 class="text-xl font-semibold text-red-700 font-montserrat mb-2">
              Horario No Disponible
            </h3>
            <p class="text-red-600">
              El horario seleccionado se solapa con una cita existente o es una
              fecha pasada. Por favor, utiliza el botón "Modificar" para
              seleccionar una nueva fecha y hora disponible.
            </p>
          </div>
        </div>
      </div>

      <!-- Leyendas -->
      <div
        id="leyendas"
        class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm"
      >
        <div
          class="flex flex-col items-center gap-4 md:flex-row md:items-center md:gap-6"
        >
          <div
            class="flex h-10 w-10 items-center justify-center rounded-lg border border-slate-200 bg-white"
          >
            <svg
              class="h-5 w-5 text-slate-700"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
              ></path>
            </svg>
          </div>
          <div
            class="flex w-full flex-wrap items-center justify-center gap-4 md:justify-start"
          >
            <div class="flex items-center gap-2">
              <span
                class="inline-block h-4 w-6 rounded-md bg-emerald-500"
              ></span>
              <span class="text-slate-600 font-medium">Hoy</span>
            </div>
            <div class="flex items-center gap-2">
              <span class="inline-block h-4 w-6 rounded-md bg-blue-600"></span>
              <span class="text-slate-600 font-medium">Seleccionado</span>
            </div>
            <div class="flex items-center gap-2">
              <span class="inline-block h-4 w-6 rounded-md bg-slate-200"></span>
              <span class="text-slate-600 font-medium">Ocupado</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Navegación de mes y ventana -->
      <div id="navegacion" class="flex items-center justify-between">
        <button
          id="btnAnterior"
          class="h-12 w-12 rounded-full border border-slate-200 bg-white text-slate-700 flex items-center justify-center transition-all duration-200 font-medium hover:border-blue-300 hover:bg-blue-50 hover:text-blue-700 active:scale-95 cursor-pointer"
        >
          <svg
            class="h-5 w-5"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M15 19l-7-7 7-7"
            ></path>
          </svg>
        </button>
        <h2
          id="etiquetaMes"
          class="text-xl font-semibold tracking-tight text-blue-800 font-montserrat"
        ></h2>
        <button
          id="btnSiguiente"
          class="h-12 w-12 rounded-full border border-slate-200 bg-white text-slate-700 flex items-center justify-center transition-all duration-200 font-medium hover:border-blue-300 hover:bg-blue-50 hover:text-blue-700 active:scale-95 cursor-pointer"
        >
          <svg
            class="h-5 w-5"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M9 5l7 7-7 7"
            ></path>
          </svg>
        </button>
      </div>

      <!-- Selector de días -->
      <div id="selectorDias" class="grid grid-cols-4 gap-4"></div>

      <!-- Grilla de horas -->
      <div id="grillaHoras" class="grid grid-cols-4 gap-4"></div>
    </div>

    <!-- Columna derecha: Resumen de la Cita -->
    <div class="lg:sticky lg:top-6 lg:h-fit">
      <section>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
          <h3
            class="text-2xl font-extrabold tracking-tight text-blue-800 font-montserrat"
          >
            Resumen de la Cita
          </h3>

          <!-- Especialidad -->
          <div class="mt-6">
            <div class="text-xl font-semibold text-blue-900 font-montserrat">
              Especialidad
            </div>
            <div
              class="mt-3 rounded-xl bg-slate-100 px-4 py-3 text-slate-600 font-medium border border-slate-200"
            >
              <?php echo htmlspecialchars($datosCita["especialidad"]); ?>
            </div>
          </div>

          <!-- Especialista -->
          <div class="mt-6">
            <div class="text-xl font-semibold text-blue-900 font-montserrat">
              Especialista
            </div>
            <div class="mt-3 flex items-center gap-3 text-slate-700">
              <svg
                class="h-5 w-5 text-blue-700"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                ></path>
              </svg>
              <span class="text-lg font-medium"
                ><?php echo htmlspecialchars($datosCita["especialista"]); ?></span
              >
            </div>
          </div>

          <!-- Fecha y Hora -->
          <div class="mt-6">
            <div class="text-xl font-semibold text-blue-900 font-montserrat">
              Fecha y Hora
            </div>
            <div class="mt-3 flex flex-col gap-3 text-slate-700">
              <div class="flex items-center gap-3">
                <svg
                  class="h-5 w-5 text-blue-700"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                  ></path>
                </svg>
                <span
                  id="fechaTexto"
                  class="text-lg font-medium capitalize"
                ></span>
              </div>
              <div class="flex items-center gap-3">
                <svg
                  class="h-5 w-5 text-blue-700"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                  ></path>
                </svg>
                <span id="horaTexto" class="text-lg font-medium"
                  ><?php echo htmlspecialchars($datosCita["hora"]); ?></span
                >
              </div>
            </div>
            <button
              id="btnModificar"
              class="mt-3 h-10 px-4 rounded-xl cursor-pointer bg-white border border-slate-200 hover:bg-slate-50 hover:border-slate-300 transition-all duration-200 font-medium"
            >
              Modificar
            </button>
          </div>

          <!-- Código de Cita -->
          <div id="codigoCitaContainer" class="mt-6">
            <div class="text-xl font-semibold text-blue-900 font-montserrat">
              Código de Cita
            </div>
            <div
              id="codigoCita"
              class="mt-3 rounded-xl bg-blue-50 border border-blue-100 px-4 py-3 text-blue-800 font-mono text-lg font-semibold"
            ></div>
          </div>

          <!-- Total -->
          <div id="totalContainer">
            <div class="w-full h-px bg-slate-200 my-6"></div>
            <div class="flex items-start justify-between">
              <div class="text-xl font-semibold text-blue-900 font-montserrat">
                Total a pagar:
              </div>
              <div class="text-2xl font-bold text-emerald-600 font-montserrat">
                $<?php echo number_format($datosCita["cita_pago"], 2); ?>
              </div>
            </div>
            <div class="mt-2 text-slate-400 font-medium">
              Consulta especializada
            </div>

            <button
              id="btnPreAgendar"
              class="mt-5 h-12 w-full rounded-xl bg-blue-700 hover:bg-blue-800 active:bg-blue-900 text-white text-lg gap-2 cursor-pointer transition-all duration-200 font-semibold border-0 flex items-center justify-center"
            >
              <svg
                class="h-5 w-5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                ></path>
              </svg>
              Preagendar Cita
            </button>
          </div>
        </div>
      </section>
    </div>
  </div>

  <!-- Modal de modificación -->
  <div
    id="modal"
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 hidden z-50"
  >
    <div
      class="max-w-md w-full mx-auto my-8 border border-slate-200 bg-white rounded-2xl shadow-lg"
    >
      <div class="p-6">
        <div class="flex items-center justify-between mb-4">
          <h3 class="font-montserrat text-xl font-semibold text-blue-800">
            Modificar Cita
          </h3>
          <button
            id="btnCerrarModal"
            class="text-slate-400 hover:text-slate-600"
          >
            <svg
              class="h-6 w-6"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12"
              ></path>
            </svg>
          </button>
        </div>

        <!-- Toggle de vista -->
        <div
          class="flex rounded-lg bg-slate-100 p-1 border border-slate-200 mb-4"
        >
          <button
            id="btnVistaFecha"
            class="flex-1 py-2 px-4 rounded-md text-sm font-medium transition-all duration-200 border-0 bg-white text-blue-700 shadow-sm border border-slate-200"
          >
            Fecha
          </button>
          <button
            id="btnVistaHora"
            class="flex-1 py-2 px-4 rounded-md text-sm font-medium transition-all duration-200 border-0 text-slate-600 hover:text-slate-900 hover:bg-slate-50 cursor-pointer opacity-50 cursor-not-allowed"
          >
            Hora
          </button>
        </div>

        <!-- Vista de fecha -->
        <div id="vistaFecha" class="space-y-4">
          <!-- Navegación del mes -->
          <div class="flex items-center justify-between">
            <button
              id="btnMesAnterior"
              class="p-2 hover:bg-slate-100 rounded-lg cursor-pointer transition-all duration-200 border-0 bg-transparent"
            >
              <svg
                class="h-4 w-4"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M15 19l-7-7 7-7"
                ></path>
              </svg>
            </button>
            <h3
              id="mesModalTitulo"
              class="font-semibold font-montserrat text-blue-800"
            ></h3>
            <button
              id="btnMesSiguiente"
              class="p-2 hover:bg-slate-100 rounded-lg cursor-pointer transition-all duration-200 border-0 bg-transparent"
            >
              <svg
                class="h-4 w-4"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 5l7 7-7 7"
                ></path>
              </svg>
            </button>
          </div>

          <!-- Días de la semana -->
          <div
            class="grid grid-cols-7 gap-1 text-center text-sm font-medium text-slate-600"
          >
            <div class="p-2 font-semibold">dom</div>
            <div class="p-2 font-semibold">lun</div>
            <div class="p-2 font-semibold">mar</div>
            <div class="p-2 font-semibold">mié</div>
            <div class="p-2 font-semibold">jue</div>
            <div class="p-2 font-semibold">vie</div>
            <div class="p-2 font-semibold">sáb</div>
          </div>

          <!-- Calendario -->
          <div id="calendarioModal" class="grid grid-cols-7 gap-1"></div>
        </div>

        <!-- Vista de hora -->
        <div id="vistaHora" class="space-y-4 hidden">
          <h3
            id="fechaSeleccionadaTitulo"
            class="font-semibold font-montserrat text-center text-blue-800"
          ></h3>
          <div
            id="horasModal"
            class="grid grid-cols-2 gap-3 max-h-60 overflow-y-auto"
          ></div>
        </div>
      </div>
    </div>
  </div>
</main>

<script>
  // Datos iniciales desde PHP
  const rutaPrincipal = "<?php echo $ruta; ?>";
  const datosCitaIniciales = <?php echo json_encode($datosCita); ?>;
  let eventosOcupados = [];

  
  // Variables globales
  let datosCita = {
      especialista_id: datosCitaIniciales.especialista_id,
      id_usuario: 1,
      fecha: new Date(datosCitaIniciales.fecha),
      hora: datosCitaIniciales.hora,
      cita_pago: datosCitaIniciales.cita_pago,

      // Estados para el código
      cita_codigo: null,        // cache del código generado para la fecha actual
      _fechaCodigoMs: null      // timestamp del día de la fecha (para evitar recálculo innecesario)
  };

  console.log(datosCita);
  

  let ultimaSolicitudCodigo = 0;
  
  

  async function cargarEventos() {
    // Mostrar la pantalla de carga
    document.getElementById('loader').classList.remove('hidden');

    try {
      // Esperar un momento para que el spinner sea visible
      await new Promise(resolve => setTimeout(resolve, 300)); // 300 ms

      const response = await
      fetch(`${rutaPrincipal}controladores/agenda.controlador.php`, {
        method: "POST",
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify({ especialista_id: datosCitaIniciales.especialista_id})
      });

      if (!response.ok) {
        throw new Error("Error al traer los eventos");
      }

      const data = await response.json();
      eventosOcupados = data;

      actualizarInterfaz();
    } catch (error) {
      console.error("Error:", error);
      alert("Hubo un problema al cargar la disponibilidad");
    } finally {
      // Ocualtar pantalla de carga
      document.getElementById('loader').classList.add('hidden');
    }
  }

  // Constantes
  const DIAS_SEMANA_CORTO = ["dom", "lun", "mar", "mié", "jue", "vie", "sáb"];
  const MESES = [
      "enero", "febrero", "marzo", "abril", "mayo", "junio",
      "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre"
  ];
  const HORAS = ["08:00", "09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00", "17:00"];

  // Fechas límite
  const HOY = new Date();
  HOY.setHours(0, 0, 0, 0);
  const LIMITE_SUPERIOR = new Date(HOY);
  LIMITE_SUPERIOR.setMonth(LIMITE_SUPERIOR.getMonth() + 2);
  const MIN_INICIO_VENTANA = new Date(HOY);
  const MAX_INICIO_VENTANA = new Date(LIMITE_SUPERIOR);
  MAX_INICIO_VENTANA.setDate(MAX_INICIO_VENTANA.getDate() - 3);

  // Estado de la aplicación
  let fechaInicioVentana = calcularInicioVentana(datosCita.fecha);
  let modalAbierto = false;
  let vistaModal = "fecha";
  let fechaModalSeleccionada = null;
  let horaModalSeleccionada = null;
  let mesModalActual = new Date();

  // Utilidades de fecha
  function inicioDeDia(d) {
      return new Date(d.getFullYear(), d.getMonth(), d.getDate());
  }

  function sumarDias(d, cantidad) {
      const nd = new Date(d);
      nd.setDate(nd.getDate() + cantidad);
      return nd;
  }

  function sumarMeses(d, cantidad) {
      const nd = new Date(d);
      nd.setMonth(nd.getMonth() + cantidad);
      return nd;
  }

  function esMismoDia(a, b) {
      return a.getFullYear() === b.getFullYear() &&
             a.getMonth() === b.getMonth() &&
             a.getDate() === b.getDate();
  }

  function esDomingo(d) {
      return d.getDay() === 0;
  }

  function claveISOFecha(d) {
      const y = d.getFullYear();
      const m = String(d.getMonth() + 1).padStart(2, "0");
      const dd = String(d.getDate()).padStart(2, "0");
      return `${y}-${m}-${dd}`;
  }

  function fechaHoraCompleta(fecha, hora) {
      const fechaStr = claveISOFecha(fecha);
      return `${fechaStr} ${hora}`;
  }

  function sumarUnaHora(fechaHora) {
      const [fechaParte, horaParte] = fechaHora.split(" ");
      const [horas, minutos] = horaParte.split(":").map(Number);

      const fecha = new Date(fechaParte + "T" + horaParte + ":00");
      fecha.setHours(fecha.getHours() + 1);

      const nuevaFecha = claveISOFecha(fecha);
      const nuevaHora = `${String(fecha.getHours()).padStart(2, "0")}:${String(fecha.getMinutes()).padStart(2, "0")}`;

      return `${nuevaFecha} ${nuevaHora}`;
  }

  function rangosSeSolapan(inicio1, fin1, inicio2, fin2) {
      const fechaInicio1 = new Date(inicio1.replace(" ", "T") );
      const fechaFin1 = new Date(fin1.replace(" ", "T"));
      const fechaInicio2 = new Date(inicio2.replace(" ", "T"));
      const fechaFin2 = new Date(fin2.replace(" ", "T"));

      return fechaInicio1 < fechaFin2 && fechaInicio2 < fechaFin1;
  }

  function obtenerHorasOcupadasEnDia(dia, eventos) {
      const fechaDia = claveISOFecha(dia);
      const horasOcupadas = new Set();

      eventos.forEach(evento => {
          const inicioEvento = new Date(evento.cita_inicio.replace(" ", "T"));
          const finEvento = new Date(evento.cita_fin.replace(" ", "T"));
          

          const horaActual = new Date(inicioEvento);
          while (horaActual < finEvento) {
              const fechaHoraActual = claveISOFecha(horaActual);
              if (fechaHoraActual === fechaDia) {
                  const horaStr = `${String(horaActual.getHours()).padStart(2, "0")}:${String(horaActual.getMinutes()).padStart(2, "0")}`;
                  horasOcupadas.add(horaStr);
              }
              horaActual.setHours(horaActual.getHours() + 1);
          }
      });

      return horasOcupadas;
  }

  function calcularInicioVentana(fechaSeleccionada) {
      const candidatoInicio = sumarDias(fechaSeleccionada, -3);
      const tiempo = candidatoInicio.getTime();
      const minTiempo = MIN_INICIO_VENTANA.getTime();
      const maxTiempo = MAX_INICIO_VENTANA.getTime();
      return new Date(Math.max(minTiempo, Math.min(maxTiempo, tiempo)));
  }

  function tieneConflicto() {
      const fechaCitaInicio = inicioDeDia(datosCita.fecha);
      const esFechaPasada = fechaCitaInicio < HOY;

      if (esFechaPasada) {
          return true;
      }

      const inicioNuevaCita = fechaHoraCompleta(datosCita.fecha, datosCita.hora);
      const finNuevaCita = sumarUnaHora(inicioNuevaCita);

      return eventosOcupados.some(evento =>
          rangosSeSolapan(inicioNuevaCita, finNuevaCita, evento.cita_inicio, evento.cita_fin)
      );
  }

  function estaOcupado(dia, hora) {
      if (esDomingo(dia)) return true;

      const horasOcupadasEnDia = obtenerHorasOcupadasEnDia(dia, eventosOcupados);
      
      return horasOcupadasEnDia.has(hora);
  }

  
  // Helper accesible para pintar estados en el div #codigoCita
  function setEstadoCodigo(texto, { busy = false, error = false } = {}) {
    const box = document.getElementById('codigoCita');
    if (!box) return;

    box.setAttribute('aria-live', 'polite');
    box.setAttribute('aria-busy', busy ? 'true' : 'false');

    box.classList.toggle('opacity-60', !!busy);
    box.classList.toggle('text-red-700', !!error);
    box.classList.toggle('border-red-200', !!error);
    box.classList.toggle('bg-red-50', !!error);

    box.textContent = texto ?? '';
  }

  // Normaliza a Date sin hora
  function normalizarFecha(f) {
    if (f instanceof Date) return new Date(f.getFullYear(), f.getMonth(), f.getDate());
    return new Date(String(f) + "T00:00");
  }

  async function generarCodigoCita(fecha) {
    let codigo;
    let existe = true;

    do {
      const año = fecha.getFullYear();
      const mes = String(fecha.getMonth() + 1).padStart(2, "0");
      const dia = String(fecha.getDate()).padStart(2, "0");
      const digitos = Math.floor(1000 + Math.random() * 9000);
      codigo = `CITA-${año}${mes}${dia}-${digitos}`;

      const resp = await fetch(`${rutaPrincipal}controladores/validarCodigo.controlador.php`, {
        method: "POST",
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify({ codigo })
      });

      if (!resp.ok) throw new Error(`HTTP ${resp.status}`);
      const data = await resp.json();

      existe = Boolean(data?.existe);  // true si ya está en DB, false si no está
      console.log("validación", { codigo, existe });
      console.log(codigo);
      

    } while (existe);


    return codigo;
  }

  async function mostrarCodigoCita() {
    const box = document.getElementById('codigoCita');
    if (!box) return;

    const fecha = normalizarFecha(datosCita.fecha);
    const stamp = fecha.getTime();

    // si ya hay un código válido para ese día, reusar
    if (datosCita.cita_codigo && datosCita._fechaCodigoMs === stamp) {
      setEstadoCodigo(datosCita.cita_codigo);
      return;
    }

    setEstadoCodigo('Generando código…', { busy: true });
    const miSolicitud = ++ultimaSolicitudCodigo;

    try {
      const codigo = await generarCodigoCita(fecha);

      // aplica solo si esta fue la última solicitud
      if (miSolicitud !== ultimaSolicitudCodigo) return;

      datosCita.cita_codigo = codigo;
      datosCita._fechaCodigoMs = stamp;
      setEstadoCodigo(codigo);
    } catch (err) {
      if (miSolicitud !== ultimaSolicitudCodigo) return;
      console.error('Error al generar código:', err);
      setEstadoCodigo('Error al generar el código', { error: true });
    }
  }

  function actualizarInterfaz() {
      const conflicto = tieneConflicto();

      // Mostrar/ocultar elementos según conflicto
      document.getElementById('mensajeConflicto').classList.toggle('hidden', !conflicto);
      document.getElementById('leyendas').classList.toggle('hidden', conflicto);
      document.getElementById('navegacion').classList.toggle('hidden', conflicto);
      document.getElementById('selectorDias').classList.toggle('hidden', conflicto);
      document.getElementById('grillaHoras').classList.toggle('hidden', conflicto);
      document.getElementById('codigoCitaContainer').classList.toggle('hidden', conflicto);
      document.getElementById('totalContainer').classList.toggle('hidden', conflicto);

      if (!conflicto) {
          // Actualizar etiqueta de mes
          const nombreMes = MESES[fechaInicioVentana.getMonth()];
          const capitalizado = nombreMes.charAt(0).toUpperCase() + nombreMes.slice(1);
          document.getElementById('etiquetaMes').textContent = `${capitalizado} ${fechaInicioVentana.getFullYear()}`;

          // Actualizar botones de navegación
          const deshabilitarAnterior = inicioDeDia(fechaInicioVentana).getTime() <= MIN_INICIO_VENTANA.getTime();
          const deshabilitarSiguiente = inicioDeDia(fechaInicioVentana).getTime() >= MAX_INICIO_VENTANA.getTime();

          const btnAnterior = document.getElementById('btnAnterior');
          const btnSiguiente = document.getElementById('btnSiguiente');

          btnAnterior.disabled = deshabilitarAnterior;
          btnSiguiente.disabled = deshabilitarSiguiente;

          if (deshabilitarAnterior) {
              btnAnterior.classList.add('opacity-50', 'cursor-not-allowed', 'bg-slate-50');
              btnAnterior.classList.remove('hover:border-blue-300', 'hover:bg-blue-50', 'hover:text-blue-700', 'cursor-pointer');
          } else {
              btnAnterior.classList.remove('opacity-50', 'cursor-not-allowed', 'bg-slate-50');
              btnAnterior.classList.add('hover:border-blue-300', 'hover:bg-blue-50', 'hover:text-blue-700', 'cursor-pointer');
          }

          if (deshabilitarSiguiente) {
              btnSiguiente.classList.add('opacity-50', 'cursor-not-allowed', 'bg-slate-50');
              btnSiguiente.classList.remove('hover:border-blue-300', 'hover:bg-blue-50', 'hover:text-blue-700', 'cursor-pointer');
          } else {
              btnSiguiente.classList.remove('opacity-50', 'cursor-not-allowed', 'bg-slate-50');
              btnSiguiente.classList.add('hover:border-blue-300', 'hover:bg-blue-50', 'hover:text-blue-700', 'cursor-pointer');
          }

          // Generar días visibles
          const diasVisibles = Array.from({ length: 4 }, (_, i) => sumarDias(fechaInicioVentana, i));

          // Actualizar selector de días
          const selectorDias = document.getElementById('selectorDias');
          selectorDias.innerHTML = '';

          diasVisibles.forEach(d => {
              const seleccionado = esMismoDia(d, datosCita.fecha);
              const hoy = esMismoDia(d, HOY);
              const domingo = esDomingo(d);

              const div = document.createElement('div');
              div.className = `rounded-3xl border p-4 text-center relative select-none transition-all duration-200 ${
                  domingo ? 'bg-slate-100 text-slate-400 border-slate-200 cursor-not-allowed' :
                  seleccionado ? 'bg-blue-700 text-white border-blue-700 shadow-md' :
                  'bg-white text-slate-700 border-slate-200 hover:border-blue-200 hover:shadow-sm'
              }`;

              div.innerHTML = `
                  ${hoy ? `<span class="absolute left-2 top-2 h-2 w-2 rounded-full bg-emerald-500 ${seleccionado && !domingo ? 'bg-white' : ''}"></span>` : ''}
                  <div class="text-base capitalize font-medium ${seleccionado && !domingo ? 'text-white/90' : 'text-blue-900/90'}">
                      ${DIAS_SEMANA_CORTO[d.getDay()]}
                  </div>
                  <div class="text-3xl font-bold leading-tight">${d.getDate()}</div>
                  <div class="text-sm font-medium ${seleccionado && !domingo ? 'text-white/90' : 'text-blue-900/90'}">
                      ${MESES[d.getMonth()].substring(0, 3)}
                  </div>
              `;

              selectorDias.appendChild(div);
          });

          // Actualizar grilla de horas
          const grillaHoras = document.getElementById('grillaHoras');
          grillaHoras.innerHTML = '';

          HORAS.forEach(hora => {
              diasVisibles.forEach((dia, idxDia) => {
                  const ocupado = estaOcupado(dia, hora);
                  const seleccionado = esMismoDia(dia, datosCita.fecha) && hora === datosCita.hora && !ocupado;

                  const div = document.createElement('div');
                  div.className = `h-12 rounded-2xl border text-sm font-semibold flex items-center justify-center select-none transition-all duration-200 ${
                      ocupado ? 'bg-slate-100 text-slate-400 border-slate-200' :
                      !seleccionado ? 'bg-white text-blue-900 border-slate-200 hover:border-blue-200 hover:shadow-sm' :
                      'bg-blue-600 text-white border-blue-600 shadow-md'
                  }`;

                  div.textContent = hora;
                  grillaHoras.appendChild(div);
              });
          });

          // Actualizar código de cita
          setEstadoCodigo('Generando código…', { busy: true });
          mostrarCodigoCita();
      }

      // Actualizar fecha y hora en el resumen
      const fechaTexto = new Intl.DateTimeFormat('es-ES', {
          weekday: 'long',
          day: 'numeric',
          month: 'long',
          year: 'numeric'
      }).format(datosCita.fecha);

      document.getElementById('fechaTexto').textContent = fechaTexto;
      document.getElementById('horaTexto').textContent = datosCita.hora;
  }

  function irAnterior() {
      const deshabilitarAnterior = inicioDeDia(fechaInicioVentana).getTime() <= MIN_INICIO_VENTANA.getTime();
      if (deshabilitarAnterior) return;

      const nuevaFecha = sumarDias(fechaInicioVentana, -4);
      const tiempo = nuevaFecha.getTime();
      const minTiempo = MIN_INICIO_VENTANA.getTime();
      const maxTiempo = MAX_INICIO_VENTANA.getTime();
      fechaInicioVentana = new Date(Math.max(minTiempo, Math.min(maxTiempo, tiempo)));

      actualizarInterfaz();
  }

  function irSiguiente() {
      const deshabilitarSiguiente = inicioDeDia(fechaInicioVentana).getTime() >= MAX_INICIO_VENTANA.getTime();
      if (deshabilitarSiguiente) return;

      const nuevaFecha = sumarDias(fechaInicioVentana, 4);
      const tiempo = nuevaFecha.getTime();
      const minTiempo = MIN_INICIO_VENTANA.getTime();
      const maxTiempo = MAX_INICIO_VENTANA.getTime();
      fechaInicioVentana = new Date(Math.max(minTiempo, Math.min(maxTiempo, tiempo)));

      actualizarInterfaz();
  }

  function abrirModal() {
      modalAbierto = true;
      vistaModal = "fecha";
      fechaModalSeleccionada = null;
      horaModalSeleccionada = null;
      mesModalActual = new Date();

      document.getElementById('modal').classList.remove('hidden');
      actualizarModal();
  }

  function cerrarModal() {
      modalAbierto = false;
      document.getElementById('modal').classList.add('hidden');
  }

  function cambiarVistaModal(vista) {
      if (vista === "hora" && !fechaModalSeleccionada) return;

      vistaModal = vista;
      actualizarModal();
  }

  function seleccionarFechaModal(fecha) {
      fechaModalSeleccionada = fecha;
      vistaModal = "hora";
      actualizarModal();
  }

  function seleccionarHoraModal(hora) {
      horaModalSeleccionada = hora;

      if (fechaModalSeleccionada) {
          const inicioNuevaCita = fechaHoraCompleta(fechaModalSeleccionada, hora);
          const finNuevaCita = sumarUnaHora(inicioNuevaCita);

          const hayConflicto = eventosOcupados.some(evento =>
              rangosSeSolapan(inicioNuevaCita, finNuevaCita, evento.cita_inicio, evento.cita_fin)
          );

          if (!hayConflicto) {
              datosCita.fecha = fechaModalSeleccionada;
              datosCita.hora = hora;

              fechaInicioVentana = calcularInicioVentana(fechaModalSeleccionada);
              cargarEventos();
          }

          cerrarModal();
      }
  }

  function navegarMesModal(direccion) {
      const nuevoMes = direccion === "anterior" ? sumarMeses(mesModalActual, -1) : sumarMeses(mesModalActual, 1);

      const limiteInferior = new Date(HOY.getFullYear(), HOY.getMonth(), 1);
      const limiteSuperior = new Date(LIMITE_SUPERIOR.getFullYear(), LIMITE_SUPERIOR.getMonth(), 1);

      if (nuevoMes >= limiteInferior && nuevoMes <= limiteSuperior) {
          mesModalActual = nuevoMes;
          actualizarModal();
      }
  }

  function actualizarModal() {
      // Actualizar botones de vista
      const btnVistaFecha = document.getElementById('btnVistaFecha');
      const btnVistaHora = document.getElementById('btnVistaHora');

      if (vistaModal === "fecha") {
          btnVistaFecha.className = "flex-1 py-2 px-4 rounded-md text-sm font-medium transition-all duration-200 border-0 bg-white text-blue-700 shadow-sm border border-slate-200";
          btnVistaHora.className = `flex-1 py-2 px-4 rounded-md text-sm font-medium transition-all duration-200 border-0 text-slate-600 hover:text-slate-900 hover:bg-slate-50 ${!fechaModalSeleccionada ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'}`;
      } else {
          btnVistaFecha.className = "flex-1 py-2 px-4 rounded-md text-sm font-medium transition-all duration-200 border-0 text-slate-600 hover:text-slate-900 hover:bg-slate-50 cursor-pointer";
          btnVistaHora.className = "flex-1 py-2 px-4 rounded-md text-sm font-medium transition-all duration-200 border-0 bg-white text-blue-700 shadow-sm border border-slate-200";
      }

      // Mostrar/ocultar vistas
      document.getElementById('vistaFecha').classList.toggle('hidden', vistaModal !== "fecha");
      document.getElementById('vistaHora').classList.toggle('hidden', vistaModal !== "hora");

      if (vistaModal === "fecha") {
          // Actualizar título del mes
          const nombreMes = MESES[mesModalActual.getMonth()];
          const capitalizado = nombreMes.charAt(0).toUpperCase() + nombreMes.slice(1);
          document.getElementById('mesModalTitulo').textContent = `${capitalizado} ${mesModalActual.getFullYear()}`;

          // Generar calendario
          const primerDia = new Date(mesModalActual.getFullYear(), mesModalActual.getMonth(), 1);
          const ultimoDia = new Date(mesModalActual.getFullYear(), mesModalActual.getMonth() + 1, 0);
          const diasDelMes = [];

          // Días vacíos al inicio
          const diaSemanaPrimero = primerDia.getDay();
          for (let i = 0; i < diaSemanaPrimero; i++) {
              diasDelMes.push(null);
          }

          // Días del mes
          for (let dia = 1; dia <= ultimoDia.getDate(); dia++) {
              diasDelMes.push(new Date(mesModalActual.getFullYear(), mesModalActual.getMonth(), dia));
          }

          const calendarioModal = document.getElementById('calendarioModal');
          calendarioModal.innerHTML = '';

          diasDelMes.forEach((dia, index) => {
              const button = document.createElement('button');

              if (!dia) {
                  button.className = 'p-2';
                  calendarioModal.appendChild(button);
                  return;
              }

              const esHoy = esMismoDia(dia, HOY);
              const esPasado = dia < HOY;
              const esDomingoModal = esDomingo(dia);
              const estaSeleccionado = fechaModalSeleccionada && esMismoDia(dia, fechaModalSeleccionada);
              const estaDeshabilitado = esPasado || esHoy || esDomingoModal;

              button.className = `p-2 text-sm rounded-lg transition-all duration-200 font-medium border-0 ${
                  estaDeshabilitado ? 'text-slate-300 cursor-not-allowed bg-transparent' :
                  !estaSeleccionado ? 'hover:bg-slate-100 cursor-pointer bg-transparent' :
                  'bg-blue-600 text-white shadow-sm'
              }`;

              button.textContent = dia.getDate();
              button.disabled = estaDeshabilitado;

              if (!estaDeshabilitado) {
                  button.onclick = () => seleccionarFechaModal(dia);
              }

              calendarioModal.appendChild(button);
          });
      } else if (vistaModal === "hora" && fechaModalSeleccionada) {
          // Actualizar título de fecha seleccionada
          const fechaTexto = new Intl.DateTimeFormat('es-ES', {
              weekday: 'long',
              day: 'numeric',
              month: 'long',
              year: 'numeric'
          }).format(fechaModalSeleccionada);

          document.getElementById('fechaSeleccionadaTitulo').textContent = fechaTexto;

          // Generar horas
          const horasModal = document.getElementById('horasModal');
          horasModal.innerHTML = '';

          HORAS.forEach(hora => {
              const horasOcupadasEnDia = obtenerHorasOcupadasEnDia(fechaModalSeleccionada, eventosOcupados);
              const estaOcupado = horasOcupadasEnDia.has(hora);
              const estaSeleccionado = horaModalSeleccionada === hora;

              const button = document.createElement('button');
              button.className = `p-3 text-sm rounded-lg border transition-all duration-200 font-semibold ${
                  estaOcupado ? 'bg-slate-100 text-slate-400 cursor-not-allowed border-slate-200' :
                  !estaSeleccionado ? 'bg-white hover:bg-slate-50 border-slate-200 cursor-pointer hover:border-blue-200' :
                  'bg-blue-600 text-white border-blue-600 shadow-sm'
              }`;

              button.textContent = hora;
              button.disabled = estaOcupado;

              if (!estaOcupado) {
                  button.onclick = () => seleccionarHoraModal(hora);
              }

              horasModal.appendChild(button);
          });
      }
  }

  async function preagendar() {
    // 1. Validar que no hay traslapes en cliente
    if (tieneConflicto()) {
      await Swal.fire({
        icon: "info",
        title: "Aviso",
        text: "El horario está ocupado o la fecha es inválida"
      });
    return;
    }

    // 2. Asegurar que ya tenemos un código de cita
    if (!datosCita.cita_codigo) {
      await mostrarCodigoCita();
      if (!datosCita.cita_codigo) {
        await Swal.fire({
          icon: "error",
          title: "Error",
          text: "No se pudo generar el código de la cita"
        });
        return;
      }
    }

    // 3. Preparar inicio y fin
    const inicio = `${claveISOFecha(datosCita.fecha)} ${datosCita.hora}`;
    const fin    = sumarUnaHora(inicio);

    // 4. Armar payload para enviar
    const payload = {
      id_especialista: datosCita.especialista_id,
      id_usuario: datosCita.id_usuario,    // inyectado desde PHP al renderizar agenda.php
      codigo_cita: datosCita.cita_codigo,
      cita_inicio: inicio,
      cita_fin: fin,
      motivo: null
    };

    // 5. Mostrar loader
    Swal.fire({
      title: "Guardando tu cita...",
      allowOutsideClick: false,
      didOpen: () => Swal.showLoading()
    });

    try {
      // 6. Llamada al controlador
      const resp = await fetch(`${rutaPrincipal}controladores/preagendar.controlador.php`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload)
      });
      const data = await resp.json();

      // 7. Manejo de errores
      if (!resp.ok || !data.ok) {
        throw new Error(data.error || "Error al crear la cita");
      }

      // 8. Éxito → mostrar alerta y redirigir
      await Swal.fire({
        icon: "success",
        title: "¡Cita pre-agendada!",
        text: "Tu cita quedó registrada como pendiente",
        confirmButtonText: "Ir a mi perfil"
      });

      window.location.href = `${rutaPrincipal}perfil`;

    } catch (err) {
      await Swal.fire({
        icon: "error",
        title: "Error",
        text: "No se pudo guardar la cita: " + err.message
      });
    }
  }

  // Event listeners
  document.getElementById('btnAnterior').onclick = irAnterior;
  document.getElementById('btnSiguiente').onclick = irSiguiente;
  document.getElementById('btnModificar').onclick = abrirModal;
  document.getElementById('btnCerrarModal').onclick = cerrarModal;
  document.getElementById('btnVistaFecha').onclick = () => cambiarVistaModal("fecha");
  document.getElementById('btnVistaHora').onclick = () => cambiarVistaModal("hora");
  document.getElementById('btnMesAnterior').onclick = () => navegarMesModal("anterior");
  document.getElementById('btnMesSiguiente').onclick = () => navegarMesModal("siguiente");
   document.getElementById("btnPreAgendar").onclick = () => preagendar();

  // Cerrar modal al hacer clic fuera
  document.getElementById('modal').onclick = (e) => {
      if (e.target.id === 'modal') {
          cerrarModal();
      }
  };

  // cargar eventos
  cargarEventos();
</script>
