$(document).ready(function () {
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "2000"
  };

  // Seteo del combo de usuario que realizaron el cierre
  $.ajax({
    url: 'ajax/getUsuarioCierre.php',
    type: 'GET',
    dataType: 'json',
    success: function (data) {
      var select = $('#usuarioIncidenciasCerradas');
      select.empty();
      select.append('<option value="" selected disabled>Seleccione un usuario</option>');
      $.each(data, function (index, value) {
        select.append('<option value="' + value.USU_codigo + '">' + value.usuarioCierre + '</option>');
      });
    },
    error: function (error) {
      console.error(error);
    }
  });

  // Setear campos del usuario seleccionado
  $('#usuarioIncidenciasCerradas').change(function () {
    var selectedOption = $(this).find('option:selected');
    var codigoUsuario = selectedOption.val();
    var nombreUsuario = selectedOption.text();
    $('#codigoUsuarioIncidenciasCerradas').val(codigoUsuario);
    $('#nombreUsuarioIncidenciasCerradas').val(nombreUsuario);
  });

  // Buscador para el combo de usuario que realizaron el cierre
  $('#usuarioIncidenciasCerradas').select2({
    allowClear: true,
    width: '100%',
    dropdownCssClass: 'text-xs', // Use Tailwind CSS class
    language: {
      noResults: function () {
        return "No se encontraron resultados";
      }
    }
  });

  // Funcion para realizar la consulta sin filtros
  function nuevaConsultaIncidenciasCerradas() {
    // limpiar los campos fechas 
    $('#fechaInicioIncidenciasCerradas').val('');
    $('#fechaFinIncidenciasCerradas').val('');
    $('#usuarioIncidenciasCerradas').val(null).trigger('change');

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

      var faltaUsuario = ($('#usuarioIncidenciasCerradas').val() !== null && $('#usuarioIncidenciasCerradas').val().trim() !== '');
      var fechaInicioSeleccionada = ($('#fechaInicioIncidenciasCerradas').val() !== null && $('#fechaInicioIncidenciasCerradas').val().trim() !== '');
      var fechaFinSeleccionada = ($('#fechaFinIncidenciasCerradas').val() !== null && $('#fechaFinIncidenciasCerradas').val().trim() !== '');


      // Verificar si al menos un campo está lleno
      if (faltaUsuario || fechaInicioSeleccionada || fechaFinSeleccionada) {
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
