    
    <header class="relative z-50 bg-fondo-alternativo">
      <div class="px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="flex justify-between items-center h-16 md:h-20">
          <!-- Logo -->
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="lucide lucide-scan-heart-icon lucide-scan-heart text-primario w-8 lg:w-12 h-8 lg:h-12"
              >
                <path
                  d="M11.246 16.657a1 1 0 0 0 1.508 0l3.57-4.101A2.75 2.75 0 1 0 12 9.168a2.75 2.75 0 1 0-4.324 3.388z"
                />
                <path d="M17 3h2a2 2 0 0 1 2 2v2" />
                <path d="M21 17v2a2 2 0 0 1-2 2h-2" />
                <path d="M3 7V5a2 2 0 0 1 2-2h2" />
                <path d="M7 21H5a2 2 0 0 1-2-2v-2" />
              </svg>
            </div>
            <h2 class="ml-1">Odonto365</h2>
          </div>

          <!-- Menú para pantallas grandes -->
          <nav class="hidden lg:flex space-x-4">
            <a
              href="#"
              class="btn suave bg-transparent hover:bg-boton-suave-hover-bg"
              >Instalaciones</a
            >
            <a
              href="#"
              class="btn suave bg-transparent hover:bg-boton-suave-hover-bg"
              >Especialidades</a
            >
            <a
              href="#"
              class="btn suave bg-transparent hover:bg-boton-suave-hover-bg"
              >Blog</a
            >
            <a
              href="#"
              class="btn suave bg-transparent hover:bg-boton-suave-hover-bg"
              >Contacto</a
            >
          </nav>

          <!-- Botones de inicio de sesión y registro para pantallas grandes -->
          <div class="hidden lg:flex space-x-2 text-base lg:text-lg">
            <a
              href="perfil.html"
              class="flex justify-center items-center focus:outline-none gap-2 btn suave border border-borde"
            >
              Acceder
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="lucide lucide-log-in-icon lucide-log-in w-5 h-5"
              >
                <path d="m10 17 5-5-5-5" />
                <path d="M15 12H3" />
                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
              </svg>
            </a>
          </div>

          <!-- Botón de menú para pantallas pequeñas -->
          <button
            id="menuToggle"
            class="lg:hidden text-titulos focus:outline-none"
            aria-label="Abrir menú"
          >
            <svg
              class="menu-icon w-8 h-8"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
            >
              <line x1="3" y1="6" x2="21" y2="6" class="line line1" />
              <line x1="3" y1="12" x2="21" y2="12" class="line line2" />
              <line x1="3" y1="18" x2="21" y2="18" class="line line3" />
            </svg>
          </button>
        </div>
      </div>
    </header>

    <!-- Menú Mobile -->
    <div class="lg:hidden">
      <div
        id="menu"
        class="fixed top-16 left-0 right-0 z-40 px-4 sm:px-6 lg:px-8 hidden"
      >
        <div class="p-5 bg-fondo-principal shadow-sm rounded-lg">
          <nav class="space-y-4 mt-2">
            <a
              href="#"
              class="block btn suave bg-transparent hover:bg-boton-suave-hover-bg px-0"
              >Instalaciones</a
            >
            <a
              href="#"
              class="block btn suave bg-transparent hover:bg-boton-suave-hover-bg px-0"
              >Especialidades</a
            >
            <a
              href="#"
              class="block btn suave bg-transparent hover:bg-boton-suave-hover-bg px-0"
              >Blog</a
            >
            <a
              href="#"
              class="block btn suave bg-transparent hover:bg-boton-suave-hover-bg px-0"
              >Contacto</a
            >
          </nav>
          <div class="mt-6 flex w-full gap-2 font-heading">
            <a
              href="perfil.html"
              class="flex justify-center items-center focus:outline-none gap-2 btn primario movil"
            >
              Acceder
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="lucide lucide-log-in-icon lucide-log-in w-5 h-5"
              >
                <path d="m10 17 5-5-5-5" />
                <path d="M15 12H3" />
                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
              </svg>
            </a>
          </div>
        </div>
      </div>
    </div>