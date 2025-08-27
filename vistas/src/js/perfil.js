// Variables globales
let datosUsuario = null;
let citaPendiente = null;
let historialCitas = [];
let paymentBrick = null;
let paginaActual = 1;
const citasPorPagina = 5;
const lucide = window.lucide; // Declare the lucide variable

document.addEventListener("DOMContentLoaded", () => {
  cargarDatosPerfil();
  lucide.createIcons();
});

async function cargarDatosPerfil() {
  try {
    const startTime = Date.now();

    const userId = "USR-20250116-001";

    const response = await fetch(
      "http://localhost/sistema-citas-odonto365/controladores/perfil.controlador.php",
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          action: "obtenerDatosPerfil",
          userId: userId,
        }),
      }
    );

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const data = await response.json();

    if (!data.success) {
      throw new Error(data.error || "Error desconocido");
    }

    const elapsedTime = Date.now() - startTime;
    const remainingTime = Math.max(300 - elapsedTime, 0);

    setTimeout(() => {
      datosUsuario = data.data.usuario;
      citaPendiente = data.data.citaPendiente;
      historialCitas = data.data.historialCitas || [];

      renderizarInterfaz();

      document.getElementById("loadingSpinner").classList.add("hidden");
      document.getElementById("contenidoPrincipal").classList.remove("hidden");

      lucide.createIcons();
    }, remainingTime);
  } catch (error) {
    console.error("Error:", error);
    setTimeout(() => {
      mostrarErrorCarga();
    }, 300);
  }
}

function mostrarErrorCarga() {
  document.getElementById("loadingSpinner").innerHTML = `
        <div class="text-center">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="alert-circle" class="h-8 w-8 text-red-600"></i>
            </div>
            <p class="text-red-700 font-medium mb-4">Error al cargar los datos</p>
            <button onclick="location.reload()" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                Reintentar
            </button>
        </div>
    `;
  lucide.createIcons();
}

function renderizarInterfaz() {
  renderizarSaludo();
  renderizarInfoUsuario();

  if (citaPendiente) {
    renderizarCitaPendiente();
  }

  if (historialCitas && historialCitas.length > 0) {
    renderizarHistorialCitas();
    actualizarPaginacion();
  } else {
    renderizarHistorialVacio();
  }
}

// Renderizar saludo del usuario
function renderizarSaludo() {
  const nombre = datosUsuario.nombre.split(" ")[1] || "Usuario";
  document.getElementById("saludoUsuario").textContent = `Hola, ${nombre}`;
}

// Renderizar información del usuario
function renderizarInfoUsuario() {
  const infoUsuario = document.getElementById("infoUsuario");
  infoUsuario.innerHTML = `
        <div class="flex flex-col md:flex-row items-start gap-6">
            <div class="flex-shrink-0">
                <img src="${datosUsuario.foto}" alt="Foto de perfil" class="w-32 h-32 rounded-full object-cover border-4 border-blue-100 shadow-md" />
            </div>
            <div class="flex-1 space-y-4">
                <div class="flex items-center gap-3">
                    <i data-lucide="user" class="h-5 w-5 text-blue-600"></i>
                    <div>
                        <p class="text-sm font-medium text-slate-500">Nombre</p>
                        <p class="text-lg font-semibold text-slate-900">${datosUsuario.nombre}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <i data-lucide="mail" class="h-5 w-5 text-blue-600"></i>
                    <div>
                        <p class="text-sm font-medium text-slate-500">Correo Electrónico</p>
                        <p class="text-lg font-semibold text-slate-900">${datosUsuario.email}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <i data-lucide="phone" class="h-5 w-5 text-blue-600"></i>
                    <div>
                        <p class="text-sm font-medium text-slate-500">Teléfono</p>
                        <p class="text-lg font-semibold text-slate-900">${datosUsuario.telefono}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <i data-lucide="badge" class="h-5 w-5 text-blue-600"></i>
                    <div>
                        <p class="text-sm font-medium text-slate-500">ID de Usuario</p>
                        <p class="text-lg font-semibold text-slate-900">${datosUsuario.id}</p>
                    </div>
                </div>
            </div>
        </div>
    `;
}

