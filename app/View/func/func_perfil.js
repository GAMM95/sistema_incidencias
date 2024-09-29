// Habilitar campos para edición
document.getElementById('habilitar').addEventListener('click', function () {
  document.querySelectorAll('input').forEach(input => {
    if (input.id !== 'codigoUsuario') {
      input.disabled = false;
    }
  });
  document.getElementById('editar-datos').disabled = false;
  document.getElementById('nuevo-registro').disabled = false;
  document.getElementById('habilitar').disabled = true;
});

// Cancelar edición
document.getElementById('nuevo-registro').addEventListener('click', function () {
  document.querySelectorAll('input').forEach(input => {
    input.disabled = true;
  });
  document.getElementById('editar-datos').disabled = true;
  document.getElementById('nuevo-registro').disabled = true;
  document.getElementById('habilitar').disabled = false;
});

// Recopilacion de valores de cada input

$(document).ready(function () {
  // Configurar la posición de Toastr
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "2000"
  };

  document.getElementById('habilitar').addEventListener('click', function () {
    // Habilitar los campos
    document.querySelectorAll('#formPerfil input[type="text"]').forEach(input => input.disabled = false);
    // Habilitar el botón de editar y deshabilitar el botón de habilitar
    document.getElementById('editar-datos').disabled = false;
    this.disabled = true;
    document.getElementById('nuevo-registro').disabled = false;

    // Mostrar el mensaje de Toastr
    toastr.info('Campos habilitados para edición', 'Mensaje');
  });

  document.getElementById('nuevo-registro').addEventListener('click', function () {
    // Deshabilitar los campos
    document.querySelectorAll('#formPerfil input[type="text"]').forEach(input => input.disabled = true);
    // Habilitar el botón de habilitar y deshabilitar el botón de editar
    document.getElementById('habilitar').disabled = false;
    document.getElementById('editar-datos').disabled = true;
    this.disabled = true;

    // Mostrar el mensaje de Toastr
    toastr.info('Campos deshabilitados', 'Mensaje');
  });

  function enviarFormulario(action) {
    var url = 'mi-perfil.php?action=' + action;
    var data = $('#formPerfil').serialize();

    $.ajax({
      url: url,
      method: 'POST',
      data: data,
      success: function () {
        if (action === 'editar') {
          toastr.success('Datos actualizados', 'Mensaje');
        }
        setTimeout(function () {
          location.reload();
        }, 1500);
      },
      error: function () {
        toastr.success('Datos actualizados.', 'Mensaje');
        setTimeout(function () {
          location.reload();
        }, 1500);
      }
    });
  }

  $('#editar-datos').on('click', function (e) {
    e.preventDefault();
    enviarFormulario('editar');
  });
});
