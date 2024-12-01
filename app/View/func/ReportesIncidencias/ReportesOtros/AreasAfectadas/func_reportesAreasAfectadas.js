$(document).ready(function () {
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "2000"
  };

  // Seteo del combo de usuario que realizaron el cierre
  $.ajax({
    url: 'ajax/getCategoryData.php',
    type: 'GET',
    dataType: 'json',
    success: function (data) {
      var select = $('#categoriaSeleccionada');
      select.empty();
      select.append('<option value="" selected disabled>Seleccione una categor&iacute;a</option>');
      $.each(data, function (index, value) {
        select.append('<option value="' + value.CAT_codigo + '">' + value.CAT_nombre + '</option>');
      });
    },
    error: function (error) {
      console.error(error);
    }
  });

  // Setear campos del usuario seleccionado
  $('#categoriaSeleccionada').change(function () {
    var selectedOption = $(this).find('option:selected');
    var copdigoCategoria = selectedOption.val();
    var nombreCategoria = selectedOption.text();
    $('#codigoCategoriaSeleccionada').val(copdigoCategoria);
    $('#nombreCategoriaSeleccionada').val(nombreCategoria);
  });

  // Buscador para el combo de usuario que realizaron el cierre
  $('#categoriaSeleccionada').select2({
    allowClear: true,
    width: '120%',
    dropdownCssClass: 'text-xs', // Use Tailwind CSS class
    language: {
      noResults: function () {
        return "No se encontraron resultados";
      }
    }
  });

  // Funcion para realizar la consulta sin filtros
  function nuevaConsultaAreasMasAfectadas() {
    // limpiar los campos fechas 
    $('#fechaInicioAreaMasAfectada').val('');
    $('#fechaFinAreaMasAfectada').val('');
    $('#categoriaSeleccionada').val(null).trigger('change');

    // Realizar la solicitud AJAX para obtener todos los registros (sin filtros)
    $.ajax({
      url: 'reportes.php?action=consultarAreasMasAfectadas',
      type: 'GET',
      dataType: 'html', // Esperamos HTML para renderizar la tabla
      success: function (response) {
        console.log("Resultados de nueva consulta: ", response);
        // Limpia el contenido actual de la tabla antes de agregar nuevos datos
        $('#tablaAreasMasAfectadas tbody').empty();
        // Actualiza el contenido de la tabla con la respuesta
        $('#tablaAreasMasAfectadas tbody').html(response);
      },
      error: function (xhr, status, error) {
        console.error('Error en la consulta AJAX:', error);
      }
    });
  }

  // Evento para el botón de limpiar campos
  $('#limpiarCamposAreasMasAfectadas').on('click', nuevaConsultaAreasMasAfectadas);

  // Validación y envío del formulario
  $('#formAreasMasAfectadas').submit(function (event) {
    event.preventDefault(); // Evita el envío del formulario por defecto

    // Verifica si los campos y las fechas son válidos
    if (!validarCamposAreasMasAfectadas() || !validarFechasAreasMasAfectadas()) {
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
      url: 'reportes.php?action=consultarAreasMasAfectadas',
      type: 'GET',
      data: dataObject,
      success: function (response) {
        console.log("Resultados filtrados:", response);
        $('#tablaAreasMasAfectadas tbody').empty(); // Limpia el contenido actual de la tabla antes de agregar nuevos datos
        $('#tablaAreasMasAfectadas tbody').html(response); // Actualiza el contenido de la tabla con la respuesta
      },
      error: function (xhr, status, error) {
        console.error('Error en la consulta AJAX:', error);
      }
    });

    // Función para validar los campos fechas
    function validarCamposAreasMasAfectadas() {
      var valido = false;
      var mensajeError = '';

      var faltaUsuario = ($('#categoriaSeleccionada').val() !== null && $('#categoriaSeleccionada').val().trim() !== '');
      var fechaInicioSeleccionada = ($('#fechaInicioAreaMasAfectada').val() !== null && $('#fechaInicioAreaMasAfectada').val().trim() !== '');
      var fechaFinSeleccionada = ($('#fechaFinAreaMasAfectada').val() !== null && $('#fechaFinAreaMasAfectada').val().trim() !== '');


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
  function validarFechasAreasMasAfectadas() {
    const fechaInicio = new Date($('#fechaInicioAreaMasAfectada').val());
    const fechaFin = new Date($('#fechaFinAreaMasAfectada').val());
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
  $('#fechaInicioAreaMasAfectada, #fechaFinAreaMasAfectada').on('change', function () {
    validarFechasAreasMasAfectadas();
  });
});
