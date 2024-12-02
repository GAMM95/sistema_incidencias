<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="public/assets/logo.ico">
  <link rel="stylesheet" href="dist/assets/css/style.css">
  <!-- Importación de librería jQuery -->
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
  <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <!-- Agrega las hojas de estilo de Tailwind CSS -->
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <!-- Agrega la fuente Poppins desde Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,600,700" rel="stylesheet">
  <!-- Implementación de iconos -->
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <title class="text-center text-3xl font-poppins">Sistema de Gesti&oacute;n de Incidencias</title>
</head>

<body class="bg-green-50 relative">
  <!-- Fondo con imagen -->
  <img src="public/assets/fondo1.jpeg" alt="Fondo" class="absolute inset-0 w-full h-full object-cover opacity-30 z-0">

  <!-- Fondo verde transparente -->
  <div class="absolute inset-0 bg-green-500 opacity-20 z-0"></div>

  <!-- Contenedor principal centrado vertical y horizontalmente -->
  <div class="flex justify-center items-center min-h-screen relative z-10">

    <!-- Contenedor del formulario -->
    <div class="scaled-container bg-white p-3 rounded-xl shadow-lg max-w-screen-lg relative">
      <!-- Panel de logo MDE con video de fondo -->
      <div class="w-full hidden md:block mb-4 relative">
        <video src="public/assets/video_login.mp4" autoplay muted loop class='videoLogin rounded-xl w-full max-w-md mx-auto h-48 object-cover'></video>
        <!-- Texto sobre el video -->
        <h3 class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-2xl font-bold text-white font-poppins w-full text-center">Sistema de Gesti&oacute;n de Incidencias</h3>
      </div>

      <!-- Panel del formulario -->
      <div class="formDiv mx-auto">
        <!-- Encabezado y logo -->
        <div class="headerDiv text-center">
          <img src="public/assets/logo_01.png" alt="imagen de mde" class="img_logo_login w-24 h-auto mx-auto mb-2" />
        </div>

        <!-- Formulario de inicio de sesión -->
        <form action="index.php?action=login" method="POST" class="form max-w-sm mx-auto">
          <!-- Campo de entrada para usuario -->
          <div class="inputDiv w-60 mb-2 mx-auto">
            <div class="input flex items-center border rounded-2xl p-2">
              <i class='bx bxs-user icon-input icon text-2xl mr-2' style="color: #159A80;"></i>
              <input type='text' id='username' placeholder='Ingrese su usuario' name='username' autofocus class="w-full max-w-xs outline-none text-md font-poppins ml-2 text-gray-600" oninput="uppercaseInput(this)">
              <script>
                function uppercaseInput(element) {
                  element.value = element.value.toUpperCase();
                }
              </script>
            </div>
          </div>
          <!-- Campo de entrada para contraseña -->
          <div class="inputDiv w-60 mb-3 mx-auto">
            <div class="input flex items-center border rounded-2xl p-2">
              <i class="bx bxs-lock icon-input icon text-2xl mr-2" style="color: #159A80;"></i>
              <input type="password" id="password" placeholder="Ingrese su contrase&ntilde;a" name="password" class="w-full max-w-xs outline-none text-sm font-poppins ml-2 text-gray-600">
              <!-- Icono para mostrar/ocultar contraseña -->
              <div id="togglePassword" class="show-hide-link icon cursor-pointer text-gray-400 text-lg"><i class='feather icon-eye text-gray-400 text-md'></i></div>
            </div>
          </div>


          <!-- Autenticación en 2 pasos -->
          <div class="inputDiv w-60 mb-3 mx-auto" id="twoStepAuth" style="display: none;">
            <p class="mb-2 text-center text-green-600 text-md">Autenticaci&oacute;n en 2 pasos</p>
            <div class="input flex items-center border rounded-2xl p-2">
              <input type='text' id='digitos' placeholder='2 &uacute;ltimos dígitos de su DNI'
                name='digitos' maxlength="2"
                pattern="\d{1,12}" oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                class="w-full max-w-xs outline-none text-md font-poppins ml-2 text-gray-600 text-center">
            </div>
          </div>

          <!-- Enlace para pedir ayuda -->
          <p class="mb-2 text-center text-gray-800 text-md">¿Necesitas asistencia?
            <a class="text-md font-bold cursor-pointer" data-toggle="modal" data-target="#exampleModalCenter" style="color: #159A80;">Pedir ayuda</a>
          </p>

          <!-- Botón de inicio de sesión -->
          <div class="flex justify-center mt-4 mb-2">
            <button type='submit' class='btn-primary text-white font-bold py-2 px-4 rounded-md' name='btnIniciarSesion' content='Iniciar Sesi&oacute;n'>
              <span class="text-md">Iniciar Sesi&oacute;n</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Superposición del fondo -->
  <div id="overlay" class="fixed inset-0 bg-black opacity-50 z-40 hidden"></div>

  <!-- Modal de ayuda -->
  <div id="exampleModalCenter" class="modal fade show" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" style="z-index: 1050;">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-xl" id="exampleModalCenterTitle">Mensaje</h5>
          <button type="button" class="text-gray-400 hover:text-gray-600" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p class="text-gray-800 text-md">Para recuperar tus credenciales o reactivar tu cuenta, contacta a la <i>Subgerencia de Inform&aacute;tica y Sistemas</i> marcando el <b><u>ANEXO 120</u></b>.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary rounded-md" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Fin de modal de ayuda -->

</body>

</html>
<script src="https://cdn.tailwindcss.com"></script>
<script src="./app/View/func/Login/func_login.js"></script>