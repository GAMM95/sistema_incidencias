$(document).ready(function () {
  // Configuración de Toastr
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "2000"
  };

  // SETEO DE COMBO ESTADO
  $.ajax({
    url: 'ajax/getEstadoConsulta.php',
    type: 'POST',
    dataType: 'json',
    success: function (data) {
      var select = $('#estado');
      select.empty();
      select.append('<option value="" selected disabled>Seleccione un estado</option>');
      $.each(data, function (index, value) {
        select.append('<option value="' + value.EST_codigo + '">' + value.EST_descripcion + '</option>');
      });
    },
    error: function (error) {
      console.error(error);
    }
  });

  // BUSCADOR PARA EL COMBO AREA Y ESTADO
  $('#estado').select2({
    allowClear: true,
    width: '100%',
    dropdownCssClass: 'text-xs',
    language: {
      noResults: function () {
        return "No se encontraron resultados";
      }
    }
  });

  function nuevaConsulta() {
    const form = document.getElementById('formConsultarIncidenciaUser');
    form.reset();

    // Restablecer Select2 manualmente
    $('#estado').val(null).trigger('change');

    window.location.reload();
  }

  // Evento para nueva consulta
  $('#limpiarCampos').on('click', nuevaConsulta);

  $('#formConsultarIncidenciaUser').submit(function (event) {
    event.preventDefault(); // Evita el envío del formulario por defecto

    // Verifica si los campos y las fechas son válidos
    if (!validarCampos() || !validarFechas()) {
      return; // Detiene el envío si los campos o las fechas no son válidos
    }

    var formData = $(this).serializeArray(); // Recopila los datos del formulario<
    var dataObject = {}; // Crea un objeto para los datos del formulario
    console.log(dataObject);
    // Recorre los datos del formulario
    formData.forEach(function (item) {
      // Solo agrega los parámetros al objeto si tienen valor
      if (item.value.trim() !== '') {
        dataObject[item.name] = item.value;
      }
    });

    // Realiza la solicitud AJAX
    $.ajax({
      url: 'consultar-incidencia-user.php?action=consultar',
      type: 'GET',
      data: dataObject,
      success: function (response) {
        console.log("Resultados filtrados:", response); // Depuración
        // Limpia el contenido actual de la tabla antes de agregar nuevos datos
        $('#tablaIncidencias tbody').empty();
        // Actualiza el contenido de la tabla con la respuesta
        $('#tablaIncidencias tbody').html(response);
      },
      error: function (xhr, status, error) {
        console.error('Error en la consulta AJAX:', error);
      }
    });

    function validarCampos() {
      var valido = false;
      var mensajeError = '';

      var codigoPatrimonial = ($('#codigoPatrimonial').val() !== null && $('#codigoPatrimonial').val().trim() !== '');
      var estadoSeleccionado = ($('#estado').val() !== null && $('#estado').val().trim() !== '');
      var fechaInicioSeleccionada = ($('#fechaInicio').val() !== null && $('#fechaInicio').val().trim() !== '');
      var fechaFinSeleccionada = ($('#fechaFin').val() !== null && $('#fechaFin').val().trim() !== '');

      // Verificar si al menos un campo está lleno
      if (codigoPatrimonial || estadoSeleccionado || fechaInicioSeleccionada || fechaFinSeleccionada) {
        valido = true;
      } else {
        mensajeError = 'Debe completar al menos un campo para realizar la búsqueda.';
      }

      if (!valido) {
        toastr.warning(mensajeError.trim(), 'Advertencia');
      }

      return valido;
    }
  });

  function validarFechas() {
    // Obtener valores de los campos de fecha
    const fechaInicio = new Date($('#fechaInicio').val());
    const fechaFin = new Date($('#fechaFin').val());

    // Obtener la fecha actual
    const fechaHoy = new Date();

    // Validar la fecha de inicio y fin
    let valido = true;
    let mensajeError = '';

    // Bloquear fechas posteriores a la fecha actual
    if (fechaInicio > fechaHoy) {
      mensajeError = 'La fecha de inicio no puede ser posterior a la fecha actual.';
      valido = false;
    }

    if (fechaFin > fechaHoy) {
      mensajeError = 'La fecha de fin no puede ser posterior a la fecha actual.';
      valido = false;
    }

    // Verificar que la fecha de fin sea posterior a la fecha de inicio
    if (fechaInicio && fechaFin && fechaFin < fechaInicio) {
      mensajeError = 'La fecha de fin debe ser posterior a la fecha de inicio.';
      valido = false;
    }

    // Mostrar mensaje de error con Toastr si la validación falla
    if (!valido) {
      toastr.warning(mensajeError.trim(), 'Advertencia');
    }

    return valido;
  }

  // Agregar eventos para validar fechas cuando cambien
  $('#fechaInicio, #fechaFin').on('change', function () {
    validarFechas();
  });
});