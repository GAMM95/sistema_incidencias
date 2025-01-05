$(document).ready(function () {
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "2000"
  };

  // Seteo del combo de usuario que realizaron el cierre
  $.ajax({
    url: 'ajax/getAreaData.php',
    type: 'GET',
    dataType: 'json',
    success: function (data) {
      var select = $('#areaIncidencia');
      select.empty();
      select.append('<option value="" selected disabled>Seleccione un &aacute;rea</option>');
      $.each(data, function (index, value) {
        select.append('<option value="' + value.ARE_codigo + '">' + value.ARE_nombre + '</option>');
      });
    },
    error: function (error) {
      console.error(error);
    }
  });

  // Setear campos del área seleccionada
  $('#areaIncidencia').change(function () {
    var selectedOption = $(this).find('option:selected');
    var codigoArea = selectedOption.val();
    var nombreArea = selectedOption.text();
    $('#codigoArea').val(codigoArea);
    $('#nombreArea').val(nombreArea);
  });

  // Inicialización de select2
  $('#areaIncidencia').select2({
    allowClear: true,
    width: '100%',
    dropdownCssClass: 'text-xs',
    language: {
      noResults: function () {
        return "No se encontraron resultados";
      }
    }
  });

  // Función para limpiar los campos y realizar una nueva consulta sin filtros
  function nuevaConsultaAreas() {
    $('#fechaInicioIncidenciasArea').val('');
    $('#fechaFinIncidenciasArea').val('');
    $('#areaIncidencia').val(null).trigger('change');

    // Realizar la solicitud AJAX para obtener todos los registros (sin filtros)
    $.ajax({
      url: 'reportes.php?action=consultarIncidenciasAreas',
      type: 'GET',
      dataType: 'html', // Esperamos HTML para renderizar la tabla
      success: function (response) {
        $('#tablaIncidenciasArea tbody').empty(); // Limpia el contenido actual de la tabla
        $('#tablaIncidenciasArea tbody').html(response); // Actualiza con nuevos datos
      },
      error: function (xhr, status, error) {
        console.error('Error en la consulta AJAX:', error);
      }
    });
  }

  // Evento para el botón "Limpiar Campos"
  $('#limpiarCamposIncidenciasArea').on('click', nuevaConsultaAreas);

  // Validación de campos y fechas antes de enviar el formulario
  $('#formIncidenciasAreas').submit(function (event) {
    event.preventDefault(); // Evitar envío normal del formulario

    // Verifica si los campos y las fechas son válidos
    if (!validarCamposAreas() || !validarFechasAreas()) {
      return; // Detiene el envío si hay problemas en la validación
    }

    var formData = $(this).serializeArray(); // Recopilar datos del formulario
    var dataObject = {}; // Convertir el array a un objeto

    // Agregar solo los campos no vacíos al objeto
    formData.forEach(function (item) {
      if (item.value.trim() !== '') {
        dataObject[item.name] = item.value;
      }
    });

    // Realizar la solicitud AJAX para filtrar los resultados
    $.ajax({
      url: 'reportes.php?action=consultarIncidenciasAreas',
      type: 'GET',
      data: dataObject, // Enviar solo los campos con valores
      success: function (response) {
        $('#tablaIncidenciasArea tbody').empty(); // Limpiar la tabla antes de agregar nuevos datos
        $('#tablaIncidenciasArea tbody').html(response); // Actualizar la tabla con los nuevos datos
      },
      error: function (xhr, status, error) {
        console.error('Error en la consulta AJAX:', error);
      }
    });
  });

  // Función para validar que al menos un campo esté lleno
  function validarCamposAreas() {
    var valido = false;
    var mensajeError = '';

    var areaSeleccionada = $('#areaIncidencia').val() !== null;
    var fechaInicioSeleccionada = $('#fechaInicioIncidenciasArea').val().trim() !== '';
    var fechaFinSeleccionada = $('#fechaFinIncidenciasArea').val().trim() !== '';

    if (areaSeleccionada || fechaInicioSeleccionada || fechaFinSeleccionada) {
      valido = true;
    } else {
      mensajeError = 'Debe completar al menos un campo para filtrar la tabla.';
      toastr.warning(mensajeError, 'Advertencia');
    }

    return valido;
  }

  // Función para validar fechas
  function validarFechasAreas() {
    const fechaInicio = new Date($('#fechaInicioIncidenciasArea').val());
    const fechaFin = new Date($('#fechaFinIncidenciasArea').val());
    const fechaHoy = new Date();
    fechaHoy.setHours(0, 0, 0, 0); // Ajustar la hora para comparar solo las fechas

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

    if (fechaInicio && fechaFin && fechaInicio > fechaFin) {
      mensajeError = 'La fecha fin debe ser posterior a la fecha de inicio.';
      valido = false;
    }

    if (!valido) {
      toastr.warning(mensajeError, 'Advertencia');
    }

    return valido;
  }

  // Validar fechas al cambiar las fechas en los inputs
  $('#fechaInicioIncidenciasArea, #fechaFinIncidenciasArea').on('change', function () {
    validarFechasAreas();
  });
});
