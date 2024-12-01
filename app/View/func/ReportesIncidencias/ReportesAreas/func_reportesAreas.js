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
      var select = $('#areaSeleccionada');
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

  // Setear campos del usuario seleccionado
  $('#areaSeleccionada').change(function () {
    var selectedOption = $(this).find('option:selected');
    var codigoArea = selectedOption.val();
    var nombreArea = selectedOption.text();
    $('#codigoAreaSeleccionada').val(codigoArea);
    $('#nombreAreaSeleccionada').val(nombreArea);
  });

  // Buscador para el combo de usuario que se le han asignado las incidencias
  $('#areaSeleccionada').select2({
    allowClear: true,
    width: '100%',
    dropdownCssClass: 'text-xs', // Use Tailwind CSS class
    language: {
      noResults: function () {
        return "No se encontraron resultados";
      }
    }
  });

  // Funcion para relaizar la consulta 
  function nuevaConsultaIncidenciasArea() {
    // limpiar los campos fechas 
    $('#fechaInicioIncidenciasArea').val('');
    $('#fechaFinIncidenciasArea').val('');
    $('#areaSeleccionada').val(null).trigger('change');

    // Realizar la solicitud AJAX para obtener todos los registros (sin filtros)
    $.ajax({
      url: 'reportes.php?action=consultarIncidenciasAreas',
      type: 'GET',
      dataType: 'html', // Esperamos HTML para renderizar la tabla
      success: function (response) {
        console.log("Resultados de nueva consulta areas:", response);
        // Limpia el contenido actual de la tabla antes de agregar nuevos datos
        $('#tablaIncidenciasAreas tbody').empty();
        // Actualiza el contenido de la tabla con la respuesta
        $('#tablaIncidenciasAreas tbody').html(response);
      },
      error: function (xhr, status, error) {
        console.error('Error en la consulta AJAX:', error);
      }
    });
  }

  // Evento para el boton limpiar campos
    $('#limpiarCamposIncidenciasAreas').on('click', nuevaConsultaIncidenciasArea);

  // Validación y envío del formulario
  $('#formIncidenciasAreas').submit(function (event) {
    event.preventDefault(); // Evita el envío del formulario por defecto

    // Verifica si los campos y las fechas son válidos
    if (!validarCamposIncidenciasArea() || !validarFechasIncidenciasArea()) {
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
      url: 'reportes.php?action=consultarIncidenciasAreas',
      type: 'GET',
      data: dataObject,
      success: function (response) {
        console.log("Resultados filtrados:", response);
        $('#tablaIncidenciasAreas tbody').empty(); // Limpia el contenido actual de la tabla antes de agregar nuevos datos
        $('#tablaIncidenciasAreas tbody').html(response); // Actualiza el contenido de la tabla con la respuesta
      },
      error: function (xhr, status, error) {
        console.error('Error en la consulta AJAX:', error);
      }
    });

    // Función para validar los campos fechas
    function validarCamposIncidenciasArea() {
      var valido = false;
      var mensajeError = '';

      var faltaArea = ($('#areaSeleccionada').val() !== null && $('#areaSeleccionada').val().trim() !== '');
      var fechaInicioSeleccionada = ($('#fechaInicioIncidenciasArea').val() !== null && $('#fechaInicioIncidenciasArea').val().trim() !== '');
      var fechaFinSeleccionada = ($('#fechaFinIncidenciasArea').val() !== null && $('#fechaFinIncidenciasArea').val().trim() !== '');

      // Verificar si al menos un campo está lleno
      if (faltaArea || fechaInicioSeleccionada || fechaFinSeleccionada) {
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
  function validarFechasIncidenciasArea() {
    const fechaInicio = new Date($('#fechaInicioIncidenciasArea').val());
    const fechaFin = new Date($('#fechaFinIncidenciasArea').val());
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
  }

  // Agregar eventos para validar fechas cuando cambien
  $('#fechaInicioIncidenciasArea, #fechaFinIncidenciasArea').on('change', function () {
    validarFechasIncidenciasArea();
  });
}); 