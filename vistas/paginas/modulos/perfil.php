
    <!-- Header -->
    <header class="bg-white border-b border-slate-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-blue-700 rounded-full flex items-center justify-center shadow-md">
                        <i data-lucide="stethoscope" class="h-5 w-5 text-white"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-blue-900 font-montserrat" id="saludoUsuario">
                            Cargando...
                        </h1>
                    </div>
                </div>
                <button onclick="cerrarSesion()" class="flex items-center gap-2 px-4 py-2 rounded-lg border border-slate-300 hover:bg-red-50 hover:border-red-300 hover:text-red-700 transition-colors bg-transparent">
                    <i data-lucide="log-out" class="h-4 w-4"></i>
                    Cerrar Sesión
                </button>
            </div>
        </div>
    </header>

    <!-- Spinner de carga principal -->
    <div id="loadingSpinner" class="fixed inset-0 bg-white bg-opacity-90 flex items-center justify-center z-50">
        <div class="text-center">
            <div class="spinner mx-auto mb-4"></div>
            <p class="text-blue-700 font-medium">Cargando información del perfil...</p>
        </div>
    </div>

    <!-- Contenido principal -->
    <div id="contenidoPrincipal" class="px-4 py-6 hidden">
        <div class="max-w-7xl mx-auto">
            <!-- Tarjeta de cita pendiente -->
            <div id="citaPendienteContainer" class="mb-8 hidden">
                <!-- Se llenará dinámicamente -->
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Información del usuario -->
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-2xl font-bold text-blue-900 font-montserrat mb-6">Información Personal</h2>
                    <div id="infoUsuario">
                        <!-- Se llenará dinámicamente -->
                    </div>
                </div>

                <!-- Historial de citas -->
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-2xl font-bold text-blue-900 font-montserrat mb-6">Historial de Citas</h2>
                    <div id="historialContainer">
                        <!-- Se llenará dinámicamente -->
                    </div>
                    
                    <!-- Paginación -->
                    <div id="paginacionContainer" class="hidden">
                        <div id="paginacion" class="flex items-center justify-center gap-2 mt-6 pt-6 border-t border-slate-200">
                            <!-- Se llenará dinámicamente -->
                        </div>
                        <div id="infoPaginacion" class="text-center mt-4">
                            <!-- Se llenará dinámicamente -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de pago -->
    <div id="paymentModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 hidden">
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-slate-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-blue-900 font-montserrat">Procesar Pago</h3>
                        <p class="text-slate-600 mt-1">Completa los datos para confirmar tu cita</p>
                    </div>
                    <button onclick="cerrarModalPago()" class="rounded-full w-10 h-10 p-0 border border-slate-300 hover:bg-slate-100 bg-transparent">
                        <i data-lucide="x" class="h-5 w-5"></i>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <div id="resumenCita" class="bg-blue-50 rounded-xl p-4 mb-6 border border-blue-200">
                    <!-- Se llenará dinámicamente -->
                </div>
                <div id="payment-brick-container" class="min-h-[400px]"></div>
                <div id="loadingPayment" class="flex items-center justify-center py-8 hidden">
                    <div class="spinner"></div>
                    <span class="ml-3 text-blue-700 font-medium">Procesando pago...</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de éxito -->
    <div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 hidden">
        <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full mx-4">
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="check-circle" class="h-8 w-8 text-emerald-600"></i>
                </div>
                <h3 class="text-xl font-semibold text-slate-900 font-montserrat mb-2">¡Cita Agendada Exitosamente!</h3>
                <p class="text-slate-600 mb-6">Tu pago ha sido procesado correctamente y tu cita ha sido confirmada. Recibirás un correo de confirmación en breve.</p>
                <button onclick="cerrarModalExito()" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-3 rounded-xl font-semibold transition-colors">
                    Entendido
                </button>
            </div>
        </div>
    </div>
