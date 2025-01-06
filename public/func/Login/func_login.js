document.addEventListener("DOMContentLoaded", function () {
  // Obtener referencia al campo de entrada de contraseña y al icono de alternar
  const passwordInput = document.getElementById("password");
  const togglePassword = document.getElementById("togglePassword");

  // Variable para rastrear si la contraseña está visible u oculta
  let passwordVisible = false;

  // Agregar un event listener para el clic en el icono de alternar
  togglePassword.addEventListener("click", () => {
    // Cambiar el tipo de entrada entre "password" y "text"
    if (passwordVisible) {
      passwordInput.type = "password";
      togglePassword.innerHTML = "<i class='feather icon-eye'></i>";
    } else {
      passwordInput.type = "text";
      togglePassword.innerHTML = "<i class='feather icon-eye-off '></i>";
    }

    // Cambiar el estado de la variable passwordVisible
    passwordVisible = !passwordVisible;
  });
});

// Ocultar la autenticación en 2 pasos si el usuario es 'ADMIN' o si no se completan los campos
document.addEventListener('DOMContentLoaded', function () {
  const usernameInput = document.getElementById('username');
  const passwordInput = document.getElementById('password');
  const twoStepAuthDiv = document.getElementById('twoStepAuth');

  // Función para mostrar u ocultar la autenticación en 2 pasos
  function toggleTwoStepAuth() {
    const usernameValue = usernameInput.value.trim().toUpperCase();
    const passwordValue = passwordInput.value.trim();

    // Mostrar la autenticación en 2 pasos solo si los dos primeros campos están llenos y el usuario no es 'ADMIN'
    if (usernameValue !== 'ADMIN' && usernameValue !== '' && passwordValue !== '') {
      twoStepAuthDiv.style.display = 'block';
    } else {
      twoStepAuthDiv.style.display = 'none';
    }
  }

  // Agregar eventos para monitorear cambios en los inputs
  usernameInput.addEventListener('input', toggleTwoStepAuth);
  passwordInput.addEventListener('input', toggleTwoStepAuth);
});

$(document).ready(function () {
  // Mostrar el modal
  $("a[href='#']").click(function (event) {
    event.preventDefault();
    $("#helpModal").removeClass('hidden');
  });

  // Cerrar el modal
  $("#closeModal").click(function () {
    $("#helpModal").addClass('hidden');
  });
});


$(document).ready(function () {
  var state = new URLSearchParams(window.location.search).get('state');
  var message = new URLSearchParams(window.location.search).get('message');

  if (state === 'failed') {
    toastr.error(message || 'Credenciales incorrectas.', 'Inicio de sesión fallido.');
  } else if (state === 'inactive') {
    toastr.error('Usuario inactivo. Por favor, contacte al administrador.', 'Inicio de sesión fallido.');
  }
});


$(document).ready(function () {
  // Configuración de Toastr
  toastr.options = {
    "positionClass": "toast-top-right",
    "progressBar": true,
    "timeOut": "3000"
  };




  // // Verificar si hay un parámetro de estado en la URL
  // var state = new URLSearchParams(window.location.search).get('state');
  // if (state === 'failed') {
  //   toastr.error('Credenciales incorrectas.', 'Inicio de sesi&oacute;n fallido.');
  // } else if (state === 'inactive') {
  //   toastr.error('Usuario inactivo. Por favor, contacte al administrador.', 'Inicio de sesi&oacute;n fallido.');
  // }

  // Manejar la presentación del formulario
  // $('.form').submit(function (event) {
  //   var valido = true;
  //   var mensajeError = '';

  //   var faltaUsername = ($('#username').val().trim() === '');
  //   var faltaPassword = ($('#password').val().trim() === '');

  //   if (faltaUsername && faltaPassword) {
  //     mensajeError = 'Por favor, complete ambos campos.';
  //     valido = false;
  //   } else if (faltaUsername) {
  //     mensajeError = 'Por favor, ingrese su nombre de usuario.';
  //     valido = false;
  //   } else if (faltaPassword) {
  //     mensajeError = 'Por favor, ingrese su contraseña.';
  //     valido = false;
  //   }

  //   // Mostrar mensaje de error si hay
  //   if (!valido) {
  //     toastr.warning(mensajeError, 'Advertencia');
  //   }
  //   return valido;
  // });

  // Manejar la presentación del formulario
  $('.form').submit(function (event) {
    var valido = true;
    var mensajeError = '';

    var username = $('#username').val().trim();
    var password = $('#password').val().trim();
    var digitosDni = $('#digitos').val().trim();

    var faltaUsername = (username === '');
    var faltaPassword = (password === '');
    var faltaDni = (digitosDni === '');

    if (faltaUsername && faltaPassword) {
      mensajeError = 'Por favor, complete ambos campos.';
      valido = false;
    } else if (faltaUsername) {
      mensajeError = 'Por favor, ingrese su nombre de usuario.';
      valido = false;
    } else if (faltaPassword) {
      mensajeError = 'Por favor, ingrese su contraseña.';
      valido = false;
    } else if (username.toLowerCase() !== 'admin' && faltaDni) {
      // Si el usuario no es "ADMIN", validar los dígitos del DNI
      mensajeError = 'Por favor, ingrese los 2 últimos dígitos de su DNI.';
      valido = false;
    }

    // Mostrar mensaje de error si hay
    if (!valido) {
      toastr.warning(mensajeError, 'Advertencia');
      event.preventDefault(); // Prevenir el envío del formulario si no es válido
    }

    return valido;
  });

});

$(document).ready(function () {
  // Abrir el modal
  $('[data-toggle="modal"]').on('click', function () {
    var target = $(this).data('target');
    $('#overlay').removeClass('hidden').addClass('flex'); // Mostrar superposición
    $(target).removeClass('hidden').addClass('flex'); // Mostrar modal
  });

  // Cerrar el modal con el botón de cierre (×)
  $(document).on('click', '.modal-content .close', function () {
    $('#overlay').addClass('hidden').removeClass('flex'); // Ocultar superposición
    $(this).closest('.modal').removeClass('flex').addClass('hidden'); // Ocultar modal
  });

  // Cerrar el modal con el botón "Cerrar"
  $(document).on('click', '[data-dismiss="modal"]', function () {
    $('#overlay').addClass('hidden').removeClass('flex'); // Ocultar superposición
    $(this).closest('.modal').removeClass('flex').addClass('hidden'); // Ocultar modal
  });

  // Cerrar el modal cuando se presiona la tecla Esc
  $(document).on('keydown', function (e) {
    if (e.key === 'Escape') {
      $('#overlay').addClass('hidden').removeClass('flex'); // Ocultar superposición
      $('.modal.flex').removeClass('flex').addClass('hidden'); // Ocultar modal
    }
  });

  // Cerrar el modal si se hace clic fuera del contenido del modal
  $(document).on('click', '#overlay', function () {
    $('#overlay').addClass('hidden').removeClass('flex'); // Ocultar superposición
    $('.modal.flex').removeClass('flex').addClass('hidden'); // Ocultar modal
  });
});