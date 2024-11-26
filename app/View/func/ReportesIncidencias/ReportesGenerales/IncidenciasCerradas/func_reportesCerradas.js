$(document).ready(function () {
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "2000"
  };

  // Funcion para realizar la consulta sin filtros
  function nuevaConsultaIncidenciasCerradas() {
    // limpiar los campos fechas 
    $('#fechaInicioIncidenciasCerradas').val('');
    $('#fechaFinIncidenciasCerradas').val('');

    // Realizar la solicitud AJAX para obtener todos los registros (sin filtros)
    $.ajax({
      url: 'reportes.php?action=consultarIncidenciasCerradas',
      type: 'GET',
      dataType: 'html', // Esperamos HTML para renderizar la tabla
      success: function (response) {
        console.log("Resultados de nueva consulta (sin filtros):", response);
        // Limpia el contenido actual de la tabla antes de agregar nuevos datos
        $('#tablaIncidenciasCerradas tbody').empty();
        // Actualiza el contenido de la tabla con la respuesta
        $('#tablaIncidenciasCerradas tbody').html(response);
      },
      error: function (xhr, status, error) {
        console.error('Error en la consulta AJAX:', error);
      }
    });
  }

  // Evento para el botón de limpiar campos
  $('#limpiarCamposIncidenciasCerradas').on('click', nuevaConsultaIncidenciasCerradas);

  // Validación y envío del formulario
  $('#formIncidenciasCerradas').submit(function (event) {
    event.preventDefault(); // Evita el envío del formulario por defecto

    // Verifica si los campos y las fechas son válidos
    if (!validarCamposIncidenciasCerradas() || !validarFechasIncidenciasCerradas()) {
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
      url: 'reportes.php?action=consultarIncidenciasCerradas',
      type: 'GET',
      data: dataObject,
      success: function (response) {
        console.log("Resultados filtrados:", response);
        $('#tablaIncidenciasCerradas tbody').empty(); // Limpia el contenido actual de la tabla antes de agregar nuevos datos
        $('#tablaIncidenciasCerradas tbody').html(response); // Actualiza el contenido de la tabla con la respuesta
      },
      error: function (xhr, status, error) {
        console.error('Error en la consulta AJAX:', error);
      }
    });

    // Función para validar los campos fechas
    function validarCamposIncidenciasCerradas() {
      var valido = false;
      var mensajeError = '';

      var fechaInicioSeleccionada = ($('#fechaInicioIncidenciasCerradas').val() !== null && $('#fechaInicioIncidenciasCerradas').val().trim() !== '');
      var fechaFinSeleccionada = ($('#fechaFinIncidenciasCerradas').val() !== null && $('#fechaFinIncidenciasCerradas').val().trim() !== ''); 


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
  function validarFechasIncidenciasCerradas() {
    const fechaInicio = new Date($('#fechaInicioIncidenciasCerradas').val());
    const fechaFin = new Date($('#fechaFinIncidenciasCerradas').val());
    const fechaHoy = new Date();
    fechaHoy.setHours(0, 0, 0, 0); // Ajustar la hora para comparar solo las fechas

    let valido = true;
    let mensajeError = ''; // Asegurarse de que mensajeError sea una cadena vacía

    if (fechaInicio > fechaHoy) {
      mensajeError = 'La fecha de inicio no puede ser posterior a la fecha actual.';
      valido = false;
    }

    if (fechaFin > fechaHoy) {
      mensajeError = 'La fecha fin no puede ser posterior a la fecha actual.';
      valido = false;
    }

    if (fechaInicio && fechaFin && fechaInicio > fechaFin) {
      mensajeError = 'La fecha fin debe ser posterior a la fecha de inicio.';
      valido = false;
    }

    if (!valido) {
      toastr.warning(mensajeError.trim(), 'Advertencia'); // Ahora mensajeError siempre será una cadena
    }

    return valido;
  }

  // Agregar eventos para validar fechas cuando cambien
  $('#fechaInicioIncidenciasCerradas, #fechaFinIncidenciasCerradas').on('change', function () {
    validarFechasIncidenciasCerradas();
  });
});