// Renderizar cita pendiente
function renderizarCitaPendiente() {
  const container = document.getElementById("citaPendienteContainer");
  const fecha = new Date(citaPendiente.fecha).toLocaleDateString("es-ES", {
    weekday: "long",
    year: "numeric",
    month: "long",
    day: "numeric",
  });

  container.innerHTML = `
        <div class="rounded-2xl border border-orange-200 bg-gradient-to-r from-orange-50 to-amber-50 p-6 shadow-sm">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                        <i data-lucide="alert-circle" class="h-6 w-6 text-orange-600"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-orange-900 font-montserrat mb-1">Cita Pendiente de Pago</h3>
                        <p class="text-orange-700 font-medium">Tienes una cita confirmada pendiente de pago</p>
                    </div>
                </div>
                <div class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold border bg-orange-100 text-orange-800 border-orange-200">
                    <i data-lucide="alert-circle" class="h-4 w-4"></i>
                    Pendiente
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <i data-lucide="stethoscope" class="h-5 w-5 text-orange-600"></i>
                        <div>
                            <p class="text-sm font-medium text-orange-600">Especialista</p>
                            <p class="text-lg font-semibold text-orange-900">${citaPendiente.especialista}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <i data-lucide="badge" class="h-5 w-5 text-orange-600"></i>
                        <div>
                            <p class="text-sm font-medium text-orange-600">Especialidad</p>
                            <p class="text-lg font-semibold text-orange-900">${citaPendiente.especialidad}</p>
                        </div>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <i data-lucide="calendar" class="h-5 w-5 text-orange-600"></i>
                        <div>
                            <p class="text-sm font-medium text-orange-600">Fecha</p>
                            <p class="text-lg font-semibold text-orange-900 capitalize">${fecha}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <i data-lucide="clock" class="h-5 w-5 text-orange-600"></i>
                        <div>
                            <p class="text-sm font-medium text-orange-600">Hora</p>
                            <p class="text-lg font-semibold text-orange-900">${citaPendiente.hora}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-between pt-4 border-t border-orange-200">
                <div>
                    <p class="text-sm font-medium text-orange-600">Total a pagar</p>
                    <p class="text-2xl font-bold text-orange-900">$${citaPendiente.total.toFixed(2)}</p>
                </div>
                <button onclick="procederAlPago()" class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-3 rounded-xl font-semibold flex items-center gap-2 transition-all duration-200">
                    <i data-lucide="credit-card" class="h-5 w-5"></i>
                    Proceder al Pago
                </button>
            </div>
        </div>
    `;

  container.classList.remove("hidden");
}

function renderizarHistorialCitas() {
  const container = document.getElementById("historialContainer");
  const totalPaginas = Math.ceil(historialCitas.length / citasPorPagina);
  const indiceInicio = (paginaActual - 1) * citasPorPagina;
  const indiceFin = indiceInicio + citasPorPagina;
  const citasActuales = historialCitas.slice(indiceInicio, indiceFin);

  let html = '<div class="space-y-4">';

  citasActuales.forEach((cita) => {
    const fecha = new Date(cita.fecha).toLocaleDateString("es-ES", {
      weekday: "long",
      year: "numeric",
      month: "long",
      day: "numeric",
    });

    const statusConfig = getStatusConfig(cita.status);

    html += `
            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 hover:shadow-md transition-all duration-200">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-bold text-slate-600">${cita.id}</span>
                    <div class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold border ${statusConfig.class}">
                        <i data-lucide="${statusConfig.icon}" class="h-4 w-4"></i>
                        ${statusConfig.text}
                    </div>
                </div>
                <div class="space-y-2">
                    <h3 class="text-lg font-semibold text-slate-900">${cita.especialista}</h3>
                    <p class="text-slate-600 font-medium">${cita.especialidad}</p>
                    <div class="flex items-center gap-4 text-sm text-slate-500">
                        <div class="flex items-center gap-1">
                            <i data-lucide="calendar" class="h-4 w-4"></i>
                            <span class="capitalize">${fecha}</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <i data-lucide="clock" class="h-4 w-4"></i>
                            <span>${cita.hora}</span>
                        </div>
                    </div>
                </div>
            </div>
        `;
  });

  html += "</div>";
  container.innerHTML = html;

  if (totalPaginas > 1) {
    document.getElementById("paginacionContainer").classList.remove("hidden");
  }
}

