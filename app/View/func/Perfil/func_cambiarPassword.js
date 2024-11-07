
document.addEventListener("DOMContentLoaded", function () {
  // Obtener referencia a cada campo de entrada de contraseña y sus respectivos iconos de alternar
  const passwordActualInput = document.getElementById("passwordActual");
  const togglePasswordActual = document.getElementById("togglePasswordActual");

  const passwordNuevoInput = document.getElementById("passwordNuevo");
  const togglePasswordNuevo = document.getElementById("togglePasswordNuevo");

  const passwordConfirmInput = document.getElementById("passwordConfirm");
  const togglePasswordConfirm = document.getElementById("togglePasswordConfirm");

  // Función para alternar el tipo de entrada entre "password" y "text"
  function togglePasswordVisibility(inputField, toggleIcon) {
    if (inputField.type === "password") {
      inputField.type = "text";
      toggleIcon.innerHTML = "<i class='feather icon-eye-off'></i>";
    } else {
      inputField.type = "password";
      toggleIcon.innerHTML = "<i class='feather icon-eye'></i>";
    }
  }

  // Event listeners para alternar la visibilidad de cada campo de contraseña
  togglePasswordActual.addEventListener("click", function () {
    togglePasswordVisibility(passwordActualInput, togglePasswordActual);
  });

  togglePasswordNuevo.addEventListener("click", function () {
    togglePasswordVisibility(passwordNuevoInput, togglePasswordNuevo);
  });

  togglePasswordConfirm.addEventListener("click", function () {
    togglePasswordVisibility(passwordConfirmInput, togglePasswordConfirm);
  });
});


$(document).ready(function () {
  function enviarFormulario(action) {
    var url = 'mi-perfil.php?action=' + action;
    var data = $('#formCambiarPassword').serialize();

    $.ajax({
      url: url,
      method: 'POST',
      data: data,
      success: function (response) {
        if (action === 'cambiarContraseña') {
          toastr.success(response.message, 'Mensaje');
        }
        setTimeout(function () {
          location.reload();
        }, 1500);
      },
      error: function () {
        toastr.success('Hubo un problema al cambiar la contrase&ntilde;a. Int&eacute;ntalo de nuevo.', 'Mensaje de error');
        setTimeout(function () {
          location.reload();
        }, 1500);
      }
    });
  }

  $('#cambiarPassword').on('click', function (e) {
    e.preventDefault();
    enviarFormulario('cambiarContraseña');
  });
});
