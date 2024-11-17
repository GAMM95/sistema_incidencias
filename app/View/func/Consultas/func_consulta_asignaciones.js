$(document).ready(function () {
  // Configuración de Toastr
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "2000"
  };

  // SETEO DE COMBO AREA
  $.ajax({
    url: 'ajax/getUsuarioAsignado.php',
    type: 'GET',
    dataType: 'json',
    success: function (data) {
      // console.log("Areas cargadas:", data); // Depuración

      var select = $('#usuarioAsignado');
      select.empty();
      select.append('<option value="" selected disabled>Seleccione un usuario</option>');
      $.each(data, function (index, value) {
        select.append('<option value="' + value.USU_codigo + '">' + value.usuarioAsignado + '</option>');
      });
    },
    error: function (error) {
      console.error(error);
    }
  });

  // BUSCADOR PARA EL COMBO AREA Y ESTADO
  $('#usuarioAsignado').select2({
    allowClear: true,
    width: '100%',
    dropdownCssClass: 'text-xs', // Use Tailwind CSS class
    language: {
      noResults: function () {
        return "No se encontraron resultados";
      }
    }
  });

  // Evento para nueva consulta
  function nuevaConsulta() {
    // limpiar los inputs
    document.getElementById('formConsultarAsignaciones').reset();
    $('#usuarioAsignado').val(null).trigger('change');
    $('#codigoPatrimonial').val(null).trigger('change');



    $(document).ready(function () {
      // Capturar evento clic en el botón "limpiarcamposSoporte"
      $('#limpiarcamposSoporte').on('click', function () {
        // Realizar una solicitud AJAX para obtener las incidencias del soporte
        $.ajax({
          url: 'consultar-asignaciones.php?action=listarAdmin',
          type: 'GET',
          success: function (response) {
            // Mostrar el resultado en el área de la tabla
            $('#tablaIncidenciasMantenimiento').html(response);
          },
          error: function (xhr, status, error) {
            console.error('Error al obtener las incidencias:', error);
          }
        });
      });
    });

    // Realizar una consulta ajax para obtener todos los registros al presionar el boton nueva consulta
    $.ajax({
      url: 'consultar-asignaciones.php?action=consultar',
      type: 'GET',
      success: function (response) {
        console.log("Resultados: ", response);
        // Limpiar el contenido actual de la tabla
        $('#tablaIncidenciasMantenimiento tbody').empty();
        // Actualizar el contenido de la tabla con la respuesta
        $('#tablaIncidenciasMantenimiento tbody').html(response);
      },
      error: function (error) {
        console.error("Error al obtener registros: ", error);
      }
    })
  }

  // Evento para nueva consulta
  $('#limpiarCampos').on('click', nuevaConsulta);

  $('#formConsultarAsignaciones').submit(function (event) {
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
      url: 'consultar-asignaciones.php?action=consultar',
      type: 'GET',
      data: dataObject,
      success: function (response) {
        console.log("Resultados filtrados:", response); // Depuración
        // Limpia el contenido actual de la tabla antes de agregar nuevos datos
        $('#tablaIncidenciasMantenimiento tbody').empty();
        // Actualiza el contenido de la tabla con la respuesta
        $('#tablaIncidenciasMantenimiento tbody').html(response);
      },
      error: function (xhr, status, error) {
        console.error('Error en la consulta AJAX:', error);
      }
    });

    function validarCampos() {
      var valido = false;
      var mensajeError = '';

      var usuarioSeleccionado = ($('#usuarioAsignado').val() !== null && $('#usuarioAsignado').val().trim() !== '');
      var codigoPatrimonial = ($('#codigoPatrimonial').val() !== null && $('#codigoPatrimonial').val().trim() !== '');
      var fechaInicioSeleccionada = ($('#fechaInicio').val() !== null && $('#fechaInicio').val().trim() !== '');
      var fechaFinSeleccionada = ($('#fechaFin').val() !== null && $('#fechaFin').val().trim() !== '');

      // Verificar si al menos un campo está lleno
      if (usuarioSeleccionado || codigoPatrimonial || fechaInicioSeleccionada || fechaFinSeleccionada) {
        valido = true;
      } else {
        mensajeError = 'Debe completar al menos un campo para realizar la b&uacute;squeda.';
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


$(document).ready(function () {
  // Configuración de Toastr
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "2000"
  };


  // Evento para nueva consulta
  function nuevaConsulta() {
    const form = document.getElementById('formConsultarAsignacionesSoporte');
    form.reset();
    window.location.reload();
  }


  // Evento para nueva consulta
  $('#limpiarCamposSoporte').on('click', nuevaConsulta);

  $('#formConsultarAsignacionesSoporte').submit(function (event) {
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
      url: 'consultar-asignaciones.php?action=consultar',
      type: 'GET',
      data: dataObject,
      success: function (response) {
        console.log("Resultados filtrados:", response); // Depuración
        // Limpia el contenido actual de la tabla antes de agregar nuevos datos
        $('#tablaIncidenciasMantenimientoSoporte tbody').empty();
        // Actualiza el contenido de la tabla con la respuesta
        $('#tablaIncidenciasMantenimientoSoporte tbody').html(response);
      },
      error: function (xhr, status, error) {
        console.error('Error en la consulta AJAX:', error);
      }
    });

    function validarCampos() {
      var valido = false;
      var mensajeError = '';

      var codigoPatrimonial = ($('#codigoPatrimonial').val() !== null && $('#codigoPatrimonial').val().trim() !== '');
      var fechaInicioSeleccionada = ($('#fechaInicio').val() !== null && $('#fechaInicio').val().trim() !== '');
      var fechaFinSeleccionada = ($('#fechaFin').val() !== null && $('#fechaFin').val().trim() !== '');

      // Verificar si al menos un campo está lleno
      if (codigoPatrimonial || fechaInicioSeleccionada || fechaFinSeleccionada) {
        valido = true;
      } else {
        mensajeError = 'Debe completar al menos un campo para realizar la b&uacute;squeda.';
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