function actualizarPaginacion() {
  const totalPaginas = Math.ceil(historialCitas.length / citasPorPagina);
  const paginacion = document.getElementById("paginacion");
  const infoPaginacion = document.getElementById("infoPaginacion");

  if (totalPaginas <= 1) {
    document.getElementById("paginacionContainer").classList.add("hidden");
    return;
  }

  // Generar botones de paginación
  let html = `
        <button onclick="cambiarPagina(${paginaActual - 1})" 
                ${paginaActual === 1 ? "disabled" : ""}
                class="flex items-center gap-1 h-9 px-3 rounded-lg border-slate-300 hover:bg-blue-50 hover:border-blue-300 disabled:opacity-50 disabled:cursor-not-allowed bg-transparent border transition-all duration-200">
            <i data-lucide="chevron-left" class="h-4 w-4"></i>
            Anterior
        </button>
        <div class="flex items-center gap-1">
    `;

  for (let i = 1; i <= totalPaginas; i++) {
    const esActual = i === paginaActual;
    html += `
            <button onclick="cambiarPagina(${i})" 
                    class="h-9 w-9 rounded-lg ${
                      esActual
                        ? "bg-blue-700 hover:bg-blue-800 text-white"
                        : "border-slate-300 hover:bg-blue-50 hover:border-blue-300 bg-transparent border"
                    } transition-all duration-200">
                ${i}
            </button>
        `;
  }

  html += `
        </div>
        <button onclick="cambiarPagina(${paginaActual + 1})" 
                ${paginaActual === totalPaginas ? "disabled" : ""}
                class="flex items-center gap-1 h-9 px-3 rounded-lg border-slate-300 hover:bg-blue-50 hover:border-blue-300 disabled:opacity-50 disabled:cursor-not-allowed bg-transparent border transition-all duration-200">
            Siguiente
            <i data-lucide="chevron-right" class="h-4 w-4"></i>
        </button>
    `;

  paginacion.innerHTML = html;

  // Información de paginación
  const indiceInicio = (paginaActual - 1) * citasPorPagina;
  const indiceFin = Math.min(indiceInicio + citasPorPagina, historialCitas.length);

  infoPaginacion.innerHTML = `
        <p class="text-sm text-slate-500">
            Mostrando ${indiceInicio + 1} - ${indiceFin} de ${historialCitas.length} citas
        </p>
    `;
}

function cambiarPagina(nuevaPagina) {
  const totalPaginas = Math.ceil(historialCitas.length / citasPorPagina);
  if (nuevaPagina >= 1 && nuevaPagina <= totalPaginas) {
    paginaActual = nuevaPagina;
    renderizarHistorialCitas();
    actualizarPaginacion();
    lucide.createIcons();
  }
}

// Renderizar historial vacío
function renderizarHistorialVacio() {
  const container = document.getElementById("historialContainer");
  container.innerHTML = `
        <div class="text-center py-12">
            <i data-lucide="calendar" class="h-16 w-16 text-slate-300 mx-auto mb-4"></i>
            <p class="text-slate-500 font-medium">Aún no has registrado ninguna cita</p>
            <p class="text-slate-400 text-sm mt-2">Tus citas aparecerán aquí una vez que las agendes</p>
        </div>
    `;
}

// Obtener configuración de status
function getStatusConfig(status) {
  switch (status) {
    case "agendada":
      return {
        class: "bg-blue-100 text-blue-800 border-blue-200",
        icon: "alert-circle",
        text: "Agendada",
      };
    case "completada":
      return {
        class: "bg-emerald-100 text-emerald-800 border-emerald-200",
        icon: "check-circle",
        text: "Completada",
      };
    case "cancelada":
      return {
        class: "bg-red-100 text-red-800 border-red-200",
        icon: "x-circle",
        text: "Cancelada",
      };
    default:
      return {
        class: "bg-slate-100 text-slate-800 border-slate-200",
        icon: "alert-circle",
        text: "Desconocido",
      };
  }
}

