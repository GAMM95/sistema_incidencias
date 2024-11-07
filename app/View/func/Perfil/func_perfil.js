// Habilitar campos para edición, excluyendo rol y área
document.getElementById('habilitar').addEventListener('click', function () {
  document.querySelectorAll('#formPerfil input[type="text"]').forEach(input => {
    if (input.id !== 'dni' && input.id !== 'username' && input.id !== 'rol' && input.id !== 'area') {
      input.disabled = false;
    }
  });
  document.getElementById('editar-datos').disabled = false;
  document.getElementById('nuevo-registro').disabled = false;
  document.getElementById('habilitar').disabled = true;
});

// Cancelar edición, deshabilitar todos los campos
document.getElementById('nuevo-registro').addEventListener('click', function () {
  document.querySelectorAll('#formPerfil input[type="text"]').forEach(input => {
    input.disabled = true;
  });
  document.getElementById('editar-datos').disabled = true;
  document.getElementById('nuevo-registro').disabled = true;
  document.getElementById('habilitar').disabled = false;
});

// Recopilación de valores y envío del formulario

$(document).ready(function () {
  // Configurar la posición de Toastr
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "2000"
  };

  // Habilitar los campos, excluyendo rol y área
  document.getElementById('habilitar').addEventListener('click', function () {
    document.querySelectorAll('#formPerfil input[type="text"]').forEach(input => {
      if (input.id !== 'dni' && input.id !== 'username' && input.id !== 'rol' && input.id !== 'area') {
        input.disabled = false;
      }
    });
    document.getElementById('editar-datos').disabled = false;
    this.disabled = true;
    document.getElementById('nuevo-registro').disabled = false;

    toastr.info('Campos habilitados para edici&oacute;n', 'Mensaje');
  });

  // Deshabilitar los campos
  document.getElementById('nuevo-registro').addEventListener('click', function () {
    document.querySelectorAll('#formPerfil input[type="text"]').forEach(input => {
      input.disabled = true;
    });
    document.getElementById('habilitar').disabled = false;
    document.getElementById('editar-datos').disabled = true;
    this.disabled = true;

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

