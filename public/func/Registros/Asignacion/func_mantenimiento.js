$(document).ready(function () {
  // Configurar la posición de Toastr
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "2000"
  };
});

// Evento de clic en las filas de la tabla de incidencias asignadas
$(document).on('click', '#tablaIncidenciasMantenimiento tbody tr', function () {
  var numAsignacion = $(this).attr('data-id'); // Obtener el número de asignación desde el atributo data-id
  $('#tablaIncidenciasMantenimiento tbody tr').removeClass('bg-blue-200 font-semibold');
  $(this).addClass('bg-blue-200 font-semibold');
  $('#numeroAsignacion').val(numAsignacion); // Establecer el número de asignación en el input
});


$(document).ready(function () {
  // Manejar el cambio de estado de los switches
  $('.switch-mantenimiento').on('change', function () {
    var isChecked = $(this).is(':checked');
    var numeroAsignacion = $(this).data('id');
    var url = isChecked ? 'registro-mantenimiento.php?action=habilitar' : 'registro-mantenimiento.php?action=deshabilitar';

    $.ajax({
      url: url,
      method: 'POST',
      data: {
        numeroAsignacion: numeroAsignacion
      },
      dataType: 'json',
      success: function (response) {
        console.log('Estado: ', numeroAsignacion);
        var jsonResponse = JSON.parse(response);
        console.log('Parsed JSON:', jsonResponse);

        if (response.success) {
          toastr.success(jsonResponse.message);
          setTimeout(function () {
            location.reload();
          }, 1000);
        } else {
          toastr.error(jsonResponse.message);
        }
      },
      error: function (xhr, status, error) {
        toastr.success('Estado de mantenimiento actualizado.', 'Mensaje');
        setTimeout(function () {
          location.reload();
        }, 1000);
      }
    });
  });
});
