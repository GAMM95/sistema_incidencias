$(document).ready(function () {
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "2000"
  };

  // Funcion para realizar la consulta sin filtros
  function nuevaConsultaIncidenciasTotales() {
    // limpiar los campos fechas 
    $('#fechaInicioIncidenciasTotales').val('');
    $('#fechaFinIncidenciasTotales').val('');

    // Realizar la solicitud AJAX para obtener todos los registros (sin filtros)
    $.ajax({
      url: 'reportes.php?action=consultarIncidenciasTotales',
      type: 'GET',
      dataType: 'html', // Esperamos HTML para renderizar la tabla
      success: function (response) {
        console.log("Resultados de nueva consulta (sin filtros):", response);
        // Limpia el contenido actual de la tabla antes de agregar nuevos datos
        $('#tablaIncidenciasTotales tbody').empty();
        // Actualiza el contenido de la tabla con la respuesta
        $('#tablaIncidenciasTotales tbody').html(response);
      },
      error: function (xhr, status, error) {
        console.error('Error en la consulta AJAX:', error);
      }
    });
  }

  // Evento para el botón de limpiar campos
  $('#limpiarCamposIncidenciasTotales').on('click', nuevaConsultaIncidenciasTotales);

  // Validación y envío del formulario
  $('#formIncidenciasTotales').submit(function (event) {
    event.preventDefault(); // Evita el envío del formulario por defecto

    // Verifica si los campos y las fechas son válidos
    if (!validarCamposIncidenciasTotales() || !validarFechasIncidenciasTotales()) {
      return; // Detiene el envío si los campos o las fechas no son válidos
    }

    var formData = $(this).serializeArray(); // Recopila los datos del formulario
    var dataObject = {}; // Crea un objeto para los datos del formulario
    console.log(dataObject);

    // Recorre los datos del formulario y llena el objeto con los valores
    formData.forEach(function (item) {
      if (item.value.trim() !== '') {
        dataObject[item.name] = item.value;
      }
    });

    // Realiza la solicitud AJAX
    $.ajax({
      url: 'reportes.php?action=consultarIncidenciasTotales',
      type: 'GET',
      data: dataObject,
      success: function (response) {
        console.log("Resultados filtrados:", response);
        $('#tablaIncidenciasTotales tbody').empty(); // Limpia el contenido actual de la tabla antes de agregar nuevos datos
        $('#tablaIncidenciasTotales tbody').html(response); // Actualiza el contenido de la tabla con la respuesta
      },
      error: function (xhr, status, error) {
        console.error('Error en la consulta AJAX:', error);
      }
    });

    // Función para validar los campos de usuario y fechas
    function validarCamposIncidenciasTotales() {
      var valido = false;
      var mensajeError = '';

      var fechaInicioSeleccionada = ($('#fechaInicioIncidenciasTotales').val() !== null && $('#fechaInicioIncidenciasTotales').val().trim() !== '');
      var fechaFinSeleccionada = ($('#fechaFinIncidenciasTotales').val() !== null && $('#fechaFinIncidenciasTotales').val().trim() !== '');

      // Verificar si al menos un campo está lleno
      if (fechaInicioSeleccionada || fechaFinSeleccionada) {
        valido = true;
      } else {
        mensajeError = 'Debe completar al menos un campo para filtrar la tabla.';
      }

      if (!valido) {
        toastr.warning(mensajeError.trim(), 'Advertencia');
      }

      return valido;
    }
  });

  // Función para validar fechas
  function validarFechasIncidenciasTotales() {
    const fechaInicio = new Date($('#fechaInicioIncidenciasTotales').val());
    const fechaFin = new Date($('#fechaFinIncidenciasTotales').val());
    const fechaHoy = new Date();

    let valido = true;
    let mensajeError = '';

    if (fechaInicio > fechaHoy) {
      mensajeError = 'La fecha de inicio no puede ser posterior a la fecha actual.';
      valido = false;
    }

    if (fechaFin > fechaHoy) {
      mensajeError = 'La fecha fin no puede ser posterior a la fecha actual.';
      valido = false;
    }

    if (fechaInicio > fechaFin && fechaFin < fechaInicio) {
      mensajeError = 'La fecha fin debe ser posterior a la fecha de inicio.';
      valido = false;
    }

    if (!valido) {
      toastr.warning(mensajeError.trim(), 'Advertencia');
    }

    return valido;
  }

  // Agregar eventos para validar fechas cuando cambien
  $('#fechaInicioIncidenciasTotales, #fechaFinIncidenciasTotales').on('change', function () {
    validarFechasIncidenciasTotales();
  });
});