async function procederAlPago() {
  if (!citaPendiente) return;

  document.getElementById("paymentModal").classList.remove("hidden");

  const fecha = new Date(citaPendiente.fecha).toLocaleDateString("es-ES", {
    weekday: "long",
    year: "numeric",
    month: "long",
    day: "numeric",
  });

  document.getElementById("resumenCita").innerHTML = `
        <h4 class="font-semibold text-blue-900 mb-3">Resumen de la cita</h4>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-blue-600 font-medium">Especialista</p>
                <p class="text-blue-900">${citaPendiente.especialista}</p>
            </div>
            <div>
                <p class="text-blue-600 font-medium">Especialidad</p>
                <p class="text-blue-900">${citaPendiente.especialidad}</p>
            </div>
            <div>
                <p class="text-blue-600 font-medium">Fecha</p>
                <p class="text-blue-900 capitalize">${fecha}</p>
            </div>
            <div>
                <p class="text-blue-600 font-medium">Hora</p>
                <p class="text-blue-900">${citaPendiente.hora}</p>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-blue-200">
            <div class="flex justify-between items-center">
                <span class="font-semibold text-blue-900">Total a pagar:</span>
                <span class="text-2xl font-bold text-blue-900">$${citaPendiente.total.toFixed(2)}</span>
            </div>
        </div>
    `;

  // Inicializar MercadoPago Bricks
  setTimeout(() => {
    inicializarMercadoPago();
  }, 100);
}

function inicializarMercadoPago() {
  if (typeof window !== "undefined" && window.MercadoPago) {
    const mp = new window.MercadoPago("", {
      locale: "es-MX",
    });

    const bricksBuilder = mp.bricks();

    const settings = {
      initialization: {
        amount: citaPendiente.total,
      },
      customization: {
        paymentMethods: {
          creditCard: "all",
          debitCard: "all",
          ticket: "all",
          bankTransfer: "all",
          mercadoPago: "all",
        },
      },
      callbacks: {
        onReady: () => {
          console.log("Payment Brick ready");
        },
        onSubmit: async ({ selectedPaymentMethod, formData }) => {
          await procesarPago(selectedPaymentMethod, formData);
        },
        onError: (error) => {
          console.error("Payment Brick error:", error);
          alert("Error en el formulario de pago. Por favor, verifica los datos.");
        },
      },
    };

    bricksBuilder
      .create("payment", "payment-brick-container", settings)
      .then((controller) => {
        paymentBrick = controller;
      })
      .catch((error) => {
        console.error("Error creating Payment Brick:", error);
      });
  }
}

async function procesarPago(selectedPaymentMethod, formData) {
  document.getElementById("loadingPayment").classList.remove("hidden");

  try {
    const response = await fetch("procesar-pago.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        citaId: citaPendiente.id,
        especialista: citaPendiente.especialista,
        especialidad: citaPendiente.especialidad,
        fecha: citaPendiente.fecha,
        hora: citaPendiente.hora,
        total: citaPendiente.total,
        formData: formData,
        paymentMethodId: selectedPaymentMethod,
      }),
    });

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const data = await response.json();

    if (data.status === "approved") {
      cerrarModalPago();
      mostrarModalExito();
      // Recargar datos para actualizar la interfaz
      setTimeout(() => {
        cargarDatosPerfil();
      }, 2000);
    } else if (data.status === "pending") {
      alert("Tu pago está siendo procesado. Te notificaremos cuando se confirme.");
      cerrarModalPago();
    } else {
      alert("El pago no pudo ser procesado. Por favor, intenta nuevamente.");
    }
  } catch (error) {
    console.error("Error processing payment:", error);
    alert("Error al procesar el pago. Por favor, intenta nuevamente.");
  } finally {
    document.getElementById("loadingPayment").classList.add("hidden");
  }
}

function mostrarModalExito() {
  document.getElementById("successModal").classList.remove("hidden");
  lucide.createIcons();
}

function cerrarModalExito() {
  document.getElementById("successModal").classList.add("hidden");
}

function cerrarModalPago() {
  document.getElementById("paymentModal").classList.add("hidden");
  if (paymentBrick) {
    paymentBrick.unmount();
    paymentBrick = null;
  }
}

function cerrarSesion() {
  if (confirm("¿Estás seguro de que deseas cerrar sesión?")) {
    window.location.href = "login.php";
  }
}
