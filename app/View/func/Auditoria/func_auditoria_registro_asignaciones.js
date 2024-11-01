$(document).ready(function () {
  // Configuración de Toastr
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "2000"
  };

  function nuevaConsulta() {
    // Limpiar los campos de fecha
    $('#fechaInicio_auditoria_recepciones').val('');
    $('#fechaFin_auditoria_recepciones').val('');

    // Realiza una llamada AJAX para obtener todos los registros
    $.ajax({
      url: 'auditoria.php?action=listarRegistrosRecepciones', // Asegúrate de que esta URL apunte al método correcto
      type: 'GET',
      success: function (response) {
        console.log("Resultados completos:", response); // Para depuración
        // Limpia el contenido actual de la tabla antes de agregar nuevos datos
        $('#tablaRecepciones tbody').empty();
        // Actualiza el contenido de la tabla con la respuesta
        $('#tablaRecepciones tbody').html(response);
      },
      error: function (xhr, status, error) {
        console.error('Error al obtener registros:', error);
      }
    });
  }

  // Evento para nueva consulta
  $('#limpiarCampos_auditoria_recepciones').on('click', nuevaConsulta);

  $('#formAuditoriaRecepciones').submit(function (event) {
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
      url: 'auditoria.php?action=listarRegistrosRecepciones',
      type: 'GET',
      data: dataObject,
      success: function (response) {
        console.log("Resultados filtrados:", response); // Depuración
        // Limpia el contenido actual de la tabla antes de agregar nuevos datos
        $('#tablaRecepciones tbody').empty();
        // Actualiza el contenido de la tabla con la respuesta
        $('#tablaRecepciones tbody').html(response);
      },
      error: function (xhr, status, error) {
        console.error('Error en la consulta AJAX:', error);
      }
    });

    function validarCampos() {
      var valido = false;
      var mensajeError = '';

      var fechaInicioSeleccionada = ($('#fechaInicio_auditoria_recepciones').val() !== null && $('#fechaInicio_auditoria_recepciones').val().trim() !== '');
      var fechaFinSeleccionada = ($('#fechaFin_auditoria_recepciones').val() !== null && $('#fechaFin_auditoria_recepciones').val().trim() !== '');

      // Verificar si al menos un campo está lleno
      if (fechaInicioSeleccionada || fechaFinSeleccionada) {
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
    const fechaInicio = new Date($('#fechaInicio_auditoria_recepciones').val());
    const fechaFin = new Date($('#fechaFin_auditoria_recepciones').val());

    // Obtener la fecha actual
    const fechaHoy = new Date();

    // Validar la fecha de inicio y fin
    let valido = true;
    let mensajeError = '';

    // Bloquear fechas posteriores a la fecha actuals
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
  $('#fechaInicio_auditoria_recepciones, #fechaFin_auditoria_recepciones').on('change', function () {
    validarFechas();
  });
});